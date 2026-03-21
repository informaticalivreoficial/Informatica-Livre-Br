<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-search mr-2"></i> Cofre</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Cofre</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-sm-6 my-2">
                    <div class="card-tools">
                        <div style="width: 250px;">
                            <form class="input-group input-group-sm" action="" method="post">
                                <input type="text" wire:model.live="search" class="form-control float-right" placeholder="Pesquisar"> 
                            </form>
                        </div>
                      </div>
                </div>
                <div class="col-12 col-sm-6 my-2 text-right">
                    <a
                        wire:navigate
                        href="{{route('safe.create')}}"
                        class="btn btn-sm btn-default"
                    >
                        <i class="fas fa-plus mr-2"></i> Cadastrar
                </a>
                </div>
            </div>
        </div> 
        <div class="card-body pb-0">
            <div class="row">
                @forelse ($safes as $safe)
                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                        <div class="card d-flex flex-fill {{ $safe->status ? 'bg-light' : 'bg-amber-200' }}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-9"
                                        x-data="{
                                            show: false,
                                            password: null,
                                            copied: false,
                                            hideTimeout: null,

                                            init() {
                                                window.addEventListener('reveal-password', (e) => {
                                                    const data = e.detail[0];
                                                    if (data.id !== {{ $safe->id }}) return;

                                                    this.password = data.password;
                                                    this.show = true;

                                                    this.startAutoHide();
                                                });
                                            },

                                            startAutoHide() {
                                                clearTimeout(this.hideTimeout);
                                                this.hideTimeout = setTimeout(() => {
                                                    this.hide();
                                                }, 10000);
                                            },

                                            toggle() {
                                                if (this.show) {
                                                    this.hide();
                                                } else {
                                                    $wire.revealPassword({{ $safe->id }});
                                                }
                                            },

                                            hide() {
                                                this.show = false;
                                                this.password = null;
                                                clearTimeout(this.hideTimeout);
                                            },

                                            copy() {
                                                if (!this.password) return;

                                                // ⛔ impede o auto-hide enquanto copia
                                                clearTimeout(this.hideTimeout);

                                                if (navigator.clipboard && window.isSecureContext) {
                                                    navigator.clipboard.writeText(this.password);
                                                } else {
                                                    const input = document.createElement('input');
                                                    input.value = this.password;
                                                    document.body.appendChild(input);
                                                    input.select();
                                                    document.execCommand('copy');
                                                    document.body.removeChild(input);
                                                }

                                                this.copied = true;

                                                // 🔄 reinicia o auto-hide após copiar
                                                this.startAutoHide();

                                                setTimeout(() => this.copied = false, 1500);
                                            }
                                        }"
                                    >
                                        <h2 class="lead"><b>{{ $safe->title }}</b></h2>
                                        <p class="text-muted text-sm">
                                            {{ $safe->email }}
                                        </p>
                                        <span class="flex items-center gap-2 text-sm">
                                            Senha: <span x-text="show && password ? password : '••••••••'"></span>
                                            <button
                                                @click="toggle"
                                                class="text-gray-500 hover:text-gray-700"
                                                title="Mostrar / ocultar"
                                            >
                                                <i x-show="!show" class="fas fa-eye"></i>
                                                <i x-show="show" class="fas fa-eye-slash"></i>
                                            </button>
                                            <button
                                                @click="copy"
                                                x-show="show"
                                                class="text-gray-500 hover:text-gray-700"
                                                title="Copiar"
                                            >
                                                <i x-show="!copied" class="fas fa-copy"></i>
                                                <i x-show="copied" class="fas fa-check"></i>
                                            </button>
                                            <span x-show="copied" x-transition class="text-green-600 text-xs">Copiado!</span>
                                        </span>
                                    </div>
                                    <div class="col-3 text-center">
                                        <img src="{{ $safe->getlogo() }}" alt="{{ $safe->title }}" class="w-16 h-16 object-cover rounded-full">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="flex justify-end items-center gap-2">
                                    <x-forms.switch-toggle
                                        wire:key="safe-switch-{{ $safe->id }}"
                                        wire:click="toggleStatus({{ $safe->id }})"
                                        :checked="$safe->status"
                                        size="sm"
                                        color="green"
                                    />
                                    <a 
                                        href="{{ route('safe.edit', [ 'safe' => $safe->id ]) }}"
                                        class="btn btn-xs btn-default" 
                                        title="Editar"
                                    >
                                        <i class="fas fa-pen"></i>
                                    </a>                                     

                                    <button 
                                        class="btn btn-xs bg-danger" 
                                        title="Excluir"
                                        wire:click="confirmDelete({{ $safe->id }})"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>                                     
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            Nenhum cofre cadastrado
                        </div>
                    </div>                    
                @endforelse
            </div>
        </div>        
            
        <div class="card-footer">
            <div class="card-footer paginacao">{{ $safes->links() }}</div>            
        </div>
    </div>
</div>
