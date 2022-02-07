@extends('web.master.master')


@section('content')
<section class="section section-30 section-xxl-40 section-xxl-66 section-xxl-bottom-90 novi-background bg-gray-dark page-title-wrap" style="background-image: url({{$configuracoes->gettopodosite()}});">
  <div class="container">
      <div class="page-title">
      <h2>Soluções</h2>
      </div>
  </div>
</section>

<section class="section section-60 section-md-90 section-xl-bottom-15 bg-whisper novi-background">
    <div class="container">
      <div class="row row-40">
        <div class="col-md-6 col-lg-8">
            <h5>Caso necessite de uma consultoria mais detalhada faça por aqui, temos a solução ideal para sua empresa!</h5>
          
        </div>
        <div class="col-md-5 col-lg-4">
            <a class="btn btn-primary btn-block" href="{{route('web.formorcamento')}}">Orçamento Personalizado</a>
        </div>
      </div>
    </div>
  </section>

  @if (!empty($produtos) && $produtos->count() > 0)
    <section class="bg-decoration-wrap bg-decoration-bottom section-bottom-60 section-xl-top-100 section-xl-bottom-100 bg-whisper novi-background">
        <div class="container bg-decoration-content">
            <div class="row justify-content-md-center">
                <div class="col-lg-10 col-xl-12">
                    <div class="row row-40 align-items-md-end">                
                        @foreach ($produtos as $produto)
                            <div class="col-md-6 col-xl-4">
                                <div class="pricing-table">
                                    <div class="pricing-table-body">
                                    <h5 class="pricing-table-header text-center">{{$produto->name}}</h5>
                                        <div class="pricing-object pricing-object-lg text-center">
                                            <span class="small small-top">R$</span>
                                            <span class="price">{{ str_replace(',00', '', $produto->valor) }}</span>
                                        </div>
                                    <div class="divider-circle"></div>
                                    {!!$produto->content!!}
                                    </div>
                                    <div class="pricing-table-footer">
                                        <a class="btn btn-round-bottom btn-default btn-block" href="registration.html">Comprar</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-decoration-object bg-iron"></div>
    </section>    
  @endif
  

@endsection