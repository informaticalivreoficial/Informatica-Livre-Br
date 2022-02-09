@extends('web.master.master')


@section('content')
<section class="section section-30 section-xxl-40 section-xxl-66 section-xxl-bottom-90 novi-background bg-gray-dark page-title-wrap" style="background-image: url({{$configuracoes->gettopodosite()}});">
  <div class="container">
      <div class="page-title">
      <h2>{{$post->titulo}}</h2>
      </div>
  </div>
</section>

<section class="section section-60 section-md-90 bg-white">
    <div class="container">
        <div class="row justify-content-lg-start">
            <div class="col-12">
                <div class="offset-top-4">
                    {!!$post->content!!}
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-60 section-md-100 novi-background bg-black">
    <div class="container">
        <div class="row row-40">
            <div class="col-sm-6 col-md-3">
                <div class="box-counter"><span class="novi-icon icon icon-md icon-primary material-icons-content_copy"></span>
                    <div class="text-large counter">{{$projetosCount}}</div>
                    <h5 class="box-header">Projetos</h5>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="box-counter"><span class="novi-icon icon icon-md icon-primary material-icons-mood"></span>
                    <div class="text-large counter counter">{{$clientesCount}}</div>
                    <h5 class="box-header">Clientes Atendidos</h5>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="box-counter"><span class="novi-icon icon icon-md icon-primary material-icons-language"></span>
                    <div class="text-large counter">75</div>
                    <h5 class="box-header">Domínios</h5>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="box-counter"><span class="novi-icon icon icon-md icon-primary material-icons-code"></span>
                    <div class="text-large counter counter">62</div>
                    <h5 class="box-header">Sites Hospedados</h5>
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