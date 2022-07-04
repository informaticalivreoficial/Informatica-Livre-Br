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
        <div class="col-12">            
            <h4>Olá {{\App\Helpers\Renato::getSaudacao(\App\Helpers\Renato::getPrimeiroNome($orcamento->name))}}</h4>
            <p style="color: #333;font-size:1.2em;">Seja muito bem vindo! Queremos já de antemão lhe agradecer por ter escolhido 
                nossa equipe para dessenvolver seu projeto.<br>
                {{\App\Helpers\Renato::getPrimeiroNome($orcamento->name)}} segue abaixo um formulário com informações
                importantes para darmos seguimento ao seu projeto. Ah {{\App\Helpers\Renato::getPrimeiroNome($orcamento->name)}}
                fique tranquilo suas informações estão em ambiente seguro, criptografado e odiamos SPAM!
            </p>
            <form class="j_formsubmit" method="post" action="" autocomplete="off">
                @csrf
                <div class="row row-30">                
                    <div id="js-contact-result" style="margin-bottom: 10px;"></div>    
                    <h5 class="form_hide">Dados do responsável</h5>                
                    <div class="col-sm-6 col-md-6 col-lg-4 form_hide">
                        <div class="form-wrap">
                            <label style="color: #333;" for="contact-email">*Nome</label>
                            <input class="form-input" id="contact-name" type="text" name="nome" value="{{$orcamento->name}}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4 form_hide">
                        <div class="form-wrap">
                            <label style="color: #333;" for="contact-email">*Email</label>
                            <input class="form-input" id="contact-email" type="email" name="email" value="{{$orcamento->email}}">                            
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-2 form_hide">   
                        <div class="form-wrap">      
                            <label style="color: #333;" for="contact-email">*Telefone</label>               
                            <input class="form-input celularmask" type="text" name="telefone" value="{{$orcamento->telefone}}">                         
                        </div>                       
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-2 form_hide">   
                        <div class="form-wrap">      
                            <label style="color: #333;" for="contact-email">*CPF</label>               
                            <input class="form-input cpfmask" type="text" name="cpf">                         
                        </div>                       
                    </div>

                    <h5 class="form_hide">Dados da empresa</h5>
                    <p class="form_hide" style="color: #333;font-size:1.2em;">Estes dados são necessários para configuração de domínio e hospedagem de sites, 
                        caso o projeto seja para pessoa física você pode deixar em branco. </p>
                    <div class="col-sm-6 col-md-6 col-lg-4 form_hide">
                        <div class="form-wrap">
                            <label style="color: #333;" for="contact-email">Empresa</label>
                            <input class="form-input" id="contact-name" type="text" name="empresa">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4 form_hide">
                        <div class="form-wrap">
                            <label style="color: #333;" for="contact-email">Email</label>
                            <input class="form-input" id="contact-email" type="email" name="email_empresa">                            
                        </div>
                    </div>
                    <div class="col-sm-5 col-md-6 col-lg-4 form_hide">
                        <div class="form-wrap">
                            <label style="color: #333;" for="contact-email">CNPJ</label>
                            <input class="form-input cnpjmask" id="contact-name" type="text" name="cnpj">
                        </div>
                    </div>                    
                    
                    <div class="col-sm-7 col-md-6 col-lg-3 form_hide">
                        <div class="form-wrap">
                            <label style="color: #333;" for="contact-email">Endereço</label>
                            <input class="form-input" id="contact-name" type="text" name="rua">
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-2 col-lg-2 form_hide">
                        <div class="form-wrap">
                            <label style="color: #333;" for="contact-email">Número</label>
                            <input class="form-input" id="contact-name" type="text" name="num">
                        </div>
                    </div>
                    <div class="col-sm-9 col-md-4 col-lg-3 form_hide">
                        <div class="form-wrap">
                            <label style="color: #333;" for="contact-email">Bairro</label>
                            <input class="form-input" id="contact-name" type="text" name="bairro">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-2 form_hide">
                        <div class="form-wrap">
                            <label style="color: #333;" for="contact-email">Complemento</label>
                            <input class="form-input" id="contact-name" type="text" name="complemento">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-2 form_hide">
                        <div class="form-wrap">
                            <label style="color: #333;" for="contact-email">CEP</label>
                            <input class="form-input mask-zipcode" id="contact-name" type="text" name="cep">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 form_hide">
                        <div class="form-wrap">
                            <label style="color: #333;">Estado</label>
                            <select id="state-dd" name="uf" class="form-control">
                                @if(!empty($estados))
                                    @foreach($estados as $estado)
                                        <option value="{{$estado->estado_id}}">{{$estado->estado_nome}}</option>
                                    @endforeach                                                                        
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 form_hide">
                        <div class="form-wrap">
                            <label style="color: #333;" for="contact-email">Cidade</label>
                            <select id="city-dd" name="cidade" class="form-control">
                                <option value="">Selecione o Estado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-sm-6 col-md-4 col-lg-2 form_hide">   
                        <div class="form-wrap">      
                            <label style="color: #333;" for="contact-email">Telefone Fixo</label>               
                            <input class="form-input telefonemask" type="text" name="telefone1">                         
                        </div>                       
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-2 form_hide">   
                        <div class="form-wrap">      
                            <label style="color: #333;" for="contact-email">Celular</label>               
                            <input class="form-input celularmask" type="text" name="celular">                         
                        </div>                       
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-2 form_hide">   
                        <div class="form-wrap">      
                            <label style="color: #333;" for="contact-email">WhatsApp</label>               
                            <input class="form-input whatsappmask" type="text" name="whatsapp">                         
                        </div>                       
                    </div>
                    <h5 class="form_hide">Informações Adicionais</h5>
                    <p class="form_hide" style="color: #333;font-size:1.2em;">
                        Caso tenha mais informações para acrescentar ao 
                        projeto como outros telefones, Emails, Skype, Link de redes sociais etc.., 
                        pode descrever abaixo.
                    </p>
                    <div class="col-sm-12 form_hide">
                        <div class="form-wrap">
                            <div class="textarea-lined-wrap">
                            <textarea class="form-input" id="contact-message" name="notas_adicionais"></textarea>
                            <label class="form-label" for="contact-message">Informações Adicionais</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 form_hide">
                        <button class="btn btn-primary btn-block" id="js-contact-btn" type="submit">Enviar Agora</button>
                    </div> 
                </div>            
            </form>
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
        var $zipcode = $(".mask-zipcode");
        $zipcode.mask('00.000-000', {reverse: true});
        var $Cpf = $(".cpfmask");
        $Cpf.mask('000.000.000-00', {reverse: true});
        var $Cnpj = $(".cnpjmask");
        $Cnpj.mask('00.000.000/0000-00', {reverse: true});
        var $whatsapp = $(".whatsappmask");
        $whatsapp.mask('(99) 99999-9999', {reverse: false});
        var $telefone = $(".telefonemask");
        $telefone.mask('(99) 9999-9999', {reverse: false});
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
                url: "{{ route('web.sendFormCaptacao') }}",
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

        $('#state-dd').on('change', function () {
            var idState = this.value;
            $("#city-dd").html('');
            $.ajax({
                url: "{{route('users.fetchCity')}}",
                type: "POST",
                data: {
                    estado_id: idState,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#select2-city-dd-container').html('Selecione a cidade');
                    $.each(res.cidades, function (key, value) {
                        $("#city-dd").append('<option value="' + value
                            .cidade_id + '">' + value.cidade_nome + '</option>');
                    });
                }
            });
        });

    });
</script>   
@endsection