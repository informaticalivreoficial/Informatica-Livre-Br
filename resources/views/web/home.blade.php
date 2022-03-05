@extends('web.master.master')

@section('content')

@if (!empty($slides) && $slides->count() > 0)
<section class="swiper-container swiper-slider swiper-variant-1 bg-black" data-loop="false" data-autoplay="5500" data-simulate-touch="true">
    <div class="swiper-wrapper text-center">
        @foreach ($slides as $slide)
            <div class="swiper-slide" data-slide-bg="{{$slide->getimagem()}}">
                <div class="swiper-slide-caption">
                    <div class="container">
                    <div class="row justify-content-md-center">
                        <div class="col-md-11 col-lg-10 col-xl-9">
                        <h2 class="slider-header" data-caption-animate="fadeInUp" data-caption-delay="0s">{{$slide->titulo}}</h2>
                        <p class="text-bigger slider-text" data-caption-animate="fadeInUp" data-caption-delay="100">{{$slide->subtitulo ?? ''}}</p>
                        @if ($slide->botaolabel)
                            <div class="group-xl-responsive">
                                <a class="btn btn-xl btn-primary-contrast" data-caption-animate="fadeInUp" data-caption-delay="250" href="{{$slide->link}}" {{($slide->target == 1 ? 'target="_blank"' : '')}}>{{$slide->botaolabel}}</a>
                            </div>
                        @endif                        
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        @endforeach 
    </div>
    <div class="swiper-scrollbar"></div>
    <div class="swiper-nav-wrap">
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</section>    
@endif


<section class="section section-50 section-md-60 section-lg-75 bg-gray-lighter novi-background">
    <div class="container">
        <div class="row row-40 justify-content-md-center align-items-md-center flex-row-md-reverse">
            <div class="col-md-5 col-xl-4 text-start">
                <div class="box-container-small text-start">
                    <h3><span>Super Imóveis</h3>
                    <p class="text-secondary">
                    Administre e venda imóveis de uma forma inteligente e profissional, nosso sistema é 
                    integrado com as principais plataformas de imóveis do mercado imobiliário.
                    </p>
                    <a class="btn btn-xl btn-primary" href="https://superimoveis.info/planos" target="_blank">Quero 1 Mês Gratis</a>
                </div>
            </div>
            <div class="col-md-7 col-xl-8">
                <div class="image-wrap-1"><img src="{{url(asset('frontend/assets/images/landing-1-790x377.png'))}}" alt="" width="790" height="377" />
                </div>
            </div>
        </div>
    </div>
</section>


@if (!empty($empresas) && $empresas->count() > 0)
<section class="section section-66 section-md-top-90 section-md-bottom-75">
    <div class="container">
        <h3>Nossos Clientes</h3>
        <div class="slick-slider slick-slider-1" data-arrows="false" data-loop="true" data-dots="true" data-swipe="true" data-items="1" data-sm-items="2" data-md-items="3" data-lg-items="5" data-xl-items="6" data-slide-to-scroll="1">
            @php $rowCount = 0; @endphp
            <div class="item">
                @foreach ($empresas as $empresa)
                    <div class="link-image-wrap">
                        <a class="link-image" href="javascript:void(0)">
                            <img src="{{$empresa->nocover()}}" alt="" width="126" height="68" />
                        </a>
                    </div>
                    @php $rowCount++  @endphp
                    @php if($rowCount % 2 == 0) echo '</div><div class="item">';  @endphp                    
                @endforeach  
            </div>                      
        </div>
    </div>
</section>  

<section class="section section-60 section-md-100 bg-accent novi-background">
    <div class="container text-center text-lg-start">
        <div class="row row-30 align-items-md-center justify-content-lg-center">
            <div class="col-lg-8 col-xl-7">
                <h3>Solicite Agora um Orçamento</h3>
            </div>
            <div class="col-lg-4 col-xl-3">
                <a class="btn btn-xl btn-black-outline" href="{{route('web.formorcamento')}}">Quero um Orçamento</a>
            </div>
        </div>
    </div>
</section>
@endif

@endsection

@section('css')

@endsection

@section('js')

@endsection