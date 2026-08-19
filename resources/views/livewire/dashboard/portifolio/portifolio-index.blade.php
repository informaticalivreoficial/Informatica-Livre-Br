<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-project-diagram mr-2"></i> Portifólio</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Portifólio</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-project-diagram text-lg"></i>
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
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Publicados</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['active'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0">
                <i class="fas fa-eye-slash text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ocultos</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['draft'] }}</p>
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
                    placeholder="Buscar por nome, conteúdo ou slug..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>
            <a
                href="{{ route('portifolio.create') }}"
                class="inline-flex items-center justify-center bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition shrink-0"
            >
                <i class="fas fa-plus mr-2"></i> Cadastrar Trabalho
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Capa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide cursor-pointer" wire:click="sortBy('name')">
                            Projeto <i class="fas fa-caret-down fa-fw ml-1"></i>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Categoria</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Views</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Valor</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Exibir</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($trabalhos as $portifolio)
                        <tr class="hover:bg-gray-50 transition {{ $portifolio->status ? '' : 'bg-yellow-50' }}">
                            <td class="px-4 py-3">
                                <a href="{{ $portifolio->cover() }}" data-basiclightbox class="cursor-pointer">
                                    <img
                                        src="{{ $portifolio->thumb() }}"
                                        alt="{{ $portifolio->name }}"
                                        class="w-14 h-14 object-cover rounded-lg hover:scale-105 transition-transform"
                                    >
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $portifolio->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ $portifolio->categoryRelation->title ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ $portifolio->views }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 text-center">
                                @if($portifolio->value)
                                    R$ {{ number_format($portifolio->value, 2, ',', '.') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $portifolio->exibir ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $portifolio->exibir ? 'Sim' : 'Não' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <x-forms.switch-toggle
                                        wire:key="safe-switch-{{ $portifolio->id }}"
                                        wire:click="toggleStatus({{ $portifolio->id }})"
                                        :checked="$portifolio->status"
                                        size="sm"
                                        color="green"
                                    />
                                    <button
                                        x-data
                                        @click="
                                            Swal.fire({
                                                title: 'Postar no Facebook?',
                                                text: 'Deseja publicar este portfólio no Facebook?',
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonText: 'Sim, postar',
                                                cancelButtonText: 'Cancelar',
                                                confirmButtonColor: '#2563eb'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $wire.postar({{ $portifolio->id }}, 'facebook')
                                                }
                                            })
                                        "
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                        title="Postar no Facebook"
                                    >
                                        <i class="fab fa-facebook text-xs"></i>
                                    </button>

                                    <button
                                        x-data
                                        @click="
                                            Swal.fire({
                                                title: 'Postar no Instagram?',
                                                text: 'Deseja publicar este portfólio no Instagram?',
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonText: 'Sim, postar',
                                                cancelButtonText: 'Cancelar',
                                                confirmButtonColor: '#ec4899'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $wire.postar({{ $portifolio->id }}, 'instagram')
                                                }
                                            })
                                        "
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-pink-50 text-pink-600 hover:bg-pink-600 hover:text-white transition"
                                        title="Postar no Instagram"
                                    >
                                        <i class="fab fa-instagram text-xs"></i>
                                    </button>

                                    <a target="_blank" href="{{ route('web.portifolio.single', [ 'slug' => $portifolio->slug]) }}"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                        title="Visualizar">
                                        <i class="fas fa-search text-xs"></i>
                                    </a>
                                    <a href="{{ route('portifolio.edit', $portifolio) }}"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition"
                                        title="Editar">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <button type="button"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
                                        title="Excluir"
                                        wire:click="setDeleteId({{ $portifolio->id }})">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Nenhum trabalho encontrado</p>
                                <a href="{{ route('portifolio.create') }}" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                    <i class="fas fa-plus mr-1"></i> Cadastrar o primeiro trabalho
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($trabalhos->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $trabalhos->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-basiclightbox]').forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault();
                basicLightbox.create(`<img src="${el.getAttribute('href')}" style="max-width:90vw; max-height:90vh;">`).show();
            });
        });
    });

    document.addEventListener('livewire:updated', () => {
        document.querySelectorAll('[data-basiclightbox]').forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault();
                basicLightbox.create(`<img src="${el.getAttribute('href')}" style="max-width:90vw; max-height:90vh;">`).show();
            });
        });
    });
</script>
@endpush