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
                                                <th class="cs-width_3 cs-semi_bold cs-primary_color cs-focus_bg">Qtd</th>
                                                <th class="cs-width_4 cs-semi_bold cs-primary_color cs-focus_bg">Descrição</th>
                                                <th class="cs-width_1 cs-semi_bold cs-primary_color cs-focus_bg">Valor</th>
                                                <th class="cs-width_2 cs-semi_bold cs-primary_color cs-focus_bg cs-text_right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="cs-width_3">App Development</td>
                                                <td class="cs-width_4">Mobile &Ios Application Development</td>
                                                <td class="cs-width_1">$460</td>
                                                <td class="cs-width_2 cs-text_right">$920</td>
                                            </tr>                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div class="cs-invoice_footer cs-border_top">
                                    <div class="cs-left_footer cs-mobile_hide">
                                        <p class="cs-mb0">
                                            <b class="cs-primary_color">Additional Information:</b>
                                        </p>
                                        <p class="cs-m0">
                                            At check in you may need to present the credit <br>card used for payment of this ticket.
                                        </p>
                                    </div>
                                    <div class="cs-right_footer">
                                        <table>
                                            <tbody>
                                                <tr class="cs-border_left">
                                                    <td class="cs-width_3 cs-semi_bold cs-primary_color cs-focus_bg">Subtoal</td>
                                                    <td class="cs-width_3 cs-semi_bold cs-focus_bg cs-primary_color cs-text_right">$1140</td>
                                                </tr>
                                                <tr class="cs-border_left">
                                                    <td class="cs-width_3 cs-semi_bold cs-primary_color cs-focus_bg">Tax</td>
                                                    <td class="cs-width_3 cs-semi_bold cs-focus_bg cs-primary_color cs-text_right">-$20</td>
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
                                                <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color cs-text_right">{{$fatura->valor}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="cs-note">
                            <div class="cs-note_left">
                                <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                    <path d="M416 221.25V416a48 48 0 01-48 48H144a48 48 0 01-48-48V96a48 48 0 0148-48h98.75a32 32 0 0122.62 9.37l141.26 141.26a32 32 0 019.37 22.62z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                                    <path d="M256 56v120a32 32 0 0032 32h120M176 288h160M176 368h160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
                                </svg>
                            </div>
                            <div class="cs-note_right">
                                <p class="cs-mb0">
                                    <b class="cs-primary_color cs-bold">Note:</b>
                                </p>
                                <p class="cs-m0">Here we can write a additional notes for the client to get a better understanding of this invoice.</p>
                            </div>
                        </div>
                    @else
                        <div class="cs-note_left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                <path d="M416 221.25V416a48 48 0 01-48 48H144a48 48 0 01-48-48V96a48 48 0 0148-48h98.75a32 32 0 0122.62 9.37l141.26 141.26a32 32 0 019.37 22.62z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                                <path d="M256 56v120a32 32 0 0032 32h120M176 288h160M176 368h160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
                            </svg>
                        </div>
                        <div class="cs-note_right">
                            <p class="cs-mb0">
                                <b class="cs-primary_color cs-bold">Note:</b>
                            </p>
                            <p class="cs-m0">Here we can write a additional notes for the client to get a better understanding of this invoice.</p>
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
                        <span>Print</span>
                    </a>
                    <button id="download_btn" class="cs-invoice_btn cs-color2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <title>Download</title>
                            <path d="M336 176h40a40 40 0 0140 40v208a40 40 0 01-40 40H136a40 40 0 01-40-40V216a40 40 0 0140-40h40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M176 272l80 80 80-80M256 48v288"/>
                        </svg>
                        <span>Download</span>
                    </button>
                </div>
            </div>
        </div>
        <script src="{{url(asset('frontend/assets/css/fatura.js'))}}"></script>        
        <script src="{{url(asset('frontend/assets/css/html2canvas.min.js'))}}"></script>        
    </body>
</html>
