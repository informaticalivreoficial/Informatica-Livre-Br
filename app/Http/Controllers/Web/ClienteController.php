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
    public function login()
    {
        return view('web.cliente.login');
    }

    public function fatura($uuid)
    {
        $fatura = Fatura::where('uuid', $uuid)->first();
        $gateways = Gateway::orderBy('created_at', 'ASC')->available()->get();         
        return view('web.cliente.fatura',[
            'fatura' => $fatura,
            'gateways' => $gateways,
        ]);
    }

    public function pagar($uuid)
    {
        $fatura = Fatura::where('uuid', $uuid)->first();
        
        if($fatura->gateway == 2){
            $data = [
                'order_id' => $fatura->id,
                'payer_name' => $fatura->pedidoObject->getEmpresa->alias_name,
                'payer_email' => $fatura->pedidoObject->getEmpresa->email,
                'payer_phone' => str_replace(['.', '-', '(', ')', ' '], "", $fatura->pedidoObject->getEmpresa->celular), // fixou ou móvel
                'payer_street' => $fatura->pedidoObject->getEmpresa->rua,
                'payer_number' => $fatura->pedidoObject->getEmpresa->num,
                'payer_complement' => $fatura->pedidoObject->getEmpresa->complemento,
                'payer_district' => $fatura->pedidoObject->getEmpresa->bairro,
                'payer_city' => $fatura->pedidoObject->getEmpresa->cidade,
                'payer_state' => $fatura->pedidoObject->getEmpresa->uf, // apenas sigla do estado
                'payer_zip_code' => str_replace(['.', '-', ' '], "", $fatura->pedidoObject->getEmpresa->cep),
                'payer_cpf_cnpj' => str_replace(['.','-','/'], "", ($fatura->pedidoObject->getEmpresa->cnpj ? $fatura->pedidoObject->getEmpresa->cnpj : $fatura->pedidoObject->getEmpresa->owner->cpf)),
                'days_due_date' => (Carbon::parse($fatura->vencimento)->lt(Carbon::parse(Carbon::now())) ? '5' : Carbon::parse($fatura->vencimento)->diffInDays(Carbon::parse(Carbon::now()))),
                'type_bank_slip' => 'boletoa4',
                'notification_url' => 'https://webhook.site/d8762b26-8f9c-4e78-9cf0-aa4d08e6cddc',
                // 'items' => [
                //     [
                //         'item_id' => 1,
                //         'description' => $fatura->pedidoObject->planoObject->name,
                //         'quantity' => 1,
                //         'price_cents' => str_replace('.', '', $fatura->valor)
                //     ]
                // ]
            ];

            if(!empty($fatura->pedidoObject->itens()) && $fatura->pedidoObject->itens->count() > 0){
                $itensPedido = ItemPedido::where('pedido', $fatura->id)->get();
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
            }elseif($fatura->pedidoObject->tipo_pedido == 2){               
                $items['items'][] = [                                        
                    'item_id' => 1,
                    'description' => $fatura->pedidoObject->service->name,
                    'quantity' => 1,
                    'price_cents' => str_replace(',', '', filter_var($fatura->valor, FILTER_SANITIZE_NUMBER_INT))   
                ];
            }else{
                $items['items'][] = [                                        
                    'item_id' => 1,
                    'description' => $fatura->pedidoObject->getProduto->name,
                    'quantity' => 1,
                    'price_cents' => str_replace(',', '', $fatura->valor)   
                ];
            }
            
            $array = array_merge($data, $items); 
            return $this->gerarBoleto($array); 
                  
        }
         
               
    }

    public function gerarBoleto($data)
    {        
        $paghiper = new PagHiper(
            env('PAGHIPER_APIKEY'), 
            env('PAGHIPER_TOKEM')
        );
        $transaction = $paghiper->billet()->create($data);
        
        if(!empty($transaction) && $transaction['result'] == 'success'){
            $fatura = Fatura::where('id', $transaction['order_id'])->first();
            $fatura->transaction_id = $transaction['transaction_id'];
            $fatura->status = $transaction['status'];
            $fatura->valor = floatval(number_format($transaction['value_cents'] / 100, 2, '.', ''));
            $fatura->url_slip = $transaction['bank_slip']['url_slip'];
            $fatura->digitable_line = $transaction['bank_slip']['digitable_line'];
            $fatura->vencimento = $transaction['due_date'];
            $fatura->save(); 
            return Redirect::away($fatura->url_slip);           
        }  
    }

    
}
