<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ControlAccessLog;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class ControlAccessLogIngestController extends Controller
{
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
            $log = ControlAccessLog::create([
                'fecha' => $this->parseDate($record['fecha'] ?? null),
                'personal_id' => $record['personal_id'],
                'nombre' => $record['nombre'] ?? null,
                'departamento' => $record['departamento'] ?? null,
                'primera_entrada' => $this->parseDate($record['primera_entrada'] ?? null),
                'ultima_salida' => $this->parseDate($record['ultima_salida'] ?? null),
                'pin' => $record['pin'] ?? null,
            ]);
            $stored[] = $log->id;
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
}
