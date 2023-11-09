<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="ThemeMarch">
        <title>Fatura</title>
        <link rel="stylesheet" href="{{url(asset('frontend/assets/css/fatura.css'))}}">

        <link rel="icon" href="{{$configuracoes->getfaveicon()}}" type="image/x-icon">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <style>
            .badge-primary {
                color: #fff;
                background-color: #007bff;
            }
            .badge-success {
                color: #fff;
                background-color: #28a745;
            }
            .badge-warning {
                color: #fff;
                background-color: #ffc107;
            }
            .badge-danger {
                color: #fff;
                background-color: #dc3545;
            }
            .badge-info {
                color: #fff;
                background-color: #17a2b8;
            }
            .badge {
                display: inline-block;
                padding: 0.25em 0.4em;
                font-size: 85%;
                font-weight: 700;
                line-height: 1;
                text-align: center;
                white-space: nowrap;
                vertical-align: baseline;
                border-radius: 0.25rem;
                transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            }

            input[type="radio"] {
                    visibility: hidden;
            }
            .selecionada {
                opacity: 0.5;
            }
        </style>
    </head>
    <body>
        <div class="cs-container">
            <div class="cs-invoice cs-style1">
                <div class="cs-invoice_in">
                    @if (!empty($fatura) && $fatura->count() > 0)
                        <div class="cs-invoice_head cs-type1 cs-mb25">
                            <div class="cs-invoice_left">
                                <p class="cs-invoice_number cs-primary_color cs-mb5 cs-f16">
                                    <b class="cs-primary_color">Fatura:</b>
                                    #{{$fatura->id}}
                                </p>
                                <p class="cs-invoice_date cs-primary_color cs-m0" style="margin-bottom: 20px;">     
                                    <b class="cs-primary_color">Data: </b>                               
                                    {{Carbon\Carbon::parse($fatura->created_at)->format('d/m/Y')}}
                                    <br>
                                    <b class="cs-primary_color">Vencimento: </b>
                                    @if (Carbon\Carbon::parse($fatura->vencimento)->lt(Carbon\Carbon::now()))
                                        <span style="color: #dc3545;">{{Carbon\Carbon::parse($fatura->vencimento)->format('d/m/Y')}}</span>
                                    @else
                                        {{Carbon\Carbon::parse($fatura->vencimento)->format('d/m/Y')}}
                                    @endif
                                    
                                    <br>
                                    {!!$fatura->getStatus()!!}                                    
                                </p>
                                @if (Carbon\Carbon::parse($fatura->vencimento)->lt(Carbon\Carbon::now()))
                                    @if ($fatura->status != 'canceled' || $fatura->status != 'paid' || $fatura->status != 'completed')
                                        <a target="_blank" href="{{route('web.pagar', [ 'uuid' => $fatura->uuid ])}}" class="cs-invoice_btn cs-color2 setBoleto cs-hide_print">
                                            <span>Pagar Fatura</span>
                                        </a>
                                    @endif
                                @else
                                    @if ($fatura->status != 'canceled' || $fatura->status != 'paid' || $fatura->status != 'completed')
                                        <a target="_blank" href="{{$fatura->url_slip ?? route('web.pagar', [ 'uuid' => $fatura->uuid ])}}" class="cs-invoice_btn cs-color2 setBoleto cs-hide_print">
                                            <span>Pagar Fatura</span>
                                        </a>
                                    @endif
                                @endif
                            </div>
                            <div class="cs-invoice_right cs-text_right">
                                <div class="cs-logo cs-mb5">                                
                                    <img 
                                        width="{{env('LOGOMARCA_GERENCIADOR_WIDTH')}}" 
                                        height="{{env('LOGOMARCA_GERENCIADOR_HEIGHT')}}" 
                                        src="{{$configuracoes->getlogoadmin()}}" 
                                        alt="{{$configuracoes->nomedosite}}">
                                </div>                                
                            </div>
                        </div>
                        <div class="cs-invoice_head cs-mb10">
                            <div class="cs-invoice_left">
                                <b class="cs-primary_color">De:</b>
                                <p>
                                    {{$configuracoes->nomedosite}} <br>
                                    @if($configuracoes->rua)	
                                        {{$configuracoes->rua}}
                                            @if($configuracoes->num)
                                            , {{$configuracoes->num}}
                                            @endif
                                        @if($configuracoes->bairro)
                                        <br>{{$configuracoes->bairro}}
                                            @if($configuracoes->cep)
                                            , {{$configuracoes->cep}}
                                            @endif
                                        @endif
                                        @if($configuracoes->cidade)  
                                        <br>{{\App\Helpers\Cidade::getCidadeNome($configuracoes->cidade, 'cidades')}}
                                        @endif
                                    @endif
                                </p>
                            </div>
                            <div class="cs-invoice_right cs-text_right">
                                <b class="cs-primary_color">Para:</b>
                                <p>
                                    {{$fatura->pedidoObject->getEmpresa->alias_name}} <br>
                                    @if($fatura->pedidoObject->getEmpresa->rua)	
                                        {{$fatura->pedidoObject->getEmpresa->rua}}
                                            @if($fatura->pedidoObject->getEmpresa->num)
                                            , {{$fatura->pedidoObject->getEmpresa->num}}
                                            @endif
                                        @if($fatura->pedidoObject->getEmpresa->bairro)
                                        <br>{{$fatura->pedidoObject->getEmpresa->bairro}}
                                            @if($fatura->pedidoObject->getEmpresa->cep)
                                            , {{$fatura->pedidoObject->getEmpresa->cep}}
                                            @endif
                                        @endif
                                        @if($fatura->pedidoObject->getEmpresa->cidade)  
                                        <br>{{$fatura->pedidoObject->getEmpresa->cidade}}
                                        @endif
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="cs-table cs-style1">
                            <div class="cs-round_border">
                                <div class="cs-table_responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="cs-width_1 cs-semi_bold cs-primary_color cs-focus_bg">Qtd</th>
                                                <th class="cs-width_4 cs-semi_bold cs-primary_color cs-focus_bg">Descrição</th>
                                                <th class="cs-width_2 cs-semi_bold cs-primary_color cs-focus_bg">Valor</th>
                                                <th class="cs-width_3 cs-semi_bold cs-primary_color cs-focus_bg cs-text_right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($fatura->pedidoObject->itens()) && $fatura->pedidoObject->itens->count() > 0)
                                                @foreach ($fatura->pedidoObject->itens()->get() as $item)
                                                <tr>
                                                    <td class="cs-width_1">{{$item->quantidade}}</td>
                                                    <td class="cs-width_4">{{$item->descricao}}</td>                                        
                                                    <td class="cs-width_2">R$ {{str_replace(',00', '', $item->valor)}}</td>
                                                    <td class="cs-width_3 cs-text_right">R$ {{str_replace(',00', '', ($item->quantidade * $item->valor))}}</td>
                                                </tr>
                                                @endforeach
                                            @elseif($fatura->pedidoObject->tipo_pedido == 2)
                                                <tr>
                                                    <td class="cs-width_1">1</td>
                                                    <td class="cs-width_4">{{$fatura->pedidoObject->service->name}}</td>                                        
                                                    <td class="cs-width_2">R$ {{$fatura->valor}}</td>
                                                    <td class="cs-width_3 cs-text_right">R$ {{$fatura->valor}}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td class="cs-width_1">1</td>
                                                    <td class="cs-width_4">{{$fatura->getProduto->name}}</td>                                        
                                                    <td class="cs-width_2">R$ {{$fatura->valor}}</td>
                                                    <td class="cs-width_3 cs-text_right">R$ {{$fatura->valor}}</td>
                                                </tr>
                                            @endif                                                                                      
                                        </tbody>
                                    </table>
                                </div>
                                <div class="cs-invoice_footer cs-border_top">
                                    <div class="cs-left_footer cs-mobile_hide">
                                        @if ($fatura->notas_adicionais)
                                            <p class="cs-mb0">
                                                <b class="cs-primary_color">Informações adicionais:</b>
                                            </p>
                                            <p class="cs-m0">
                                                {{$fatura->notas_adicionais}}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="cs-right_footer">
                                        <table>
                                            <tbody>
                                                <tr class="cs-border_left">
                                                    <td class="cs-width_3 cs-semi_bold cs-primary_color cs-focus_bg">Subtotal</td>
                                                    <td class="cs-width_3 cs-semi_bold cs-focus_bg cs-primary_color cs-text_right">R$ {{$fatura->valor ?? str_replace(',00', '', $fatura->itensTotalValor())}}</td>
                                                </tr>
                                                <tr class="cs-border_left">
                                                    <td class="cs-width_3 cs-semi_bold cs-primary_color cs-focus_bg">Taxas</td>
                                                    <td class="cs-width_3 cs-semi_bold cs-focus_bg cs-primary_color cs-text_right">--</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="cs-invoice_footer">
                                <div class="cs-left_footer cs-mobile_hide"></div>
                                <div class="cs-right_footer">
                                    <table>
                                        <tbody>
                                            <tr class="cs-border_none">
                                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color">Total</td>
                                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color cs-text_right">R$ {{$fatura->valor ?? str_replace(',00', '', $fatura->itensTotalValor())}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif 
                </div>

                <div style="width: 100% !important;" class="cs-hide_print">
                    @if ($fatura->pedidoObject->notas_adicionais)
                        <p >*{{$fatura->pedidoObject->notas_adicionais}}</p>
                    @endif
                    @if ($fatura->status != 'canceled' || $fatura->status != 'paid' || $fatura->status != 'completed')
                        <div style="width: 100%;display:block;">
                            <p class="lead">Forma de Pagamento:</p>
                            @if (!empty($gateways) && $gateways->count() > 0)
                            @foreach ($gateways as $gateway)
                                <label class="gateway {{ ($gateway->id == $fatura->gateway ? 'selecionada' : '') }}" for="{{$gateway->id}}" data-id="{{ $gateway->id }}">
                                    <img class="m-2" width="120" src="{{$gateway->logomarca}}" alt="{{$gateway->nome}}">
                                </label>
                                <input class="gateway" type="radio" name="gateway" value="{{$gateway->id}}" {{ ($gateway->id == $fatura->gateway ? 'checked' : '') }} />
                            @endforeach
                            @endif                       
                        </div>  
                    @endif
                    
                    <div style="width: 100%;display:flex;justify-content:flex-end">
                        <a href="javascript:window.print()" class="cs-invoice_btn cs-color1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                <path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                                <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                                <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                                <circle cx="392" cy="184" r="24"/>
                            </svg>
                            <span>Imprimir</span>
                        </a>
                        @if (Carbon\Carbon::parse($fatura->vencimento)->lt(Carbon\Carbon::now()))
                            @if ($fatura->status != 'canceled' || $fatura->status != 'paid' || $fatura->status != 'completed')
                                <a target="_blank" href="{{route('web.pagar', [ 'uuid' => $fatura->uuid ])}}" class="cs-invoice_btn cs-color2 setBoleto">
                                    <span>Pagar Fatura</span>
                                </a>
                            @endif
                        @else
                            @if ($fatura->status != 'canceled' || $fatura->status != 'paid' || $fatura->status != 'completed')
                                <a target="_blank" href="{{$fatura->url_slip ?? route('web.pagar', [ 'uuid' => $fatura->uuid ])}}" class="cs-invoice_btn cs-color2 setBoleto">
                                    <span>Pagar Fatura</span>
                                </a>
                            @endif
                        @endif
                                                
                    </div>
                                        
                </div>
            </div>
        </div>
        <script src="{{url(asset('frontend/assets/js/core.min.js'))}}"></script>
        <script src="{{url(asset('frontend/assets/js/fatura.js'))}}"></script>        
        <script src="{{url(asset('frontend/assets/js/html2canvas.min.js'))}}"></script>   
        <script>
            $(function () {   
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            
                //$(".gateway").each(function(){
                    // if($(this).find('input[type="radio"]').first().attr("checked")){
                    //     $(this).addClass('selecionada');
                    // }else{
                    //     $(this).removeClass('selecionada');
                    // }                    
                //});

                $(".gateway").on("click", function(e){  
                    
                    $(".gateway").removeClass('selecionada');
                    $(this).addClass('selecionada');

                    var gateway_id = $(this).data('id');
                    $.ajax({
                        type: 'GET',
                        dataType: 'JSON',
                        url: "{{ route('web.SetGateway') }}",
                        data: {
                            'gateway': gateway_id,
                            'pedido': "{{$fatura->id}}"
                        },
                        success:function(data) {
                            
                        }
                    });
                                       
                    var $radio = $(this).find('input[type="radio"]');
                    $radio.prop("checked",!$radio.prop("checked"));
                     

                    e.preventDefault();
                });

                $('.setBoleto').click(function() {
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                });
                
            });
        </script>     
    </body>
</html>
