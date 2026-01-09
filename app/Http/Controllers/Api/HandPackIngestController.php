<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HandPack;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class HandPackIngestController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'rut' => ['required', 'string', 'max:255'],
            'embalaje' => ['required', 'string', 'max:255'],
            'guuid' => ['required', 'string', 'max:255'],
        ]);

        Log::info('HandPack ingest payload received.', [
            'rut' => $payload['rut'],
            'embalaje' => $payload['embalaje'],
            'guuid' => $payload['guuid'],
        ]);

        if (HandPack::where('guuid', $payload['guuid'])->exists()) {
            Log::warning('HandPack ingest rejected: guuid already exists.', [
                'guuid' => $payload['guuid'],
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'CAJA REPETIDA',
            ], Response::HTTP_CONFLICT);
        }

        $handPack = HandPack::create([
            'rut' => $payload['rut'],
            'embalaje' => $payload['embalaje'],
            'guuid' => $payload['guuid'],
            'fecha' => Carbon::now(),
        ]);

        Log::info('HandPack ingest stored.', [
            'id' => $handPack->id,
            'guuid' => $handPack->guuid,
        ]);

        return response()->json([
            'status' => 'ok',
            'data' => $handPack,
        ], Response::HTTP_CREATED);
    }
}
