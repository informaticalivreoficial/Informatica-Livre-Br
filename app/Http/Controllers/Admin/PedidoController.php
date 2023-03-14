<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::orderBy('created_at', 'DESC')->paginate(25);

        return view('admin.pedidos.index', [
            'pedidos' => $pedidos
        ]);
    }

    public function create()
    {
        $empresas = Empresa::orderBy('created_at', 'DESC')->get();
        return view('admin.pedidos.create',[
            'empresas' => $empresas
        ]);
    }

    public function store()
    {
        //
    }
    
}
