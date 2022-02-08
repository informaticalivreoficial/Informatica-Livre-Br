@component('mail::layout')

{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <!-- header here -->
    @endcomponent
@endslot
{{-- Body --}}
    <div style="width:100%; padding:10px;">        
        <div style="background:#ffefa4; overflow:hidden; padding:15px;">                        
            <div style="float:left; font:14px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold; text-align:right;">
                ✅ Fromulário Preenchido pelo Cliente
            </div>
            <div style="float:right; font:12px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold;">
                Enviada @php echo date('d/m/Y'); @endphp
            </div>                        
        </div>
        <div style="background:#FFF; font:14px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
            <h1 style="font-size:18px; color:#000; background:#F4F4F4; padding:10px;">Cliente: <strong style="color:#09F;">{{ $nome }}</strong></h1>
            <p style="padding-left:10px;">
                <strong>E-mail: </strong>
                <span style="color:#09F;">{{ $email }}</span> 
                <strong style="margin-left: 10px;">Telefone: </strong>
                <span style="color:#09F;">{{ $telefone }}</span>            
                <br />
                <strong>Cpf: </strong>
                <span style="color:#09F;">{{ $cpf }}</span>                       
            </p>
            <h1 style="font-size:18px; color:#000; background:#F4F4F4; padding:10px;">Empresa: <strong style="color:#09F;">{{ $empresa }}</strong></h1>
            <p style="padding-left:10px;">
                <strong>E-mail: </strong>
                <span style="color:#09F;">{{ $email_empresa }}</span> 
                <strong style="margin-left: 10px;">Telefone: </strong>
                <span style="color:#09F;">{{ $telefone_empresa }}</span>            
                <br />
                <strong>Celular: </strong>
                <span style="color:#09F;">{{ $celular }}</span>  
                <strong style="margin-left: 10px;">WhatsApp: </strong>
                <span style="color:#09F;">{{ $whatsapp }}</span> 
                <br><br>
                <strong>Informações Adicionais: </strong>
            </p>
            <p style="padding-left:10px;font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#09F;">@php echo nl2br($notasadicionais); @endphp</p>
            <p style="padding-left:10px;font:16px Trebuchet MS, Arial, Helvetica, sans-serif;">🌐 Acessar Painel Gerenciador: <a href="{{env('APP_URL')}}/admin">Clique Aqui</a></p>
        </div> 
    </div>
{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por {{env('DESENVOLVEDOR')}} <br> <a href="mailto:{{env('DESENVOLVEDOR_EMAIL')}}">{{env('DESENVOLVEDOR_EMAIL')}}</a></pre></div>
    @endcomponent
@endslot

@endcomponent