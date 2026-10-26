<div class="pb-10">
    @section('title', $title)

    <x-ui.page-header
        :title="$produto?->exists ? 'Editar produto' : 'Novo produto'"
        subtitle="Pacote de desenvolvimento, manutenção ou hospedagem"
        :breadcrumbs="[
            'Painel de Controle' => route('admin'),
            'Loja' => route('loja.produtos.index'),
            'Produtos' => route('loja.produtos.index'),
            ($produto?->exists ? $produto->nome : 'Novo') => null,
        ]"
    >
        <a
            href="{{ route('loja.produtos.index') }}"
            class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition"
        >
            <i class="fas fa-arrow-left text-xs"></i> Voltar
        </a>

        <button
            type="button"
            wire:click="save"
            wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition disabled:opacity-60"
        >
            <i class="fas fa-save"></i>
            <span wire:loading.remove wire:target="save">Salvar produto</span>
            <span wire:loading wire:target="save">Salvando...</span>
        </button>
    </x-ui.page-header>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
        <div class="xl:col-span-2 space-y-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-800">Dados principais</h2>
                </div>

                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                        <input
                            type="text"
                            wire:model="nome"
                            placeholder="Ex.: Sistema de Gestão de Clínicas"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('nome') border-red-400 @enderror"
                        >
                        @error('nome') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input
                            type="text"
                            wire:model="slug"
                            placeholder="gerado automaticamente"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('slug') border-red-400 @enderror"
                        >
                        @error('slug') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL da demonstração</label>
                        <input
                            type="url"
                            wire:model="demo_url"
                            placeholder="https://demo.seusistema.com.br"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('demo_url') border-red-400 @enderror"
                        >
                        @error('demo_url') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Chamada (headline)</label>
                        <input
                            type="text"
                            wire:model="headline"
                            placeholder="Ex.: Gestão completa da sua clínica em um só lugar"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('headline') border-red-400 @enderror"
                        >
                        @error('headline') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição curta</label>
                        <textarea
                            wire:model="descricao"
                            rows="3"
                            placeholder="Resumo exibido nos cards e listagens"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('descricao') border-red-400 @enderror"
                        ></textarea>
                        @error('descricao') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-800">Conteúdo da página</h2>
                </div>

                <div class="p-5">
                    <x-editor-quill :value="$conteudo" model="conteudo" />
                    @error('conteudo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-800">Diferenciais</h2>
                    <p class="text-xs text-gray-400 mt-1">Itens exibidos como "o que está incluso" no site.</p>
                </div>

                <div class="p-5 space-y-3">
                    <div class="flex gap-2">
                        <input
                            type="text"
                            wire:model="featureInput"
                            wire:keydown.enter.prevent="addFeature"
                            placeholder="Ex.: Agenda com confirmação automática"
                            class="flex-1 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                        >
                        <button
                            type="button"
                            wire:click="addFeature"
                            class="inline-flex items-center gap-2 bg-gray-800 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-900 transition"
                        >
                            <i class="fas fa-plus text-xs"></i> Adicionar
                        </button>
                    </div>

                    @error('features.*') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror

                    <ul class="divide-y divide-gray-50">
                        @forelse($features as $index => $feature)
                            <li class="flex items-center justify-between gap-3 py-2" wire:key="feature-{{ $index }}">
                                <span class="flex items-center gap-2 text-sm text-gray-700">
                                    <i class="fas fa-check text-teal-500 text-xs"></i> {{ $feature }}
                                </span>

                                <button
                                    type="button"
                                    wire:click="removeFeature({{ $index }})"
                                    class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition"
                                    title="Remover"
                                >
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </li>
                        @empty
                            <li class="py-3 text-sm text-gray-400">Nenhum diferencial cadastrado.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-800">Imagens</h2>
                        <p class="text-xs text-gray-400 mt-1">A primeira imagem marcada como capa aparece no site.</p>
                    </div>

                    <label class="inline-flex items-center gap-2 text-sm font-medium text-teal-700 bg-teal-50 hover:bg-teal-100 px-4 py-2.5 rounded-lg cursor-pointer transition">
                        <i class="fas fa-upload text-xs"></i> Enviar
                        <input type="file" wire:model="images" multiple accept="image/jpeg,image/png,image/webp" class="hidden">
                    </label>
                </div>

                <div class="p-5 space-y-4">
                    <div wire:loading wire:target="images" class="text-xs text-teal-600">
                        <i class="fas fa-circle-notch fa-spin mr-1"></i> Enviando imagens...
                    </div>

                    @error('images.*') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror

                    @if(!empty($images))
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Novas imagens</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach($images as $index => $image)
                                    <div class="relative group rounded-lg overflow-hidden border border-gray-100" wire:key="tmp-image-{{ $index }}">
                                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-28 object-cover">

                                        <button
                                            type="button"
                                            wire:click="removeTempImage({{ $index }})"
                                            class="absolute top-1.5 right-1.5 w-7 h-7 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-red-600 transition"
                                            title="Remover"
                                        >
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($produto?->exists && $savedImages->isNotEmpty())
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Imagens cadastradas</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach($savedImages as $image)
                                    <div class="relative group rounded-lg overflow-hidden border border-gray-100" wire:key="saved-image-{{ $image->id }}">
                                        <img src="{{ $image->url }}" class="w-full h-28 object-cover">

                                        @if($image->cover)
                                            <span class="absolute top-1.5 left-1.5 bg-teal-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">CAPA</span>
                                        @endif

                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                                            <button
                                                type="button"
                                                wire:click="toggleCover({{ $image->id }})"
                                                class="w-8 h-8 rounded-full bg-white/90 text-gray-700 flex items-center justify-center hover:bg-teal-600 hover:text-white transition"
                                                title="Definir como capa"
                                            >
                                                <i class="fas fa-star text-xs"></i>
                                            </button>

                                            <button
                                                type="button"
                                                wire:click="removeSavedImage({{ $image->id }})"
                                                class="w-8 h-8 rounded-full bg-white/90 text-gray-700 flex items-center justify-center hover:bg-red-600 hover:text-white transition"
                                                title="Excluir imagem"
                                            >
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-800">Planos e preços</h2>
                        <p class="text-xs text-gray-400 mt-1">Pagamento único para o pacote, ou recorrente para manutenção e hospedagem.</p>
                    </div>

                    <button
                        type="button"
                        wire:click="openPlanoCreate"
                        @disabled(! $produto?->exists)
                        class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <i class="fas fa-plus text-xs"></i> Novo plano
                    </button>
                </div>

                <div class="p-5">
                    @if(! $produto?->exists)
                        <div class="rounded-lg bg-amber-50 border border-amber-100 text-amber-700 text-sm px-4 py-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Salve o produto para cadastrar os planos.
                        </div>
                    @elseif($planos->isEmpty())
                        <div class="text-center py-8">
                            <i class="fas fa-layer-group text-3xl text-gray-200 mb-2"></i>
                            <p class="text-sm text-gray-400">Nenhum plano cadastrado.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto -mx-5">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead>
                                    <tr>
                                        <th class="px-5 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Plano</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Cobrança</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Preço</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                                        <th class="px-5 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-50">
                                    @foreach($planos as $plano)
                                        <tr class="hover:bg-gray-50 transition" wire:key="plano-{{ $plano->id }}">
                                            <td class="px-5 py-3">
                                                <div class="flex items-center gap-2">
                                                    <p class="text-sm font-medium text-gray-800">{{ $plano->nome }}</p>

                                                    @if($plano->destaque)
                                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">POPULAR</span>
                                                    @endif
                                                </div>

                                                @if($plano->descricao)
                                                    <p class="text-xs text-gray-400 truncate max-w-xs">{{ $plano->descricao }}</p>
                                                @endif
                                            </td>

                                            <td class="px-3 py-3">
                                                @if($plano->isRecurring())
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600">
                                                        <i class="fas fa-sync-alt text-[10px]"></i> {{ $plano->billingLabel() }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                        <i class="fas fa-check text-[10px]"></i> {{ $plano->billingLabel() }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-3 py-3">
                                                <p class="text-sm font-semibold text-gray-800">{{ $plano->preco_com_periodo }}</p>

                                                @if($plano->tem_desconto)
                                                    <p class="text-xs text-gray-400 line-through">{{ $plano->preco_de_formatado }}</p>
                                                @endif
                                            </td>

                                            <td class="px-3 py-3">
                                                <button wire:click="togglePlanoStatus({{ $plano->id }})" title="Ativar/desativar">
                                                    @if($plano->status)
                                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Ativo</span>
                                                    @else
                                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inativo</span>
                                                    @endif
                                                </button>
                                            </td>

                                            <td class="px-5 py-3">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <button
                                                        wire:click="togglePlanoDestaque({{ $plano->id }})"
                                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-amber-500 hover:text-white transition"
                                                        title="Marcar como popular"
                                                    >
                                                        <i class="fas fa-star text-xs {{ $plano->destaque ? 'text-amber-500' : 'text-gray-400' }}"></i>
                                                    </button>

                                                    <button
                                                        wire:click="openPlanoEdit({{ $plano->id }})"
                                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white transition"
                                                        title="Editar plano"
                                                    >
                                                        <i class="fas fa-pen text-xs"></i>
                                                    </button>

                                                    <button
                                                        wire:click="confirmDeletePlano({{ $plano->id }})"
                                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
                                                        title="Excluir plano"
                                                    >
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-800">Publicação</h2>
                </div>

                <div class="p-5 space-y-4">
                    <x-input.switch wire:model="status" label="Produto ativo no site" />

                    <x-input.switch wire:model="destaque" label="Exibir em destaque" />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ordem de exibição</label>
                        <input
                            type="number"
                            min="0"
                            wire:model="ordem"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                        >
                    </div>

                    @if($produto?->exists)
                        <a
                            href="{{ route('web.produto', $produto->slug) }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 text-sm text-teal-600 hover:text-teal-700 font-medium"
                        >
                            <i class="fas fa-external-link-alt text-xs"></i> Ver página no site
                        </a>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-800">Resumo</h2>
                </div>

                <div class="p-5 space-y-3 text-sm">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-gray-500">Slug</span>
                        <span class="text-gray-800 font-medium truncate">{{ $slug ?: '—' }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <span class="text-gray-500">Planos</span>
                        <span class="text-gray-800 font-medium">{{ $produto?->exists ? $planos->count() : 0 }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <span class="text-gray-500">Pedidos</span>
                        <span class="text-gray-800 font-medium">{{ $produto?->exists ? $produto->pedidoItens()->count() : 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ open: @entangle('showPlanoModal') }" x-cloak>
        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" x-transition.opacity>
            <div class="fixed inset-0 bg-black/50" @click="open = false"></div>

            <div class="relative min-h-full flex items-start justify-center p-4">
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl my-8">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-800">
                            {{ $editingPlanoId ? 'Editar plano' : 'Novo plano' }}
                        </h3>

                        <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="savePlano">
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do plano *</label>
                                    <input
                                        type="text"
                                        wire:model="planoNome"
                                        placeholder="Ex.: Site Institucional / Manutenção mensal"
                                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('planoNome') border-red-400 @enderror"
                                    >
                                    @error('planoNome') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                                    <textarea
                                        wire:model="planoDescricao"
                                        rows="2"
                                        placeholder="Ex.: Site com até 8 páginas e painel de administração"
                                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('planoDescricao') border-red-400 @enderror"
                                    ></textarea>
                                    @error('planoDescricao') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de cobrança *</label>
                                    <select
                                        wire:model.live="planoBillingType"
                                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 bg-white @error('planoBillingType') border-red-400 @enderror"
                                    >
                                        <option value="one_time">Pagamento único</option>
                                        <option value="recurring">Recorrente</option>
                                    </select>
                                    @error('planoBillingType') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div x-show="$wire.planoBillingType === 'recurring'" x-transition>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Intervalo *</label>
                                    <select
                                        wire:model="planoBillingInterval"
                                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 bg-white @error('planoBillingInterval') border-red-400 @enderror"
                                    >
                                        <option value="monthly">Mensal</option>
                                        <option value="quarterly">Trimestral</option>
                                        <option value="semiannual">Semestral</option>
                                        <option value="yearly">Anual</option>
                                    </select>
                                    @error('planoBillingInterval') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <x-input.money wire:model="planoPreco" label="Preço *" :error="$errors->first('planoPreco')" />

                                <x-input.money wire:model="planoPrecoDe" label="Preço riscado (opcional)" :error="$errors->first('planoPrecoDe')" />

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label>
                                    <input
                                        type="number"
                                        min="0"
                                        wire:model="planoOrdem"
                                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                                    >
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Itens inclusos</label>

                                <div class="flex gap-2">
                                    <input
                                        type="text"
                                        wire:model="planoInclusoInput"
                                        wire:keydown.enter.prevent="addIncluso"
                                        placeholder="Ex.: Hospedagem inclusa por 12 meses"
                                        class="flex-1 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                                    >
                                    <button
                                        type="button"
                                        wire:click="addIncluso"
                                        class="inline-flex items-center gap-2 bg-gray-800 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-900 transition"
                                    >
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>

                                <ul class="mt-2 divide-y divide-gray-50">
                                    @forelse($planoIncluso as $index => $item)
                                        <li class="flex items-center justify-between gap-3 py-2" wire:key="incluso-{{ $index }}">
                                            <span class="flex items-center gap-2 text-sm text-gray-700">
                                                <i class="fas fa-check text-teal-500 text-xs"></i> {{ $item }}
                                            </span>

                                            <button
                                                type="button"
                                                wire:click="removeIncluso({{ $index }})"
                                                class="text-red-500 hover:text-red-600"
                                                title="Remover"
                                            >
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </li>
                                    @empty
                                        <li class="py-2 text-xs text-gray-400">Nenhum item incluso informado.</li>
                                    @endforelse
                                </ul>
                            </div>

                            <div class="flex flex-wrap items-center gap-6 pt-2">
                                <x-input.switch wire:model="planoStatus" label="Plano ativo" />
                                <x-input.switch wire:model="planoDestaque" label="Marcar como mais popular" />
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2">
                            <button
                                type="button"
                                @click="open = false"
                                class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition"
                            >
                                Cancelar
                            </button>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition disabled:opacity-60"
                            >
                                <i class="fas fa-save"></i>
                                <span wire:loading.remove wire:target="savePlano">Salvar plano</span>
                                <span wire:loading wire:target="savePlano">Salvando...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
