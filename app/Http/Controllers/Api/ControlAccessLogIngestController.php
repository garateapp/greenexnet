<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ControlAccessLog;
use App\Models\Personal;
use App\Models\Entidad;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ControlAccessLogIngestController extends Controller
{
    protected array $contractorGroups = [
        'Valsán Ltda' => ['valsan', 'valsán', 'valsan ltda', 'valsan noche'],
        'Las Orquídeas SpA' => ['Las Orquideas SpA','las orquideas', 'las orquídeas','Orquídeas Noche'],
        'Isaias Ballesteros' => ['isaias ballesteros', 'isaias ballesteros noche'],
        'Agrícola Lancair' => ['agrícola lancair', 'lancair noche'],
        'Fernando Urbina' => ['fernando urbina'],
        'Claudia Viera'=>['claudia viera'],
    ];

    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'records' => ['required', 'array', 'min:1'],
            'records.*.fecha' => ['nullable', 'string'],
            'records.*.personal_id' => ['required', 'string', 'max:255'],
            'records.*.nombre' => ['nullable', 'string', 'max:255'],
            'records.*.departamento' => ['nullable', 'string', 'max:255'],
            'records.*.primera_entrada' => ['nullable', 'string'],
            'records.*.ultima_salida' => ['nullable', 'string'],
            'records.*.pin' => ['nullable', 'string', 'max:255'],
            'records.*.fecha_operativa' => ['nullable', 'string'],
            'records.*.turno' => ['nullable', 'string', 'max:20'],
            'records.*.max_event_id_pair' => ['nullable', 'string', 'max:255'],
            'records.*.pair_max_time' => ['nullable', 'string'],
        ]);

        $stored = [];

        foreach ($payload['records'] as $record) {
            $departmentName = $record['departamento'] ?? null;
            $contractorGroup = $this->resolveContractorGroup($departmentName);

            if ($contractorGroup) {
                $departmentName = $contractorGroup;
            }

            $personal = Personal::where('rut', $this->formatearRutConDv($record['personal_id']))->first();
            $entidad = Entidad::where('nombre', 'like', $departmentName.'%')->first();
            if ($entidad) {
                $departmentName = $entidad->nombre;
            }

            $primera = $this->parseDate($record['primera_entrada'] ?? null);
            $ultima  = $this->parseDate($record['ultima_salida'] ?? null);

            // Clave de emparejado: persona + día operativo (fecha a medianoche)
            $fechaOperativa = $this->parseDate($record['fecha_operativa'] ?? null);
            $turno = $record['turno'] ?? null;

            if (!$fechaOperativa && $primera) {
                [$fechaOperativa, $turnoCalc] = $this->computeOperationalFromEntrada($primera);
                $turno = $turno ?: $turnoCalc;
            }

            if ($fechaOperativa) {
                $fechaOperativa = $fechaOperativa->copy()->startOfDay();
            }

            $logQuery = ControlAccessLog::query()
                ->where('personal_id', $record['personal_id']);

            if ($fechaOperativa) {
                $logQuery->whereDate('fecha', $fechaOperativa->toDateString());
            }

            $log = $logQuery->first();

            if (!$log) {
                $log = new ControlAccessLog();
                $log->fecha = $fechaOperativa; // guardamos la fecha operativa (00:00) para emparejar en corridas futuras
                $log->personal_id = $record['personal_id'];
                $log->primera_entrada = $primera;
                $log->ultima_salida = $ultima;
            } else {
                // MERGE: primera_entrada = MIN, ultima_salida = MAX (no pisar con null)
                if ($primera && (!$log->primera_entrada || $primera->lt(Carbon::parse($log->primera_entrada)))) {
                    $log->primera_entrada = $primera;
                }
                if ($ultima && (!$log->ultima_salida || $ultima->gt(Carbon::parse($log->ultima_salida)))) {
                    $log->ultima_salida = $ultima;
                }
            }

            // Datos descriptivos
            if (!empty($record['nombre'])) $log->nombre = $record['nombre'];
            $log->departamento = $departmentName;
            if (!empty($record['pin'])) $log->pin = $record['pin'];

            $log->save();
            $stored[] = $log->id;

            // ----------- Tu lógica Personal / contractorGroup se mantiene -----------
            if (!($personal)) {
                // SOLO crear Personal si pertenece a un ContractorGroup
                if ($contractorGroup) {
                    $rutFormateado = $this->formatearRutConDv($record['personal_id']);
                    $personal = Personal::where('rut', $rutFormateado)->first();
                    $deptoId = $this->getDeptoId($contractorGroup);

                    if (!$personal && $deptoId) {
                        Personal::create([
                            'nombre'     => $record['nombre'] ?? null,
                            'rut'        => $rutFormateado,
                            'entidad_id' => $deptoId,
                            'cargo_id'   => 1,
                        ]);
                    }
                }
            } else {
                if ($contractorGroup) {
                    $personal->entidad_id = $this->getDeptoId($contractorGroup);
                    $personal->save();
                } else {
                    $personal->entidad_id = $entidad ? $entidad->id : $personal->entidad_id;
                    $personal->save();
                }
            }
        }

        Log::info("resultado:", [
            'status' => 'ok',
            'stored' => count($stored),
            'ids' => $stored,
        ]);

        return response()->json([
            'status' => 'ok',
            'stored' => count($stored),
            'ids' => $stored,
        ]);
    }

    protected function resolveContractorGroup(?string $departamento): ?string
    {
        if (!$departamento) {
            return null;
        }

        $dep = mb_strtolower(trim($departamento));

        foreach ($this->contractorGroups as $groupName => $aliases) {
            foreach ($aliases as $alias) {
                if ($dep === mb_strtolower($alias)) {
                    return $groupName;
                }
            }
        }

        return null;
    }

    private function computeOperationalFromEntrada(Carbon $entrada): array
    {
        // Turno día: 08:00–18:00 (llegadas hasta 60 min antes cuentan como día)
        // Turno noche: 18:30–08:00 (consideramos noche desde 17:30 y también antes de 07:00 pertenece a la noche del día anterior)
        $dayStart = Carbon::createFromTimeString('07:00:00');        // 08:00 - 60 min
        $nightStartGrace = Carbon::createFromTimeString('17:30:00'); // 18:30 - 60 min

        $t = Carbon::createFromTime($entrada->hour, $entrada->minute, $entrada->second);

        if ($t->lt($dayStart)) {
            return [$entrada->copy()->subDay()->startOfDay(), 'NOCHE'];
        }

        if ($t->gte($nightStartGrace)) {
            return [$entrada->copy()->startOfDay(), 'NOCHE'];
        }

        return [$entrada->copy()->startOfDay(), 'DIA'];
    }

    private function parseDate(?string $value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $exception) {
            return null;
        }
    }

    public function getDeptoId(string $departamento)
    {
        $depto = Entidad::where('nombre', 'like', $departamento.'%')->first();
        if (!$depto) {
            return null;
        }
        return $depto->id;
    }

    function formatearRutConDv($rut) {
        $rut = preg_replace('/[^0-9]/', '', $rut);

        if ($rut === '' || !ctype_digit($rut)) {
            return null;
        }

        $suma = 0;
        $multiplicador = 2;

        for ($i = strlen($rut) - 1; $i >= 0; $i--) {
            $suma += intval($rut[$i]) * $multiplicador;
            $multiplicador++;
            if ($multiplicador > 7) {
                $multiplicador = 2;
            }
        }

        $resto = $suma % 11;
        $dv = 11 - $resto;

        if ($dv == 11) {
            $dv = "0";
        } elseif ($dv == 10) {
            $dv = "K";
        } else {
            $dv = (string)$dv;
        }

        $rutInvertido = strrev($rut);
        $rutFormateado = '';

        for ($i = 0; $i < strlen($rutInvertido); $i++) {
            if ($i > 0 && $i % 3 === 0) {
                $rutFormateado .= '.';
            }
            $rutFormateado .= $rutInvertido[$i];
        }

        $rutFormateado = strrev($rutFormateado);

        return $rutFormateado . '-' . $dv;
    }
}
