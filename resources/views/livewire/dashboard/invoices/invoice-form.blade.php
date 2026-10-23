<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-file-invoice mr-2"></i>
                        {{ $this->invoice ? 'Duplicar Fatura #' . $this->invoice->id : 'Nova Fatura' }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Faturas</a></li>
                        <li class="breadcrumb-item active">{{ $this->invoice ? 'Duplicar' : 'Nova' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-teal card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit mr-2"></i> Dados da fatura
            </h3>
        </div>

        <div class="card-body text-muted">
            @if ($this->invoice)
                <div class="alert alert-info">
                    <i class="fas fa-copy mr-2"></i>
                    Você está duplicando a fatura <strong>#{{ $this->invoice->id }}</strong>
                    ({{ $this->invoice->payerLabel() }}).
                    Ajuste os dados abaixo; uma nova fatura será criada.
                </div>
            @endif

            <div class="form-group">
                <label class="labelforms"><b>*Pagador</b></label>
                <div class="d-flex flex-wrap" style="gap: 1.5rem;">
                    <div class="custom-control custom-radio">
                        <input
                            type="radio"
                            id="payer_type_custom"
                            value="custom"
                            class="custom-control-input"
                            wire:model.live="payer_type"
                        >
                        <label class="custom-control-label" for="payer_type_custom">
                            Pagador avulso (empresa ou pessoa física não cadastrada)
                        </label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input
                            type="radio"
                            id="payer_type_company"
                            value="company"
                            class="custom-control-input"
                            wire:model.live="payer_type"
                        >
                        <label class="custom-control-label" for="payer_type_company">
                            Empresa cadastrada no sistema
                        </label>
                    </div>
                </div>
                @error('payer_type') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            @if ($payer_type === 'company')
                <div class="row mb-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="labelforms"><b>*Empresa</b></label>
                            <select class="form-control @error('company_id') is-invalid @enderror" wire:model="company_id">
                                <option value="">Selecione</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->alias_name }}</option>
                                @endforeach
                            </select>
                            @error('company_id') <span class="text-red-500">{{ $message }}</span> @enderror
                            <small class="text-muted">A cobrança usa o e-mail e o CPF/CNPJ cadastrados na empresa.</small>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning py-2">
                    <i class="fas fa-user mr-2"></i>
                    Esta fatura não será vinculada a nenhuma empresa do sistema e não aparecerá no painel do cliente.
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="labelforms"><b>*Nome / Razão social</b></label>
                            <input
                                type="text"
                                class="form-control @error('payer_name') is-invalid @enderror"
                                wire:model="payer_name"
                                maxlength="191"
                                placeholder="Ex.: João da Silva ou ACME LTDA"
                            />
                            @error('payer_name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="labelforms"><b>*CPF / CNPJ</b></label>
                            <input
                                type="text"
                                class="form-control @error('payer_document') is-invalid @enderror"
                                wire:model="payer_document"
                                maxlength="20"
                                placeholder="CPF ou CNPJ"
                            />
                            @error('payer_document') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="labelforms"><b>*E-mail</b></label>
                            <input
                                type="email"
                                class="form-control @error('payer_email') is-invalid @enderror"
                                wire:model="payer_email"
                                maxlength="191"
                                placeholder="pagador@email.com"
                            />
                            @error('payer_email') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="labelforms">Telefone</label>
                            <input
                                type="text"
                                class="form-control @error('payer_phone') is-invalid @enderror"
                                wire:model="payer_phone"
                                maxlength="30"
                                placeholder="(11) 99999-9999"
                            />
                            @error('payer_phone') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="labelforms">CEP</label>
                            <input
                                type="text"
                                class="form-control @error('payer_zipcode') is-invalid @enderror"
                                wire:model="payer_zipcode"
                                maxlength="10"
                                placeholder="00000-000"
                            />
                            @error('payer_zipcode') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="labelforms">Logradouro</label>
                            <input
                                type="text"
                                class="form-control @error('payer_street') is-invalid @enderror"
                                wire:model="payer_street"
                                maxlength="191"
                                placeholder="Rua, avenida..."
                            />
                            @error('payer_street') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="labelforms">Número</label>
                            <input
                                type="text"
                                class="form-control @error('payer_number') is-invalid @enderror"
                                wire:model="payer_number"
                                maxlength="20"
                            />
                            @error('payer_number') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="labelforms">Complemento</label>
                            <input
                                type="text"
                                class="form-control @error('payer_complement') is-invalid @enderror"
                                wire:model="payer_complement"
                                maxlength="60"
                            />
                            @error('payer_complement') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-5">
                        <div class="form-group">
                            <label class="labelforms">Bairro</label>
                            <input
                                type="text"
                                class="form-control @error('payer_neighborhood') is-invalid @enderror"
                                wire:model="payer_neighborhood"
                                maxlength="80"
                            />
                            @error('payer_neighborhood') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-12 col-md-9">
                        <div class="form-group">
                            <label class="labelforms">Cidade</label>
                            <input
                                type="text"
                                class="form-control @error('payer_city') is-invalid @enderror"
                                wire:model="payer_city"
                                maxlength="80"
                            />
                            @error('payer_city') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="labelforms">UF</label>
                            <input
                                type="text"
                                class="form-control @error('payer_state') is-invalid @enderror"
                                wire:model="payer_state"
                                maxlength="2"
                                placeholder="SP"
                            />
                            @error('payer_state') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <small class="text-muted d-block mb-3">
                    Telefone e endereço são opcionais, mas ajudam na emissão do boleto.
                </small>
            @endif

            <div class="row mb-3">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="labelforms"><b>*Vencimento</b></label>
                        <input
                            type="text"
                            id="invoice_due_date"
                            data-value="{{ $due_date }}"
                            class="form-control @error('due_date') is-invalid @enderror"
                            placeholder="DD/MM/YYYY"
                            autocomplete="off"
                        />
                        @error('due_date') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="labelforms"><b>*Valor</b></label>
                        <input
                            type="text"
                            id="invoice_amount"
                            data-value="{{ $amount }}"
                            class="form-control @error('amount') is-invalid @enderror"
                            autocomplete="off"
                        />
                        @error('amount') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="form-group">
                        <label class="labelforms"><b>*Descrição</b></label>
                        <input
                            type="text"
                            class="form-control @error('description') is-invalid @enderror"
                            wire:model="description"
                            maxlength="255"
                            placeholder="Ex.: Consultoria avulsa, manutenção de servidor..."
                        />
                        @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
                        <small class="text-muted">Este texto aparece na fatura e no boleto.</small>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="custom-control custom-checkbox">
                        <input
                            type="checkbox"
                            class="custom-control-input"
                            id="generate_charge"
                            wire:model="generate_charge"
                        >
                        <label class="custom-control-label" for="generate_charge">
                            Gerar cobrança no PagHiper agora
                        </label>
                    </div>
                    <small class="text-muted">
                        Se desmarcado, a fatura fica pendente e pode ser cobrada depois pelo detalhe da fatura.
                    </small>
                </div>
            </div>

            <div class="row text-right">
                <div class="col-12 pb-4 mt-3">
                    <a href="{{ route('invoices.index') }}" class="btn btn-lg btn-outline-secondary p-3 mr-2">
                        Cancelar
                    </a>
                    <button
                        wire:click="save"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="btn btn-lg btn-success p-3"
                    >
                        <span wire:loading.remove wire:target="save">
                            <i class="nav-icon fas fa-check mr-2"></i> Salvar fatura
                        </span>
                        <span wire:loading wire:target="save">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Salvando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function initInvoiceForm() {
        const amountInput = document.getElementById('invoice_amount');
        if (amountInput) {
            if (amountInput._imask) {
                amountInput._imask.destroy();
            }

            const mask = IMask(amountInput, {
                mask: 'num',
                blocks: {
                    num: {
                        mask: Number,
                        scale: 2,
                        signed: false,
                        thousandsSeparator: '.',
                        padFractionalZeros: true,
                        normalizeZeros: true,
                        radix: ',',
                        mapToRadix: ['.'],
                    }
                }
            });

            amountInput._imask = mask;

            const value = parseFloat(amountInput.dataset.value);
            if (value > 0) {
                mask.typedValue = value;
            }

            amountInput.addEventListener('accept', function () {
                @this.set('amount', mask.typedValue.toFixed(2));
            });
        }

        const dueDateInput = document.getElementById('invoice_due_date');
        if (dueDateInput) {
            if (dueDateInput._flatpickr) {
                dueDateInput._flatpickr.destroy();
            }

            flatpickr(dueDateInput, {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd/m/Y',
                locale: 'pt',
                defaultDate: dueDateInput.dataset.value || null,
                onChange: function (selectedDates, dateStr) {
                    @this.set('due_date', dateStr);
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initInvoiceForm);
    document.addEventListener('livewire:initialized', initInvoiceForm);
    document.addEventListener('livewire:navigated', initInvoiceForm);
</script>
@endpush
