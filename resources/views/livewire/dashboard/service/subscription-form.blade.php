<div>
    <div class="space-y-6">

    <div>
        <label class="label">Empresa</label>
        <select wire:model="company_id" class="input">
            <option value="">Selecione</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}">{{ $company->alias_name }}</option>
            @endforeach
        </select>
        @error('company_id') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="label">Serviço</label>
        <select wire:model.change="service_id" class="input">
            <option value="">Selecione</option>
            @foreach ($services as $service)
                <option value="{{ $service->id }}">{{ $service->name }}</option>
            @endforeach
        </select>
        @error('service_id') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>

    <div> 
        <input
            type="number"
            step="0.01"
            wire:model="amount"
            class="input"
            
        >
    </div>

    <div>
        <label class="label">Início</label>
        <input type="date" wire:model="start_date" class="input">
    </div>

    <div class="flex justify-end">
        <button wire:click="save" class="btn btn-primary">
            Salvar
        </button>
    </div>

</div>
</div>
