<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-vault mr-2"></i> Cofre</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Cofre</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-vault text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ativos</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['active'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0">
                <i class="fas fa-eye-slash text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Inativos</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['inactive'] }}</p>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Buscar por título, email, login ou conteúdo..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>
            <a
                wire:navigate
                href="{{ route('safe.create') }}"
                class="inline-flex items-center justify-center bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition shrink-0"
            >
                <i class="fas fa-plus mr-2"></i> Cadastrar Acesso
            </a>
        </div>

        <div class="p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($safes as $safe)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col gap-4 {{ $safe->status ? '' : 'bg-yellow-50' }}"
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

                                this.startAutoHide();

                                setTimeout(() => this.copied = false, 1500);
                            }
                        }"
                    >
                        <div class="flex items-start gap-3">
                            <img src="{{ $safe->getlogo() }}" alt="{{ $safe->title }}" class="w-12 h-12 object-cover rounded-lg shrink-0">
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $safe->title }}</h3>
                                <p class="text-xs text-gray-500 truncate">{{ $safe->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 text-sm bg-gray-50 rounded-lg px-3 py-2">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Senha:</span>
                            <span class="font-mono text-gray-700" x-text="show && password ? password : '••••••••'"></span>
                            <button
                                @click="toggle"
                                class="ml-auto text-gray-400 hover:text-gray-600 transition"
                                title="Mostrar / ocultar"
                            >
                                <i x-show="!show" class="fas fa-eye"></i>
                                <i x-show="show" class="fas fa-eye-slash"></i>
                            </button>
                            <button
                                @click="copy"
                                x-show="show"
                                class="text-gray-400 hover:text-gray-600 transition"
                                title="Copiar"
                            >
                                <i x-show="!copied" class="fas fa-copy"></i>
                                <i x-show="copied" class="fas fa-check text-green-600"></i>
                            </button>
                            <span x-show="copied" x-transition class="text-green-600 text-xs">Copiado!</span>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <x-forms.switch-toggle
                                wire:key="safe-switch-{{ $safe->id }}"
                                wire:click="toggleStatus({{ $safe->id }})"
                                :checked="$safe->status"
                                size="sm"
                                color="green"
                            />
                            <div class="flex items-center gap-1.5">
                                <a
                                    href="{{ route('safe.edit', [ 'safe' => $safe->id ]) }}"
                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition"
                                    title="Editar"
                                >
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <button
                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
                                    title="Excluir"
                                    wire:click="confirmDelete({{ $safe->id }})"
                                >
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center">
                        <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-400">Nenhum acesso cadastrado</p>
                        <a wire:navigate href="{{ route('safe.create') }}" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                            <i class="fas fa-plus mr-1"></i> Cadastrar o primeiro acesso
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        @if ($safes->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $safes->links() }}
            </div>
        @endif
    </div>
</div>