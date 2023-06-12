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
                                <p class="cs-invoice_date cs-primary_color cs-m0">     
                                    <b class="cs-primary_color">Data: </b>                               
                                    {{Carbon\Carbon::parse($fatura->created_at)->format('d/m/Y')}}
                                    <br>
                                    <b class="cs-primary_color">Vencimento: </b>
                                    {{Carbon\Carbon::parse($fatura->vencimento)->format('d/m/Y')}}
                                    <br>
                                    {!!$fatura->getStatus()!!}
                                </p>
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
                                    {{$fatura->getEmpresa->alias_name}} <br>
                                    @if($fatura->getEmpresa->rua)	
                                        {{$fatura->getEmpresa->rua}}
                                            @if($fatura->getEmpresa->num)
                                            , {{$fatura->getEmpresa->num}}
                                            @endif
                                        @if($fatura->getEmpresa->bairro)
                                        <br>{{$fatura->getEmpresa->bairro}}
                                            @if($fatura->getEmpresa->cep)
                                            , {{$fatura->getEmpresa->cep}}
                                            @endif
                                        @endif
                                        @if($fatura->getEmpresa->cidade)  
                                        <br>{{\App\Helpers\Cidade::getCidadeNome($fatura->getEmpresa->cidade, 'cidades')}}
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
                                            @if (!empty($fatura->itens()) && $fatura->itens->count() > 0)
                                                @foreach ($fatura->itens()->get() as $item)
                                                <tr>
                                                    <td class="cs-width_1">{{$item->quantidade}}</td>
                                                    <td class="cs-width_4">{{$item->descricao}}</td>                                        
                                                    <td class="cs-width_2">R$ {{str_replace(',00', '', $item->valor)}}</td>
                                                    <td class="cs-width_3 cs-text_right">R$ {{str_replace(',00', '', ($item->quantidade * $item->valor))}}</td>
                                                </tr>
                                                @endforeach
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
                                                    <td class="cs-width_3 cs-semi_bold cs-primary_color cs-focus_bg">Subtoal</td>
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

                <div class="cs-invoice_btns cs-hide_print">
                    <a href="javascript:window.print()" class="cs-invoice_btn cs-color1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                            <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                            <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                            <circle cx="392" cy="184" r="24"/>
                        </svg>
                        <span>Imprimir</span>
                    </a>
                    @if ($fatura->url_slip && $fatura->status != 'paid' && $fatura->status != 'completed' && $fatura->status != 'canceled')
                        <a href="{{$fatura->url_slip}}" target="_blank" class="cs-invoice_btn cs-color2">
                            <span>Pagar Fatura</span>
                        </a>
                    @endif                    
                </div>
            </div>
        </div>
        <script src="{{url(asset('frontend/assets/css/fatura.js'))}}"></script>        
        <script src="{{url(asset('frontend/assets/css/html2canvas.min.js'))}}"></script>        
    </body>
</html>
