@extends('web.master.master')

@section('content')
<header class="page-head slider-menu-position">
    @include('web.include.header')
    
    <section class="section-height-800 breadcrumb-modern rd-parallax context-dark bg-gray-darkest text-lg-left">
        <div data-speed="0.2" data-type="media" data-url="{{url(asset('frontend/assets/images/backgrounds/background-01-1920x900.jpg'))}}" class="rd-parallax-layer"></div>
        <div data-speed="0" data-type="html" class="rd-parallax-layer">
        <div class="bg-primary-chathams-blue-reverse">
            <div class="shell section-top-57 section-bottom-30 section-md-top-185">
            <div class="veil reveal-md-block">
                <h1 class="text-bold">Comprar passeio</h1>
            </div>
            <ul class="list-inline list-inline-icon list-inline-icon-type-1 list-inline-icon-extra-small list-inline-icon-white p offset-top-30 offset-md-top-40">
                <li><a href="{{route('web.home')}}" class="text-white">Início</a></li>
                <li><a href="{{route('web.roteiros')}}" class="text-white">Roteiros</a></li>
                <li><a href="{{route('web.roteiro',['slug' => $passeio->roteiro->slug])}}" class="text-white">{{$passeio->roteiro->name}}</a></li>
                <li>Comprar passeio</li>
            </ul>
            </div>
        </div>
        </div>
    </section>  
</header>

<main class="page-content">
<!-- Login and Register-->
<section class="section-top-90 section-bottom-90 section-md-top-82 section-md-bottom-100 section-md-111 text-sm-left">
    <div class="shell">
    <div class="range range-xs-center range-md-left">
        <div class="cell-xs-10 cell-sm-8 cell-md-6 cell-lg-7">        
            <div>                
                <form class="text-left j_formsubmit" method="post" action="" autocomplete="off">
                    @csrf
                    <div id="js-contact-result"></div>
                    <input type="hidden" name="id_passeio" value="{{$passeio->id}}">
                    <div class="range">
                        <div style="margin-top: 10px;" class="cell-lg-6">
                            <div class="form-group form-group-label-outside">
                                <label for="responsavel" class="form-label form-label-outside text-dark">*Responsável</label>
                                <input id="responsavel" type="text" name="nome" class="form-control">
                            </div>                            
                        </div>
                        <div style="margin-top: 10px;" class="cell-lg-6">
                            <div class="form-group form-group-label-outside">
                                <label for="email" class="form-label form-label-outside text-dark">*Email</label>
                                <input id="email" type="text" name="email" class="form-control">
                            </div>                            
                        </div>
                        <div style="margin-top: 10px;" class="cell-lg-6">
                            <div class="form-group form-group-label-outside">
                                <label for="cpf" class="form-label form-label-outside text-dark">*CPF</label>
                                <input id="cpf" type="text" name="cpf" class="form-control cpfmask">
                            </div>                            
                        </div>
                        <div style="margin-top: 10px;" class="cell-lg-6">
                            <div class="form-group form-group-label-outside">
                                <label for="celular" class="form-label form-label-outside text-dark">*Celular</label>
                                <input id="celular" type="text" name="celular" class="form-control celularmask">
                            </div>                            
                        </div>
                        @php
                            if ($passeio->valor_venda_promocional != ''){
                                $valorAdulto = substr(str_replace('.', '', str_replace(',', '.', $passeio->valor_venda_promocional)), 0, -2);
                            }else{
                                $valorAdulto = substr(str_replace('.', '', str_replace(',', '.', $passeio->valor_venda)), 0, -2); 
                            }
                        @endphp
                        <div style="margin-top: 10px;" class="cell-lg-4">
                            <div class="form-group form-group-label-outside">
                                <label class="form-label-outside text-dark">Adultos</label>
                                <select name="adultos" class="form-control select_adultos">
                                    @for($i = 1; $i <= 15; $i++)
                                        <option value="{{$i}}">{{$i}} -
                                        @if ($passeio->valor_venda_promocional != '')
                                            (R$ {{$i * $valorAdulto}})
                                        @else
                                            (R$ {{$i * $valorAdulto}}) 
                                        @endif
                                        </option>
                                    @endfor                                    
                                </select>
                            </div>                            
                        </div>
                        <div style="margin-top: 10px;" class="cell-lg-4">
                            <div class="form-group form-group-label-outside">
                                <label class="form-label-outside text-dark">Crianças de 0 a 5</label>
                                <select name="valor_v_zerocinco" class="form-control select_zerocinco">
                                    @for($i = 0; $i <= 15; $i++)
                                        <option value="{{$i}}">{{$i}} -
                                            (R$ {{$i * substr(str_replace('.', '', str_replace(',', '.', $passeio->valor_v_zerocinco)), 0, -2)}})
                                        </option>
                                    @endfor
                                </select>
                            </div>                            
                        </div>
                        <div style="margin-top: 10px;" class="cell-lg-4">
                            <div class="form-group form-group-label-outside">
                                <label class="form-label-outside text-dark">Crianças de 6 a 12</label>
                                <select name="valor_v_seisdoze" class="form-control select_seisdoze">
                                    @for($i = 0; $i <= 15; $i++)
                                        <option value="{{$i}}">{{$i}} -
                                            (R$ {{$i * substr(str_replace('.', '', str_replace(',', '.', $passeio->valor_v_seisdoze)), 0, -2)}})
                                        </option>
                                    @endfor
                                </select>
                            </div>                            
                        </div>
                        <div style="margin-top: 12px;" class="cell-lg-6">
                            <div class="form-group form-group-label-outside">
                                <label for="datapasseio" class="form-label form-label-outside text-dark">*Data do Passeio</label>
                                <input id="datapasseio" type="text" name="datapasseio" class="form-control datepicker-here" data-language='pt-BR'>
                            </div>                            
                        </div>
                        <div style="margin-top: 10px;" class="cell-lg-6">
                            <div class="offset-top-15 offset-sm-top-30" style="width: 100%;">
                                <div class="reveal-xs-inline-block text-middle" style="width: 100%;">
                                    <button style="width: 100%;" id="js-contact-btn" type="submit" class="btn btn-ripe-lemon">Finalizar >></button>
                                </div>                        
                            </div>                           
                        </div>
                    </div>                    
                    
                    
                </form>
            </div>
        </div>
        <div class="section-60 cell-xs-10 cell-sm-8 cell-md-6 cell-lg-5">
            <h4>Meu Pedido</h4>
            <p>
                <b>Roteiro:</b> {{$passeio->roteiro->name}}<br>
                <b>Local de saída:</b> {{$passeio->rua}}
                {{($passeio->rua && $passeio->num ? ', '.$passeio->num : '')}}
                {{($passeio->rua && $passeio->num ? ' - '.$passeio->bairro : $passeio->bairro)}}<br>
                <b>Horário de Saída:</b> {{$passeio->saida}}<br>
                <b>Duração do Passeio:</b> {{$passeio->duracao}}hs<br>
                <b>Data Selecionada:</b> <br>
            </p>
            <hr>
            <p>
                <b>Adultos:</b> <span class="qtdAdulto">R$ 0,00</span><br>
                <b>Crianças de 0 a 5 anos:</b> <span class="qtd05">R$ 0,00</span><br>
                <b>Crianças de 6 a 12 anos:</b> <span class="qtd612">R$ 0,00</span><br>
                <b>Total:</b> <span class="valorTotal">R$ 0,00</span><br>
            </p>
        </div>
    </div>
    </div>
</section>
</main>
@endsection

@section('css')
<style>
    html.lt-ie-10 * + [class*='cell-'], * + [class*='cell-'], html.lt-ie-10 * + .range-sm, * + .range-sm {
    margin-top: 0px;
}
</style>
<link href="{{url(asset('backend/plugins/airdatepicker/css/datepicker.min.css'))}}" rel="stylesheet" type="text/css">
@endsection

@section('js')
<script src="{{url(asset('backend/plugins/airdatepicker/js/datepicker.min.js'))}}"></script>
<script src="{{url(asset('backend/plugins/airdatepicker/js/i18n/datepicker.pt-BR.js'))}}"></script>
<script src="{{url(asset('backend/assets/js/jquery.mask.js'))}}"></script>
<script>
    $(document).ready(function () { 
        var $Cpf = $(".cpfmask");
        $Cpf.mask('000.000.000-00', {reverse: true});
        var $celularmask = $(".celularmask");
        $celularmask.mask('(99) 99999-9999', {reverse: false});
    });

    $(function(){
       //Desabilita dias da semana
        var disabledDays = [
            {{$passeio->domingo == 1 ? '' : '0'}},
            {{$passeio->segunda == 1 ? '' : '1'}},
            {{$passeio->terca == 1 ? '' : '2'}},
            {{$passeio->quarta == 1 ? '' : '3'}},
            {{$passeio->quinta == 1 ? '' : '4'}},
            {{$passeio->sexta == 1 ? '' : '5'}},
            {{$passeio->sabado == 1 ? '' : '6'}},
        ];
         
        $('.datepicker-here').datepicker({
            autoClose: true,            
            minDate: new Date(),
            position: "top right", //'right center', 'right bottom', 'right top', 'top center', 'bottom center'
            onRenderCell: function (date, cellType) {
                if (cellType == 'day') {
                    var day = date.getDay(),
                        isDisabled = disabledDays.indexOf(day) != -1;

                    return {
                        disabled: isDisabled
                    }
                }
            }
        });  
             
    });

    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select_adultos').change(function(){
            var valueadulto = $(this).val();
            var valor = (valueadulto * {{$valorAdulto}});
            $('.qtdAdulto').html(parseFloat(valor).toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));            
        });

        $('.select_zerocinco').change(function(){
            var valuezerocinco = $(this).val();
            var valorzerocinco = (valuezerocinco * {{substr(str_replace('.', '', str_replace(',', '.', $passeio->valor_v_zerocinco)), 0, -2)}});
            $('.qtd05').html(parseFloat(valorzerocinco).toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));            
        });

        $('.select_seisdoze').change(function(){
            var valueseisdoze = $(this).val();
            var valorseisdoze = (valueseisdoze * {{substr(str_replace('.', '', str_replace(',', '.', $passeio->valor_v_seisdoze)), 0, -2)}});
            $('.qtd612').html(parseFloat(valorseisdoze).toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
        });

        $('.select_adultos,.select_zerocinco,.select_seisdoze').on('change', function() {
            var valor = parseFloat($('.select_adultos').val() * {{$valorAdulto}}) || 0;
            var valorzerocinco = parseFloat($('.select_zerocinco').val() * {{substr(str_replace('.', '', str_replace(',', '.', $passeio->valor_v_zerocinco)), 0, -2)}}) || 0;
            var valorseisdoze = parseFloat($('.select_seisdoze').val() * {{substr(str_replace('.', '', str_replace(',', '.', $passeio->valor_v_seisdoze)), 0, -2)}}) || 0;

            var totalValorAdicionalDX = valor + valorzerocinco + valorseisdoze;

            $('.valorTotal').html(parseFloat(totalValorAdicionalDX).toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
        });

        $('.j_formsubmit').submit(function (){
            var form = $(this);
            var dataString = $(form).serialize();

            $.ajax({
                url: "{{ route('web.passeios.carrinhocreate') }}",
                data: dataString,
                type: 'GET',
                dataType: 'JSON',
                beforeSend: function(){
                    form.find("#js-contact-btn").attr("disabled", true);
                    form.find('#js-contact-btn').html("Carregando...");                
                    form.find('.alert').fadeOut(500, function(){
                        $(this).remove();
                    });
                },
                success: function(resposta){
                    $('html, body').animate({scrollTop:$('#js-contact-result').offset().top-130}, 'slow');
                    if(resposta.error){
                        form.find('#js-contact-result').html('<div class="alert alert-danger error-msg">'+ resposta.error +'</div>');
                        form.find('.error-msg').fadeIn();                    
                    }else{
                        form.find('.error-msg').fadeIn(); 
                        setTimeout(function() {
                            window.location.href = resposta.redirect;
                        }, 2000); 
                    }
                },
                complete: function(resposta){
                    form.find("#js-contact-btn").attr("disabled", false);
                    form.find('#js-contact-btn').html("Finalizar >>");                                
                }

            });

            return false;
        });
    });
</script>
@endsection