<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido recebido</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:'Segoe UI', Arial, sans-serif; color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f5f7; padding:32px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                    <tr>
                        <td style="background-color:#0f766e; padding:24px 32px;">
                            <p style="margin:0; color:#ffffff; font-size:20px; font-weight:700;">{{ config('app.name') }}</p>
                            <p style="margin:6px 0 0; color:#99f6e4; font-size:13px;">Pedido {{ $pedido->codigo }} recebido</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px; font-size:16px;">Olá, <strong>{{ $pedido->nome }}</strong>!</p>

                            <p style="margin:0 0 20px; font-size:14px; line-height:1.6; color:#4b5563;">
                                Recebemos o seu pedido e já estamos preparando tudo.
                                @if ($pedido->payment_url)
                                    Finalize o pagamento pelo botão abaixo para liberar o acesso.
                                @else
                                    Nossa equipe enviará o link de pagamento em breve.
                                @endif
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e5e7eb; border-radius:10px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:16px 20px; border-bottom:1px solid #e5e7eb; font-size:12px; color:#6b7280; text-transform:uppercase; letter-spacing:.05em;">Itens do pedido</td>
                                </tr>

                                @foreach ($pedido->itens as $item)
                                    <tr>
                                        <td style="padding:14px 20px; border-bottom:1px solid #f3f4f6; font-size:14px;">
                                            <strong style="color:#111827;">{{ $item->produto_nome }}</strong>
                                            <span style="color:#6b7280;"> — {{ $item->plano_nome }}</span>
                                            <span style="float:right; color:#111827; font-weight:600;">{{ $item->preco_formatado }}</span>
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td style="padding:16px 20px; font-size:14px;">
                                        <span style="color:#6b7280;">Forma de pagamento:</span>
                                        <strong style="color:#111827;">{{ $pedido->metodo_pagamento_label }}</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:0 20px 18px; font-size:18px; font-weight:700; color:#0f766e;">
                                        Total: {{ $pedido->total_formatado }}
                                    </td>
                                </tr>
                            </table>

                            @if ($pedido->recorrente && $pedido->billing_interval)
                                <p style="margin:0 0 20px; font-size:13px; color:#4b5563; line-height:1.6;">
                                    Este pedido é uma assinatura <strong>{{ $pedido->billing_interval->label() }}</strong>.
                                    A próxima cobrança será enviada em
                                    <strong>{{ optional($pedido->proximo_vencimento)->format('d/m/Y') }}</strong>.
                                </p>
                            @elseif ($pedido->isRenovacao())
                                <p style="margin:0 0 20px; font-size:13px; color:#4b5563; line-height:1.6;">
                                    Esta é a cobrança de renovação da sua assinatura
                                    <strong>{{ $pedido->pai?->codigo }}</strong>.
                                </p>
                            @endif

                            @if ($pedido->payment_url)
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:24px 0;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ $pedido->payment_url }}" style="display:inline-block; background-color:#0f766e; color:#ffffff; text-decoration:none; font-size:15px; font-weight:700; padding:14px 32px; border-radius:10px;">
                                                Pagar agora
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <p style="margin:0; font-size:12px; color:#9ca3af; line-height:1.6;">
                                Se o botão não funcionar, copie e cole este link no navegador:<br>
                                <span style="word-break:break-all; color:#0f766e;">{{ $pedido->payment_url ?: route('cliente.entrar') }}</span>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color:#f9fafb; padding:20px 32px; font-size:12px; color:#9ca3af; text-align:center;">
                            {{ config('app.name') }} · Este é um e-mail automático, não responda.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
