@extends('web.master.master')

@section('content')
<section class="section section-30 section-xxl-40 section-xxl-66 section-xxl-bottom-90 novi-background bg-gray-dark page-title-wrap" style="background-image: url({{$configuracoes->gettopodosite()}});">
    <div class="container">
        <div class="page-title">
            <h2>Quem Somos</h2>
        </div>
    </div>
</section>

<section class="section section-66 section-md-90 section-xl-bottom-120 novi-background">
    <div class="container">
        <div class="row row-40 justify-content-lg-between">
            <div class="col-12 col-md-6 col-lg-7 text-secondary">
                <div class="inset-md-right-15 inset-xl-right-0">
                    <h3>Quem Somos</h3>
                    {!!$paginaQuemSomos->content!!}                    
                </div>
            </div>            
            <div class="col-12 col-md-6 col-lg-5 text-secondary">
                <div class="inset-md-right-15 inset-xl-right-0">                    
                    <h3>Principios e valores</h3>
                    <ul class="list-marked" style="font-size: 1.2em;">
                        <li>Comprometimento</li>
                        <li>Crescer junto com o cliente</li>
                        <li>Prazos e responsabilidade</li>
                        <li>Flexibilidade</li>
                    </ul>
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

<!--
<section class="section section-60 section-md-90 section-lg-bottom-120 bg-gray-dark novi-background text-center">
    <div class="container">
        <h3>Depoimento de Clientes</h3>
        <div class="row row-40 row-xl-60 justify-content-sm-center justify-content-md-start offset-xl-top-60">
            <div class="col-md-4">
                <blockquote class="quote-vertical">
                    <div class="quote-body">
                        <div class="quote-open">
                            <svg version="1.1" baseprofile="tiny" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="12px" viewbox="0 0 21 15" overflow="scroll" xml:space="preserve" preserveAspectRatio="none">
                                <path d="M9.597,10.412c0,1.306-0.473,2.399-1.418,3.277c-0.944,0.876-2.06,1.316-3.349,1.316                  c-1.287,0-2.414-0.44-3.382-1.316C0.482,12.811,0,11.758,0,10.535c0-1.226,0.58-2.716,1.739-4.473L5.603,0H9.34L6.956,6.37                  C8.716,7.145,9.597,8.493,9.597,10.412z M20.987,10.412c0,1.306-0.473,2.399-1.418,3.277c-0.944,0.876-2.06,1.316-3.35,1.316                  c-1.288,0-2.415-0.44-3.381-1.316c-0.966-0.879-1.45-1.931-1.45-3.154c0-1.226,0.582-2.716,1.74-4.473L16.994,0h3.734l-2.382,6.37                  C20.106,7.145,20.987,8.493,20.987,10.412z"></path>
                            </svg>
                        </div>
                        <p class="quote-text">
                            <q>Crescemos em mais de 70% nosso volume de vendas nesses últimos 4 anos de parceria.</q>
                        </p>
                    </div>
                    <div class="quote-meta">
                        <figure class="quote-image">
                            <img src="assets/images/clients-testimonials-8-113x113.png" alt="" width="113" height="113" />
                        </figure>
                        <cite>Cliente 1</cite>
                        <p class="caption">Gerente</p>
                    </div>
                </blockquote>
            </div>
            <div class="col-md-4">
                <blockquote class="quote-vertical">
                    <div class="quote-body">
                        <div class="quote-open">
                            <svg version="1.1" baseprofile="tiny" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="12px" viewbox="0 0 21 15" overflow="scroll" xml:space="preserve" preserveAspectRatio="none">
                                <path d="M9.597,10.412c0,1.306-0.473,2.399-1.418,3.277c-0.944,0.876-2.06,1.316-3.349,1.316                  c-1.287,0-2.414-0.44-3.382-1.316C0.482,12.811,0,11.758,0,10.535c0-1.226,0.58-2.716,1.739-4.473L5.603,0H9.34L6.956,6.37                  C8.716,7.145,9.597,8.493,9.597,10.412z M20.987,10.412c0,1.306-0.473,2.399-1.418,3.277c-0.944,0.876-2.06,1.316-3.35,1.316                  c-1.288,0-2.415-0.44-3.381-1.316c-0.966-0.879-1.45-1.931-1.45-3.154c0-1.226,0.582-2.716,1.74-4.473L16.994,0h3.734l-2.382,6.37                  C20.106,7.145,20.987,8.493,20.987,10.412z"></path>
                            </svg>
                        </div>
                        <p class="quote-text">
                            <q>Crescemos em mais de 70% nosso volume de vendas nesses últimos 4 anos de parceria.</q>
                        </p>
                    </div>
                    <div class="quote-meta">
                        <figure class="quote-image">
                            <img src="assets/images/clients-testimonials-8-113x113.png" alt="" width="113" height="113" />
                        </figure>
                        <cite>Cliente 2</cite>
                        <p class="caption">Sócio Fundador</p>
                    </div>
                </blockquote>
            </div>
            <div class="col-md-4">
                <blockquote class="quote-vertical">
                    <div class="quote-body">
                        <div class="quote-open">
                            <svg version="1.1" baseprofile="tiny" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="12px" viewbox="0 0 21 15" overflow="scroll" xml:space="preserve" preserveAspectRatio="none">
                                <path d="M9.597,10.412c0,1.306-0.473,2.399-1.418,3.277c-0.944,0.876-2.06,1.316-3.349,1.316                  c-1.287,0-2.414-0.44-3.382-1.316C0.482,12.811,0,11.758,0,10.535c0-1.226,0.58-2.716,1.739-4.473L5.603,0H9.34L6.956,6.37                  C8.716,7.145,9.597,8.493,9.597,10.412z M20.987,10.412c0,1.306-0.473,2.399-1.418,3.277c-0.944,0.876-2.06,1.316-3.35,1.316                  c-1.288,0-2.415-0.44-3.381-1.316c-0.966-0.879-1.45-1.931-1.45-3.154c0-1.226,0.582-2.716,1.74-4.473L16.994,0h3.734l-2.382,6.37                  C20.106,7.145,20.987,8.493,20.987,10.412z"></path>
                            </svg>
                        </div>
                        <p class="quote-text">
                            <q>Crescemos em mais de 70% nosso volume de vendas nesses últimos 4 anos de parceria.</q>
                        </p>
                    </div>
                    <div class="quote-meta">
                        <figure class="quote-image">
                            <img src="assets/images/clients-testimonials-8-113x113.png" alt="" width="113" height="113" />
                        </figure>
                        <cite>Cliente 3</cite>
                        <p class="caption">Proprietário</p>
                    </div>
                </blockquote>
            </div>
        </div>
    </div>
</section>
-->
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