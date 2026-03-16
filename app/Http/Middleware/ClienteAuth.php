<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClienteAuth
{
    public function handle(Request $request, Closure $next)
    {
        $companyId = session('cliente_company_id');

        if (!$companyId) {
            return redirect()->route('cliente.entrar')
                ->with('error', 'Faça login para acessar o painel.');
        }

        $company = Company::find($companyId);

        if (!$company) {
            session()->forget('cliente_company_id');
            return redirect()->route('cliente.entrar');
        }

        // Injeta a company na request
        $request->merge(['cliente_company' => $company]);

        return $next($request);
    }
}
