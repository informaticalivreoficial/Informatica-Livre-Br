@extends('web.master.master')


@section('content')
<header class="page-head slider-menu-position">
    @include('web.include.header')
  
    <!-- Modern Breadcrumbs-->
    <section class="section-height-800 breadcrumb-modern rd-parallax context-dark bg-gray-darkest text-lg-left">
      <div data-speed="0.2" data-type="media" data-url="{{url(asset('frontend/assets/images/backgrounds/background-01-1920x900.jpg'))}}" class="rd-parallax-layer"></div>
      <div data-speed="0" data-type="html" class="rd-parallax-layer">
        <div class="bg-primary-chathams-blue-reverse">
          <div class="shell section-top-57 section-bottom-30 section-md-top-185">
            <div class="veil reveal-md-block">
              <h1 class="text-bold">Atendimento</h1>
            </div>
            <ul class="list-inline list-inline-icon list-inline-icon-type-1 list-inline-icon-extra-small list-inline-icon-white p offset-top-30 offset-md-top-40">
              <li><a href="{{route('web.home')}}" class="text-white">Início</a></li>
              <li>Atendimento</li>
            </ul>
          </div>
        </div>
      </div>
    </section>  
  </header>
  
  
  <!-- Page Contents-->
  <main class="page-content">
    <!-- Get in touch-->
    <section class="section-90 section-md-111 text-left">
      <div class="shell">
        <div class="range range-xs-center">
          <div class="cell-xs-10 cell-md-8">
            <div class="inset-lg-right-40">           
              
              <div class="">
                <!-- RD Mailform-->
                <form method="post" action="" class="text-left j_formsubmit" autocomplete="off">
                  @csrf
                  <div id="js-contact-result"></div>
                  <div>
                      <!-- HONEYPOT -->
                      <input type="hidden" class="noclear" name="bairro" value="" />
                      <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                  </div>
                  <div class="range range-xs-center form_hide">
                    <div class="cell-sm-6">
                      <div class="form-group form-group-label-outside">
                        <label for="contacts-first-name" class="form-label form-label-outside text-dark">Nome</label>
                        <input id="contacts-first-name" type="text" name="nome" class="form-control">
                      </div>
                    </div> 
                    <div class="cell-sm-6">
                      <div class="form-group form-group-label-outside">
                        <label for="contacts-email" class="form-label form-label-outside text-dark">Email</label>
                        <input id="contacts-email" type="email" name="email" class="form-control">
                      </div>
                    </div>                 
                  </div>
                  <div class="form-group form-group-label-outside offset-top-20 form_hide">
                    <label for="contacts-message" class="form-label form-label-outside text-dark">Mensagem</label>
                    <textarea id="contacts-message" name="mensagem" style="max-height: 150px;" class="form-control"></textarea>
                  </div>
                  <div class="offset-top-18 offset-sm-top-30 text-center text-sm-left form_hide">
                    <button type="submit" class="btn btn-ripe-lemon" id="js-contact-btn">Enviar Agora</button>
                  </div>
                </form>
              </div>
              
            </div>
          </div>
          <div class="cell-xs-10 cell-md-4 offset-top-60 offset-md-top-0">
            <!-- Sidebar-->
            <aside class="text-left">
              <div class="inset-md-left-30">
                <!-- Socials-->
                <div>
                  <h5 class="text-bold">Redes Sociais</h5>
                </div>
                <div class="offset-top-6">
                  <div class="text-subline bg-pizazz"></div>
                </div>
                <div class="offset-top-20">
                  <ul class="list-inline list-inline-2">
                    @if ($configuracoes->email)
                        <li><a href="mailto:{{$configuracoes->facebook}}" class="icon icon-xxs icon-silver-filled icon-circle fa fa-envelope"></a></li>
                    @endif
                    @if ($configuracoes->facebook)
                        <li><a target="_blank" href="{{$configuracoes->facebook}}" class="icon icon-xxs icon-silver-filled icon-circle fa fa-facebook"></a></li>
                    @endif
                    @if ($configuracoes->twitter)
                        <li><a target="_blank" href="{{$configuracoes->twitter}}" class="icon icon-xxs icon-silver-filled icon-circle fa fa-twitter"></a></li>
                    @endif
                    @if ($configuracoes->instagram)
                        <li><a target="_blank" href="{{$configuracoes->instagram}}" class="icon icon-xxs icon-silver-filled icon-circle fa fa-instagram"></a></li>
                    @endif                
                    </ul>
                </div>
                <!-- Phones-->
                <div class="offset-top-30 offset-md-top-60">
                  <div>
                    <h5 class="text-bold">Telefones</h5>
                  </div>
                  <div class="offset-top-6">
                    <div class="text-subline bg-pizazz"></div>
                  </div>
                  @if ($configuracoes->telefone1)
                      <div class="offset-top-20">
                        <div class="unit unit-middle unit-horizontal unit-spacing-xs">
                          <div class="unit-left">
                            <span class="icon icon-xxs icon-primary icon-primary-filled icon-circle mdi mdi-phone"></span>
                          </div>
                          <div class="unit-body text-gray-darker">
                            <p>
                              <a href="callto:{{$configuracoes->telefone1}}" class="reveal-block reveal-xs-inline-block text-gray-darker">{{$configuracoes->telefone1}}</a>                        
                            </p>
                          </div>
                        </div>
                      </div>                      
                  @endif
                  @if ($configuracoes->telefone2)
                      <div class="offset-top-20">
                        <div class="unit unit-middle unit-horizontal unit-spacing-xs">
                          <div class="unit-left">
                            <span class="icon icon-xxs icon-primary icon-primary-filled icon-circle mdi mdi-phone"></span>
                          </div>
                          <div class="unit-body text-gray-darker">
                            <p>
                              <a href="callto:{{$configuracoes->telefone2}}" class="reveal-block reveal-xs-inline-block text-gray-darker">{{$configuracoes->telefone2}}</a>                        
                            </p>
                          </div>
                        </div>
                      </div>                      
                  @endif
                  @if ($configuracoes->telefone3)
                      <div class="offset-top-20">
                        <div class="unit unit-middle unit-horizontal unit-spacing-xs">
                          <div class="unit-left">
                            <span class="icon icon-xxs icon-primary icon-primary-filled icon-circle mdi mdi-phone"></span>
                          </div>
                          <div class="unit-body text-gray-darker">
                            <p>
                              <a href="callto:{{$configuracoes->telefone3}}" class="reveal-block reveal-xs-inline-block text-gray-darker">{{$configuracoes->telefone3}}</a>                        
                            </p>
                          </div>
                        </div>
                      </div>                      
                  @endif
                  @if ($configuracoes->whatsapp)
                    <div class="offset-top-20">
                      <div class="unit unit-middle unit-horizontal unit-spacing-xs">
                        <div class="unit-left">
                          <span style="background-color: #34af23;color:#fff;" class="icon icon-xxs icon-circle mdi mdi-whatsapp"></span>
                        </div>
                        <div class="unit-body text-gray-darker">
                          <p>
                            <a href="{{getNumZap($configuracoes->whatsapp ,'Atendimento Aqua Marine Turismo Náutico')}}" class="reveal-block reveal-xs-inline-block text-gray-darker">{{$configuracoes->whatsapp}}</a>                        
                          </p>
                        </div>
                      </div>
                    </div>                    
                  @endif                  
                  
                </div>
                @if ($configuracoes->email)
                  <div class="offset-top-30 offset-md-top-60">
                    <div>
                      <h5 class="text-bold">Email</h5>
                    </div>
                    <div class="offset-top-6">
                      <div class="text-subline bg-pizazz"></div>
                    </div>
                    <div class="offset-top-20">
                      <div class="unit unit-middle unit-horizontal unit-spacing-xs">
                        <div class="unit-left"><span class="icon icon-xxs icon-primary icon-primary-filled icon-circle mdi mdi-email-outline"></span></div>
                        <div class="unit-body">
                          <p><a href="mailto:{{$configuracoes->email}}" class="text-ripe-lemon">{{$configuracoes->email}}</a></p>
                        </div>
                      </div>
                    </div>
                  </div>                      
                @endif
                @if ($configuracoes->email1)
                  <div class="offset-top-30 offset-md-top-60">
                    <div>
                      <h5 class="text-bold">Email</h5>
                    </div>
                    <div class="offset-top-6">
                      <div class="text-subline bg-pizazz"></div>
                    </div>
                    <div class="offset-top-20">
                      <div class="unit unit-middle unit-horizontal unit-spacing-xs">
                        <div class="unit-left"><span class="icon icon-xxs icon-primary icon-primary-filled icon-circle mdi mdi-email-outline"></span></div>
                        <div class="unit-body">
                          <p><a href="mailto:{{$configuracoes->email1}}" class="text-ripe-lemon">{{$configuracoes->email1}}</a></p>
                        </div>
                      </div>
                    </div>
                  </div>                      
                @endif

                @if($configuracoes->rua)	
                <div class="offset-top-30 offset-md-top-60">
                  <div>
                    <h5 class="text-bold">Ponto de venda</h5>
                  </div>
                  <div class="offset-top-6">
                    <div class="text-subline bg-pizazz"></div>
                  </div>
                  <div class="offset-top-20">
                    <div class="unit unit-horizontal unit-spacing-xs">
                      <div class="unit-left"><span class="icon icon-xxs icon-primary icon-primary-filled icon-circle mdi mdi-map-marker"></span></div>
                      <div class="unit-body">
                        <p>{{$configuracoes->rua}}
                        @if($configuracoes->num)
                        , {{$configuracoes->num}}
                        @if($configuracoes->bairro)
                            , {{$configuracoes->bairro}}
                        @endif
                        @endif
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                @endif
                              
              </div>
            </aside>
          </div>
        </div>
      </div>
    </section>
    <section>
      
      <div class="rd-google-map">
        {!!$configuracoes->mapa_google!!}
      </div>

    </section>
  </main>
  @endsection

@section('css')
<style>
  iframe{
    height: 450px;
    width: 100%;
    display: inline-block;
    overflow: hidden"
  }
</style>
@endsection

@section('js')
  <script>
    $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Seletor, Evento/efeitos, CallBack, Ação
        $('.j_formsubmit').submit(function (){
            var form = $(this);
            var dataString = $(form).serialize();

            $.ajax({
                url: "{{ route('web.sendEmail') }}",
                data: dataString,
                type: 'GET',
                dataType: 'JSON',
                beforeSend: function(){
                    form.find("#js-contact-btn").attr("disabled", true);
                    form.find('#js-contact-btn').html("Carregando...");                
                    form.find('.alert').fadeOut(500, function(){
                        $(this).remove();
                    });
                },
                success: function(resposta){
                    $('html, body').animate({scrollTop:$('#js-contact-result').offset().top-100}, 'slow');
                    if(resposta.error){
                        form.find('#js-contact-result').html('<div class="alert alert-danger error-msg">'+ resposta.error +'</div>');
                        form.find('.error-msg').fadeIn();                    
                    }else{
                        form.find('#js-contact-result').html('<div class="alert alert-success error-msg">'+ resposta.sucess +'</div>');
                        form.find('.error-msg').fadeIn();                    
                        form.find('input[class!="noclear"]').val('');
                        form.find('textarea[class!="noclear"]').val('');
                        form.find('.form_hide').fadeOut(500);
                    }
                },
                complete: function(resposta){
                    form.find("#js-contact-btn").attr("disabled", false);
                    form.find('#js-contact-btn').html("Enviar Agora");                                
                }

            });

            return false;
        });

    });
</script>   
  @endsection
  
  