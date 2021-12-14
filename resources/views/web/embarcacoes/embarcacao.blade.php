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
                <h1 class="text-bold">Embarcação - {{$embarcacao->name}}</h1>
            </div>
            <ul class="list-inline list-inline-icon list-inline-icon-type-1 list-inline-icon-extra-small list-inline-icon-white p offset-top-30 offset-md-top-40">
                <li><a href="{{route('web.home')}}" class="text-white">Início</a></li>
                <li><a href="{{route('web.embarcacoes')}}" class="text-white">Embarcações</a></li>
                <li>Embarcação - {{$embarcacao->name}}</li>
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
                <h2 class="text-bold text-center text-sm-left">{{$embarcacao->name}}</h2>
                <hr class="divider hr-sm-left-0 bg-gray-darker">
                <div class="offset-top-30 offset-md-top-60">
                    {!!$embarcacao->content!!}                    
                </div>
            </div>
            </div>
            <div class="cell-xs-10 cell-sm-6 offset-top-69 offset-sm-top-0">
                <h4 class="text-bold text-sm-left">Informações</h4>
                <div class="offset-top-20 inset-left-30">
                <!-- List Marked-->
                <ul class="list list-marked list-marked-icon text-dark">
                    <li> <b>Capacidade de Passageiros:</b> {{$embarcacao->passageiros}}</li>
                    <li> <b>Tripulantes:</b> {{$embarcacao->tripulantes}}</li>
                    <li> <b>Comprimento:</b> {{$embarcacao->comprimento}}</li>
                    <li> <b>Ano de Construcao:</b> {{$embarcacao->ano_de_construcao}}</li>
                </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-60 section-sm-0 bg-zircon">
    <div class="shell list-index-lg">
      <div class="range range-xs-center range-sm-right">
        <div class="cell-xs-10 cell-sm-6 section-image-aside section-image-aside-left text-left">
          <div style="background-image: url({{$embarcacao->cover()}}); background-position: center center;" class="section-image-aside-img veil reveal-sm-block"></div>
          <div class="section-image-aside-body section-sm-top-90 section-sm-bottom-90 section-md-top-100 section-md-bottom-162 inset-left-15 inset-right-15 inset-sm-left-50 inset-lg-left-100">
            @if($embarcacao->images()->get()->count())
                <div class="offset-top-15 offset-md-top-20">
                    <div class="range range-xs-center section-gallery-lg-column-offset">
                    @foreach($embarcacao->images()->get() as $image)
                        <div class="cell-xs-4 cell-md-6 inset-lg-left-13 inset-lg-right-13 offset-top-20">
                            <div class="inset-left-30 inset-right-30 inset-xs-left-0 inset-xs-right-0">
                                <a class="thumbnail-rayen" href="{{ $image->getUrlImageAttribute() }}" data-toggle="lightbox"
                                    data-gallery="property-gallery" data-type="image">
                                    <span class="figure">
                                        <img width="160" height="160" src="{{ $image->getUrlCroppedAttribute() }}" alt="{{ $embarcacao->name }}">
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