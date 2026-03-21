<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token não informado.'], 401);
        }

        $company = Company::where('api_token', $token)->first();

        if (!$company) {
            return response()->json(['message' => 'Token inválido.'], 401);
        }

        $request->merge(['auth_company' => $company]);

        return $next($request);
    }
}
