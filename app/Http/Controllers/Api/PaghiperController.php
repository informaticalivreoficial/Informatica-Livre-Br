<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Services\PagHiperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PaghiperController extends Controller
{
    public function request(Request $request)
    {
        $notificationId = $request->input('notification_id');
        $transactionId = $request->input('transaction_id');

        if (!$notificationId || !$transactionId) {
            Log::warning('PagHiper webhook de pedido sem notification_id/transaction_id', $request->all());
            return response()->json(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }

        $sourceApi = $request->input('source_api', '');
        $operation = str_contains($sourceApi, 'pix.paghiper.com') ? 'pix' : 'api';

        $payload = app(PagHiperService::class)
            ->getNotification($notificationId, $transactionId, $operation);

        if (!$payload) {
            Log::error('Falha ao obter notificação completa do PagHiper', [
                'notification_id' => $notificationId,
                'transaction_id' => $transactionId,
            ]);
            return response()->json(['error' => 'Notification not found'], Response::HTTP_NOT_FOUND);
        }

        $pedido = Pedido::where('gateway_id', $transactionId)->first();

        if (!$pedido) {
            Log::warning('Pedido não encontrado', ['transaction_id' => $transactionId]);
            return response()->json(['error' => 'Pedido not found'], Response::HTTP_NOT_FOUND);
        }

        $status = $payload['status'] ?? null;

        $pedido->gateway_response = $payload;
        $pedido->status_pagamento = match ($status) {
            'paid', 'completed' => 'pago',
            'canceled'          => 'cancelado',
            'refunded'          => 'estornado',
            default             => 'pendente',
        };
        $pedido->status = match ($status) {
            'paid', 'completed' => 'confirmado',
            'canceled'          => 'cancelado',
            'refunded'          => 'reembolsado',
            default             => 'pendente',
        };

        if (in_array($status, ['paid', 'completed'])) {
            $pedido->pago_em = now();
        }

        $pedido->save();

        Log::info('Pedido atualizado via webhook PagHiper', [
            'pedido_id' => $pedido->id,
            'status' => $status,
        ]);

        return response()->json(['success' => true]);
    }
}
