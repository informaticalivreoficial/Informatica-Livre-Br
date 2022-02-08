@extends('web.master.master')

@section('content')
<section class="section section-30 section-xxl-40 section-xxl-66 section-xxl-bottom-90 novi-background bg-gray-dark page-title-wrap" style="background-image: url({{$configuracoes->gettopodosite()}});">
    <div class="container">
        <div class="page-title">
            <h2>Blog</h2>
        </div>
    </div>
</section>
  <section class="section section-60 section-md-75 section-xl-90">
    <div class="container">
      <div class="row row-50">
        <div class="col-lg-8 col-xl-9">
          <article class="post post-single">
            <div class="post-image">
              <figure>
                  <img src="{{$post->cover()}}" alt="{{$post->titulo}}" width="870" height="412"/>
              </figure>
            </div>
            <div class="post-header">
              <h4>{{$post->titulo}}</h4>
            </div>
            <div class="post-meta">
              <ul class="list-bordered-horizontal">
                <li>
                  <dl class="list-terms-inline">
                    <dt>Data</dt>
                    <dd>
                      <time datetime="{{\Carbon\Carbon::createFromFormat('d/m/Y', $post->publish_at)->format('Y-m-d')}}">{{$post->publish_at}}</time>
                    </dd>
                  </dl>
                </li>
                <li>
                  <dl class="list-terms-inline">
                    <dt>Por</dt>
                    <dd>{{$post->user->name}}</dd>
                  </dl>
                </li>                
                <li>
                  <dl class="list-terms-inline">
                    <dt>Categoria</dt>
                    <dd>{{$post->categoriaObject->titulo}}</dd>
                  </dl>
                </li>
              </ul>
            </div>
            <div class="divider-fullwidth bg-gray-light"></div>
            <div class="post-body" style="color: #0c0707;">
                {!!$post->content!!}

                
                @if($post->images()->get()->count())                    
                    @foreach($post->images()->get() as $image)
                        <div class="cell-xs-4 cell-md-6 inset-lg-left-13 inset-lg-right-13 offset-top-20">
                            <div class="inset-left-30 inset-right-30 inset-xs-left-0 inset-xs-right-0">
                                <a class="thumbnail-rayen" href="{{ $image->getUrlCroppedAttribute() }}" data-toggle="lightbox" data-gallery="property-gallery" data-type="image">
                                    <img width="160" height="160" src="{{ $image->getUrlCroppedAttribute() }}" alt="{{ $post->titulo }}"> 
                                </a>
                            </div>
                        </div>
                    @endforeach                                
                @endif 
               
            </div>
            <div class="post-footer">
                <h5>Compartilhe este artigo:</h5>
                <div class="fb-share-button" data-href="{{url()->current()}}" data-layout="button_count" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartilhar</a></div>
                <a class="btn-front mdi mdi-whatsapp" target="_blank" href="https://web.whatsapp.com/send?text={{url()->current()}}" data-action="share/whatsapp/share"> Compartilhar</a>
            </div>
          </article>
          <div class="divider-fullwidth bg-gray-lighter"></div>         
          
        </div>
        <div class="col-lg-4 col-xl-3">
          <div class="blog-aside">
            <!--<div class="blog-aside-item">
              <form class="rd-search rd-search-classic" action="search-results.html" method="GET">
                <div class="form-wrap">
                  <label class="form-label" for="rd-search-form-input-1">Search...</label>
                  <input class="form-input" id="rd-search-form-input-1" type="text" name="s" autocomplete="off">
                </div>
                <button class="rd-search-submit" type="submit"></button>
              </form>
            </div>-->
            <div class="blog-aside-item">
                <h6>Categorias</h6>
                <ul class="list-marked-bordered">
                    @if(!empty($categorias) && $categorias->count() > 0)
                        @foreach($categorias as $categoria)                                    
                            @if($categoria->children)
                                @foreach($categoria->children as $subcategoria)
                                    @if($subcategoria->countposts() >= 1)
                                        <li><a href="{{route('web.blog.categoria', ['slug' => $subcategoria->slug] )}}" title="{{ $subcategoria->titulo }}"><span>{{ $subcategoria->titulo }}</span><span class="list-counter">({{$subcategoria->countposts()}})</span></a></li>
                                    @endif                                            
                                @endforeach
                            @endif                                                                                                                             
                        @endforeach
                    @endif
                </ul>
            </div>

            @if(!empty($postsMais) && $postsMais->count() > 0)
            <div class="blog-aside-item">
                <h6>Popular posts</h6>
                <div class="post-preview-wrap">
                    @foreach ($postsMais as $postsmais)
                        <article class="post post-preview">
                            <a href="{{route('web.blog.artigo', ['slug' => $postsmais->slug] )}}">
                                <div class="unit unit-spacing-sm">
                                    <div class="unit-left">
                                        <figure class="post-image">
                                            <img style="width: 70px !important;height: 70px !important;" src="{{$postsmais->nocover()}}" alt="{{$postsmais->titulo}}"/>
                                        </figure>
                                    </div>
                                    <div class="unit-body">
                                        <div class="post-header">
                                            <p>{{$postsmais->titulo}}</p>
                                        </div>
                                        <div class="post-meta">
                                        <ul class="list-meta">
                                            <li>
                                                <time datetime="{{\Carbon\Carbon::createFromFormat('d/m/Y', $post->publish_at)->format('Y-m-d')}}">{{$post->publish_at}}</time>
                                            </li>                                            
                                        </ul>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="blog-aside-item">
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
  
@endsection

@section('css')
<!-- Ekko Lightbox -->
<link rel="stylesheet" href="{{url(asset('backend/plugins/ekko-lightbox/ekko-lightbox.css'))}}"/>
<style>
    .btn-front{
        background-color: #6ebf58;
        color:#fff;
        border-radius: .25rem;
        padding: 7px 8px !important;
        border:none;
    }
    .btn-front:hover, mdi:hover{
        color:#fff;
    }
</style>
@endsection

@section('js')
<!-- Ekko Lightbox -->
<script src="{{url(asset('backend/plugins/ekko-lightbox/ekko-lightbox.min.js'))}}"></script>
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