<div>
    @section('title', $titlee)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-film mr-2"></i> {{ $slide->exists ? 'Editar' : 'Cadastrar' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('slides.index') }}">Banners</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $slide->exists ? 'Editar' : 'Cadastrar' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-teal card-outline">
        <div class="card-body text-muted">
            <form wire:submit.prevent="save" autocomplete="off">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="labelforms"><b>*Título:</b></label>
                            <input class="form-control" wire:model="title" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="labelforms text-muted"><b>URL</b> <small class="text-info">(Ex: https://www.dominio.com)</small></label>
                        <input class="form-control" wire:model="link" />
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="form-group" x-data="{ value: @entangle('expired_at').defer }" x-init="initFlatpickr()" x-ref="datepicker">
                            <label class="labelforms"><b>Data de Expiração</b></label>
                            <input type="text" class="form-control" wire:model="expired_at" id="datepicker" />                                                                                                                                                                          
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4"> 
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>Destino</b></label>
                            <select class="form-control" wire:model="target">
                                <option value=""> Selecione </option>
                                <option value="1">Nova Janela</option>
                                <option value="0">Mesma Janela</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4"> 
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>Exibir Título?</b></label>
                            <select class="form-control" wire:model="view_title">
                                <option value=""> Selecione </option>
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                        </div>
                    </div>                     
                </div>
                <div class="row">
                    <div class="col-12 p-4 border rounded shadow">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <b>Imagem do Banner (recomendado: 2200x1200)</b>
                        </label>

                        <div 
                            x-data="{
                                preview: '{{ $slide->getimagem() }}',
                                updatePreview(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            this.preview = e.target.result;
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                }
                            }"
                            class="flex flex-col items-start space-y-2 w-full relative"
                        >

                            {{-- Preview --}}
                            <template x-if="preview">
                                <img 
                                    :src="preview" 
                                    alt="Preview"
                                    class="border rounded max-w-full h-auto"
                                >
                            </template>

                            {{-- Loader --}}
                            <div 
                                wire:loading wire:target="image" 
                                class="absolute inset-0 bg-white bg-opacity-70 flex items-center justify-center rounded"
                            >
                                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                            </div>

                            {{-- Input --}}
                            <input 
                                type="file" 
                                accept="image/*"
                                @change="updatePreview"
                                wire:model="image"
                                class="block w-full text-sm text-gray-700
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100"
                                id="image"
                            />

                            @error('image') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-4 mt-4">
                    <label for="inputDescription" class="block text-sm font-medium text-gray-700 mb-2">
                        <b>Descrição</b>
                    </label>
                    <textarea
                        id="inputDescription"
                        wire:model.defer="content"
                        rows="5"
                        class="w-full rounded-lg border border-gray-300 shadow-sm p-3
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        placeholder="Digite a descrição do banner..."
                    ></textarea>
                </div>

                <div class="row text-right mt-3">
                    <div class="col-12 mb-4">
                        <button type="button" wire:click="save" class="btn btn-lg btn-success p-3">
                            <i class="nav-icon fas fa-check mr-2"></i> {{ $slide->exists ? 'Atualizar Agora' : 'Salvar Agora' }}
                        </button>
                    </div>
                </div>                

            </form>
        </div>
    </div>

</div>

<script>
    document.addEventListener('atualizado', function() {
        Swal.fire({
            title: 'Sucesso!',
            text: "Slide atualizado com sucesso!",
            icon: 'success',
            timerProgressBar: true,
            showConfirmButton: false,
            timer: 3000 // Fecha automaticamente após 3 segundos
        });
    });

    document.addEventListener('cadastrado', function() {
        Swal.fire({
            title: 'Sucesso!',
            text: "Slide cadastrado com sucesso!",
            icon: 'success',
            timerProgressBar: true,
            showConfirmButton: true,
            timer: 3000 // Fecha automaticamente após 3 segundos
        }).then(() => {
            window.location.href = `/admin/slides/${slide}/editar`;
        });
    });
    
    document.addEventListener("livewire:navigated", () => {
        $('#description').summernote({
            height: 300,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
            callbacks: {
                onChange: function(contents, $editable) {
                    Livewire.dispatch('updatePrivacyPolicy', { value: contents });
                }
            }
        });
    });    

    function initFlatpickr() {
        let input = document.getElementById('datepicker');
        if (!input) return;

        flatpickr(input, {
            dateFormat: "d/m/Y",
            allowInput: true,
            minDate: "today",
            //defaultDate: input.value, // Carrega a data inicial corretamente
            onChange: function(selectedDates, dateStr) {
                input.dispatchEvent(new Event('input')); // Força atualização no Alpine.js
            },
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                    longhand: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'],
                },
                months: {
                    shorthand: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    longhand: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                },
                today: "Hoje",
                clear: "Limpar",
                weekAbbreviation: "Sem",
                scrollTitle: "Role para aumentar",
                toggleTitle: "Clique para alternar",
            }
        });
    }

    document.addEventListener("livewire:load", () => {
        initFlatpickr();
    });

    document.addEventListener("livewire:updated", () => {
        initFlatpickr();
    });    
</script>