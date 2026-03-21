<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-input.select wire:model="company_id" label="Empresa">
            <option value="">Selecione</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">
                    {{ $company->alias_name ?? $company->social_name }}
                </option>
            @endforeach
        </x-input.select>

        <x-input.select wire:model="service_id" label="Serviço">
            <option value="">Selecione</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}">{{ $service->name }}</option>
            @endforeach
        </x-input.select>

        <x-input.select wire:model="interval" label="Recorrência">
            <option value="one_time">Único</option>
            <option value="monthly">Mensal</option>
            <option value="quarterly">Trimestral</option>
            <option value="semiannual">Semestral</option>
            <option value="annual">Anual</option>
            <option value="biennial">Bienal</option>
        </x-input.select>

        <x-input.money wire:model="amount" label="Valor" />

        <x-input.date wire:model="starts_at" label="Início" />
        <x-input.date wire:model="ends_at" label="Fim (opcional)" />

        <x-input.switch wire:model="active" label="Ativo" />
    </div>  
</div>
