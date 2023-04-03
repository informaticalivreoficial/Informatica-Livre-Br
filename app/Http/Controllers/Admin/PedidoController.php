<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PedidoRequest;
use App\Models\Empresa;
use App\Models\Gateway;
use App\Models\ItemPedido;
use App\Models\Orcamento;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use WebMaster\PagHiper\PagHiper;

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
        $orcamentos = Orcamento::orderBy('created_at', 'DESC')->get();
        $gateways = Gateway::orderBy('created_at', 'DESC')->get();

        return view('admin.pedidos.create',[
            'empresas' => $empresas,
            'orcamentos' => $orcamentos,
            'gateways' => $gateways
        ]);
    }

    public function store(PedidoRequest $request)
    {
        dd($request->all());  
    }

    public function show($id)
    {
        $pedido = Pedido::where('id', $id)->first();
        $gateways = Gateway::orderBy('created_at', 'ASC')->available()->get();
        return view('admin.pedidos.show',[
            'pedido' => $pedido,
            'gateways' => $gateways
        ]);
    }

    public function pagar($pedido)
    {
        $pedido = Pedido::where('id', $pedido)->first();
        $data = [
            'order_id' => $pedido->id,
            'payer_name' => $pedido->getEmpresa->alias_name,
            'payer_email' => $pedido->getEmpresa->email,
            'payer_cpf_cnpj' => ($pedido->getEmpresa->cnpj ? $pedido->getEmpresa->cnpj : $pedido->getEmpresa->owner->cpf),
            'days_due_date' => Carbon::parse($pedido->vencimento)->diffInDays(Carbon::parse(Carbon::now()))
        ];

        $itensPedido = ItemPedido::where('pedido', $pedido->id)->get();
        if(!empty($itensPedido) && $itensPedido->count() > 0){
            $items = [];
            foreach($itensPedido as $item){
                $items['items'][] = [                    
                    'description' => $item->descricao,
                    'quantity' => $item->quantidade,
                    'item_id' => $item->id,
                    'price_cents' => str_replace(',', '.', str_replace('.', '', $item->valor))                    
                ];
            }
        }        
        $array = array_merge($data, $items);
        $this->gerarBoleto($array);
    }

    public function gerarBoleto($data)
    {
        $paghiper = new PagHiper(
            env('PAGHIPER_APIKEY'), 
            env('PAGHIPER_TOKEM')
        );
        $transaction = $paghiper->billet()->create($data);
    }

    public function getTransaction(Request $request)
    {
        $paghiper = new PagHiper(
            env('PAGHIPER_APIKEY'), 
            env('PAGHIPER_TOKEM')
        );
        $transaction = $paghiper->notification()->response(
            $_POST['notification_id'], 
            $_POST['idTransacao']
        );
    }
    
}
