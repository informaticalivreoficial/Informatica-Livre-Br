@component('mail::layout')

{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <!-- header here -->
    @endcomponent
@endslot

@section('css')

@endsection
{{-- Body --}}
    <div style="width:100%;">        
        <div style="background:#20C997; overflow:hidden; padding:15px;color:#fff;">                        
            <div style="float:left; font:20px Trebuchet MS, Arial, Helvetica, sans-serif;font-weight:bold; text-align:right;">
                ⚓️ Bem vindo a Bordo!
            </div>
            <div style="float:right; font:16px Trebuchet MS, Arial, Helvetica, sans-serif;font-weight:bold;">
                Enviada @php echo date('d/m/Y'); @endphp
            </div>                        
        </div>
        <div style="background:#FFF; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
            <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Olá <strong style="color:#09F;">{{getPrimeiroNome($name)}}</strong>!</h1>
            <p style="padding-left:10px;">
                Recebemos seu pedido de compra do passeio {{$passeio}}.                
            </p> 
            <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Dados da Compra</h1>
            <table>
                <tr>
                <th style="width: 30%;text-align:left;">Passeio</th>
                <th style="text-align:center;">Data</th>                        
                <th style="width: 25%;text-align:center;">----------</th>                        
                <th style="text-align:center;">Quantidade</th>
                <th style="text-align:center;">Valor</th>
                </tr>
                <tr>
                <td style="text-align:left;">{{$passeio}}</td>
                <td style="text-align:center;">{{$data_passeio}}</td>
                <td>Adultos</td>
                <td style="text-align:center;">{{$qtd_adultos}}</td>
                <td style="text-align:center;">
                    R$ {{$valor_adulto}}
                </td>
                </tr>
                        <tr>
                <td></td>
                <td></td>
                <td>Crianças de 0 a 5 anos</td>
                <td style="text-align:center;">{{$qtd_zerocinco}}</td>
                <td style="text-align:center;">
                    R$ {{$valorCri05}}
                </td>                        
                </tr>
                        <tr>
                <td></td>
                <td></td>
                <td>Crianças de 6 a 12 anos</td>
                <td style="text-align:center;">{{$qtd_seisdoze}}</td>
                <td style="text-align:center;">
                    R$ {{$valorCri06}}
                </td>                        
                </tr>
                        <tr>
                <td></td>
                <td></td>
                <td style="text-align:right;"><b>Total:</b></td>
                <td style="text-align:center;">{{$total_passageiros}}</td>
                <td style="text-align:center;">
                    R$ {{$total}}
                </td>                        
                </tr>                      
            </table>    
            @component('mail::button', ['url' => route('web.passeios.voucher',['token' => $token]), 'color' => 'blue'])
                Clique aqui para visualizar seu pedido 
            @endcomponent         
             
        
{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por {{env('DESENVOLVEDOR')}} <br> <a href="mailto:{{env('DESENVOLVEDOR_EMAIL')}}">{{env('DESENVOLVEDOR_EMAIL')}}</a></pre></div>
    @endcomponent
@endslot

@endcomponent