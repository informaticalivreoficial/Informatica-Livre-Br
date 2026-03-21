<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-project-diagram mr-2"></i> {{ $portifolio ? 'Editar' : 'Cadastrar' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('portifolio.index') }}">Portifólio</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $portifolio ? 'Editar' : 'Cadastrar' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{
        tab: @entangle('currentTab'),
            init() {
                if (!this.tab) this.tab = 'dados';
            }
        }" class="w-full bg-white">
        <!-- Abas -->
        <div class="flex space-x-2 border-b border-green-300">
            <button type="button"
                    class="px-4 py-4 text-sm font-medium rounded-t-lg focus:outline-none transition-all duration-200"
                    :class="tab === 'dados' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-500'"
                    @click="tab = 'dados'">
                📝 Informações
            </button>
            <button type="button"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg focus:outline-none transition-all duration-200"
                    :class="tab === 'imagens' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-500'"
                    @click="tab = 'imagens'">
                📷 Imagens
            </button>
            <button type="button"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg focus:outline-none transition-all duration-200"
                    :class="tab === 'seo' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-500'"
                    @click="tab = 'seo'">
                🔍 Seo
            </button>                        
        </div>
        
            <!-- Conteúdo da aba Dados -->
            <div x-show="tab === 'dados'" x-transition>
                <div class="bg-white">
                    <div class="card-body text-muted">
                        <div class="row"> 
                            <div class="col-12 col-sm-12 col-md-5 col-lg-5"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>*Nome do Projeto</b> </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                                    @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div> 
                            <div class="col-12 col-sm-6 col-md-4 col-lg-5">
                                <div class="form-group">
                                    <label class="labelforms"><b>*Empresa:</b> <a style="font-size:11px;" href="{{route('companies.index')}}">(Cadastrar Empresa)</a></label>
                                    <select wire:model="company" class="form-control @error('company') is-invalid @enderror">
                                        <option value="">Selecione</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->alias_name }}</option>
                                        @endforeach                                                                                   
                                    </select>
                                    @error('company') <span class="text-red-500">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                                <div class="form-group">
                                    <label class="labelforms"><b>Exibir no site?</b></label>
                                    <select wire:model="exibir" class="form-control">
                                        <option value="">Selecione</option>
                                        <option value="1">Sim</option>
                                        <option value="0">Não</option>
                                    </select>
                                </div>
                            </div>                                                                                            
                        </div>
                        <div class="row">                            
                            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms">
                                        <b>*Categoria:</b> 
                                        <a style="font-size:11px;" href="{{ route('portifolio.categories.index') }}">(Cadastrar Categoria)</a>
                                    </label>
                                    <select wire:model="category" class="form-control @error('category') is-invalid @enderror">
                                        @if($categories->count() > 0)
                                            <option value="">Selecione a Categoria</option>
                                            @foreach($categories as $categoria)
                                                <optgroup label="{{ $categoria->title }}">
                                                    @foreach($categoria->children as $subcategoria)
                                                        <option value="{{ $subcategoria->id }}">{{ $subcategoria->title }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @else
                                            <option value="">Cadastre Categorias</option>
                                        @endif
                                    </select>
                                    @error('category') <span class="text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-5 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms"><b>URL do site</b> <small class="text-info">(https://www.nomedosite.com)</small></label>
                                    <input type="text" class="form-control" placeholder="URL do site" wire:model="link">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Início</b></label>
                                    <div class="input-group" wire:ignore>
                                        <input 
                                            type="text" 
                                            id="data_inicio"
                                            class="form-control" 
                                            placeholder="DD/MM/YYYY"
                                            autocomplete="off"
                                        />
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>                                                    
                            </div>
                            <div class="col-12 col-md-6 col-lg-2"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Término</b></label>
                                    <div class="input-group" wire:ignore>
                                        <input 
                                            type="text" 
                                            id="data_termino"
                                            class="form-control" 
                                            placeholder="DD/MM/YYYY"
                                            autocomplete="off"
                                        />
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>                                                    
                            </div>
                        </div>
                        
                        <div class="row mb-2"> 
                            <div class="col-12 col-sm-12 col-md-5 col-lg-2">
                                <div class="form-group" wire:ignore>
                                    <label class="labelforms"><b>Valor</b></label>

                                    <div
                                        x-data="moneyMask(@entangle('value'))"
                                        x-init="init()"
                                    >
                                        <input
                                            x-ref="input"
                                            type="text"
                                            class="form-control"
                                            placeholder="0,00"
                                        >
                                    </div>
                                </div>
                            </div>                  
                        </div>

                        <div class="row mb-2">
                            <div class="col-12">   
                                <label class="labelforms"><b>Descrição do Projeto</b></label>
                                <x-editor-quill 
                                    :value="$this->content" 
                                    model="content" 
                                />                                                                                     
                            </div>                        
                        </div>
                                         
                    </div>
                </div>
            </div>            
        
            <div x-show="tab === 'imagens'" x-transition class="relative">

                <div
                    wire:loading
                    wire:target="images"
                    class="absolute inset-0 bg-white/80 flex items-center justify-center z-[10000]"
                >
                    <div class="flex flex-col items-center gap-2">
                        <svg class="animate-spin h-8 w-8 text-blue-600"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>

                        <span class="text-sm text-gray-700 font-medium">
                            Carregando imagens...
                        </span>
                    </div>
                </div>

                <div class="bg-white p-4">
                    <div class="row">                        
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">   
                            <div class="form-group text-muted">
                                <label class="labelforms"><b>Legenda da Imagem de Capa</b></label>
                                <input type="text" class="form-control"  wire:model="thumb_legenda">
                            </div>                                                    
                        </div>
                    </div>

                    <hr class="my-4 border-gray-300">

                    <label class="block font-semibold mb-2 mt-2 text-muted">📁 Upload de Imagens:</label>
                    <input type="file" wire:model="images" class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" multiple/>

                    @error('images')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Informação sobre ordenação -->
                        @if(count($portifolio->images ?? []) > 1)
                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
                                <p class="text-sm text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <strong>Dica:</strong> Arraste e solte as imagens para reordená-las. A ordem será salva automaticamente.
                                </p>
                            </div>
                        @endif

                    
                    <div x-data="imageGallery()">
                        <!-- Galeria de Imagens com Drag & Drop -->
                        <div class="flex flex-wrap gap-4 mt-4" id="sortable-gallery">
                            {{-- Imagens já salvas (vindas do banco) --}}
                            @foreach ($portifolio->images ?? [] as $savedImage)
                                <div 
                                    class="relative image-item cursor-move"
                                    data-id="{{ $savedImage->id }}"
                                    draggable="true"
                                    @dragstart="dragStart($event)"
                                    @dragover.prevent="dragOver($event)"
                                    @drop="drop($event)"
                                    @dragend="dragEnd($event)"
                                >
                                    <img src="{{ Storage::url($savedImage->path) }}"
                                        class="w-32 h-32 object-cover rounded border cursor-pointer transition-transform hover:scale-105
                                                {{ $savedImage->cover ? 'ring-4 ring-green-500' : '' }}"
                                        @click="showModal = true; imageUrl = '{{ Storage::url($savedImage->path) }}'">

                                    {{-- Indicador de drag --}}
                                    <div class="absolute top-1 left-1 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        </svg>
                                    </div>

                                    {{-- Número da ordem --}}
                                    <div class="absolute top-1 left-10 bg-blue-600 text-white text-xs px-2 py-1 rounded font-bold">
                                        {{ $loop->index + 1 }}
                                    </div>

                                    {{-- Botão de excluir --}}
                                    <button type="button"
                                            wire:click="removeSavedImage({{ $savedImage->id }})"
                                            class="absolute top-1 right-1 w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full text-xs hover:bg-red-600">
                                        ✕
                                    </button>

                                    {{-- Botão de definir/remover capa --}}
                                    <button type="button"
                                            wire:click="toggleCover({{ $savedImage->id }})"
                                            class="absolute bottom-1 left-1 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded hover:bg-black">
                                        {{ $savedImage->cover ? 'Remover capa' : 'Definir capa' }}
                                    </button>

                                    @if (!$savedImage->watermark)
                                        <button type="button" title="Inserir Marca d'água"
                                            wire:click="applyWatermarkImage({{ $savedImage->id }})"
                                            class="absolute bottom-1 right-1 bg-yellow-500 px-2 py-1 rounded">
                                            <i class="fas fa-copyright"></i>
                                        </button>
                                    @endif                                    
                                </div>
                            @endforeach

                            {{-- Imagens recém-uploadadas via Livewire --}}
                            @foreach ($images as $index => $image)
                                <div class="relative">
                                    <img src="{!! $image->temporaryUrl() !!}" class="w-32 h-32 object-cover rounded border cursor-pointer opacity-70"
                                        @click="showModal = true; imageUrl = '{!! $image->temporaryUrl() !!}'">
                                    
                                    {{-- Badge de nova imagem --}}
                                    <div class="absolute top-1 left-1 bg-yellow-500 text-white text-xs px-2 py-1 rounded font-bold">
                                        NOVA
                                    </div>
                                    
                                    <button type="button"
                                            wire:click="removeTempImage({{ $index }})"
                                            class="absolute top-1 right-1 w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full text-xs hover:bg-red-600">
                                        ✕
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <!-- Modal de imagem -->
                        <div x-show="showModal" x-cloak
                            class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-[9999]"
                            x-transition
                            @click.self="showModal = false">
                            <div class="relative">
                                <img :src="imageUrl" class="max-w-[70vw] max-h-[70vh] object-contain mx-auto rounded shadow-lg">
                                <button type="button" @click="showModal = false"
                                        class="absolute top-2 right-2 text-white text-xl bg-black bg-opacity-50 rounded-full px-3 py-1 hover:bg-opacity-75">
                                    ✕
                                </button>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div x-show="tab === 'seo'" x-transition>
                <div class="bg-white p-4">
                    <div class="row mb-2 text-muted">                                   
                        <div class="col-12 col-md-6 col-lg-6">   
                            <div class="form-group">
                                <label class="labelforms"><b>Headline</b></label>
                                <input type="text" class="form-control" wire:model="headline">
                            </div>                                                    
                        </div>                                                            
                        <div class="col-12 mb-1"> 
                            <div class="form-group">
                                <label class="labelforms"><b>MetaTags</b></label>
                                <div 
                                    x-data="{
                                        tags: @entangle('tags'),
                                        input: '',
                                        addTag() {
                                            const trimmed = this.input.trim();
                                            if (trimmed && !this.tags.includes(trimmed)) {
                                                this.tags.push(trimmed);
                                            }
                                            this.input = '';
                                        },
                                        removeTag(index) {
                                            this.tags.splice(index, 1);
                                        }
                                    }"
                                    class="p-4 border rounded shadow"
                                    >
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        <template x-for="(tag, index) in tags" :key="index">
                                            <span class="flex items-center bg-teal-500 text-white px-3 py-1 rounded-full">
                                                <span x-text="tag"></span>
                                                <button type="button" @click="removeTag(index)" class="ml-2 hover:text-gray-200">&times;</button>
                                            </span>
                                        </template>
                                    </div>                                    
                                    <input 
                                        type="text" 
                                        x-model="input" 
                                        @keydown.enter.prevent="addTag"
                                        placeholder="Digite uma tag e pressione Enter"
                                        class="border border-gray-300 rounded px-3 py-2 w-full"
                                    >
                                </div>
                            </div>
                        </div>
                        
                    </div> 
                </div>
            </div>                       

            <div class="row text-right p-4 bg-white">
                <div class="col-12 mb-4">
                    <button 
                        wire:loading.attr="disabled"
                        wire:target="images"
                        type="button" 
                        wire:click="save('draft')" class="btn btn-info"><i class="nav-icon fas fa-check mr-2"></i>{{ $portifolio->exists ? 'Atualizar Rascunho' : 'Salvar Rascunho' }}</button>
                    <button 
                        wire:loading.attr="disabled"
                        wire:target="images"
                        type="button" 
                        wire:click="save('published')" 
                    class="btn btn-success"><i class="nav-icon fas fa-check mr-2"></i>{{ $portifolio->exists ? 'Atualizar e Publicar' : 'Salvar e Publicar' }}</button>
                </div>
            </div>
       
    </div>
    

</div>

@push('styles')
    <style>
        .image-item {
            transition: transform 0.2s, opacity 0.2s;
        }

        .image-item:hover {
            transform: translateY(-2px);
        }

        .image-item.opacity-50 {
            opacity: 0.5;
        }

        .image-item.scale-95 {
            transform: scale(0.95);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
<script>
    function initDates() {
        const inicioEl = document.getElementById('data_inicio');
        const terminoEl = document.getElementById('data_termino');

        if (inicioEl) {
            if (inicioEl._flatpickr) inicioEl._flatpickr.destroy();
            flatpickr(inicioEl, {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd/m/Y',
                locale: 'pt',
                defaultDate: '{{ $this->data_inicio }}' || null,
                onChange: function(selectedDates, dateStr) {
                    @this.set('data_inicio', dateStr);
                }
            });
        }

        if (terminoEl) {
            if (terminoEl._flatpickr) terminoEl._flatpickr.destroy();
            flatpickr(terminoEl, {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd/m/Y',
                locale: 'pt',
                defaultDate: '{{ $this->data_termino }}' || null,
                onChange: function(selectedDates, dateStr) {
                    @this.set('data_termino', dateStr);
                }
            });
        }
    }    

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

                // valor inicial
                if (this.model) {
                    this.mask.typedValue = this.model;
                }

                // quando digitar
                this.mask.on('accept', () => {
                    this.model = this.mask.typedValue;
                });

                // quando Livewire atualizar
                this.$watch('model', (value) => {
                    if (this.mask.typedValue !== value) {
                        this.mask.typedValue = value || 0;
                    }
                });
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initDates();
    });

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('reinitPlugins', () => {
            initDates();
        });
    });

    function tagInputComponent(tagsBinding) {
        return {
            tags: tagsBinding,
            input: '',
            addTag() {
                const trimmed = this.input.trim();
                if (trimmed && !this.tags.includes(trimmed)) {
                    this.tags.push(trimmed);
                }
                this.input = '';
            },
            removeTag(index) {
                this.tags.splice(index, 1);
            }
        };
    }

    function imageGallery() {
        return {
            showModal: false,
            imageUrl: null,
            draggedElement: null,
            
            dragStart(e) {
                this.draggedElement = e.target.closest('.image-item');
                this.draggedElement.classList.add('opacity-50', 'scale-95');
                e.dataTransfer.effectAllowed = 'move';
            },
            
            dragOver(e) {
                e.preventDefault();
                const container = e.currentTarget.parentElement;
                const afterElement = this.getDragAfterElement(container, e.clientX, e.clientY);
                const currentElement = e.currentTarget.closest('.image-item');
                
                if (afterElement == null) {
                    container.appendChild(this.draggedElement);
                } else {
                    container.insertBefore(this.draggedElement, afterElement);
                }
            },
            
            drop(e) {
                e.preventDefault();
                this.updateOrder();
            },
            
            dragEnd(e) {
                this.draggedElement.classList.remove('opacity-50', 'scale-95');
                this.draggedElement = null;
            },
            
            getDragAfterElement(container, x, y) {
                const draggableElements = [...container.querySelectorAll('.image-item:not(.opacity-50)')];
                
                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offsetX = x - box.left - box.width / 2;
                    const offsetY = y - box.top - box.height / 2;
                    const offset = Math.sqrt(offsetX * offsetX + offsetY * offsetY);
                    
                    if (offset < closest.offset && offsetX < 0) {
                        return { offset: offset, element: child };
                    } else {
                        return closest;
                    }
                }, { offset: Number.POSITIVE_INFINITY }).element;
            },
            
            updateOrder() {
                const gallery = document.getElementById('sortable-gallery');
                const imageItems = gallery.querySelectorAll('.image-item');
                const order = [];
                
                imageItems.forEach((item, index) => {
                    const id = item.getAttribute('data-id');
                    order.push({ id: parseInt(id), position: index + 1 });
                });
                
                // Envia a nova ordem para o Livewire
                @this.call('updateImageOrder', order);
                
                // Feedback visual
                this.showSuccessMessage();
            },
            
            showSuccessMessage() {
                const message = document.createElement('div');
                message.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50 transition-opacity';
                message.innerHTML = '✓ Ordem das imagens atualizada!';
                document.body.appendChild(message);
                
                setTimeout(() => {
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 300);
                }, 2000);
            }
        }
    }
</script>
@endpush
