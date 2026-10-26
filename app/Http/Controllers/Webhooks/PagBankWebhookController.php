<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Services\PagBankService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PagBankWebhookController extends Controller
{
    public function __invoke(Request $request, PagBankService $pagbank): JsonResponse
    {
        if (! $pagbank->verifySignature($request)) {
            Log::warning('PagBank webhook com assinatura inválida', [
                'ip' => $request->ip(),
            ]);

            return response()->json(['message' => 'Assinatura inválida.'], 401);
        }

        $payload = $request->json()->all();

        if (empty($payload)) {
            return response()->json(['message' => 'Payload inválido.'], 422);
        }

        $pedido = $this->findPedido($payload);

        if (! $pedido) {
            Log::warning('PagBank webhook sem pedido correspondente', [
                'reference_id' => $payload['reference_id'] ?? null,
                'id' => $payload['id'] ?? null,
            ]);

            return response()->json(['received' => true], 202);
        }

        $statuses = collect($payload['charges'] ?? [])
            ->pluck('status')
            ->push($payload['status'] ?? null)
            ->filter()
            ->map(fn ($status) => strtoupper((string) $status))
            ->unique();

        try {
            if ($statuses->contains('PAID')) {
                $pedido->markAsPaid();
            } elseif ($statuses->contains('REFUNDED')) {
                $pedido->markAsRefunded();
            } elseif ($statuses->intersect(['CANCELED', 'DECLINED'])->isNotEmpty() && $pedido->status_pagamento !== 'pago') {
                $pedido->markAsCanceled();
            }
        } catch (\Throwable $e) {
            Log::warning('Falha ao atualizar pedido pelo webhook PagBank', [
                'pedido_id' => $pedido->id,
                'statuses' => $statuses->values()->all(),
                'error' => $e->getMessage(),
            ]);
        }

        $pedido->refresh();

        $pedido->update([
            'gateway_response' => array_merge($pedido->gateway_response ?? [], [
                'last_notification' => $payload,
                'last_notification_at' => now()->toIso8601String(),
            ]),
        ]);

        return response()->json(['received' => true]);
    }

    private function findPedido(array $payload): ?Pedido
    {
        $reference = $payload['reference_id'] ?? null;

        if ($reference) {
            $pedido = Pedido::query()->where('codigo', $reference)->first();

            if ($pedido) {
                return $pedido;
            }
        }

        $gatewayId = $payload['id'] ?? null;

        if ($gatewayId) {
            return Pedido::query()->where('gateway_id', $gatewayId)->first();
        }

        return null;
    }
}
