<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ItemPedidoRequest;
use App\Http\Requests\Admin\PedidoRequest;
use App\Mail\Admin\FaturaClientSend;
use App\Models\Configuracoes;
use App\Models\Empresa;
use App\Models\Fatura;
use App\Models\Gateway;
use App\Models\ItemPedido;
use App\Models\Orcamento;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
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
        $empresas = Empresa::orderBy('created_at', 'DESC')->available()->get();
        $orcamentos = Orcamento::orderBy('created_at', 'DESC')->available()->get();
        $produtos = Produto::orderBy('created_at', 'DESC')->available()->get();
        $servicos = Servico::orderBy('created_at', 'DESC')->available()->get();
        $gateways = Gateway::orderBy('created_at', 'DESC')->get();

        return view('admin.pedidos.create',[
            'empresas' => $empresas,
            'orcamentos' => $orcamentos,
            'gateways' => $gateways,
            'produtos' => $produtos,
            'servicos' => $servicos
        ]);
    }

    public function storeProduct(PedidoRequest $request)
    {        
        $produto = Produto::where('id', $request->produto)->first();
        $vencimento = strtotime(Carbon::createFromFormat('d/m/Y',  $request->vencimento));
        //Cria Pedido
        $data = [
            'produto'    => $request->produto,
            'empresa'    =>  $request->empresa,
            'gateway'    => $request->gateway,
            'vencimento' => date('Y-m-d', $vencimento),
            'valor'      => str_replace(',', '', str_replace('.', '', $produto->valor)),
            'status'     => $request->status,
            'tipo_pedido'     => $request->tipo_pedido,
            'notas_adicionais' => $request->notas_adicionais,
            'created_at' => now()
        ];

        $pedidoCreate = Pedido::create($data);
        $pedidoCreate->save();

        return Redirect::route('pedidos.edit', [
            'id' => $pedidoCreate->id,
        ])->with(['color' => 'success', 'message' => 'Pedido cadastrado com sucesso!']);
    }

    public function storeService(PedidoRequest $request)
    {        
        $servico = Servico::where('id', $request->servico)->first();
        $vencimento = strtotime(Carbon::createFromFormat('d/m/Y',  $request->vencimento));
        //Cria Pedido
        $data = [
            'servico'    => $request->servico,
            'empresa'    =>  $request->empresa,
            'gateway'    => $request->gateway,
            'vencimento' => date('Y-m-d', $vencimento),
            'valor'      => str_replace(',', '', str_replace('.', '', $servico->valor)),
            'status'     => $request->status,
            'tipo_pedido'     => $request->tipo_pedido,
            'notas_adicionais' => $request->notas_adicionais,
            'created_at' => now()
        ];

        $pedidoCreate = Pedido::create($data);
        $pedidoCreate->save();

        return Redirect::route('pedidos.edit', [
            'id' => $pedidoCreate->id,
        ])->with(['color' => 'success', 'message' => 'Pedido cadastrado com sucesso!']);
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

    public function edit($id)
    {
        $pedido = Pedido::where('id', $id)->first();
        $empresas = Empresa::orderBy('created_at', 'DESC')->available()->get();
        $produtos = Produto::orderBy('created_at', 'DESC')->available()->get();
        $servicos = Servico::orderBy('created_at', 'DESC')->available()->get();
        $gateways = Gateway::orderBy('created_at', 'ASC')->available()->get();
        
        return view('admin.pedidos.edit', [
            'pedido' => $pedido,
            'gateways' => $gateways,
            'produtos' => $produtos,
            'empresas' => $empresas,
            'servicos' => $servicos
        ]);
    }

    public function updateProduct(PedidoRequest $request, $id)
    {        
        $pedidoUpdate = Pedido::where('id', $id)->first();
        $produto = Produto::where('id', $request->produto)->first();
        $vencimento = strtotime(Carbon::createFromFormat('d/m/Y',  $request->vencimento));

        $data = [
            'produto'    => $request->produto,
            'empresa'    =>  $request->empresa,
            'gateway'    => $request->gateway,
            'vencimento' => date('Y-m-d', $vencimento),
            'valor'      => str_replace(',', '', str_replace('.', '', $produto->valor)),
            'status'     => $request->status,
            'notas_adicionais' => $request->notas_adicionais,
            'tipo_pedido'     => $request->tipo_pedido
        ];

        //Cria Fatura
        if($request->gerarfatura === 'on'){
            $valor_fatura = ($request->periodo == 1 ? $plano->valor_mensal : 
                            ($request->periodo == 3 ? $plano->valor_trimestral : 
                            ($request->periodo == 6 ? $plano->valor_semestral : 
                            ($request->periodo == 12 ? $plano->valor_anual : null))));
            $dado = [
                'pedido'     => $criarPedido->id,
                'user'       => $request->user,
                'valor'      => str_replace(',', '', str_replace('.', '', $valor_fatura)),
                'vencimento' => date('Y-m-d', $vencimento),
                'status'     => 'pending',
                'created_at' => now()
            ];

            $criarFatura = Fatura::create($dado);
            $criarFatura->save();
            
            foreach(range(2, $request->periodo) as $parcela){
                $vencimento = strtotime('+30 days', $vencimento);
                $dados[] = [
                    'pedido'     => $criarPedido->id,
                    'user'       => $request->user,
                    'valor'      => str_replace(',', '', str_replace('.', '', $valor_fatura)),
                    'vencimento' => date('Y-m-d', $vencimento),
                    'status'     => 'pending',
                    'created_at' => now()
                ]; 
                
            }     
            $criarFaturas = Fatura::insert($dados);
        }
        
        $pedidoUpdate->update($data);

        return Redirect::route('pedidos.edit', [
            'id' => $pedidoUpdate->id,
        ])->with(['color' => 'success', 'message' => 'Pedido atualizado com sucesso!']);
    }

    public function updateService(PedidoRequest $request, $id)
    {        
        $pedidoUpdate = Pedido::where('id', $id)->first();
        $servico = Servico::where('id', $request->servico)->first();
        $vencimento = strtotime(Carbon::createFromFormat('d/m/Y',  $request->vencimento));

        $valor = ($request->periodo == 1 ? $servico->valor_mensal : 
                 ($request->periodo == 3 ? $servico->valor_trimestral : 
                 ($request->periodo == 6 ? $servico->valor_semestral : 
                 ($request->periodo == 12 ? $servico->valor_anual : null))));

        $data = [
            'servico'    => $request->servico,
            'empresa'    =>  $request->empresa,
            'gateway'    => $request->gateway,
            'vencimento' => date('Y-m-d', $vencimento),
            'valor'      => str_replace(',', '', str_replace('.', '', $valor)),
            'status'     => $request->status,
            'notas_adicionais' => $request->notas_adicionais,
            'tipo_pedido'     => $request->tipo_pedido,
            'periodo' => $request->periodo
        ];

        //Cria Fatura
        if($request->gerarfatura === 'on'){
            
            $dados = [
                'pedido'     => $pedidoUpdate->id,
                'valor'      => str_replace(',', '', str_replace('.', '', $valor)),
                'vencimento' => date('Y-m-d', $vencimento),
                'gateway'    => $request->gateway,
                'status'     => 'pending',
                'created_at' => now()
            ];

            $criarFatura = Fatura::create($dados);
            $criarFatura->save();            
        }
        
        $pedidoUpdate->update($data);

        return Redirect::route('pedidos.edit', [
            'id' => $pedidoUpdate->id,
        ])->with(['color' => 'success', 'message' => 'Pedido atualizado com sucesso!']);
    }

    public function pagar($pedido)
    {
        $pedido = Pedido::where('id', $pedido)->first();
        $data = [
            'order_id' => $pedido->id,
            'payer_name' => $pedido->getEmpresa->alias_name,
            'payer_email' => $pedido->getEmpresa->email,
            'payer_cpf_cnpj' => ($pedido->getEmpresa->cnpj ? $pedido->getEmpresa->cnpj : $pedido->getEmpresa->owner->cpf),
            'days_due_date' => Carbon::parse($pedido->vencimento)->diffInDays(Carbon::parse(Carbon::now())),
            'type_bank_slip' => 'boletoa4',
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
        
        if(!empty($transaction) && $transaction['result'] == 'success'){
            $pedido = Pedido::where('id', $transaction['order_id'])->first();
            $pedido->transaction_id = $transaction['transaction_id'];
            $pedido->status = $transaction['status'];
            $pedido->valor = $transaction['value_cents'];
            $pedido->url_slip = $transaction['bank_slip']['url_slip'];
            $pedido->digitable_line = $transaction['bank_slip']['digitable_line'];
            $pedido->vencimento = $transaction['due_date'];
            $pedido->save();
        }

        return Redirect::route('admin.pedidos.index')->with([
            'color' => 'success', 
            'message' => $transaction['response_message']
        ]);
        
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

    public function statusBoleto(Request $request)
    {        
        $paghiper = new PagHiper(
            env('PAGHIPER_APIKEY'), 
            env('PAGHIPER_TOKEM')
        );

        $transaction = $paghiper->billet()->status($request->pedido);
        
        if($transaction['result'] === 'success'){
            $pedido = Pedido::where('id', $transaction['order_id'])->first();
            $pedido->status = $transaction['status'];
            $pedido->vencimento = $transaction['due_date'];
            $pedido->digitable_line = $transaction['bank_slip']['digitable_line'];
            $pedido->url_slip = $transaction['bank_slip']['url_slip'];
            $pedido->url_slip_pdf = $transaction['bank_slip']['url_slip_pdf'];
            $pedido->save();
            $json = ['success' => 'Fatura atualizada!'];
        }else{
            $json = ['error' => 'Erro ao Atualizar!'];
        }
        return response()->json($json);
    }

    public function sendFormFaturaClient(Request $request)
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $pedido = Pedido::where('id', $request->id)->first();
        $pedido->form_sendat = now();
        $pedido->save();

        $data = [            
            'sitename' => $Configuracoes->nomedosite,
            'siteemail' => $Configuracoes->email,
            'client_name' => $pedido->getEmpresa->owner->name,
            'client_email' => $pedido->getEmpresa->owner->email,
            'uuid' => $pedido->uuid,
            'empresa' => $pedido->getEmpresa->alias_name,
        ];

        Mail::send(new FaturaClientSend($data, $pedido));
        
        return response()->json([
            'retorno' => true
        ]);
    }

    public function storeItem(ItemPedidoRequest $request)
    {
        $itemCreate = ItemPedido::create($request->all()); 
        $itemCreate->fill($request->all()); 
        dd($itemCreate);
        return response()->json([
            'success' => 'success'
        ]);
    }

    public function SetGateway(Request $request)
    {        
        $pedido = Pedido::find($request->pedido);
        $pedido->gateway = $request->gateway;
        $pedido->save();
        return response()->json(['success' => true]);
    }
    
}
