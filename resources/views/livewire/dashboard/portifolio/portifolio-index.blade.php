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

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-sm-6 my-2">
                    <div style="width: 250px;">
                        <input type="text" wire:model.live="search" class="form-control form-control-sm" placeholder="Pesquisar...">
                    </div>
                </div>
                <div class="col-12 col-sm-6 my-2 text-right">
                    <a href="{{ route('portifolio.create') }}" class="btn btn-sm btn-default">
                        <i class="fas fa-plus mr-2"></i> Cadastrar
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="text-center" style="width:80px">Capa</th>
                        <th wire:click="sortBy('name')" style="cursor:pointer">
                            Projeto
                            @if($sortField === 'name')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @endif
                        </th>
                        <th class="text-center">Categoria</th>
                        <th class="text-center">Views</th>
                        <th class="text-center">Valor</th>
                        <th class="text-center">Exibir</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trabalhos as $portifolio)
                        <tr class="border-t border-gray-200 hover:bg-gray-50 {{ $portifolio->status ? '' : 'bg-yellow-100' }}">
                            <td class="text-center p-2">                               
                                <a 
                                    href="{{ $portifolio->cover() }}"
                                    data-basiclightbox
                                    class="cursor-pointer"
                                >
                                    <img
                                        src="{{ $portifolio->thumb() }}"
                                        alt="{{ $portifolio->name }}"
                                        class="img-thumbnail"
                                        style="width:60px !important; height:60px !important; object-fit:cover; object-position:center; display:block;"
                                    >
                                </a>                                
                            </td>
                            <td class="px-4 py-4">{{ $portifolio->name }}</td>
                            <td class="px-4 py-4 text-center">{{ $portifolio->categoryRelation->title ?? '—' }}</td>
                            <td class="px-4 py-4 text-center">{{ $portifolio->views }}</td>
                            <td class="px-4 py-4 text-center">
                                @if($portifolio->value)
                                    R$ {{ number_format($portifolio->value, 2, ',', '.') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="badge {{ $portifolio->exibir ? 'badge-success' : 'badge-warning' }}">
                                    {{ $portifolio->exibir ? 'Sim' : 'Não' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                        <x-forms.switch-toggle
                                        wire:key="safe-switch-{{ $portifolio->id }}"
                                        wire:click="toggleStatus({{ $portifolio->id }})"
                                        :checked="$portifolio->status"
                                        size="sm"
                                        color="green"
                                    />
                                    <a target="_blank" href="{{ route('web.portifolio.single', [ 'slug' => $portifolio->slug]) }}" 
                                        class="btn btn-xs btn-info" 
                                        title="Visualizar">
                                        <i class="fas fa-search"></i>
                                    </a>
                                    <a href="{{ route('portifolio.edit', $portifolio) }}" class="btn btn-xs btn-default">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button" 
                                        class="btn btn-xs bg-danger text-white" 
                                        title="Excluir Post"
                                        wire:click="setDeleteId({{ $portifolio->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Nenhum trabalho encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            {{ $trabalhos->links() }}
        </div>
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

    // 👈 Reinicializa após re-render do Livewire
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