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
                        <label class="labelforms"><b>*Valor</b></label>
                        <input type="text" id="amount" class="form-control @error('amount') is-invalid @enderror" />
                        @error('amount') <span class="text-red-500">{{ $message }}</span> @enderror                                                                                                                                     
                    </div>
                </div>
            </div>
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

        // 👈 Pega valor do Livewire via atributo data
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
