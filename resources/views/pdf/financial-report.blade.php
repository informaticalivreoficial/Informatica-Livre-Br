<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatório Financeiro — {{ $data['label'] }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 3px solid #0d9488;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0 0 4px;
            color: #0f172a;
        }
        .header p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .summary td {
            width: 33.33%;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            text-align: center;
        }
        .summary .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            display: block;
            margin-bottom: 4px;
        }
        .summary .value {
            font-size: 16px;
            font-weight: bold;
        }
        .summary .income { color: #059669; }
        .summary .expense { color: #dc2626; }
        .summary .balance { color: #0f172a; }
        h2 {
            font-size: 13px;
            margin: 18px 0 8px;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        table.data th {
            background: #f1f5f9;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }
        table.data td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
        }
        table.data tr:nth-child(even) td { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .amount { font-weight: bold; }
        .amount-expense { color: #dc2626; }
        .amount-income { color: #059669; }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-gray { background: #f1f5f9; color: #475569; }
        .footer {
            margin-top: 24px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Financeiro</h1>
        <p>Período: <strong>{{ $data['label'] }}</strong> · Gerado em {{ now()->format('d/m/Y \à\s H:i') }}</p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <span class="label">Receitas</span>
                <span class="value income">R$ {{ number_format($data['incomeTotal'], 2, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Despesas</span>
                <span class="value expense">R$ {{ number_format($data['expenseTotal'], 2, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Saldo</span>
                <span class="value balance">R$ {{ number_format($data['balance'], 2, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <h2>Despesas por categoria</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Categoria</th>
                <th class="text-center">Qtd.</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['expenseByCategory'] as $row)
                <tr>
                    <td>{{ $row['category'] }}</td>
                    <td class="text-center">{{ $row['count'] }}</td>
                    <td class="text-right amount amount-expense">R$ {{ number_format($row['amount'], 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Nenhuma despesa no período.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Receitas por categoria</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Categoria</th>
                <th class="text-center">Qtd.</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['incomeByCategory'] as $row)
                <tr>
                    <td>{{ $row['category'] }}</td>
                    <td class="text-center">{{ $row['count'] }}</td>
                    <td class="text-right amount amount-income">R$ {{ number_format($row['amount'], 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Nenhuma receita no período.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Lançamentos do período</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Mês</th>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Status</th>
                <th class="text-right">Valor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['transactions'] as $transaction)
                <tr>
                    <td>{{ $data['months'][$transaction->month] ?? $transaction->month }}</td>
                    <td>
                        @if($transaction->type->value === 'expense')
                            <span class="badge badge-red">Despesa</span>
                        @else
                            <span class="badge badge-green">Receita</span>
                        @endif
                    </td>
                    <td>{{ $transaction->expense?->description ?? $transaction->income?->description ?? '—' }}</td>
                    <td>{{ $transaction->category?->name ?? '—' }}</td>
                    <td>
                        @if($transaction->status->value === 'paid')
                            <span class="badge badge-green">Pago</span>
                        @elseif($transaction->status->value === 'received')
                            <span class="badge badge-green">Recebido</span>
                        @elseif($transaction->status->value === 'cancelled')
                            <span class="badge badge-gray">Cancelado</span>
                        @else
                            <span class="badge badge-yellow">Pendente</span>
                        @endif
                    </td>
                    <td class="text-right amount">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Nenhum lançamento no período.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Informatica Livre BR · Relatório Financeiro gerado automaticamente pelo sistema.
    </div>
</body>
</html>