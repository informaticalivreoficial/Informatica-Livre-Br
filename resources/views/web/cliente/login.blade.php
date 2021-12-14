@extends('web.master.master')

@section('content')
<header class="page-head slider-menu-position">
    @include('web.include.header-minimo')
</header>

<main class="page-content section-bottom-60 section-md-top-111 section-md-bottom-100 text-md-left">
    <section>
      <div class="shell">
        <h2 class="text-bold">Meus Passeios</h2>
        <hr class="divider hr-md-left-0 bg-gray-darker">
        <div class="range range-xs-center range-md-left offset-top-30 offset-md-top-60">
          <div class="cell-xs-10 cell-sm-8 cell-md-6 cell-lg-4">
            <!-- RD Mailform-->
            <form data-form-output="form-output-global" data-form-type="contact" method="post" action="bat/rd-mailform.php" class="rd-mailform text-left">
              <div class="form-group form-group-label-outside">
                <label for="login" class="form-label form-label-outside text-dark">Digite seu CPF</label>
                <input id="login" type="text" name="cpf" class="form-control cpfmask">
              </div>              
              <div class="offset-top-15 offset-sm-top-30 text-center text-md-right">
                <div class="reveal-xs-inline-block text-middle">
                  <button type="submit" class="btn btn-ripe-lemon">Enviar</button>
                </div>                
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

@endsection

@section('css')

@endsection

@section('js')
<script src="{{url(asset('backend/assets/js/jquery.mask.js'))}}"></script>
<script>
    $(document).ready(function () { 
        var $Cpf = $(".cpfmask");
        $Cpf.mask('000.000.000-00', {reverse: true});        
    });

    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });       

        $('.j_formsubmit').submit(function (){
            var form = $(this);
            var dataString = $(form).serialize();

            $.ajax({
                url: "{{ route('web.passeios.carrinhocreate') }}",
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
                    $('html, body').animate({scrollTop:$('#js-contact-result').offset().top-130}, 'slow');
                    if(resposta.error){
                        form.find('#js-contact-result').html('<div class="alert alert-danger error-msg">'+ resposta.error +'</div>');
                        form.find('.error-msg').fadeIn();                    
                    }else{
                        form.find('.error-msg').fadeIn(); 
                        setTimeout(function() {
                            window.location.href = resposta.redirect;
                        }, 2000); 
                    }
                },
                complete: function(resposta){
                    form.find("#js-contact-btn").attr("disabled", false);
                    form.find('#js-contact-btn').html("Finalizar >>");                                
                }

            });

            return false;
        });
    });
</script>
@endsection