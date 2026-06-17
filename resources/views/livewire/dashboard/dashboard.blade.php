<div 
    x-data="{
        openLightbox(src) {
            basicLightbox.create(`<img src='${src}' style='max-width:90vw; max-height:90vh;'>`).show()
        }
    }"
>   
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Painel de Controle</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Início</a></li>
                        <li class="breadcrumb-item active">Painel de Controle</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info">
                            <a href="{{ route('companies.index') }}" title="Empresas">
                                <i class="fa far fa-industry"></i>
                            </a>
                        </span>            
                        <div class="info-box-content">
                            <span class="info-box-text"><b>Empresas</b></span>
                            <span class="info-box-text">{{ now()->year }}: {{ $companyYearCount }}</span>
                            <span class="info-box-text">Total: {{ $companyCount }}</span>
                        </div>            
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary">
                            <a href="{{ route('services.subscriptions.index') }}" title="Pedidos">
                                <i class="fa far fa-shopping-cart"></i>
                            </a>
                        </span>            
                        <div class="info-box-content">
                            <span class="info-box-text"><b>Pedidos</b></span>
                            <span class="info-box-text">{{ now()->year }}: {{ $subscriptionsYearCount }}</span>
                            <span class="info-box-text">Total: {{ $subscriptionsCount }}</span>
                        </div>            
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-green">
                            <a href="{{ route('invoices.index') }}" title="Faturas">
                                <i class="fa far fa-money-check"></i>
                            </a>
                        </span>            
                        <div class="info-box-content">
                            <span class="info-box-text"><b>Faturas</b></span>
                            <span class="info-box-text">{{ now()->year }}: {{ $invoicesYearCount }}</span>
                            <span class="info-box-text">Total: {{ $invoicesCount }}</span>
                        </div>            
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-teal">
                            <a href="{{-- route('posts.index') --}}" title="Posts">
                                <i class="fa far fa-pencil-alt"></i>
                            </a>
                        </span>            
                        <div class="info-box-content">
                            <span class="info-box-text"><b>Posts</b></span>
                            <span class="info-box-text">{{ now()->year }}: {{-- $postsYearCount --}}</span>
                            <span class="info-box-text">Total: {{-- $postsCount --}}</span>
                        </div>            
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>
                                R$ {{ number_format($receivedThisMonth, 2, ',', '.') }}
                            </h3>

                            <p>Recebido no mês</p>
                        </div>

                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>
                                R$ {{ number_format($pendingAmount, 2, ',', '.') }}
                            </h3>

                            <p>A receber</p>
                        </div>

                        <div class="icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>
                                R$ {{ number_format($overdueAmount, 2, ',', '.') }}
                            </h3>

                            <p>Em atraso</p>
                        </div>

                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $activeSubscriptions }}</h3>

                            <p>Assinaturas Ativas</p>
                        </div>

                        <div class="icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                    </div>
                </div>

            </div> 
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Últimos Pagamentos
                            </h3>
                        </div>

                        <div class="card-body p-0">

                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Valor</th>
                                        <th>Pagamento</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($latestPayments as $invoice)
                                        <tr>
                                            <td>{{ $invoice->company->alias_name }}</td>

                                            <td>
                                                R$ {{ number_format($invoice->amount, 2, ',', '.') }}
                                            </td>

                                            <td>
                                                {{ $invoice->paid_at?->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>                    
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Próximos Vencimentos
                            </h3>
                        </div>

                        <div class="card-body p-0">

                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Valor</th>
                                        <th>Vencimento</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($upcomingInvoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->company->alias_name }}</td>

                                            <td>
                                                R$ {{ number_format($invoice->amount, 2, ',', '.') }}
                                            </td>

                                            <td>
                                                {{ $invoice->due_date->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>                    
            </div>
            <livewire:dashboard.reports.dashboard-stats /> 

        </div>

        
    </div>

    
    
</div>

@push('scripts')  
    @if(session()->has('toastr'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                toastr["{{ session('toastr.type') }}"](
                    "{{ session('toastr.message') }}",
                    "{{ session('toastr.title') }}"
                );
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                };
            });
        </script>
    @endif
@endpush