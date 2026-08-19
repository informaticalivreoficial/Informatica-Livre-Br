<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-store mr-2"></i> {{ $subscription ? 'Editar' : 'Cadastrar' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('services.subscriptions.index') }}">Pedidos</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $subscription ? 'Editar' : 'Cadastrar' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-teal card-outline">
        <div class="card-body text-muted">
            <div class="row mb-3">
                @if ($next_billing_at)
                    <div class="col-12 mb-2">
                        <small class="text-muted">
                            <i class="fas fa-calendar mr-1"></i>
                            Próxima cobrança prevista: <strong>{{ $next_billing_at }}</strong>
                        </small>
                    </div>
                @endif
            </div>
            <div class="row mb-3">
                <div class="col-12 col-md-6 col-lg-6 mb-2">
                    <div class="form-group">
                        <label class="labelforms"><b>*Empresa</b></label>
                        <select class="form-control @error('company_id') is-invalid @enderror" wire:model="company_id">
                            <option value="">Selecione</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->alias_name }}</option>
                            @endforeach
                        </select>
                        @error('company_id') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-2">
                    <div class="form-group">
                        <label class="labelforms"><b>*Serviço</b></label>
                        <select class="form-control @error('service_id') is-invalid @enderror" wire:model.change="service_id">
                            <option value="">Selecione</option>
                            @foreach ($services as $service)
                                <option
                                    value="{{ $service->id }}">
                                    {{ $service->name }}
                                    @if ($service->interval)
                                        - {{ $service->interval?->label() }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('service_id') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-2">
                    <div class="form-group">
                        <label class="labelforms"><b>*Início</b></label>
                        <input
                            type="text"
                            id="start_date"
                            class="form-control @error('start_date') is-invalid @enderror"
                            placeholder="DD/MM/YYYY"
                            autocomplete="off"
                        />
                        @error('start_date') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-2">
                    <div class="form-group">
                        <label class="labelforms"><b>*Valor do serviço</b></label>
                        <input type="text" id="amount" class="form-control @error('amount') is-invalid @enderror" />
                        @error('amount') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            @if ($isOneTime)
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-plus-circle mr-2"></i> Itens extras
                                </h3>
                                <div class="card-tools">
                                    <button type="button" wire:click="addItem" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-plus mr-1"></i> Adicionar item
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                @if (count($items) > 0)
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Descrição</th>
                                                <th style="width: 200px;">Valor</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $index => $item)
                                                <tr>
                                                    <td>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control-sm @error('items.'.$index.'.description') is-invalid @enderror"
                                                            wire:model="items.{{ $index }}.description"
                                                            placeholder="Ex.: Hospedagem extra, domínio..."
                                                        >
                                                        @error('items.'.$index.'.description')
                                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="text"
                                                            x-data="moneyMask(@entangle('items.' . $index . '.amount'))"
                                                            x-ref="input"
                                                            class="form-control form-control-sm @error('items.'.$index.'.amount') is-invalid @enderror"
                                                            inputmode="decimal"
                                                            autocomplete="off"
                                                        >
                                                        @error('items.'.$index.'.amount')
                                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="text-center">
                                                        <button
                                                            type="button"
                                                            wire:click="removeItem({{ $index }})"
                                                            class="btn btn-xs btn-danger"
                                                            title="Remover item"
                                                        >
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-box-open text-2xl mb-2 d-block"></i>
                                        Nenhum item extra. Clique em "Adicionar item" para incluir.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row text-right">
                <div class="col-12 pb-4 mt-3">
                    <button wire:click="save" class="btn btn-lg btn-success p-3"><i class="nav-icon fas fa-check mr-2"></i>{{ $subscription ? 'Atualizar Agora' : 'Cadastrar Agora' }}</button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function moneyMask(model) {
        return {
            model: model,
            mask: null,

            init() {
                this.mask = IMask(this.$refs.input, {
                    mask: Number,
                    scale: 2,
                    signed: false,
                    thousandsSeparator: '.',
                    padFractionalZeros: true,
                    normalizeZeros: true,
                    radix: ',',
                    mapToRadix: ['.'],
                });

                if (this.model) {
                    this.mask.typedValue = this.model;
                }

                this.mask.on('accept', () => {
                    this.model = this.mask.typedValue;
                });

                this.$watch('model', (value) => {
                    if (this.mask.typedValue !== value) {
                        this.mask.typedValue = value || 0;
                    }
                });
            }
        }
    }

    function initFlatpickr() {
        const el = document.getElementById('start_date');
        if (!el) return;

        if (el._flatpickr) el._flatpickr.destroy();

        flatpickr(el, {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            locale: 'pt',
            defaultDate: '{{ $this->start_date }}' || null,
            onChange: function(selectedDates, dateStr) {
                @this.set('start_date', dateStr);
            }
        });
    }

    function initIMask() {
        const amountInput = document.getElementById('amount');
        if (!amountInput) return;

        if (amountInput._imask) {
            amountInput._imask.destroy();
            amountInput._imask = null;
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

        const livewireValue = amountInput.dataset.value;
        if (livewireValue && parseFloat(livewireValue) > 0) {
            mask.typedValue = parseFloat(livewireValue);
        }

        amountInput.addEventListener('accept', function () {
            @this.set('amount', mask.typedValue.toFixed(2));
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        initFlatpickr();
        initIMask();
    });

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('reinitPlugins', ({ amount }) => {
            const amountInput = document.getElementById('amount');
            if (amountInput) amountInput.dataset.value = amount;
            initFlatpickr();
            initIMask();
        });
    });

    document.addEventListener('livewire:navigated', () => {
        initFlatpickr();
        initIMask();
    });
</script>
@endpush