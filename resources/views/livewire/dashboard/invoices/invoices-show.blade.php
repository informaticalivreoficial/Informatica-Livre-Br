<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-file-invoice mr-2"></i> Fatura #{{ $invoice->id }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Faturas</a></li>
                        <li class="breadcrumb-item active">Fatura #{{ $invoice->id }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i> Detalhes da Fatura
                    </h3>
                    <div class="card-tools">
                        @if ($invoice->gateway_reference)
                            <button
                                wire:click="syncInvoice"
                                wire:loading.attr="disabled"
                                wire:target="syncInvoice"
                                class="btn btn-sm btn-outline-primary"
                                title="Atualizar status no PagHiper"
                            >
                                <span wire:loading.remove wire:target="syncInvoice">
                                    <i class="fas fa-sync mr-1"></i> Atualizar status
                                </span>
                                <span wire:loading wire:target="syncInvoice">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Aguarde...
                                </span>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <th class="text-muted" style="width: 40%;">Pagador</th>
                                        <td>
                                            {{ $invoice->payerLabel() }}
                                            @if ($invoice->isCustomPayer())
                                                <span class="badge badge-warning ml-1">Avulso</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">CPF/CNPJ</th>
                                        <td>{{ $invoice->company?->document_company ?: ($invoice->payer_document ?: '-') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">E-mail</th>
                                        <td>{{ $invoice->company?->email ?: ($invoice->payer_email ?: '-') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Serviço</th>
                                        <td>{{ $invoice->subscription?->service?->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Descrição</th>
                                        <td>{{ $invoice->description ?: ($invoice->subscription?->service?->name ?? '-') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Valor</th>
                                        <td class="font-weight-bold">
                                            R$ {{ number_format($invoice->amount, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Vencimento</th>
                                        <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 col-md-6">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <th class="text-muted" style="width: 40%;">Status</th>
                                        <td>
                                            <span class="badge
                                                @if($invoice->status === 'paid') badge-success
                                                @elseif($invoice->status === 'pending') badge-warning
                                                @elseif($invoice->status === 'canceled') badge-danger
                                                @elseif($invoice->status === 'refunded') badge-secondary
                                                @endif
                                            ">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Gateway</th>
                                        <td>{{ $invoice->gateway ? ucfirst($invoice->gateway) : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Transação</th>
                                        <td class="text-break">{{ $invoice->gateway_reference ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Pago em</th>
                                        <td>{{ $invoice->paid_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('invoices.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left mr-1"></i> Voltar
                    </a>

                    @if ($invoice->status === 'pending' && !$invoice->gateway_reference)
                        <button
                            wire:click="generateCharge"
                            wire:loading.attr="disabled"
                            wire:target="generateCharge"
                            class="btn btn-outline-info"
                        >
                            <span wire:loading.remove wire:target="generateCharge">
                                <i class="fas fa-barcode mr-1"></i> Gerar cobrança
                            </span>
                            <span wire:loading wire:target="generateCharge">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Gerando...
                            </span>
                        </button>
                    @endif

                    @if ($invoice->payment_url)
                        <a href="{{ $invoice->payment_url }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-barcode mr-1"></i> Ver Boleto
                        </a>
                    @endif

                    @if ($invoice->pix_qrcode)
                        <a href="{{ $invoice->pix_qrcode }}" target="_blank" class="btn btn-outline-success">
                            <i class="fas fa-qrcode mr-1"></i> Ver PIX
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>