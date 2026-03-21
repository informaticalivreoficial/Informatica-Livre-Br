<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->auth_company;

        $invoices = $company->invoices()
        ->with('subscription.service')
        ->when($request->status, fn($q) => $q->where('status', $request->status))
        ->where(function ($q) {
            $q->where('status', 'paid') // 👈 pagas aparecem sempre
            ->orWhere(function ($q) {
                $q->where('status', 'pending')
                    ->where('due_date', '<=', now()->addDays(30)); // 👈 pendentes só 30 dias antes
            });
        })
        ->orderByDesc('due_date')
        ->paginate(10);

        return response()->json([
            'data' => $invoices->map(fn($invoice) => [
                'id'          => $invoice->id,
                'service'     => $invoice->subscription->service->name ?? null,
                'amount'      => number_format($invoice->amount, 2, ',', '.'),
                'due_date'    => $invoice->due_date->format('d/m/Y'),
                'status'      => $invoice->status,
                'payment_url' => $invoice->payment_url,
                'barcode'     => $invoice->boleto_barcode ?? null,
                'paid_at'     => $invoice->paid_at?->format('d/m/Y H:i'),
            ]),
            'meta' => [
                'current_page' => $invoices->currentPage(),
                'last_page'    => $invoices->lastPage(),
                'per_page'     => $invoices->perPage(),
                'total'        => $invoices->total(),
            ],
        ]);
    }
}
