<div>
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

                <a href="{{ route('companies.index') }}" class="group matrix-card bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4 hover:shadow-md hover:border-blue-200 transition">
                    <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-industry text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Empresas</p>
                        <p class="text-xl font-bold text-gray-800 leading-tight">{{ $companyCount }}</p>
                        <p class="text-[11px] text-gray-400">{{ now()->year }}: {{ $companyYearCount }}</p>
                    </div>
                </a>

                <a href="{{ route('services.subscriptions.index') }}" class="group matrix-card bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4 hover:shadow-md hover:border-indigo-200 transition">
                    <div class="w-11 h-11 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <i class="fas fa-shopping-cart text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pedidos</p>
                        <p class="text-xl font-bold text-gray-800 leading-tight">{{ $subscriptionsCount }}</p>
                        <p class="text-[11px] text-gray-400">{{ now()->year }}: {{ $subscriptionsYearCount }}</p>
                    </div>
                </a>

                <a href="{{ route('invoices.index') }}" class="group matrix-card bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4 hover:shadow-md hover:border-green-200 transition">
                    <div class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0 group-hover:bg-green-600 group-hover:text-white transition">
                        <i class="fas fa-money-check text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Faturas</p>
                        <p class="text-xl font-bold text-gray-800 leading-tight">{{ $invoicesCount }}</p>
                        <p class="text-[11px] text-gray-400">{{ now()->year }}: {{ $invoicesYearCount }}</p>
                    </div>
                </a>

                <a href="{{ route('posts.index') }}" class="group matrix-card bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4 hover:shadow-md hover:border-teal-200 transition">
                    <div class="w-11 h-11 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 group-hover:bg-teal-600 group-hover:text-white transition">
                        <i class="fas fa-pencil-alt text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Posts</p>
                        <p class="text-xl font-bold text-gray-800 leading-tight">{{ $postsCount }}</p>
                        <p class="text-[11px] text-gray-400">{{ now()->year }}: {{ $postsYearCount }}</p>
                    </div>
                </a>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-sm p-4 text-white flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-green-100 uppercase tracking-wide">Recebido no mês</p>
                        <p class="text-2xl font-bold mt-1">R$ {{ number_format($receivedThisMonth, 2, ',', '.') }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-white/20 flex items-center justify-center shrink-0">
                        <i class="fas fa-dollar-sign text-lg"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl shadow-sm p-4 text-white flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-amber-100 uppercase tracking-wide">A receber</p>
                        <p class="text-2xl font-bold mt-1">R$ {{ number_format($pendingAmount, 2, ',', '.') }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-white/20 flex items-center justify-center shrink-0">
                        <i class="fas fa-file-invoice-dollar text-lg"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-sm p-4 text-white flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-red-100 uppercase tracking-wide">Em atraso</p>
                        <p class="text-2xl font-bold mt-1">R$ {{ number_format($overdueAmount, 2, ',', '.') }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-white/20 flex items-center justify-center shrink-0">
                        <i class="fas fa-exclamation-triangle text-lg"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-sm p-4 text-white flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-blue-100 uppercase tracking-wide">Assinaturas Ativas</p>
                        <p class="text-2xl font-bold mt-1">{{ $activeSubscriptions }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-white/20 flex items-center justify-center shrink-0">
                        <i class="fas fa-sync-alt text-lg"></i>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

                <div class="matrix-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="matrix-card-header px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-700">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Últimos Pagamentos
                        </h3>
                        <a href="{{ route('invoices.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                            Ver todas <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Empresa</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Valor</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Pagamento</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($latestPayments as $invoice)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-2.5 text-sm text-gray-700">{{ $invoice->payerLabel() }}</td>
                                        <td class="px-4 py-2.5 text-sm font-medium text-green-600">
                                            R$ {{ number_format($invoice->amount, 2, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-2.5 text-sm text-gray-500">
                                            {{ $invoice->paid_at?->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-400">
                                            Nenhum pagamento registrado
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="matrix-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="matrix-card-header px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar-alt text-amber-500 mr-2"></i>
                            Próximos Vencimentos
                        </h3>
                        <a href="{{ route('invoices.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                            Ver todas <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Empresa</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Valor</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Vencimento</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($upcomingInvoices as $invoice)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-2.5 text-sm text-gray-700">{{ $invoice->payerLabel() }}</td>
                                        <td class="px-4 py-2.5 text-sm font-medium text-gray-800">
                                            R$ {{ number_format($invoice->amount, 2, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-2.5 text-sm text-gray-500">
                                            {{ $invoice->due_date->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-400">
                                            Nenhum vencimento nos próximos 30 dias
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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