<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento confirmado</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:'Segoe UI', Arial, sans-serif; color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f5f7; padding:32px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                    <tr>
                        <td style="background-color:#15803d; padding:24px 32px;">
                            <p style="margin:0; color:#ffffff; font-size:20px; font-weight:700;">Pagamento confirmado</p>
                            <p style="margin:6px 0 0; color:#bbf7d0; font-size:13px;">Pedido {{ $pedido->codigo }}</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px; font-size:16px;">Olá, <strong>{{ $pedido->nome }}</strong>!</p>

                            <p style="margin:0 0 20px; font-size:14px; line-height:1.6; color:#4b5563;">
                                O pagamento do seu pedido foi confirmado com sucesso.
                                @if ($pedido->isRenovacao())
                                    Sua assinatura foi renovada e o acesso permanece ativo.
                                @else
                                    Abaixo estão os dados de acesso liberados para você.
                                @endif
                            </p>

                            @if ($pedido->licencas->isNotEmpty())
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e5e7eb; border-radius:10px; margin-bottom:20px;">
                                    <tr>
                                        <td style="padding:16px 20px; border-bottom:1px solid #e5e7eb; font-size:12px; color:#6b7280; text-transform:uppercase; letter-spacing:.05em;">Licenças</td>
                                    </tr>

                                    @foreach ($pedido->licencas as $licenca)
                                        <tr>
                                            <td style="padding:14px 20px; border-bottom:1px solid #f3f4f6; font-size:14px;">
                                                <strong style="color:#111827;">{{ $licenca->produto?->nome ?? $pedido->itens->first()?->produto_nome }}</strong><br>
                                                <span style="font-family:Consolas, monospace; font-size:15px; color:#0f766e; letter-spacing:.05em;">{{ $licenca->chave }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e5e7eb; border-radius:10px; margin-bottom:20px;">
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
                                    <td style="padding:16px 20px; font-size:18px; font-weight:700; color:#15803d;">
                                        Total pago: {{ $pedido->total_formatado }}
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:24px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('cliente.entrar') }}" style="display:inline-block; background-color:#0f766e; color:#ffffff; text-decoration:none; font-size:15px; font-weight:700; padding:14px 32px; border-radius:10px;">
                                            Acessar painel do cliente
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0; font-size:12px; color:#9ca3af; line-height:1.6;">
                                Utilize o mesmo e-mail desta compra para receber o link de acesso ao painel.
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
