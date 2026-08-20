<div>
    @section('title', $title)

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-arrow-up mr-2"></i> {{ $income ? 'Editar' : 'Cadastrar' }} Receita</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('financial.dashboard') }}">Financeiro</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('financial.incomes') }}">Receitas</a></li>
                        <li class="breadcrumb-item active">{{ $income ? 'Editar' : 'Cadastrar' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <form wire:submit.prevent="save">
            <div class="card-body text-muted">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-5">
                        <div class="form-group">
                            <label class="labelforms"><b>*Descrição</b></label>
                            <input type="text" class="form-control @error('description') is-invalid @enderror" wire:model.defer="description" placeholder="Ex.: Salário">
                            @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label class="labelforms"><b>*Categoria</b></label>
                            <select wire:model.defer="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                <option value="">— Selecione —</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label class="labelforms"><b>*Dia de recebimento</b></label>
                            <input type="number" min="1" max="31" class="form-control @error('receive_day') is-invalid @enderror" wire:model.defer="receive_day" placeholder="Ex.: 5">
                            @error('receive_day') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="labelforms"><b>Observações</b></label>
                            <textarea class="form-control" rows="4" wire:model.defer="notes" placeholder="Observações sobre esta receita..."></textarea>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="labelforms"><b>Status</b></label>
                            <select wire:model.defer="active" class="form-control">
                                <option value="1">Ativa</option>
                                <option value="0">Inativa</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('financial.incomes') }}" class="btn btn-default mr-2">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-1"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>