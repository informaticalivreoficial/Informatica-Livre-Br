@extends('web.master.master')

@section('content')

<header class="page-head slider-menu-position">
    @include('web.include.header')
</header>

<main class="page-content"> 

<div class="section-lg-top-50">
            @if(!empty($slides) && $slides->count() > 0)
                <!-- Slide-->
                <div data-height="" data-min-height="200px" data-slide-effect="fade" data-autoplay="true" data-simulate-touch="false" data-loop="true" class="swiper-container swiper-slider swiper-slider-offset-top-inverse context-dark text-lg-left">
                <div class="swiper-wrapper">
                    @foreach ($slides as $slide)
                        <div data-slide-bg="{{$slide->getimagem()}}" class="swiper-slide">
                            <div class="swiper-slide-caption-wrap">
                                <div class="swiper-slide-caption">
                                <div class="shell">
                                    <div class="range range-xs-center range-xs-middle range-lg-left section-lg-top-50 section-xl-top-75">
                                        <div class="cell-xs-10 cell-md-7 cell-lg-6 cell-xl-7">
                                            <h1 data-caption-animate="fadeInDown" data-caption-delay="200" class="text-bold">{{$slide->titulo}}</h1>
                                            <div data-caption-animate="fadeInUp" data-caption-delay="600" class="offset-top-20 offset-lg-top-49">
                                            <p class="h6 text-mercury">
                                                {!!$slide->content!!}
                                            </p>
                                            </div>
                                            @if (!empty($slide->link))
                                                <div data-caption-animate="fadeInUp" data-caption-delay="800" class="offset-top-20">
                                                    <a {{($slide->target == '1' ? 'target=target_blank ' : '')}}href="{{$slide->link}}" class="btn btn-default btn-skew">Ver mais</a>
                                                </div>
                                            @endif                                            
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    @endforeach                                    
                </div>
            @endif
            @if(!empty($roteiros) && $roteiros->count() > 0)
            <div>
                <div class="shell">
                <div class="range range-xs-center range-xs-middle range-lg-right">
                    <div class="cell-xs-10 cell-sm-8 cell-md-5 cell-lg-4 offset-top-40 offset-top-0 swiper-center-caption swiper-center-caption-centered">
                    
                    <!-- Panel-->
                    <div class="panel panel-lg panel-lg-vertical bg-overlay-chathams-blue text-lg-left">
                        <h3><span class="small text-bold text-white">Escolha um Roteiro</span></h3>
                        <form method="post" action="{{ route('web.roteiro.do') }}" class="offset-top-20" name="roteiro">
                         
                        <div class="group group-top">
                            <div class="group-item element-fullwidth">
                                <div class="form-group text-left">
                                    <label for="form-filter-location-from-first" class="form-label form-label-outside">Passeio para</label>
                                    <div class="select2-whitout-border shadow-drop-md">
                                        <select id="form-filter-location-from-first" name="slug" class="form-control">
                                            @foreach ($roteiros as $roteiro)
                                                <option value="{{$roteiro->slug}}">{{$roteiro->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>                   
                            <div class="group-item element-fullwidth">
                                <button type="submit" class="shadow-drop-md btn btn-block btn-ripe-lemon">Quero Reservar</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    
                    </div>
                </div>
                </div>
            </div>
            @endif
            <!-- Swiper Pagination-->
            <div class="swiper-pagination"></div>
            <!-- Swiper Navigation-->
            <div class="swiper-button-prev"><span class="icon icon-xs fa fa-chevron-left"></span></div>
            <div class="swiper-button-next"><span class="icon icon-xs fa fa-chevron-right"></span></div>
            </div>
        </div>
        
    
    </header>

    <!-- Main -->
    <main class="page-content"> 


@if(!empty($passeios) && $passeios->count() > 0)
<section class="section-90 section-md-90 bg-zircon">
    <div class="shell-wide">
    <h2 class="text-bold text-center text-md-left">Passeios</h2>
    <hr class="divider hr-md-left-0 bg-gray-darker">
    <!-- Owl Carousel-->
    <div data-items="1" data-sm-items="2" data-md-items="2" data-lg-items="3" data-loop="false" data-stage-padding="5" data-margin="25" data-nav="false" data-dots="true" class="owl-carousel owl-carousel-classic">
        @foreach($passeios as $passeio)
            <div class="owl-item section-lg-bottom-30">
                <a href="{{route('web.roteiro', ['slug' => $passeio->roteiro->slug])}}" class="post-ticket">
                    <div class="post-ticket-header">
                        <img src="{{$passeio->roteiro->cover()}}" width="420" height="280" alt="" class="img-responsive"/>
                    <div class="post-ticket-price-svg-wrap">
                        <div style="height: 66px;" class="post-ticket-price-svg">
                            <div class="post-ticket-price text-bold">
                                @if ($passeio->valor_venda_promocional != '')
                                    R$ {{$passeio->valor_venda_promocional}}<br>
                                    <span style="text-decoration: line-through">R$ {{$passeio->valor_venda}}</span>
                                @else
                                    R$ {{$passeio->valor_venda}}
                                @endif
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="post-ticket-body text-left">
                    <!-- List Inline-->
                    <div>
                        <ul class="group-xs list list-inline list-inline-icon list-inline-icon-type-1 list-inline-icon-primary">
                        <li class="text-bold text-gray-darker">{{$passeio->roteiro->name}}</li>
                        <li class="text-bold text-gray-darker">Saída {{$passeio->bairro}}</li>
                        </ul>
                    </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    </div>
</section>
@endif

<section class="section-90 section-md-0 text-left">
    <div class="shell">
    <div class="range range-xs-center range-lg-justify">
        <div class="cell-sm-10 cell-md-7 cell-lg-8 section-md-60 section-lg-111">
        <div class="inset-md-right-50 inset-lg-right-0">
            <h2 class="text-bold text-center text-md-left">Fretes & Eventos</h2>
            <hr class="divider hr-md-left-0 bg-gray-darker">
            <div class="range range-xs-center offset-top-30 offset-md-top-60">
            <div class="cell-sm-6">
                <!-- Unit-->
                <div class="unit unit-horizontal unit-spacing-sm">
                <div class="unit-left">
                    <span class="icon icon-circle icon-pizazz mdi mdi-ferry"></span>
                </div>
                <div class="unit-body">
                    <h5 class="text-bold text-shark">Pescarias</h5>
                    <p class="offset-top-10 text-gray">
                        Traga sua equipe ou seus amigos para uma pescaria em alto mar!
                    </p>
                </div>
                </div>
            </div>
            <div class="cell-sm-6 offset-top-40 offset-sm-top-0">
                <!-- Unit-->
                <div class="unit unit-horizontal unit-spacing-sm">
                <div class="unit-left"><span class="icon icon-circle icon-pizazz mdi mdi-ferry"></span></div>
                <div class="unit-body">
                    <h5 class="text-bold text-shark">Casamentos</h5>
                    <p class="offset-top-10 text-gray">Já pensou em um casamento no mar?</p>
                </div>
                </div>
            </div>
            <div class="cell-sm-6 offset-top-40">
                <!-- Unit-->
                <div class="unit unit-horizontal unit-spacing-sm">
                <div class="unit-left"><span class="icon icon-circle icon-pizazz mdi mdi-ferry"></span></div>
                <div class="unit-body">
                    <h5 class="text-bold text-shark">Eventos</h5>
                    <p class="offset-top-10 text-gray">Traga seu evento ou da sua empresa para bordo!</p>
                </div>
                </div>
            </div>
            <div class="cell-sm-6 offset-top-40">
                <!-- Unit-->
                <div class="unit unit-horizontal unit-spacing-sm">
                <div class="unit-left"><span class="icon icon-circle icon-pizazz mdi mdi-ferry"></span></div>
                <div class="unit-body">
                    <h5 class="text-bold text-shark">Aniversários</h5>
                    <p class="offset-top-10 text-gray">Seu aniversário junto a um maravilhoso passeio!</p>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="cell-sm-10 cell-md-5 cell-lg-4 section-md-111 context-md-dark bg-image-md-fullwidth bg-image-md-fullwidth-img-2 bg-image-md-fullwidth-right bg-image-md-fullwidth-atlantis"></div>
    </div>
    </div>
</section>

@if($artigos->count() && $artigos->count() > 0)
<section class="section-90 section-md-111 bg-zircon">
    <div class="shell">
    <h2 class="text-bold">Acompanhe nossas Dicas</h2>
    <hr class="divider bg-gray-darker">    
    <div class="range range-xs-center offset-top-60">
        @foreach($artigos as $artigo)
        <div class="cell-xs-10 cell-sm-7 cell-md-4">
            <article class="post post-masonry">
                <!-- Post media-->
                <header class="post-media">
                    <a href="{{route('web.blog.artigo',['slug' => $artigo->slug])}}">
                        <img src="{{$artigo->cover()}}" width="370" height="240" alt="{{$artigo->titulo}}" class="img-responsive center-block"/>
                    </a>
                </header>
                <!-- Post content-->
                <section class="post-content text-left">
                <ul class="list list-inline list-inline-dots list-inline-8 list-inline-0 text-gray">
                    <li class="offset-top-0">
                        <span style="position: relative; top: -1px;" class="icon icon-xxs mdi mdi-calendar text-gray text-middle"></span>
                        <span class="inset-left-10">{{$artigo->publish_at}}</span>
                    </li>
                    <li><span>Por <a class="text-orange-peel">{{$artigo->userObject->name}}</a></span></li>
                </ul>
                <!-- Post Title-->
                <div class="post-title offset-top-20">
                    <h6 class="text-bold text-ripe-lemon">
                        <a href="{{route('web.blog.artigo',['slug' => $artigo->slug])}}">{{$artigo->titulo}}</a>
                    </h6>
                </div>
                <!-- Post Body-->
                <div class="post-body offset-top-10">
                    <p class="text-gray-darker">
                        {!! $artigo->getContentWebAttribute() !!}
                    </p>
                    <div class="offset-top-20">
                        <div class="tags group group-sm">
                            <a href="{{route('web.blog.categoria', ['slug' => $artigo->categoriaObject->slug] )}}" class="btn-xs btn-tag btn btn-gray">{{$artigo->categoriaObject->titulo}}</a>
                        </div>
                    </div>
                </div>
                </section>
            </article>
        </div>
        @endforeach 
    </div>
    <div class="offset-top-60"><a href="{{route('web.blog.artigos')}}" class="btn btn-default shadow-drop" title="Ver mais artigos">Ver mais artigos</a></div>
    </div>
</section>
@endif

</main>
@endsection

@section('css')

@endsection

@section('js')
<script>
    $(function () { 

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $('form[name="roteiro"]').submit(function (event) {
        event.preventDefault();

        const form = $(this);
        const action = form.attr('action');
        const slug = form.find('select[name="slug"]').val();       

        $.post(action, {slug: slug}, function (response) {  
            if(response.redirect) {                
                setTimeout(function() {
                    window.location.href = response.redirect + '#reservar';
                }, 2000);                                
            }
        }, 'json');

    });

    //select roteiro
    if (plugins.selectFilter.length) {
        var i;
        for (i = 0; i < plugins.selectFilter.length; i++) {
            var select = $(plugins.selectFilter[i]);

            select.select2({
            minimumResultsForSearch: -1,
            theme: "bootstrap"
            }).next().addClass(select.attr("class").match(/(input-sm)|(input-lg)|($)/i).toString().replace(new RegExp(",", 'g'), " "));
        }
    }

    });
</script>

<!-- Your Plugin de bate-papo code -->
<div id="fb-customer-chat" class="fb-customerchat">
</div>

<script>
  var chatbox = document.getElementById('fb-customer-chat');
  chatbox.setAttribute("page_id", "101073345676964");
  chatbox.setAttribute("attribution", "biz_inbox");

  window.fbAsyncInit = function() {
    FB.init({
      xfbml            : true,
      version          : 'v12.0'
    });
  };

  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://connect.facebook.net/pt_BR/sdk/xfbml.customerchat.js';
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
@endsection