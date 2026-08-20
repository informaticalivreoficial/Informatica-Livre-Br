<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\FinancialReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FinancialReportController extends Controller
{
    public function export(Request $request)
    {
        $validated = $request->validate([
            'period' => 'required|in:mensal,trimestral,semestral,anual',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'nullable|integer|between:1,12',
            'quarter' => 'nullable|integer|between:1,4',
            'half' => 'nullable|integer|between:1,2',
        ]);

        $data = app(FinancialReportService::class)->build(
            $validated['year'],
            $validated['period'],
            $validated['period'] === 'mensal' ? $validated['month'] : null,
            $validated['period'] === 'trimestral' ? $validated['quarter'] : null,
            $validated['period'] === 'semestral' ? $validated['half'] : null,
        );

        $pdf = Pdf::loadView('pdf.financial-report', ['data' => $data])
            ->setPaper('a4', 'portrait');

        return $pdf->download("relatorio-financeiro-{$data['period']}-{$data['year']}.pdf");
    }
}