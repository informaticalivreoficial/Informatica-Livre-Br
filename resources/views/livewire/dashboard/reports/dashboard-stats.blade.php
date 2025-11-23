<div>
    <div class="row">
        <section class="col-lg-6 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Visitas/Últimos 6 meses</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </section>
        <section class="col-lg-6 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dispositivos/Últimos 6 meses</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>   
    document.addEventListener("DOMContentLoaded", function () {

    // ------------------- BAR CHART ---------------------
    const bar = document.getElementById('barChart');

    new Chart(bar, {
        type: 'bar',
        data: {
            labels: @json($barChartData['labels']),
            datasets: [
                {
                    label: "Visitas",
                    backgroundColor: "rgba(220,220,220,0.5)",
                    borderColor: "rgba(220,220,220,1)",
                    data: @json($barChartData['sessions'])
                },
                {
                    label: "Visitas Únicas",
                    backgroundColor: "rgba(60,141,188,0.7)",
                    borderColor: "rgba(60,141,188,1)",
                    data: @json($barChartData['users'])
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // ------------------- DONUT CHART ---------------------
    const donut = document.getElementById('donutChart');

    new Chart(donut, {
        type: 'doughnut',
        data: {
            labels: @json($donutChartData['labels']),
            datasets: [{
                data: @json($donutChartData['values']),
                backgroundColor: [
                    '#f78ae0', '#7ee8d1', '#4f8bb0', '#7bc8ff', '#b4e57a'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: "65%"
        }
    });

});

</script>
@endpush