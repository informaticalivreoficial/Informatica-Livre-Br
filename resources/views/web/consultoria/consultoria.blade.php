@extends('web.master.master')


@section('content')
<section class="section section-30 section-xxl-40 section-xxl-66 section-xxl-bottom-90 novi-background bg-gray-dark page-title-wrap" style="background-image: url({{$configuracoes->gettopodosite()}});">
  <div class="container">
      <div class="page-title">
      <h2>Orçamento Personalizado</h2>
      </div>
  </div>
</section>

<section class="section section-60 section-md-top-90 section-md-bottom-100">
  <div class="container">
      <div class="row row-50 justify-content-md-between">
        <div class="col-lg-7 col-xl-7">
            <h3>Preencha o Formulário</h3>
            <form class="j_formsubmit" method="post" action="" autocomplete="off">
                @csrf
                <div class="row row-30">                
                    <div id="js-contact-result" style="margin-bottom: 10px;"></div>
                    <!-- HONEYPOT -->
                    <input type="hidden" class="noclear" name="bairro" value="" />
                    <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                    <div class="col-md-5 col-lg-5 form_hide">
                        <div class="form-wrap">
                            <input class="form-input" id="contact-name" type="text" name="nome">
                            <label class="form-label" for="contact-name">Nome</label>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 form_hide">
                        <div class="form-wrap">
                            <input class="form-input" id="contact-email" type="email" name="email">
                            <label class="form-label" for="contact-email">Email</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 form_hide">   
                        <div class="form-wrap">                     
                        <input class="form-input celularmask" placeholder="Telefone" type="text" name="telefone">
                         
                        </div>                       
                    </div>
                    <div class="col-sm-12 form_hide">
                        <div class="form-wrap">
                            <div class="textarea-lined-wrap">
                            <textarea class="form-input" id="contact-message" name="mensagem"></textarea>
                            <label class="form-label" for="contact-message">Mensagem</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 form_hide">
                        <button class="btn btn-primary btn-block" id="js-contact-btn" type="submit">Enviar Agora</button>
                    </div> 
                </div>            
            </form>
        </div>
          <div class="col-lg-5 col-xl-5">
              <div class="inset-lg-right-15 inset-xl-right-0">
                  <div class="row row-30 row-md-40">
                      <div class="col-sm-12">
                          <div class="row row-30">
                              <div class="col-md-6 col-lg-12">
                                  <h4>Horário de atendimento</h4>
                                  <address class="contact-info">
                                      <p>                                        
                                      Segunda - Sexta 8h às 11h - 13h às 17h<br>
                                      Sábado e Domingo estamos fechados, apenas suporte técnico por email.
                                      </p>
                                      @if ($configuracoes->telefone1)
                                        <dl class="list-terms-inline">
                                            <dt>Telefone</dt>
                                            <dd><a class="link-secondary" href="tel:{{\App\Helpers\Renato::limpatelefone($configuracoes->telefone1)}}">{{$configuracoes->telefone1}}</a></dd>
                                            @if ($configuracoes->telefone2)
                                              <dd style="margin-left: 10px;"><a class="link-secondary" href="tel:{{\App\Helpers\Renato::limpatelefone($configuracoes->telefone2)}}">{{$configuracoes->telefone2}}</a></dd>
                                            @endif
                                        </dl>
                                      @endif
                                      @if ($configuracoes->telefone3)
                                        <dl class="list-terms-inline">
                                            <dt>Telefone</dt>
                                            <dd><a class="link-secondary" href="tel:{{\App\Helpers\Renato::limpatelefone($configuracoes->telefone3)}}">{{$configuracoes->telefone3}}</a></dd>                                            
                                        </dl>
                                      @endif
                                      @if($configuracoes->whatsapp)
                                          <dl class="list-terms-inline">
                                              <span class="novi-icon icon icon-xxs icon-primary fa-whatsapp"></span>
                                              <dd><a class="link-secondary" href="{{\App\Helpers\WhatsApp::getNumZap($configuracoes->whatsapp ,'Atendimento '.$configuracoes->nomedosite)}}">{{$configuracoes->whatsapp}}</a></dd>  
                                              @if ($configuracoes->skype)
                                                <span style="margin-left: 10px;" class="novi-icon icon icon-xxs icon-primary fa-skype"></span>
                                                <dd><a href="skype:{{$configuracoes->skype}}">{{$configuracoes->skype}}</a></dd>
                                              @endif                                          
                                          </dl>                                          
                                      @endif
                                      @if ($configuracoes->email)
                                          <dl class="list-terms-inline">
                                              <span class="novi-icon icon icon-xxs icon-primary fa-envelope-o"></span>
                                              <dd><a class="link-primary" href="mailto:{{$configuracoes->email}}">{{$configuracoes->email}}</a></dd>                                              
                                          </dl>
                                      @endif                                      
                                      @if ($configuracoes->email1)
                                          <dl class="list-terms-inline">
                                              <span class="novi-icon icon icon-xxs icon-primary fa-envelope-o"></span>
                                              <dd><a class="link-primary" href="mailto:{{$configuracoes->email1}}">{{$configuracoes->email1}}</a></dd>
                                          </dl>
                                      @endif                                      
                                  </address>
                              </div>                                                 
                          </div>
                      </div>
                  </div>
              </div>
          </div>          
      </div>
  </div>
</section>  

@endsection

@section('js')
<script src="{{url(asset('backend/assets/js/jquery.mask.js'))}}"></script>
<script>
    $(document).ready(function () { 
        var $celularmask = $(".celularmask");
        $celularmask.mask('(99) 99999-9999', {reverse: false});
    });
</script> 
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
                url: "{{ route('web.sendOrcamento') }}",
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