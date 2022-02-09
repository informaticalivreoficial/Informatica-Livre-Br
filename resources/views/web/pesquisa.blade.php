@extends('web.master.master')

@section('content')
<section class="section section-30 section-xxl-40 section-xxl-66 section-xxl-bottom-90 novi-background bg-gray-dark page-title-wrap" style="background-image: url({{url(asset('frontend/assets/images/bg-search.jpg'))}});">
  <div class="container">
      <div class="page-title">
      <h2>Pesquisa no site</h2>
      </div>
  </div>
</section>

<section class="section section-60 section-only-child">
    <div class="container">
        <div class="row row-40 justify-content-lg-center">
            <div class="col-lg-10">
            <form class="rd-search rd-search-minimal" action="{{ route('web.pesquisa') }}" method="post" data-search-live-count="15">
                @csrf
                <div class="form-wrap">
                <label class="form-label" for="rd-search-form-input-1"><span class="text-mobile">Pesquisar...</span><span class="text-default">Pesquisar no site</span></label>
                <input class="form-input" id="rd-search-form-input-1" type="text" name="search" value="{{$search ?? ''}}">
                </div>
                <button class="btn-icon-only btn-icon-only-primary" type="submit"><span class="novi-icon icon icon-sm material-icons-keyboard_return"></span></button>
            </form>
            </div>
            <div class="col-md-11">
                <div class="rd-search-results"></div>
            </div>
        </div>
    </div>
</section>

<section class="section section-md-bottom-60">
    <div class="container">        
        @if (!empty($paginas) && $paginas->count() > 0)
            @foreach ($paginas as $pagina)
                <div class="row" style="margin-top: 15px;">
                    <h5>Página: <a class="linksearch" href="{{route('web.pagina',['slug' => $pagina->slug])}}">{{$pagina->titulo}}</a></h5>
                    <div class="col-12">
                        <div class="inset-lg-right-15 inset-xl-right-0">
                            {!!$pagina->content_web!!}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        @if (!empty($artigos) && $artigos->count() > 0)
            @foreach ($artigos as $artigo)
                <div class="row" style="margin-top: 15px;">
                    <h5>Blog: <a class="linksearch" href="{{route('web.blog.artigo',['slug' => $artigo->slug])}}">{{$artigo->titulo}}</a></h5>
                    <div class="col-12">
                        <div class="inset-lg-right-15 inset-xl-right-0">
                            {!!$artigo->content_web!!}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        @if (!empty($projetos) && $projetos->count() > 0)
            @foreach ($projetos as $projeto)
                <div class="row" style="margin-top: 15px;">
                    <h5>Projetos: <a class="linksearch" href="{{route('web.projeto',['slug' => $projeto->slug])}}">{{$projeto->name}}</a></h5>
                    <div class="col-12">
                        <div class="inset-lg-right-15 inset-xl-right-0">
                            {!!$projeto->content_web!!}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>

@endsection

@section('css')
<style>
    .linksearch{
        color: #2083f4;
    }
    .linksearch:hover{
        color: #F48920;
        text-decoration: underline;
    }
</style>
@endsection