<div>
    @section('title', $title)

    {{-- HEADER --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-chart-line mr-2"></i>
                        Relatórios
                    </h1>
                </div>
            </div>
        </div>
    </div>

    {{-- CARDS --}}
    <div class="row">

        <x-dashboard.small-box
            title="Assinaturas"
            :value="$stats['subscriptions_total']"
            icon="fas fa-file-signature"
            color="info"
        />

        <x-dashboard.small-box
            title="Ativas"
            :value="$stats['subscriptions_active']"
            icon="fas fa-check-circle"
            color="success"
        />

        <x-dashboard.small-box
            title="Pendentes"
            :value="$stats['invoices_pending']"
            icon="fas fa-file-invoice"
            color="warning"
        />

        <x-dashboard.small-box
            title="Em Atraso"
            :value="'R$ ' . number_format($stats['revenue_overdue'], 2, ',', '.')"
            icon="fas fa-exclamation-triangle"
            color="danger"
        />

    </div>

    {{-- CHARTS --}}
    <div class="row mt-4">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Assinaturas
                </div>
                <div class="card-body">
                    <canvas id="subscriptionsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Receita
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('livewire:init', () => {

            const subscriptions = @json($chartSubscriptions);
            const revenue = @json($chartRevenue);

            new Chart(document.getElementById('subscriptionsChart'), {
                type: 'doughnut',
                data: {
                    labels: subscriptions.labels,
                    datasets: [{
                        data: subscriptions.data,
                        backgroundColor: ['#28a745', '#dc3545'],
                    }]
                }
            });

            new Chart(document.getElementById('revenueChart'), {
                type: 'bar',
                data: {
                    labels: revenue.labels,
                    datasets: [{
                        label: 'Receita (R$)',
                        data: revenue.data,
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    }]
                }
            });

        });
    </script>

</div>