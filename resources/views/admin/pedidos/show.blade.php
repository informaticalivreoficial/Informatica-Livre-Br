@extends('adminlte::page')

@section('title', 'Visualizar Pedido')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
       <h1>Pedido</h1>
    </div>
    <div class="col-sm-6">
       <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('pedidos.index')}}">Pedidos</a></li>
          <li class="breadcrumb-item active">Pedido</li>
       </ol>
    </div>
 </div>
@stop

@section('content')    
    <section class="content">
       <div class="container-fluid">
          <div class="row">
             <div class="col-12">                
                <div class="invoice p-3 mb-3">
                   <div class="row">
                      <div class="col-12">
                         <h4>
                            <img width="{{env('LOGOMARCA_GERENCIADOR_WIDTH')}}" height="{{env('LOGOMARCA_GERENCIADOR_HEIGHT')}}" src="{{$configuracoes->getlogoadmin()}}" alt="{{$configuracoes->nomedosite}}">
                            <small class="float-right">Data: {{Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y')}}</small>
                         </h4>
                      </div>
                   </div>
                   <div class="row invoice-info">
                      <div class="col-sm-4 invoice-col">
                         De
                         <address>
                            <strong>{{$configuracoes->nomedosite}}</strong><br>
                            795 Folsom Ave, Suite 600<br>
                            San Francisco, CA 94107<br>
                            Fone: {{$configuracoes->whatsapp}}<br>
                            Email: {{$configuracoes->email}}
                         </address>
                      </div>
                      <div class="col-sm-4 invoice-col">
                         Para
                         <address>
                            <strong>{{$pedido->getEmpresa->alias_name}}</strong><br>
                            795 Folsom Ave, Suite 600<br>
                            San Francisco, CA 94107<br>
                            Fone: {{$pedido->getEmpresa->celular}}<br>
                            Email: {{$pedido->getEmpresa->email}}
                         </address>
                      </div>
                      <div class="col-sm-4 invoice-col">
                         <b>Pedido #{{$pedido->id}}</b><br>
                         <br>
                         <b>Order ID:</b> 4F3S8J<br>
                         <b>Payment Due:</b> 2/22/2014<br>
                         <b>Account:</b> 968-34567
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-12 table-responsive">
                         <table class="table table-striped">
                            <thead>
                               <tr>
                                  <th>Qtd</th>
                                  <th>Descrição</th>                                  
                                  <th>Subtotal</th>
                               </tr>
                            </thead>
                            <tbody>
                                @if (!empty($pedido->itens()) && $pedido->itens->count() > 0)
                                    @foreach ($pedido->itens()->get() as $item)
                                    <tr>
                                        <td>{{$item->quantidade}}</td>
                                        <td>{{$item->descricao}}</td>                                        
                                        <td>R$ {{str_replace(',00', '', $item->valor)}}</td>
                                    </tr>
                                    @endforeach
                                @endif                                                               
                            </tbody>
                         </table>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-6">
                         <p class="lead">Forma de Pagamento:</p>
                         @if (!empty($gateways) && $gateways->count() > 0)
                            @foreach ($gateways as $gateway)
                                <label class="gateway" for="{{$gateway->id}}">
                                    <img class="m-2" width="120" src="{{$gateway->logomarca}}" alt="{{$gateway->nome}}">
                                </label>
                                <input class="gateway" type="radio" name="gateway" value="{{$gateway->id}}" id="{{$gateway->id}}" />
                            @endforeach
                         @endif                       
                      </div>
                      <div class="col-6">
                         <p class="lead">Total Hoje {{Carbon\Carbon::parse(now())->format('d/m/Y')}}</p>
                         <div class="table-responsive">
                            <table class="table">
                               <tr>
                                  <th style="width:50%">Subtotal:</th>
                                  <td>R$ {{str_replace(',00', '', $pedido->itensTotalValor())}}</td>
                               </tr>
                               <tr>
                                  <th>Desconto (5%)</th>
                                  <td>$10.34</td>
                               </tr>
                               <tr>
                                  <th>Total:</th>
                                  <td>$265.24</td>
                               </tr>
                            </table>
                         </div>
                      </div>
                   </div>
                   <div class="row no-print">
                      <div class="col-12">
                         <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                         <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                         Payment
                         </button>
                         <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                         <i class="fas fa-download"></i> Generate PDF
                         </button>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </section>
 </div>
 
@endsection

@section('css')
    <style>
        input[type="radio"] {
                visibility: hidden;
        }
        .selecionada {
            opacity: 0.5;
        }
    </style>
@endsection

@section('js')
    <script>
        $(function () {           
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".gateway").each(function(){
                if($(this).find('input[type="radio"]').first().attr("checked")){
                    $(this).addClass('selecionada');
                }else{
                    $(this).removeClass('selecionada');
                }
            });

            $(".gateway").on("click", function(e){
                $(".gateway").removeClass('selecionada');
                $(this).addClass('selecionada');
                var $radio = $(this).find('input[type="radio"]');
                $radio.prop("checked",!$radio.prop("checked"));

                e.preventDefault();
            });           
            
        });
    </script>
@endsection