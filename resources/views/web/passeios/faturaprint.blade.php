<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Voucher de passeio - {{$configuracoes->nomedosite}}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{url(asset('vendor/fontawesome-free/css/all.min.css'))}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url(asset('vendor/adminlte/dist/css/adminlte.min.css'))}}">

  <!-- FAVICON -->
  <link rel="shortcut icon" href="{{$configuracoes->getfaveicon()}}"/>
  <link rel="apple-touch-icon" href="{{$configuracoes->getfaveicon()}}"/>
  <link rel="apple-touch-icon" sizes="72x72" href="{{$configuracoes->getfaveicon()}}"/>
  <link rel="apple-touch-icon" sizes="114x114" href="{{$configuracoes->getfaveicon()}}"/>
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
        <h2 class="page-header">
            <img src="{{$configuracoes->getfaveicon()}}" alt="{{$configuracoes->nomedosite}}"> {{$configuracoes->nomedosite}}.
            <small class="float-right">Data: {{$pedido->created_at}}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            Prestador
            <address>
              <strong>{{$configuracoes->nomedosite}}.</strong><br>
              {{$configuracoes->rua}}, {{$configuracoes->num}}<br>
              {{$configuracoes->bairro}}, {{getCidadeNome($configuracoes->cidade, 'cidades')}}<br>
              Tel: {{$configuracoes->whatsapp}}<br>
              Email: {{$configuracoes->email}}
            </address>
        </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        Cliente
        <address>
          <strong>{{$pedido->cliente->name}}</strong><br>
          Tel: {{$pedido->cliente->celular}}<br>
          Email: {{$pedido->cliente->email}}
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Fatura #{{$pedido->id}}</b><br>
        <br>
        <b>Status da Compra:</b> {!!$pedido->getStatusPayment()!!}              
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>                    
              <th>Passeio</th>
              <th>Data</th>
              <th>Passageiros</th>
              <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>                 
            <tr>                    
              <td>{{$pedido->passeio->roteiro->name}} - <b>Saída {{$pedido->passeio->bairro}}</b></td>
              <td>{{$pedido->data_compra}}</td>
              <td>{{$pedido->total_passageiros}}</td>
              <td>R$ {{$pedido->valor}}</td>
            </tr>                  
            </tbody>
          </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-6">
        <p class="lead">Descrição:</p> 
        <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
          {!!$pedido->description!!}
          <br>
          Local de Saída: {{$pedido->passeio->bairro}}
          <br>
          Horário de Saída: {{$pedido->passeio->saida}}
        </p>
      </div>
      <!-- /.col -->
      <div class="col-6"> 
        <div class="table-responsive">
          <table class="table">                    
            <tr>
              <th>Total:</th>
              <td>R$ {{$pedido->valor}}</td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-12">
            <p class="text-muted">*{{$pedido->passeio->notasadicionais}}</p>
        </div>
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
<!-- Page specific script -->
<script>
  window.addEventListener("load", window.print());
</script>
</body>
</html>
