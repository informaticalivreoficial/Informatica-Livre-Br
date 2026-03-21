<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-store mr-2"></i> Detalhes do Pedido</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{route('services.subscriptions.index')}}">Pedidos</a></li>
                        <li class="breadcrumb-item active">Detalhes do Pedido</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">                
                        <div class="invoice p-3 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <img width="{{env('LOGOMARCA_GERENCIADOR_WIDTH')}}" height="{{env('LOGOMARCA_GERENCIADOR_HEIGHT')}}" src="{{$configuracoes->getlogoadmin()}}" alt="{{$configuracoes->app_name}}">
                                    <small class="float-right">Data: {{Carbon\Carbon::parse($subscription->created_at)->format('d/m/Y')}}</small>
                                </h4>
                            </div>
                        </div>
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                De
                                <address>
                                <strong>{{$configuracoes->app_name}}</strong><br>
                                @if($configuracoes->street)	
                                    {{$configuracoes->street}}
                                        @if($configuracoes->number)
                                        , {{$configuracoes->number}}
                                        @endif
                                    @if($configuracoes->neighborhood)
                                    <br>{{$configuracoes->neighborhood}}
                                        @if($configuracoes->zipcode)
                                        , {{$configuracoes->zipcode}}
                                        @endif
                                    @endif
                                    @if($configuracoes->city)  
                                    <br>{{$configuracoes->city}} - {{$configuracoes->state}}
                                    @endif
                                @endif
                                    <br>
                                    Fone: {{$configuracoes->whatsapp}}<br>
                                    Email: {{$configuracoes->email}}
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                Para
                                <address>
                                    <strong>{{$subscription->company->alias_name}}</strong><br>
                                    @if($subscription->company->street)	
                                    {{$subscription->company->street}}
                                        @if($subscription->company->number)
                                        , {{$subscription->company->number}}
                                        @endif
                                    @if($subscription->company->neighborhood)
                                    <br>{{$subscription->company->neighborhood}}
                                        @if($subscription->company->zipcode)
                                        , {{$subscription->company->zipcode}}
                                        @endif
                                    @endif
                                    @if($subscription->company->city)  
                                    <br>{{$subscription->company->city}}
                                    @endif
                                @endif
                                    <br>
                                    Fone: {{$subscription->company->cell_phone}}<br>
                                    Email: {{$subscription->company->email}}
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                <b>Pedido #{{$subscription->id}}</b><br>
                                <br>
                                <b>Vencimento:</b> {{Carbon\Carbon::parse($subscription->next_billing_at)->format('d/m/Y')}}<br>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <p class="lead">Observações:</p>
                                <p>{{$subscription->service->description}}</p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Intervalo</th>
                                        <th>Descrição</th>                                  
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$subscription->interval}}</td>
                                        <td>{{$subscription->service->name}}</td>
                                        <td>R$ {{number_format($subscription->amount, 2, ',', '.')}}</td>    
                                    </tr>                                                            
                                </tbody>
                                </table>
                            </div>
                        </div>
                        
                        {{--  
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
                                @if ($pedido->tipo_pedido == 2)
                                    <tr>
                                        <th>Total:</th>
                                        <td>R$ {{str_replace(',00', '', $pedido->valor)}}</td>
                                    </tr> 
                                @else
                                    <tr>
                                        <th style="width:50%">Subtotal:</th>
                                        <td>R$ {{str_replace(',00', '', $pedido->itensTotalValor())}}</td>
                                    </tr>                               
                                    <tr>
                                        <th>Total:</th>
                                        <td>R$ {{str_replace(',00', '', $pedido->itensTotalValor())}}</td>
                                    </tr>
                                @endif                               
                                </table>
                                </div>
                            </div>
                        </div>
                        --}}
                        <div class="row no-print">
                            <div class="col-12">
                                <a href="javascript:void(0)" onclick="window.print();"class="btn btn-default">
                                    <i class="fas fa-print"></i> Imprimir
                                </a>  
                                
                                @if ($subscription->invoices()->count() > 0)
                                    <a 
                                        style="margin-right: 5px;" 
                                        class="btn btn-success float-right" 
                                        href="{{ route('services.invoices.index', $subscription->id) }}">
                                        <i class="far fa-credit-card"></i> Ver Faturas
                                    </a>
                                @endif
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            </section>
        </div>
</div>
