@component('mail::layout')

{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <!-- header here -->
    @endcomponent
@endslot
{{-- Body --}}
    <div style="width:100%;">        
        <div style="background:#ffefa4; overflow:hidden; padding:15px;">                        
            <div style="float:left; font:18px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold; text-align:right;">
                {{$sitename}}
            </div>
            <div style="float:right; font:12px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold;">
                Recebido em @php echo date('d/m/Y'); @endphp
            </div>                        
        </div>
        <div style="background:#FFF; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
            <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Olá <strong style="color:#09F;">{{getPrimeiroNome($nome)}}</strong>!</h1>
            <p>Recebemos sua solicitação de orçamento pelo nosso site.</p>
            <p>Enviaremos o mais rápido possível uma resposta.</p>
            <p style="font-size:12px;line-height:18px;">
                <b>Nossos canais de atendimento:</b><br>
                Email: <a href="mailto:suporte@informaticalivre.com">suporte@informaticalivre.com</a><br>
                WhatsApp: <a href="tel:12991385030">(12) 99138-5030</a> 
            </p>
            <div style="background:#DFEFFF; padding:15px;font-size:11px;">
                <p>Este e-mail foi enviado automaticamente pelo nosso sistema. Por favor, não responder.</p>
            </div>
        </div>        
    </div>
{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por {{env('DESENVOLVEDOR')}} <br> <a href="mailto:{{env('DESENVOLVEDOR_EMAIL')}}">{{env('DESENVOLVEDOR_EMAIL')}}</a></pre></div>
    @endcomponent
@endslot

@endcomponent