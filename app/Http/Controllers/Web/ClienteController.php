<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Configuracoes;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    public function login()
    {
        return view('web.cliente.login');
    }

    public function fatura($uuid)
    {
        $fatura = Pedido::where('uuid', $uuid)->first();
         
        return view('web.cliente.fatura',[
            'fatura' => $fatura
        ]);
    }
}
