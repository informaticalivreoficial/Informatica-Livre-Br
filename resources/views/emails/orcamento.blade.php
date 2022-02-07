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
            <div style="float:left; font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold; text-align:right;">
                #Solicitação de orçamento pelo Site
            </div>
            <div style="float:right; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold;">
                Enviada @php echo date('d/m/Y'); @endphp
            </div>                        
        </div>
        <div style="background:#FFF; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
            <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Dados da Mensagem</h1>
            <p style="padding-left:10px;">
            <strong>Nome: </strong><strong style="color:#09F;">{{ $nome }}</strong>
            <br />
            <strong>E-mail: </strong><strong style="color:#09F;">{{ $email }}</strong>
            <br />
            <strong>Mensagem: </strong>            
            </p>
            <p style="padding-left:10px;font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#09F;">@php echo nl2br($mensagem); @endphp</p>
        </div> 
    </div>
{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por {{env('DESENVOLVEDOR')}} <br> <a href="mailto:{{env('DESENVOLVEDOR_EMAIL')}}">{{env('DESENVOLVEDOR_EMAIL')}}</a></pre></div>
    @endcomponent
@endslot

@endcomponent