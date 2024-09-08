@component('mail::layout')

{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <style>
            .att{
                font-size:11px;color:rgb(61, 59, 59);
            }
        </style>
    @endcomponent
@endslot
{{-- Body --}}
    <div style="width:100%;">        
        <div style="background:#ffefa4; overflow:hidden; padding:15px;">                        
            <div style="float:left; font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold; text-align:right;">
                #Fatura online
            </div>
            <div style="float:right; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold;">
                Enviada @php echo date('d/m/Y'); @endphp
            </div>                        
        </div>
        <div style="background:#FFF; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
            <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Olá <strong style="color:#09F;">{{$nome}}</strong>!</h1>
            <p>
                Você está recebendo a sua fatura para pagamento. Para acessá-la, 
                basta clicar no link abaixo:
            </p>            
            <p style="text-align: center;">
                @component('mail::button', ['url' => route('web.fatura',['uuid' => $uuid]), 'color' => 'green'])
                    Clique aqui para visualizar sua Fatura! 
                @endcomponent
            </p>
            <p><b>Resumo da fatura:</b><br>                
                Data de vencimento: {{Carbon\Carbon::parse($fatura->vencimento)->format('d/m/Y')}}<br>
                Valor da fatura: R$ {{$fatura->valor}}<br>
                Descrição: {{$fatura->content}}</p>           
            <p>att,<br>
                Renato Montanari<br>
                Analista de TI<br>
                suporte@informaticalivre.com.br<br>
                WhatsApp (12) 99138-5030<br> 
            </p>
        </div>        
    </div>
{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por {{env('DESENVOLVEDOR')}} <br> <a href="mailto:{{env('DESENVOLVEDOR_EMAIL')}}">{{env('DESENVOLVEDOR_EMAIL')}}</a></pre></div>
    @endcomponent
@endslot

@endcomponent