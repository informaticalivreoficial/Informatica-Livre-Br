<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use WebMaster\PagHiper\PagHiper;

class ClienteController extends Controller
{
    public function login()
    {
        return view('web.cliente.login');
    }

    public function fatura($uuid)
    {
        $fatura = Pedido::where('uuid', $uuid)->first();
        $gateways = Gateway::orderBy('created_at', 'ASC')->available()->get();
         
        return view('web.cliente.fatura',[
            'fatura' => $fatura,
            'gateways' => $gateways,
        ]);
    }

    public function pagarPagHiper($fatura)
    {
        $fatura = Pedido::where('id', $fatura)->first();
        $data = [
            'order_id' => $fatura->id,
            'payer_name' => $fatura->getEmpresa->alias_name,
            'payer_email' => $fatura->getEmpresa->email,
            'payer_phone' => $fatura->getEmpresa->celular, // fixou ou móvel
            'payer_street' => 'Av Brigadeiro Faria Lima',
            'payer_number' => '1461',
            'payer_complement' => 'Torre Sul 4º Andar',
            'payer_district' => 'Jardim Paulistano',
            'payer_city' => 'São Paulo',
            'payer_state' => 'SP', // apenas sigla do estado
            'payer_zip_code' => '01452002',
            'payer_cpf_cnpj' => ($fatura->getEmpresa->cnpj ? $fatura->getEmpresa->cnpj : $fatura->getEmpresa->owner->cpf),
            'days_due_date' => Carbon::parse($fatura->vencimento)->diffInDays(Carbon::parse(Carbon::now())),
            'type_bank_slip' => 'boletoa4',
            'notification_url' => 'https://mysite.com/notification/paghiper/',
            'items' => [
                [
                    'item_id' => 1,
                    'description' => $fatura->pedidoObject->planoObject->name,
                    'quantity' => 1,
                    'price_cents' => str_replace('.', '', $fatura->valor)
                ]
            ]
        ];  
        dd($data);
        //return $this->gerarBoleto($data);       
    }
}
