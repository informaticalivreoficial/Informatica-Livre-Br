<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orcamento;
use Illuminate\Http\Request;

class OrcamentoController extends Controller
{
    public function index()
    {
        $orcamentos = Orcamento::orderBy('created_at', 'DESC')->available()->paginate(25);
        return view('admin.vendas.orcamentos',[
            'orcamentos' => $orcamentos
        ]);
    }
}
