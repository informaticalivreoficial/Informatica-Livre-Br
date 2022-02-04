@extends('web.master.master')

@section('content')
<header class="page-head slider-menu-position">
    @include('web.include.header-minimo')
</header>

<main class="page-content">
    <!-- Single Ticket-->
    <section class="section-90 section-md-60 bg-zircon text-left">
      <div class="shell">         
        <div class="range range-xs-center">
          <div class="cell-sm-10 cell-md-12"> 
            <div class="offset-top-60">
              <!-- Table Schedule-->
              <div class="table-schedule-wrap">
                <div class="table-schedule">                
                  <div class="table-schedule-body bg-white">
                    <h2 class="text-bold">Meus Passeios</h2>     
                    <!-- Classic Responsive Table-->
                    <table class="table table-custom table-fixed table-hover-rows">
                      <tr>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Rating</th>
                        <th>Trip ID</th>
                        <th>Price</th>
                        <th></th>
                      </tr>
                      <tr>
                        <td>8:00am</td>
                        <td>1:00pm</td>
                        <td>
                          <div class="inset-lg-left-10">
                            <!-- Icon List-->
                            <ul class="list-inline list-inline-size-14 list-inline-0 text-ripe-lemon">
                              <li><span class="icon icon-xxs fa fa-star"></span></li>
                              <li><span class="icon icon-xxs fa fa-star"></span></li>
                              <li><span class="icon icon-xxs fa fa-star"></span></li>
                              <li><span class="icon icon-xxs fa fa-star-half-o"></span></li>
                              <li><span class="icon icon-xxs fa fa-star-o"></span></li>
                            </ul>
                          </div>
                        </td>
                        <td>ABC321</td>
                        <td class="text-bold text-gray-darker">$15.00</td>
                        <td class="p text-bold text-ripe-lemon"><a href="#">Buy Now</a></td>
                      </tr>
                      <tr>
                        <td>11:10am</td>
                        <td>4:20pm</td>
                        <td>
                          <div class="inset-lg-left-10">
                            <!-- Icon List-->
                            <ul class="list-inline list-inline-size-14 list-inline-0 text-ripe-lemon">
                              <li><span class="icon icon-xxs fa fa-star"></span></li>
                              <li><span class="icon icon-xxs fa fa-star"></span></li>
                              <li><span class="icon icon-xxs fa fa-star"></span></li>
                              <li><span class="icon icon-xxs fa fa-star-half-o"></span></li>
                              <li><span class="icon icon-xxs fa fa-star-o"></span></li>
                            </ul>
                          </div>
                        </td>
                        <td>GHJ654</td>
                        <td class="text-bold text-gray-darker">$16.20</td>
                        <td class="p text-bold text-ripe-lemon"><a href="#">Buy Now</a></td>
                      </tr>
                      <tr>
                        <td>1:30pm</td>
                        <td>6:45pm</td>
                        <td>
                          <div class="inset-lg-left-10">
                            <!-- Icon List-->
                            <ul class="list-inline list-inline-size-14 list-inline-0 text-ripe-lemon">
                              <li><span class="icon icon-xxs fa fa-star"></span></li>
                              <li><span class="icon icon-xxs fa fa-star"></span></li>
                              <li><span class="icon icon-xxs fa fa-star"></span></li>
                              <li><span class="icon icon-xxs fa fa-star-half-o"></span></li>
                              <li><span class="icon icon-xxs fa fa-star-o"></span></li>
                            </ul>
                          </div>
                        </td>
                        <td>QWE987</td>
                        <td class="text-bold text-gray-darker">$14.95</td>
                        <td class="p text-bold text-ripe-lemon"><a href="#">Buy Now</a></td>
                      </tr>
                      <tr>
                        <td>6:20pm</td>
                        <td>11:20pm</td>
                        <td>
                          <div class="inset-lg-left-10">
                            <!-- Icon List-->
                            <ul class="list-inline list-inline-size-14 list-inline-0 text-ripe-lemon">
                              <li><span class="icon icon-xxs fa fa-star"></span></li>
                              <li><span class="icon icon-xxs fa fa-star"></span></li>
                              <li><span class="icon icon-xxs fa fa-star"></span></li>
                              <li><span class="icon icon-xxs fa fa-star-half-o"></span></li>
                              <li><span class="icon icon-xxs fa fa-star-o"></span></li>
                            </ul>
                          </div>
                        </td>
                        <td>IUY951</td>
                        <td class="text-bold text-gray-darker">$15.25</td>
                        <td class="p text-bold text-ripe-lemon"><a href="#">Buy Now</a></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

@endsection

@section('css')

@endsection

@section('js')
<script>   

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