@extends('web.master.master')


@section('content')
<header class="page-head slider-menu-position">
    @include('web.include.header')
    
    <section class="section-height-800 breadcrumb-modern rd-parallax context-dark bg-gray-darkest text-lg-left">
        <p>{{$roteiro->legendaimgcapa}}</p>
        <div data-speed="0.2" data-type="media" data-url="{{url(asset('frontend/assets/images/backgrounds/background-01-1920x900.jpg'))}}" class="rd-parallax-layer"></div>
        
        <div data-speed="0" data-type="html" class="rd-parallax-layer">
        <div class="bg-primary-chathams-blue-reverse">
            <div class="shell section-top-57 section-bottom-30 section-md-top-185">
            <div class="veil reveal-md-block">
                <h1 class="text-bold">Roteiro - {{$roteiro->name}}</h1>
            </div>
            <ul class="list-inline list-inline-icon list-inline-icon-type-1 list-inline-icon-extra-small list-inline-icon-white p offset-top-30 offset-md-top-40">
                <li><a href="{{route('web.home')}}" class="text-white">Início</a></li>
                <li><a href="{{route('web.roteiros')}}" class="text-white">Roteiros</a></li>
                <li>Roteiro - {{$roteiro->name}}</li>
            </ul>
            </div>
        </div>
        </div>
    </section>  
</header>

<main class="page-content">
<!-- Press-->
<section class="section-90 section-md-111 text-left">
    <div class="shell">
        <div class="range range-xs-center">
            <div class="cell-xs-10 cell-sm-6">
            <div class="inset-sm-right-20 inset-lg-righ-50">
                <h2 class="text-bold text-center text-sm-left">{{$roteiro->name}}</h2>
                <hr class="divider hr-sm-left-0 bg-gray-darker">
                <div class="offset-top-30 offset-md-top-60">
                    {!!$roteiro->content!!}
                    <p style="font-size: 0.875em;color:#999;">*{!!$roteiro->notasadicionais!!}</p>
                </div>
            </div>
            </div>
            <div class="cell-xs-10 cell-sm-6 offset-top-69 offset-sm-top-0">
            <div class="rd-google-map-xs">
                <!-- RD Google Map-->
                <div class="rd-google-map">
                    {!!$roteiro->mapadogoogle!!}                    
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
<section class="section-60 section-sm-0 bg-zircon">
    <div class="shell list-index-lg">
      <div class="range range-xs-center range-sm-right">
        <div class="cell-xs-10 cell-sm-6 section-image-aside section-image-aside-left text-left">
          <div style="background-image: url({{$roteiro->cover()}}); background-position: center center;" class="section-image-aside-img veil reveal-sm-block"></div>
          <p>Foto maior: {{$roteiro->legendaimgcapa}}</p>
          <div class="section-image-aside-body section-sm-top-90 section-sm-bottom-90 section-md-top-100 section-md-bottom-162 inset-left-15 inset-right-15 inset-sm-left-50 inset-lg-left-100">
            @if($roteiro->images()->get()->count())
                <div class="offset-top-15 offset-md-top-20">
                    <div class="range range-xs-center section-gallery-lg-column-offset">
                    @foreach($roteiro->images()->get() as $image)
                        <div class="cell-xs-4 cell-md-6 inset-lg-left-13 inset-lg-right-13 offset-top-20">
                            <div class="inset-left-30 inset-right-30 inset-xs-left-0 inset-xs-right-0">
                                <a class="thumbnail-rayen" href="{{ $image->getUrlImageAttribute() }}" data-toggle="lightbox"
                                    data-gallery="property-gallery" data-type="image">
                                    <span class="figure">
                                        <img width="160" height="160" src="{{ $image->getUrlCroppedAttribute() }}" alt="{{ $roteiro->name }}">
                                        <span class="figcaption">
                                            <span class="icon icon-xs fa-search-plus"></span>
                                        </span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>           
            @endif            
          </div>
        </div>
      </div>
    </div>
  </section>
  @if ($passeios->count() && $passeios->count() > 0)
    <section class="section-90 section-md-111 text-left bg-zircon" id="reservar">
        <div class="shell">
        <h2 class="text-bold text-center text-md-left">Passeios disponíveis para este roteiro</h2>
        <hr class="divider hr-md-left-0 bg-gray-darker">
        <div class="offset-top-30 offset-md-top-49">
            <!-- Classic Responsive Table-->
            <table data-responsive="true" class="table table-custom-white table-deals table-fixed table-hover-rows">
            <tr>
                <th>Embarque</th>
                <th>Saída</th>
                <th>Duração</th>
                <th>Vagas</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th></th>
            </tr>
            @foreach ($passeios as $passeio)
                <tr>
                    <td>
                        <b>{{$passeio->rua}}
                        {{($passeio->num ? ', '.$passeio->num : '')}}
                        {{($passeio->rua && $passeio->num ? ' - '.$passeio->bairro : $passeio->bairro)}}</b>
                    </td>
                    <td>{{$passeio->saida}}</td>
                    <td>{{$passeio->duracao}}hs</td>
                    @if(!empty($pedidos) && $pedidos->count() > 0 && $pedidos[0]->passeio_id == $passeio->id)
                        @php
                            $sum = 0;
                            foreach ($pedidos as $key => $pedido){
                                $sum += $pedido->total_passageiros;
                            }    
                            $total = $passeio->vagas - $sum;                       
                        @endphp
                        <td>{{$total}}</td>
                    @else
                    <td>{{$passeio->vagas}}</td>
                    @endif

                    @if ($passeio->venda == true && $passeio->locacao == false)
                        <td>Individual</td>
                        <td>
                            @if ($passeio->valor_venda_promocional != '')
                                R$ {{$passeio->valor_venda_promocional}}<br>
                                <span style="text-decoration: line-through">R$ {{$passeio->valor_venda}}</span>
                            @else
                                R$ {{$passeio->valor_venda}}
                            @endif
                        </td>                        
                    @elseif($passeio->venda == false && $passeio->locacao == true)
                        <td>Pacote</td>
                        <td>
                            @if ($passeio->valor_locacao_promocional != '')
                                R$ {{$passeio->valor_locacao_promocional}}<br>
                                <span style="text-decoration: line-through">R$ {{$passeio->valor_locacao}}</span>
                            @else
                                R$ {{$passeio->valor_locacao}}
                            @endif
                        </td>                        
                    @else
                        <td>
                            Individual<br>
                            Pacote
                        </td>
                        <td>
                            @if ($passeio->valor_venda_promocional != '' && $passeio->valor_locacao_promocional != '')
                                R$ {{$passeio->valor_venda_promocional}}<br>
                                <span style="text-decoration: line-through">R$ {{$passeio->valor_venda}}</span><br>
                                R$ {{$passeio->valor_locacao_promocional}}<br>
                                <span style="text-decoration: line-through">R$ {{$passeio->valor_locacao}}</span>
                            @elseif($passeio->valor_venda_promocional == '' && $passeio->valor_locacao_promocional != '')
                                R$ {{$passeio->valor_venda_promocional}}<br>
                                <span style="text-decoration: line-through">R$ {{$passeio->valor_venda}}</span>
                            @elseif($passeio->valor_venda_promocional != '' && $passeio->valor_locacao_promocional == '')
                                R$ {{$passeio->valor_locacao_promocional}}<br>
                                <span style="text-decoration: line-through">R$ {{$passeio->valor_locacao}}</span>
                            @else
                                R$ {{$passeio->valor_venda_promocional}}<br>
                                R$ {{$passeio->valor_locacao_promocional}}
                            @endif
                        </td>                        
                    @endif  
                    <td>
                        @if ($passeio->status == '1' && $total > 0)
                            <a href="{{route('web.passeios.comprar', ['passeio' => $passeio->id])}}"><button class="btn btn-xs btn-success">Comprar</button></a>
                        @else
                            Não Disponível
                        @endif
                    </td>                  
                </tr>
            @endforeach
            </table>
        </div>
        </div>
    </section>
  @endif  
</main>
@endsection

@section('css')
<style>
    iframe{
      height: 400px;
      width: 100%;
      display: inline-block;
      overflow: hidden"
    }
  </style>
@endsection

@section('js')
<script>
    $(function () {       

        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
            alwaysShowClose: true
            });
        });

    });
</script>

@endsection