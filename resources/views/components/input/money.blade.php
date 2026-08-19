@props(['label' => '', 'error' => null])

@php
    $model = $attributes->whereStartsWith('wire:model')->first();
    $inputAttributes = $attributes->filter(fn ($value, $key) => !str_starts_with((string) $key, 'wire:model'));
@endphp

<div x-data="moneyMask(@entangle($model ?? 'value'))">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    @endif
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">R$</span>
        <input
            x-ref="input"
            {{ $inputAttributes->merge(['class' => 'w-full border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400']) }}
            type="text"
            inputmode="decimal"
            autocomplete="off"
        >
    </div>
    @if($error)
        <span class="text-red-500 text-xs mt-1 block">{{ $error }}</span>
    @endif
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
</script>
@endpush