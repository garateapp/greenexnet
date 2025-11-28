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
        'Las Orquídeas SpA' => ['las orquideas', 'las orquídeas','Orquídeas Noche'],
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
        ]);
        Log::info("ControlAccessLogIngestController::store", $payload);
        $stored = [];

        foreach ($payload['records'] as $record) {
            $departmentName = $record['departamento'] ?? null;
            $contractorGroup = $this->resolveContractorGroup($departmentName);

            if ($contractorGroup) {
                $departmentName = $contractorGroup;
            }
            $personal = Personal::where('rut',$this->formatearRutConDv($record['personal_id']))->first();
            $entidad = Entidad::where('nombre', 'like', $departmentName.'%')->first();
            if($entidad){
               $departmentName = $entidad->nombre;
            }

            $log = ControlAccessLog::create([
                'fecha' => $this->parseDate($record['fecha'] ?? null),
                'personal_id' => $record['personal_id'],
                'nombre' => $record['nombre'] ?? null,
                'departamento' => $departmentName,
                'primera_entrada' => $this->parseDate($record['primera_entrada'] ?? null),
                'ultima_salida' => $this->parseDate($record['ultima_salida'] ?? null),
                'pin' => $record['pin'] ?? null,
            ]);
            $stored[] = $log->id;

        if(!($personal)){
           // 2) SOLO crear Personal si pertenece a un ContractorGroup
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
        }
        else{
            if($contractorGroup){
                $personal->entidad_id = $this->getDeptoId($contractorGroup);
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

        $depto = Entidad::where('nombre','like', $departamento.'%')->first();
        if(!$depto){
            return null;
        }
        return  $depto->id;

    }
    function formatearRutConDv($rut) {
    // Dejar solo números
    $rut = preg_replace('/[^0-9]/', '', $rut);

    if ($rut === '' || !ctype_digit($rut)) {
        return null; // Manejo básico de error
    }

    // ---- CALCULAR DV ----
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

    // ---- FORMATEAR RUT ----
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
