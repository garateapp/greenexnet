<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PackingLineDetention;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PackingLineDetentionIngestController extends Controller
{
    /**
     * Endpoint público para recibir registros de detenciones y almacenarlos.
     */
    public function store(Request $request): JsonResponse
    {
        $payload = $this->validatePayload($request);
        $saved = [];

        foreach ($payload['detentions'] as $detention) {
            $saved[] = PackingLineDetention::updateOrCreate(
                ['event_id' => $detention['id']],
                [
                    'line' => $detention['linea'] ?? null,
                    'event_date' => $this->parseDatetime($detention['fecha_evento'] ?? null),
                    'activation_date' => $this->parseDatetime($detention['fecha_activacion'] ?? null),
                    'duration_minutes' => (int) ($detention['duracion'] ?? 0),
                    'motivo' => $detention['motivo'] ?? null,
                    'causa' => $detention['causa'] ?? null,
                    'notas' => $detention['notas'] ?? null,
                    'estado' => $this->formatEstado($detention['estado'] ?? null),
                ]
            )->event_id;
        }

        return response()->json([
            'status' => 'ok',
            'received' => count($payload['detentions']),
            'stored_event_ids' => $saved,
        ]);
    }

    private function validatePayload(Request $request): array
    {
        try {
            return $request->validate([
                'detentions' => ['required', 'array', 'min:1'],
                'detentions.*.id' => ['required', 'integer'],
                'detentions.*.linea' => ['nullable', 'string', 'max:255'],
                'detentions.*.fecha_evento' => ['nullable', 'string'],
                'detentions.*.fecha_activacion' => ['nullable', 'string'],
                'detentions.*.duracion' => ['nullable', 'numeric'],
                'detentions.*.motivo' => ['nullable', 'string', 'max:255'],
                'detentions.*.causa' => ['nullable', 'string', 'max:255'],
                'detentions.*.notas' => ['nullable', 'string'],
                'detentions.*.estado' => ['nullable'],
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        }
    }

    private function parseDatetime(?string $value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Exception $exception) {
            return null;
        }
    }

    private function formatEstado($estado): ?string
    {
        if (is_null($estado)) {
            return null;
        }

        if (is_numeric($estado)) {
            return (int) $estado === 1 ? 'Activo' : 'Inactivo';
        }

        return (string) $estado;
    }
}
