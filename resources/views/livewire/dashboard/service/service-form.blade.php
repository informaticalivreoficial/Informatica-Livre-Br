<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-lock mr-2"></i> {{ $service ? 'Editar' : 'Cadastrar' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Serviços</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $service ? 'Editar' : 'Cadastrar' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline" x-data="{ billingType: @entangle('billing_type') }">
        <form wire:submit.prevent="save">
            <div class="card-body text-muted">

                <div class="row">
                    <div class="col-12 col-md-4 col-lg-5">
                        <div class="form-group">
                            <label class="labelforms"><b>*Nome do Serviço</b></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name" placeholder="Ex.: Manutenção de site">
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label class="labelforms"><b>*Categoria:</b></label>
                            <select wire:model.defer="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                <option value="">— Selecione —</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="form-group">
                            <label class="labelforms"><b>Status:</b></label>
                            <select wire:model.defer="status" class="form-control">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label class="labelforms"><b>*Valor (R$)</b></label>
                            <input type="text"
                                id="price"
                                class="form-control @error('price') is-invalid @enderror"
                                placeholder="0,00"
                                inputmode="decimal"
                                data-value="{{ $this->price }}"
                                autocomplete="off">
                            @error('price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label class="labelforms"><b>Tipo de cobrança</b></label>
                            <select class="form-control" wire:model="billing_type">
                                <option value="one_time">Freela / Avulso</option>
                                <option value="recurring">Recorrente</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4" x-show="billingType === 'recurring'" x-transition>
                        <div class="form-group">
                            <label class="labelforms"><b>Intervalo</b></label>
                            <select class="form-control @error('interval') is-invalid @enderror" wire:model.defer="interval">
                                <option value="">— Selecione —</option>
                                @foreach(\App\Enums\BillingInterval::cases() as $interval)
                                    <option value="{{ $interval->value }}">
                                        {{ $interval->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('interval') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="labelforms"><b>Exibir:</b></label>
                            <input type="checkbox" wire:model.defer="is_public">
                            <span class="ml-1">Sim</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="labelforms"><b>Descrição do Serviço</b></label>
                            <textarea class="form-control" rows="5" wire:model.defer="description" placeholder="Descreva o que está incluso no serviço..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('services.index') }}" class="btn btn-default mr-2">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-1"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function initPriceMask() {
        const input = document.getElementById('price');
        if (!input) return;

        if (input._imask) {
            input._imask.destroy();
            input._imask = null;
        }

        const mask = IMask(input, {
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

        input._imask = mask;

        const livewireValue = input.dataset.value;
        if (livewireValue && parseFloat(livewireValue) > 0) {
            mask.typedValue = parseFloat(livewireValue);
        }

        input.addEventListener('accept', function () {
            @this.set('price', mask.typedValue.toFixed(2));
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        initPriceMask();
    });

    document.addEventListener('livewire:navigated', () => {
        initPriceMask();
    });
</script>
@endpush