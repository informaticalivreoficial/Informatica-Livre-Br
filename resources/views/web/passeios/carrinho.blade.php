@extends('web.master.master')

@section('content')
<header class="page-head slider-menu-position">
    @include('web.include.header-minimo') 
</header>

<main class="page-content">
    <!-- Single Ticket-->
    <section class="section-30 section-md-30 bg-zircon text-left">
      <div class="shell">
        <div class="range range-xs-center">
          <div class="cell-sm-10 cell-md-12">
            
            <div class="offset-top-60">
              <!-- Table Schedule-->
              
                <div class="table-schedule">
                 
                  <div class="table-schedule-body bg-white">
                    <!-- Classic Responsive Table-->
                    <div class="container__cart">
                    @php
                        if ($passeio->valor_venda_promocional != ''){
                            $valorAdulto = substr(str_replace('.', '', str_replace(',', '.', $passeio->valor_venda_promocional)), 0, -2);
                        }else{
                            $valorAdulto = substr(str_replace('.', '', str_replace(',', '.', $passeio->valor_venda)), 0, -2); 
                        }
                        $valorCri05 = substr(str_replace('.', '', str_replace(',', '.', $passeio->valor_v_zerocinco)), 0, -2);
                        $valorCri06 = substr(str_replace('.', '', str_replace(',', '.', $passeio->valor_v_seisdoze)), 0, -2);
                    @endphp
                    <table data-responsive="true" class="table table-custom-white table-deals table-fixed table-hover-rows">
                      <tr>
                        <th style="width: 30%;">Passeio</th>
                        <th style="text-align:center;">Data</th>                        
                        <th style="width: 25%;">----------</th>                        
                        <th style="text-align:center;">Quantidade</th>
                        <th>Valor</th>
                      </tr>
                      <tr>
                        <td>{{$passeio->roteiro->name}}</td>
                        <td style="text-align:center;">{{session('cart')['data_passeio']}}</td>
                        <td>Adultos</td>
                        <td style="text-align:center;">{{session('cart')['qtd_adultos']}}</td>
                        <td>
                          R$ {{number_format(($valorAdulto * session('cart')['qtd_adultos']), 2, ',', '.')}}
                        </td>
                      </tr>
					            <tr>
                        <td></td>
                        <td></td>
                        <td>Crianças de 0 a 5 anos</td>
                        <td style="text-align:center;">{{session('cart')['qtd_zerocinco']}}</td>
                        <td>
                          R$ {{number_format(($valorCri05 * session('cart')['qtd_zerocinco']), 2, ',', '.')}}
                        </td>                        
                      </tr>
					            <tr>
                        <td></td>
                        <td></td>
                        <td>Crianças de 6 a 12 anos</td>
                        <td style="text-align:center;">{{session('cart')['qtd_seisdoze']}}</td>
                        <td>
                          R$ {{number_format(($valorCri06 * session('cart')['qtd_seisdoze']), 2, ',', '.')}}
                        </td>                        
                      </tr>
					            <tr>
                        <td></td>
                        <td></td>
                        <td style="text-align:right;"><b>Total:</b></td>
                        <td style="text-align:center;">{{(session('cart')['qtd_adultos'] + session('cart')['qtd_zerocinco'] + session('cart')['qtd_seisdoze'])}}</td>
                        <td>
                          R$ {{number_format(($valorAdulto * session('cart')['qtd_adultos']) + ($valorCri05 * session('cart')['qtd_zerocinco']) + ($valorCri06 * session('cart')['qtd_seisdoze']), 2, ',', '.')}}
                        </td>                        
                      </tr>                      
                    </table>

                    <form class="j_carrinho" method="post" action="">
                      @csrf
                      <input type="hidden" name="" value="">
                      <div class="offset-top-15 offset-sm-top-30" style="width: 100%;">
                          <div class="reveal-xs-inline-block text-middle" style="width: 100%;">
                              <button style="width: 100%;" type="submit" class="btn btn-ripe-lemon open_pagamento">Ir para Pagamento >></button>
                          </div>                        
                      </div> 
                    </form>
                  </div>

                    <div class="container__payment">
                    <div class="payment-details ">
                      <form action="{{route('web.passeios.paymentsend',$passeio->roteiro->slug)}}" method="post" id="paymentForm">
                          @csrf
                          <h3 class="title">Dados Para Pagamento</h3>
                          <input type="hidden" name="qtd_adultos" value="{{(session('cart')['qtd_adultos'])}}">
                          <input type="hidden" name="qtd_zerocinco" value="{{session('cart')['qtd_seisdoze']}}">
                          <input type="hidden" name="qtd_seisdoze" value="{{session('cart')['qtd_seisdoze']}}">
                          <input type="hidden" name="id_passeio" value="{{$passeio->id}}">
                          <input type="hidden" name="valor_adulto" value="{{$valorAdulto}}">
                          <input type="hidden" name="valorCri05" value="{{$passeio->valor_v_zerocinco}}">
                          <input type="hidden" name="valorCri06" value="{{$passeio->valor_v_seisdoze}}">
                          <input type="hidden" name="name" value="{{session('cart')['cliente']}}">
                          <input type="hidden" name="celular" value="{{session('cart')['cliente_celular']}}">
                          <input type="hidden" name="data_compra" value="{{session('cart')['data_passeio']}}">
                          <div class="range">
                            <div style="margin-top: 10px;" class="cell-lg-4">
                                <div class="form-group form-group-label-outside">
                                    <label for="email" class="form-label form-label-outside text-dark">*Email</label>
                                    <input id="email" type="text" name="email" class="form-control" value="{{session('cart')['email']}}">
                                </div>                            
                            </div>
                            <div style="margin-top: 10px;" class="cell-lg-4">
                                <div class="form-group form-group-label-outside">
                                    <label for="email" class="form-label form-label-outside text-dark">Documento</label>
                                    <select id="docType" name="docType" data-checkout="docType" class="form-control"></select>
                                </div>                            
                            </div>
                            <div style="margin-top: 10px;" class="cell-lg-4">
                                <div class="form-group form-group-label-outside">
                                    <label for="email" class="form-label form-label-outside text-dark">Número</label>
                                    <input id="docNumber" name="docNumber" data-checkout="docNumber" type="text" class="form-control" value="{{limpaCPF_CNPJ(session('cart')['cpf'])}}"/>
                                </div>                            
                            </div>
                        </div>
                          
                        <h3 class="title">Dados do Cartão</h3>
                        <div class="range">                          

                          <div style="margin-top: 10px;" class="cell-lg-4">
                              <div class="form-group form-group-label-outside">
                                  <label for="email" class="form-label form-label-outside text-dark">Nome</label>
                                  <input id="cardholderName" data-checkout="cardholderName" type="text" class="form-control" value="{{session('cart')['cliente']}}"/>
                              </div>                            
                          </div>
                          <div style="margin-top: 10px;" class="cell-lg-4">
                              <div class="form-group form-group-label-outside">
                                  <label for="email" class="form-label form-label-outside text-dark">Número do Cartão</label>
                                  <div style="float:right;padding-bottom:5px;" class="brand"></div>
                                <input id="cardNumber" data-checkout="cardNumber"
                                onselectstart="return false" onpaste="return false"
                                oncopy="return false" oncut="return false"
                                ondrag="return false" ondrop="return false" autocomplete=off type="text" class="form-control"/>
                              </div>                            
                          </div>
                          <div style="margin-top: 10px;" class="cell-lg-4">
                              <div class="form-group form-group-label-outside">
                                  <label for="email" class="form-label form-label-outside text-dark">Mês/Ano</label>
                                  <div class="input-group">
                                    <input id="cardExpirationMonth" data-checkout="cardExpirationMonth"
                                    onselectstart="return false" onpaste="return false" placeholder="MM"
                                    oncopy="return false" oncut="return false" style="width: 50%;"
                                    ondrag="return false" ondrop="return false" autocomplete=off type="text" class="form-control"/>
                                    
                                    <input id="cardExpirationYear" data-checkout="cardExpirationYear"
                                    onselectstart="return false" onpaste="return false" placeholder="YY"
                                    oncopy="return false" oncut="return false" style="width: 50%;"
                                    ondrag="return false" ondrop="return false" autocomplete=off type="text" class="form-control"/>
                                  </div>
                              </div>                            
                          </div>
                          <div style="margin-top: 10px;" class="cell-lg-4">
                              <div class="form-group form-group-label-outside">
                                  <label for="email" class="form-label form-label-outside text-dark">CVV</label>
                                  <input id="securityCode" data-checkout="securityCode" type="text"
                                  onselectstart="return false" onpaste="return false"
                                  oncopy="return false" oncut="return false"
                                  ondrag="return false" ondrop="return false" autocomplete=off type="text" class="form-control"/>
                              </div>                            
                          </div>
                          <div style="margin-top: 10px;" class="cell-lg-4">
                              <div class="form-group form-group-label-outside">
                                  <label for="email" class="form-label form-label-outside text-dark">Parcelamento</label>
                                  <select id="installments" name="installments" type="text" class="form-control"></select>
                              </div>                            
                          </div>                         
                          
                          <select style="display:none !important;" id="issuer" name="issuer"></select>
                          <input type="hidden" name="transactionAmount" id="transactionAmount" value="{{($valorAdulto * session('cart')['qtd_adultos']) + ($valorCri05 * session('cart')['qtd_zerocinco']) + ($valorCri06 * session('cart')['qtd_seisdoze'])}}" />
                          <input type="hidden" name="paymentMethodId" id="paymentMethodId" />
                          <input type="hidden" name="description" id="description" value="
                          {{$passeio->roteiro->name}} - Data: {{session('cart')['data_passeio']}}" />

                          <div style="margin-top: 10px;" class="cell-lg-12">
                            <button style="margin-top: 20px;" id="form-checkout__submit" type="submit" class="btn btn-success btn-block">Pagar</button>
                          </div>
                        </div>  

                        <div class="range"> 
                          <div style="margin-top: 10px;" class="cell-lg-12">
                            <img src="https://imgmp.mlstatic.com/org-img/MLB/MP/BANNERS/tipo2_575X40.jpg?v=1" 
                            alt="Mercado Pago - Meios de pagamento" title="Mercado Pago - Meios de pagamento" 
                            width="575" height="40"/>
                            <br>
                            <a href="https://www.mercadopago.com.br/ajuda/Custos-de-parcelamento_322" target="_blank" >Veja os Juros de parcelamento</a>
                          </div>
                        </div>
                                                
                         
                      </form>
                    </div>
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
<!-- Toastr -->
<link rel="stylesheet" href="{{url(asset('backend/plugins/toastr/toastr.min.css'))}}">
  <style>
    .container__payment {
        display: none;
    }
  </style>
@endsection

@section('js')
<!-- Toastr -->
<script src="{{url(asset('backend/plugins/toastr/toastr.min.js'))}}"></script>
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script>
  (function(win,doc){
      window.Mercadopago.setPublishableKey("TEST-ec79e761-cfaf-45c1-973e-961cc468e574");

      //Pega o Documento
      window.Mercadopago.getIdentificationTypes();

      function cardBin(event){
          
          let textLength = event.target.value.length;
          if(textLength >= 6){
              let bin = event.target.value.substring(0,6);
              window.Mercadopago.getPaymentMethod({
                  "bin": bin
              }, setPaymentMethod);

              window.Mercadopago.getInstallments({
                  "bin": bin,
                  "amount": parseFloat(document.querySelector('#transactionAmount').value),
              }, setInstallments);
          }
      }

      //Pega bandeira do cartão
      if(doc.querySelector('#cardNumber')){
          let cardNumber = doc.querySelector('#cardNumber');
          cardNumber.addEventListener('keyup', cardBin, false);
      }

      function setPaymentMethod(status, response) {
          if (status == 200) {                
              let paymentMethod = response[0];
              document.getElementById('paymentMethodId').value = paymentMethod.id;                
              doc.querySelector('.brand').innerHTML = "<img src='"+ response[0].thumbnail +"' alt='Bandeira' />";

              let issuerSelect = document.getElementById('issuer');
              response.forEach( issuer => {
                  let opt = document.createElement('option');
                  opt.text = issuer.name;
                  opt.value = issuer.id;
                  issuerSelect.appendChild(opt);
              });
          } else {
              alert(`payment method info error: ${response}`);
          }
      }

      function setInstallments(status, response){
          if (status == 200) {
              document.getElementById('installments').options.length = 0;
              response[0].payer_costs.forEach( payerCost => {
                  let opt = document.createElement('option');
                  opt.text = payerCost.recommended_message;
                  opt.value = payerCost.installments;
                  document.getElementById('installments').appendChild(opt);
              });
          } else {
              alert(`installments method info error: ${response}`);
          }
      }

      // Create Token
      function SendPayment(event){
          event.preventDefault();
          window.Mercadopago.createToken(event.target, setCardTokenAndPay);
      }

      function setCardTokenAndPay(status, response) {
        //console.log(response);
        if(status == 400){
            response.cause.forEach(function(item){
                if(item.code == '209'){
                  toastr.error("O ano de expiração do cartão está vazio ou incompleto!");
                }if(item.code == '326'){
                  toastr.error("O ano de expiração do cartão está inválido!");
                }if(item.code == '208'){
                  toastr.error("O mês de expiração do cartão está vazio ou incompleto!");
                }if(item.code == '325'){
                  toastr.error("O mês de expiração do cartão está inválido!");
                }if(item.code == '205'){
                  toastr.error("O número do cartão está vazio ou incompleto!");
                }if(item.code == 'E301'){
                  toastr.error("O número do cartão está inválido!");
                }if(item.code == 'E302'){
                  toastr.error("Código de segurança vazio ou inválido!");
                }if(item.code == '221'){
                  toastr.error("O campo Nome está vazio ou inválido!");
                }if(item.code == '324'){
                  toastr.error("O campo CPF está vazio ou inválido!");
                }             
            });
        }
        if (status == 200 || status == 201) {
            let form = doc.getElementById('paymentForm');
            let card = doc.createElement('input');
            card.setAttribute('name', 'token');
            card.setAttribute('type', 'hidden');
            card.setAttribute('value', response.id);
            form.appendChild(card);
            form.submit();            
        }        
        
      };

      if(doc.querySelector('#paymentForm')){
          let formPay = doc.querySelector('#paymentForm');
          formPay.addEventListener('submit', SendPayment, false);
      }

      function getIssuers(paymentMethodId) {
          window.Mercadopago.getIssuers(
              paymentMethodId,
              setIssuers
          );
      }

  })(window, document);    
</script>


<script>

  $(function (){
  
      $('.open_pagamento').on('click', function (event) {
          event.preventDefault();
  
          $('#cardNumber').val('');
          $('#securityCode').val('');
          $('#issuer').val('');
          $('#installments').val('');
          $('#paymentMethodId').val('');
          
          var box1 = $('.container__cart');
          var box = $(".container__payment");        
          
          box1.slideToggle();
          box.slideToggle();
      });
  
      $('#goback').on('click', function (event) {
          event.preventDefault();
          $('.open_pagamento').css("display", "none");
      });
      
  });  
      
  </script>
@endsection