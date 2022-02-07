@extends('web.master.master')

@section('content')
<section class="section section-30 section-xxl-40 section-xxl-66 section-xxl-bottom-90 novi-background bg-gray-dark page-title-wrap" style="background-image: url({{$configuracoes->gettopodosite()}});">
    <div class="container">
        <div class="page-title">
            <h2>Política de Privacidade</h2>
        </div>
    </div>
</section>

<section class="section section-60 section-md-90 section-xl-bottom-120">
    <div class="container">
        <h3>Política de Privacidade</h3>
        <div class="row justify-content-md-center">
            <div class="col-12" style="color: #0c0707;">
                {!! $configuracoes->politicas_de_privacidade !!}
            </div>
        </div>
    </div>
  </section>
@endsection