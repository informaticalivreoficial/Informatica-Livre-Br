<div>
    @section('title', $page)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-lock mr-2"></i> {{ $safeId ? 'Editar' : 'Cadastrar' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('safes.index') }}">Cofre</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $safeId ? 'Editar' : 'Cadastrar' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Título</label>
                        <input type="text" class="form-control" wire:model.defer="title">
                        @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" wire:model.defer="email">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Login</label>
                        <input type="text" class="form-control" wire:model.defer="login">
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Senha</label>
                        <input type="password" class="form-control" wire:model.defer="password">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Link de acesso</label>
                        <input type="text" class="form-control" wire:model.defer="link">
                    </div>                    
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Token de acesso</label>
                        <input type="text" class="form-control" wire:model.defer="token">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="col-12">
                        <div class="form-group">
                            <input 
                                type="file"
                                id="logo"
                                wire:model="logo" 
                                accept="image/png,image/jpeg,image/webp"
                                class="hidden"
                            >
                            @error('logo')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            <label for="logo">
                                <img
                                    src="{{ $this->logoUrl }}"
                                    alt="{{ $this->title }}"
                                    class="file-input-container max-w-[262px]"
                                >
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-8">
                    <div class="form-group">
                        <label>Observações</label>
                        <textarea rows="5" class="form-control" wire:model.defer="content"></textarea>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" wire:model="status">
                        <label class="form-check-label">Ativo</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-success" wire:click="save">{{ $safeId ? 'Atualizar' : 'Salvar' }}</button>
        </div>
    </div>
</div>
