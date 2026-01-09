<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HandPack;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        if (HandPack::where('guuid', $payload['guuid'])->exists()) {
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

        return response()->json([
            'status' => 'ok',
            'data' => $handPack,
        ], Response::HTTP_CREATED);
    }
}
