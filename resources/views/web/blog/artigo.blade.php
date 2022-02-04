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
                <h1 class="text-bold">{{$post->titulo}}</h1>
            </div>
            <ul class="list-inline list-inline-icon list-inline-icon-type-1 list-inline-icon-extra-small list-inline-icon-white p offset-top-30 offset-md-top-40">
                <li><a href="{{route('web.home')}}" class="text-white">Início</a></li>
                <li><a href="{{route('web.blog.artigos')}}" class="text-white">Blog</a></li>
                <li>{{$post->titulo}}</li>
            </ul>
            </div>
        </div>
    </div>
</section>
</header>
<!-- Page Contents-->
<main class="page-content">
<!-- Blog Classic-->
<div id="fb-root"></div>
<section class="section-90 section-md-111 text-left">
    <div class="shell">
    <div class="range range-xs-center range-lg-right">
        <div class="cell-sm-10 cell-md-8">
        <div class="offset-top-25">
            <img src="{{$post->cover()}}" width="770" height="420" alt="" class="img-responsive center-block">
        </div>
        <div class="offset-top-15">
            <ul class="list list-inline list-inline-dots list-inline-8 list-inline-0 text-gray">
            <li class="offset-top-0">
                <span style="position: relative; top: -1px;" class="icon icon-xxs mdi mdi-calendar text-gray text-middle"></span>
                <span class="inset-left-10">{{$post->publish_at}}</span>
            </li>
            <li><span>Por <a class="text-orange-peel">{{$post->userObject->name}}</a></span></li>
            </ul>
        </div>
        <div class="offset-top-30">
            {!! $post->content !!}
        </div> 

        @if($post->images()->get()->count())
            <div class="offset-top-15 offset-md-top-20">
                <div class="range range-xs-center section-gallery-lg-column-offset">
                @foreach($post->images()->get() as $image)
                    <div class="cell-xs-4 cell-md-6 inset-lg-left-13 inset-lg-right-13 offset-top-20">
                        <div class="inset-left-30 inset-right-30 inset-xs-left-0 inset-xs-right-0">
                            <a class="thumbnail-rayen" href="{{ $image->getUrlCroppedAttribute() }}" data-toggle="lightbox"
                                data-gallery="property-gallery" data-type="image">
                                <span class="figure">
                                    <img width="160" height="160" src="{{ $image->getUrlCroppedAttribute() }}" alt="{{ $post->titulo }}">
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
        <div class="offset-top-30">
            <div class="pull-sm-left">
            <div class="tags group group-sm">
                <a href="{{route('web.blog.categoria', ['slug' => $post->categoriaObject->slug] )}}" class="btn-xs btn-tag btn btn-ripe-lemon">{{$post->categoriaObject->titulo}}</a>
            </div>
            </div>
            <div class="pull-sm-right inset-sm-right-6">
            <div class="reveal-inline-block">
                <p class="text-dark">Compartilhe:</p>
            </div>
            <div class="main_property_content_descripition">
                <div style="top:2px;" class="fb-share-button" data-href="{{url()->current()}}" data-layout="button_count" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartilhar</a></div>
                <a class="btn-front mdi mdi-whatsapp" target="_blank" href="https://web.whatsapp.com/send?text={{url()->current()}}" data-action="share/whatsapp/share"> Compartilhar</a>                        
            </div>
            </div>
            <div class="clearfix"></div>
        </div>
        
        @if($postsMais->count())
            <div class="offset-top-55">
                <h5 class="text-bold">Outros Artigos</h5>
            </div>
            <div class="offset-top-6">
                <div class="text-subline bg-pizazz"></div>
            </div>
            @foreach($postsMais as $postsmais)
            <div class="offset-top-20 text-left">
                <div class="reveal-inline-block">
                <!-- Unit-->
                <div class="unit unit-xs unit-xs-horizontal unit-spacing-19">
                    <div class="unit-left">
                        <a href="{{route('web.blog.artigo', ['slug' => $postsmais->slug] )}}">
                            <img src="{{$postsmais->cover()}}" width="270" height="150" alt="{{$postsmais->titulo}}" class="img-responsive"/>
                        </a>
                    </div>
                    <div class="unit-body offset-top-10 offset-xs-top-0 offset-sm-top-4">
                    <h6 class="text-bold">
                        <a href="{{route('web.blog.artigo', ['slug' => $postsmais->slug] )}}" class="text-ripe-lemon">{{$postsmais->titulo}}</a>
                    </h6>
                    <div class="offset-top-10">
                        <ul class="list list-inline list-inline-8 list-inline-0 text-gray">
                        <li>
                            <span class="icon icon-xxs mdi mdi-calendar text-gray text-middle"></span>
                            <span class="inset-left-10 text-middle">{{$postsmais->publish_at}}</span>
                        </li>
                        <li class="offset-top-0">
                            <span>Por <a class="text-orange-peel">{{$postsmais->userObject->name}}</a></span>
                        </li>
                        </ul>
                    </div>
                    <div class="offset-top-20">
                        <div class="tags group group-sm">
                            <a href="{{route('web.blog.categoria', ['slug' => $postsmais->categoriaObject->slug] )}}" class="btn-xs btn-tag btn btn-ripe-lemon">{{$postsmais->categoriaObject->titulo}}</a>                            
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>  
            @endforeach
        @endif 
          
       
        </div>
        <div class="cell-sm-10 cell-md-4 offset-top-60 offset-md-top-0">
        <div class="inset-md-left-30">
            <!-- Aside-->
            <aside class="text-left">
            <!-- Search in Blog-->
            @include('web.include.blog-search')
            
            <!-- Categories-->
            <div class="offset-top-30 offset-md-top-60">
                <h5 class="text-bold">Categorias</h5>
            </div>
            <div class="offset-top-6">
                <div class="text-subline bg-pizazz"></div>
            </div>
            <div class="offset-top-15 offset-md-top-20">
                <div class="inset-xs-left-8">
                <!-- List Marked-->
                <ul class="list list-marked list-marked-icon text-dark">
                    @if(!empty($categorias) && $categorias->count() > 0)
                        @foreach($categorias as $categoria)                                    
                            @if($categoria->children)
                                @foreach($categoria->children as $subcategoria)
                                    @if($subcategoria->countposts() >= 1)
                                        <li><a class="text-ripe-lemon" href="{{route('web.blog.categoria', ['slug' => $subcategoria->slug] )}}" title="{{ $subcategoria->titulo }}">{{ $subcategoria->titulo }}</a> ({{$subcategoria->countposts()}})</li>
                                    @endif                                            
                                @endforeach
                            @endif                                                                                                                             
                        @endforeach
                    @endif
                </ul>
                </div>
            </div>
            <div class="offset-top-30 offset-md-top-60">
                <!-- Facebook standart widget-->
                <div>
                    <div class="fb-root fb-widget">
                        <div class="fb-page-responsive">
                        <div data-href="{{$configuracoes->facebook}}" data-tabs="timeline" data-height="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" class="fb-page">
                            <div class="fb-xfbml-parse-ignore">
                            <blockquote cite="{{$configuracoes->facebook}}"><a href="{{$configuracoes->facebook}}">{{$configuracoes->nomedosite}}</a></blockquote>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>    

            </aside>
        </div>
        </div>
    </div>
    </div>
</section>
</main>      
@endsection

@section('css')
<style>
    .btn-front{
        background-color: #6ebf58;
        color:#fff;
        border-radius: .25rem;
        padding: 3px 8px !important;
        border:none;

        
    }
    .btn-front:hover, mdi:hover{
        color:#fff;
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
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v11.0&appId=1787040554899561&autoLogAppEvents=1" nonce="1eBNUT9J"></script>
@endsection