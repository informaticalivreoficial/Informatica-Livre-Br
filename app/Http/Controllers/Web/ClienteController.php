<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Licenca;
use App\Models\Pedido;
use App\Services\LojaNotificacaoService;
use App\Services\PagBankService;
use App\Services\PagHiperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function entrar()
    {
        if (session('cliente_company_id')) {
            return redirect()->route('cliente.dashboard');
        }

        return view('web.cliente.entrar');
    }

    public function enviarLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Informe seu e-mail.',
            'email.email'    => 'Informe um e-mail válido.',
        ]);

        $company = Company::where('email', $request->email)
            ->orWhere('responsable_email', $request->email)
            ->first();

        // Sempre mostra sucesso por segurança
        if (!$company) {
            return back()->with('success', 'Se o e-mail estiver cadastrado, você receberá o link em breve.');
        }

        $token = $company->generateMagicToken();
        $link  = route('cliente.auth', $token);

        // Envia por e-mail
        Mail::raw(
            "Olá, {$company->alias_name}!\n\nClique no link abaixo para acessar seu painel:\n\n{$link}\n\nEste link expira em 15 minutos.",
            function ($mail) use ($company) {
                $mail->to($company->email ?? $company->responsable_email)
                    ->subject('Acesso ao Painel do Cliente - Informática Livre');
            }
        );

        return back()->with('success', 'Link enviado! Verifique seu e-mail.');
    }

    public function autenticar(string $token)
    {
        $company = Company::where('magic_token', $token)->first();

        if (!$company || !$company->isMagicTokenValid($token)) {
            return redirect()->route('cliente.entrar')
                ->with('error', 'Link inválido ou expirado. Solicite um novo.');
        }

        // Invalida o token após uso
        $company->update([
            'magic_token'            => null,
            'magic_token_expires_at' => null,
        ]);

        session(['cliente_company_id' => $company->id]);

        return redirect()->route('cliente.dashboard');
    }

    public function sair()
    {
        session()->forget('cliente_company_id');
        return redirect()->route('cliente.entrar')
            ->with('success', 'Você saiu do painel.');
    }

    public function dashboard(Request $request)
    {
        $company = $request->cliente_company;

        $totalFaturas    = $company->invoices()->count();
        $faturasAbertas  = $company->invoices()->where('status', 'pending')->count();
        $faturasPagas    = $company->invoices()->where('status', 'paid')->count();
        $totalServicos   = $company->subscriptions()->active()->count();

        return view('web.cliente.dashboard', compact(
            'company',
            'totalFaturas',
            'faturasAbertas',
            'faturasPagas',
            'totalServicos'
        ));
    }

    public function faturas(Request $request)
    {
        $company = $request->cliente_company;

        $faturas = $company->invoices()
            ->with('subscription.service')
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->orderByDesc('due_date')
            ->paginate(10);

        return view('web.cliente.faturas', compact('company', 'faturas'));
    }

    public function pagarFatura(Request $request, int $id)
    {
        $company = $request->cliente_company;

        $invoice = $company->invoices()
            ->with(['company', 'subscription.service'])
            ->findOrFail($id);

        if ($invoice->status === 'paid') {
            return back()->with('success', 'Esta fatura já está paga.');
        }

        if ($invoice->status !== 'pending') {
            return back()->with('error', 'Esta fatura não está disponível para pagamento.');
        }

        if (! $invoice->payment_url) {
            try {
                $response = app(PagHiperService::class)->createInvoice($invoice);
            } catch (\Throwable $e) {
                return back()->with('error', 'Não foi possível gerar a cobrança: ' . $e->getMessage());
            }

            $invoice->update([
                'gateway' => 'paghiper',
                'gateway_reference' => $response['transaction_id'] ?? null,
                'payment_url' => $response['bank_slip']['url_slip']
                    ?? $response['bank_slip']['url_slip_pdf']
                    ?? data_get($response, 'pix.url')
                    ?? null,
                'pix_qrcode' => $response['pix']['qrcode_image'] ?? null,
                'boleto_barcode' => $response['bank_slip']['digitable_line'] ?? null,
            ]);
        }

        if (! $invoice->fresh()->payment_url) {
            return back()->with('error', 'A cobrança foi gerada, mas o link de pagamento não está disponível. Tente novamente em instantes.');
        }

        return redirect()->away($invoice->fresh()->payment_url);
    }

    public function servicos(Request $request)
    {
        $company = $request->cliente_company;

        $servicos = $company->subscriptions()
            ->with('service')
            ->latest()
            ->get();

        return view('web.cliente.servicos', compact('company', 'servicos'));
    }

    public function empresa(Request $request)
    {
        $company = $request->cliente_company;
        return view('web.cliente.empresa', compact('company'));
    }

    public function atualizarEmpresa(Request $request)
    {
        $company = $request->cliente_company;

        $data = $request->validate([
            'alias_name' => 'required|string|max:255',
            'social_name' => 'nullable|string|max:255',
            'document_company' => 'nullable|string|max:20',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('companies', 'email')->ignore($company->id),
            ],
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'responsable_name' => 'required|string|max:255',
            'responsable_email' => 'required|email|max:255',
            'responsable_cpf' => 'nullable|string|max:20',
            'zipcode' => 'nullable|string|max:9',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
        ], [
            'alias_name.required' => 'Informe o nome fantasia.',
            'email.required' => 'Informe o e-mail principal.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado em outra empresa.',
            'responsable_name.required' => 'Informe o nome do responsável.',
            'responsable_email.required' => 'Informe o e-mail do responsável.',
            'responsable_email.email' => 'Informe um e-mail válido para o responsável.',
            'state.max' => 'Informe a UF com 2 letras.',
        ]);

        if (! empty($data['state'])) {
            $data['state'] = strtoupper($data['state']);
        }

        $company->update($data);

        return redirect()->route('cliente.empresa')
            ->with('success', 'Seus dados foram atualizados com sucesso.');
    }

    public function pedidos(Request $request)
    {
        $company = $request->cliente_company;

        $pedidos = $this->pedidosDaEmpresa($company)
            ->with(['itens', 'licencas'])
            ->when($request->status, fn ($query) => $query->where('status_pagamento', $request->status))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('web.cliente.pedidos', compact('company', 'pedidos'));
    }

    public function pedido(Request $request, string $codigo)
    {
        $company = $request->cliente_company;

        $pedido = $this->pedidosDaEmpresa($company)
            ->with(['itens', 'licencas.produto', 'cobrancas'])
            ->where('codigo', $codigo)
            ->firstOrFail();

        return view('web.cliente.pedido', compact('company', 'pedido'));
    }

    public function licencas(Request $request)
    {
        $company = $request->cliente_company;

        $licencas = Licenca::query()
            ->with(['produto', 'pedido'])
            ->whereIn('pedido_id', $this->pedidosDaEmpresa($company)->select('id'))
            ->latest()
            ->paginate(15);

        return view('web.cliente.licencas', compact('company', 'licencas'));
    }

    public function reenviarLink(Request $request, string $codigo)
    {
        $company = $request->cliente_company;

        $pedido = $this->pedidosDaEmpresa($company)
            ->where('codigo', $codigo)
            ->firstOrFail();

        if ($pedido->status_pagamento === 'pago') {
            return back()->with('success', 'Este pedido já está pago. Consulte a aba de licenças para acessar seus dados.');
        }

        try {
            app(PagBankService::class)->createPedidoCheckout($pedido);
        } catch (\Throwable $e) {
            return back()->with('error', 'Não foi possível gerar um novo link de pagamento: ' . $e->getMessage());
        }

        app(LojaNotificacaoService::class)->pedidoRecebido($pedido->fresh());

        return back()->with('success', 'Novo link de pagamento gerado e enviado para o seu e-mail.');
    }

    private function pedidosDaEmpresa(Company $company)
    {
        return Pedido::query()->where(function ($query) use ($company) {
            $query->where('company_id', $company->id);

            foreach (array_filter([$company->email, $company->responsable_email]) as $email) {
                $query->orWhere('email', $email);
            }
        });
    }
}
