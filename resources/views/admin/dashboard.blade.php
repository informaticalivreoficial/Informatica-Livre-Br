@extends('adminlte::page')

@section('title', 'Painel de Controle')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Painel de Controle</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Início</a></li>
            <li class="breadcrumb-item active">Painel de Controle</li>
        </ol>
    </div><!-- /.col -->
</div>
@stop

@section('content')
<div class="row">
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><a href="{{ route('vendas.orcamentos') }}" title="Orçamentos"><i class="fa far fa-file"></i></a></span>

            <div class="info-box-content">
                <span class="info-box-text"><b>Orçamentos</b></span>
                <span class="info-box-text">Pendentes: {{ $orcamentosPendentes }}</span>
                <span class="info-box-text">Concluídos: {{ $orcamentosConcluidos }}</span>
                <span class="info-box-text">Total: {{ $orcamentosPendentes + $orcamentosConcluidos }}</span>
            </div>            
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="info-box">
            <span class="info-box-icon bg-teal"><a href="{{route('produtos.index')}}" title="Produtos"><i class="fa far fa-store"></i></a></span>

            <div class="info-box-content">
                <span class="info-box-text"><b>Produtos</b></span>
                <span class="info-box-text">Disponíveis: {{ $produtosAvailable }}</span>
                <span class="info-box-text">Inativos: {{ $produtosUnavailable }}</span>
                <span class="info-box-text">Total: {{ $produtosTotal }}</span>
            </div>
        </div>
    </div> 
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="info-box">
            <span class="info-box-icon bg-teal"><a href="{{route('empresas.index')}}" title="Empresas"><i class="fa far fa-industry"></i></a></span>

            <div class="info-box-content">
                <span class="info-box-text"><b>Empresas</b></span>
                <span class="info-box-text">Ativas: {{ $empresasAvailable }}</span>
                <span class="info-box-text">Inativas: {{ $empresasUnavailable }}</span>
                <span class="info-box-text">Total: {{ $empresasTotal }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>       
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="info-box">
            <span class="info-box-icon bg-teal"><a href="{{--route('embarcacoes.index')--}}" title="Pedidos"><i class="fa far fa-money-check"></i></a></span>

            <div class="info-box-content">
                <span class="info-box-text"><b>Pedidos</b></span>
                <span class="info-box-text">Aprovados: {{-- $pedidosApproved --}}</span>
                <span class="info-box-text">Processando: {{-- $pedidosInprocess --}}</span>
                <span class="info-box-text">Cancelado: {{-- $pedidosRejected --}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
    <!-- /.info-box -->
    </div>
</div>

<div class="row">
    <section class="col-lg-6 connectedSortable">
            <!-- BAR CHART -->
            <div class="card card-teal">
                <div class="card-header">
                    <h3 class="card-title">Visitas/Últimos 6 meses</h3>

                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </section>
        <section class="col-lg-6 connectedSortable">
        <!-- DONUT CHART -->
        <div class="card card-teal">
            <div class="card-header">
            <h3 class="card-title">Dispositivos/Últimos 6 meses</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
            </div>
            </div>
            <div class="card-body">
            <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </section>
    </div><!-- /.row -->


<div class="row">
    <div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-3">
        <div class="card card-danger">                
            <div class="card-body">
              <canvas id="donutChartusers" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-3">
        <div class="card card-danger">                
            <div class="card-body">
              <canvas id="donutChartposts" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-3">
        <div class="card card-danger">                
            <div class="card-body">
              <canvas id="donutChartpedidos" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

@if(!empty($artigosTop) && $artigosTop->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Artigos mais visitados</h3>
        </div>
        <div class="card-body p-0">
          <table class="table table-sm">
            <thead>
              <tr>
                    <th>Foto</th>
                    <th>Título</th>
                    <th></th>
                    <th class="text-center">Visitas</th>
              </tr>
            </thead>
            <tbody>                            
                @foreach($artigosTop as $artigotop)
                @php
                    //REALIZA PORCENTAGEM DE VISITAS!
                    if($artigotop->views == 0){
                        $percent = 1;
                    }else{
                        $percent = substr(( $artigotop->views / $artigostotalviews ) * 100, 0, 5);
                    }
                    
                    $percenttag = str_replace(",", ".", $percent);
                @endphp
                <tr>
                    <td>
                        <a href="{{url($artigotop->nocover())}}" data-title="{{$artigotop->titulo}}" data-toggle="lightbox"> 
                            <img src="{{url($artigotop->cover())}}" alt="{{$artigotop->titulo}}" class="img-size-50">
                        </a>
                    </td>
                    <td>{{$artigotop->titulo}}</td>
                    <td style="width:10%;">
                      <div class="progress progress-md progress-striped active">
                        <div class="progress-bar bg-primary" style="width: {{$percenttag}}%" title="{{$percenttag}}%"></div>
                      </div>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-primary">{{$artigotop->views}}</span>
                      <a data-toggle="tooltip" data-placement="top" title="Editar Artigo" href="{{route('posts.edit', ['id' => $artigotop->id])}}" class="btn btn-xs btn-default ml-2"><i class="fas fa-pen"></i></a>
                      <a target="_blank" href="{{route('web.blog.artigo',['slug' => $artigotop->slug])}}" class="btn btn-xs btn-info text-white"><i class="fas fa-search"></i></a>
                    </td>
                </tr>
                @endforeach                            
            </tbody>
          </table>
        </div>
    </div>
@endif

@if(!empty($paginasTop) && $paginasTop->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Páginas mais visitadas</h3>
        </div>
        <div class="card-body p-0">
          <table class="table table-sm">
            <thead>
              <tr>
                    <th>Foto</th>
                    <th>Título</th>
                    <th></th>
                    <th class="text-center">Visitas</th>
              </tr>
            </thead>
            <tbody>                            
                @foreach($paginasTop as $paginatop)
                @php
                    //REALIZA PORCENTAGEM DE VISITAS!
                    if($paginatop->views == '0'){
                        $percent = 1;
                    }else{
                        $percent = substr(( $paginatop['views'] / $paginastotalviews ) * 100, 0, 5);
                    }
                    
                    $percenttag = str_replace(",", ".", $percent);
                @endphp
                <tr>
                    <td>
                        <a href="{{url($paginatop->nocover())}}" data-title="{{$paginatop->titulo}}" data-toggle="lightbox"> 
                            <img src="{{url($paginatop->cover())}}" alt="{{$paginatop->titulo}}" class="img-size-50">
                        </a>
                    </td>
                    <td>{{$paginatop->titulo}}</td>
                    <td style="width:10%;">
                      <div class="progress progress-md progress-striped active">
                        <div class="progress-bar bg-primary" style="width: {{$percenttag}}%" title="{{$percenttag}}%"></div>
                      </div>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-primary">{{$paginatop->views}}</span>
                      <a data-toggle="tooltip" data-placement="top" title="Editar Página" href="{{route('posts.edit', ['id' => $paginatop->id])}}" class="btn btn-xs btn-default ml-2"><i class="fas fa-pen"></i></a>
                      <a target="_blank" href="{{route('web.pagina',['slug' => $paginatop->slug])}}" class="btn btn-xs btn-info text-white"><i class="fas fa-search"></i></a>
                    </td>
                </tr>
                @endforeach                            
            </tbody>
          </table>
        </div>
    </div>
@endif
</section>
@stop

@section('css')
<link rel="stylesheet" href="{{url(asset('backend/plugins/ekko-lightbox/ekko-lightbox.css'))}}">
<style>
    .info-box .info-box-content {   
        line-height: 120%;
    }
</style>
@endsection

@section('js')
<script src="{{url(asset('backend/plugins/ekko-lightbox/ekko-lightbox.min.js'))}}"></script>
    <script>  
    $(function (){

        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
            alwaysShowClose: true
            });
        }); 

        var areaChartData = {
            labels  : [
            @foreach($analyticsData->rows as $dataMonth)                
                'Mês/{{substr($dataMonth[0], -2)}}',                                 
            @endforeach
            ],
            datasets: [
                {
                label               : 'Visitas Únicas',
                backgroundColor     : 'rgba(60,141,188,0.9)',
                borderColor         : 'rgba(60,141,188,0.8)',
                pointRadius          : false,
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data                : [
                                    @foreach($analyticsData->rows as $dataMonth)                
                                        '{{$dataMonth[2]}}',                                 
                                    @endforeach
                                    ]
                },
                {
                label               : 'Visitas',
                backgroundColor     : 'rgba(210, 214, 222, 1)',
                borderColor         : 'rgba(210, 214, 222, 1)',
                pointRadius         : false,
                pointColor          : 'rgba(210, 214, 222, 1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data                : [
                                    @foreach($analyticsData->rows as $dataMonth)                
                                        '{{$dataMonth[1]}}',                                 
                                    @endforeach
                                    ]
                },
            ]
        }

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = jQuery.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        barChartData.datasets[0] = temp1
        barChartData.datasets[1] = temp0

        var barChartOptions = {
        responsive              : true,
        maintainAspectRatio     : false,
        datasetFill             : false
        }

        var barChart = new Chart(barChartCanvas, {
        type: 'bar', 
        data: barChartData,
        options: barChartOptions
        });

        function dynamicColors() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgba(" + r + "," + g + "," + b + ", 0.5)";
        }

        //-------------
        //- DONUT CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
        var donutData        = {
          labels: [
              @if(!empty($top_browser))
                @foreach($top_browser as $browser)
                  '{{$browser['browser']}}',
                @endforeach
              @else
                'Chrome', 
                'IE',
                'FireFox', 
                'Safari', 
                'Opera', 
                'Navigator',
              @endif               
          ],
          datasets: [
            {
              data: [
                @if(!empty($top_browser))
                  @foreach($top_browser as $key => $browser)
                    {{$browser['sessions']}},
                  @endforeach
                @else
                  700,500,400,600,300,100
                @endif                
                ],
              backgroundColor : [
                @foreach($top_browser as $key => $browser)
                  dynamicColors(),
                @endforeach
                ],
            }
          ]
        }
        var donutOptions     = {
          maintainAspectRatio : false,
          responsive : true,
        }

        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        var donutChart = new Chart(donutChartCanvas, {
          type: 'doughnut',
          data: donutData,
          options: donutOptions      
        });




        var donutChartCanvasUsers = $('#donutChartusers').get(0).getContext('2d');
        var donutDatausers        = {
            labels: [ 
                'Clientes Inativos', 
                'Clientes Ativos',
                'Time'             
            ],
            datasets: [
                {
                data: [{{ $usersUnavailable }},{{ $usersAvailable }}, {{ $time }}],
                    backgroundColor : ['#4BC0C0', '#36A2EB', '#FF6384'],
                }
            ]
            }
            var donutOptions     = {
            maintainAspectRatio : false,
            responsive : true,
            }

            var donutChart = new Chart(donutChartCanvasUsers, {
            type: 'doughnut',
            data: donutDatausers,
            options: donutOptions      
            });
    }); 

    $(function (){
        var donutChartCanvasPosts = $('#donutChartposts').get(0).getContext('2d');
        var donutDataposts        = {
            labels: [ 
                'Artigos', 
                'Páginas'             
            ],
            datasets: [
                {
                data: [{{ $postsArtigos }}, {{ $postsPaginas }}],
                    backgroundColor : ['#018577', '#BAC431'],
                }
            ]
            }
            var donutOptions     = {
            maintainAspectRatio : false,
            responsive : true,
            }

            var donutChart = new Chart(donutChartCanvasPosts, {
            type: 'doughnut',
            data: donutDataposts,
            options: donutOptions      
            });
    });  

    // $(function (){
    //     var donutChartCanvasPedidos = $('#donutChartpedidos').get(0).getContext('2d');
    //     var donutDatapedidos        = {
    //         labels: [ 
    //             'Aprovados', 
    //             'Processando',
    //             'Cancelado'             
    //         ],
    //         datasets: [
    //             {
    //             data: [{{-- $pedidosApproved --}},{{-- $pedidosInprocess --}}, {{-- $pedidosRejected --}}],
    //                 backgroundColor : ['#97C490', '#EBCF7F', '#E98BA5'],
    //             }
    //         ]
    //         }
    //         var donutOptions     = {
    //         maintainAspectRatio : false,
    //         responsive : true,
    //         }

    //         var donutChart = new Chart(donutChartCanvasPedidos, {
    //         type: 'doughnut',
    //         data: donutDatapedidos,
    //         options: donutOptions      
    //         });
    // });    
    </script>
@stop
