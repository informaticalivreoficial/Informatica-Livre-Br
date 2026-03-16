<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Fatura;
use App\Models\Gateway;
use App\Models\ItemPedido;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use WebMaster\PagHiper\PagHiper;

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

        // Envia por WhatsApp via link
        $whatsapp = preg_replace('/\D/', '', $company->whatsapp ?? $company->cell_phone);
        if ($whatsapp) {
            $mensagem = urlencode("Olá, {$company->alias_name}! Acesse seu painel pelo link: {$link} (válido por 15 minutos)");
            // Armazena o link do WhatsApp na sessão para exibir na view
            session(['whatsapp_link' => "https://api.whatsapp.com/send?phone=55{$whatsapp}&text={$mensagem}"]);
        }

        return back()->with('success', 'Link enviado! Verifique seu e-mail e WhatsApp.');
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

    
}
