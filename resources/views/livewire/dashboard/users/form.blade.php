<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-user mr-2"></i> {{ $userId ? 'Editar' : 'Cadastrar' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('users.index') }}">Usuários</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $userId ? 'Editar' : 'Cadastrar' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="save" autocomplete="off">
        <div class="card card-teal card-outline">            
            <div class="card-body"> 
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <input type="file" id="foto" wire:model="foto" style="display: none;">
                            @error('foto')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            @php
                                if (
                                    !empty($avatar) &&
                                    env('AWS_PASTA') . \Illuminate\Support\Facades\Storage::exists($avatar)
                                ) {
                                    $cover = \Illuminate\Support\Facades\Storage::url($avatar);
                                } else {
                                    $cover = url(asset('theme/images/image.jpg'));
                                }
                            @endphp
                            @if ($fotoUrl)
                                <label for="foto">
                                    <img class="file-input-container" src="{{ $fotoUrl }}"
                                        alt="{{ $name }}" style="max-width: 262px;">
                                </label>
                            @else
                                <label for="foto">
                                    <img class="file-input-container" src="{{ $cover }}"
                                        alt="{{ $name }}" style="max-width: 262px;">
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-9">
                        <div class="row mb-2 text-muted">
                            <div class="col-12 col-md-6 col-lg-8 mb-2">
                                <div class="form-group">
                                    <label class="labelforms"><b>*Nome</b></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nome" wire:model="name">
                                    @error('name')
                                        <span class="error erro-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                                <div class="form-group" x-data="{ value: @entangle('birthday').defer }" x-init="initFlatpickr()" x-ref="datepicker">
                                    <label class="labelforms"><b>*Data de Nascimento</b></label>
                                    <input type="text" class="form-control @error('birthday') is-invalid @enderror" wire:model="birthday" id="datepicker" />
                                    @error('birthday')
                                        <span class="error erro-feedback">{{ $message }}</span>
                                    @enderror                                                                                                                                      
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                                <div class="form-group">
                                    <label class="labelforms"><b>Genero</b></label>
                                    <select class="form-control" wire:model="gender">
                                        <option value="masculino">Masculino</option>
                                        <option value="feminino">Feminino</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                                <div class="form-group">
                                    <label class="labelforms"><b>Estado Civil</b></label>
                                    <select class="form-control" wire:model="civil_status">
                                        <optgroup label="Cônjuge Obrigatório">
                                            <option value="casado">Casado</option>
                                            <option value="separado">Separado</option>
                                            <option value="solteiro">Solteiro</option>
                                            <option value="divorciado">Divorciado</option>
                                            <option value="viuvo">Viúvo(a)</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                                <div class="form-group">
                                    <label class="labelforms"><b>*CPF</b></label>
                                    <input type="text" class="form-control @error('cpf') is-invalid @enderror" placeholder="000.000.000-00" id="cpf" wire:model="cpf" x-mask="999.999.999-99" />
                                    @error('cpf')
                                        <span class="error erro-feedback">{{ $message }}</span>
                                    @enderror
                                </div>                                        
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                                <div class="form-group">
                                    <label class="labelforms"><b>RG</b></label>
                                    <input type="text" class="form-control" placeholder="RG do Cliente"
                                        id="rg" wire:model="rg" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                                <div class="form-group">
                                    <label class="labelforms"><b>Órgão Expedidor</b></label>
                                    <input type="text" class="form-control" placeholder="Expedição"
                                        id="rg_expedition" wire:model="rg_expedition">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                                <div class="form-group">
                                    <label class="labelforms"><b>Naturalidade</b></label>
                                    <input type="text" class="form-control"
                                        placeholder="Cidade de Nascimento" id="naturalness"
                                        wire:model="naturalness">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                        
                <div class="card text-muted">
                    <div class="card-header">
                        <h4>
                            <strong>Contato</strong>
                        </h4>
                    </div>                                
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms"><b>Telefone fixo:</b></label>
                                    <input type="text" class="form-control" placeholder="(00) 0000-0000"
                                        x-mask="(99) 9999-9999" wire:model="phone" id="phone">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms"><b>*Celular:</b></label>
                                    <input type="text" class="form-control @error('cell_phone') is-invalid @enderror" placeholder="(00) 00000-0000"
                                        x-mask="(99) 99999-9999" wire:model="cell_phone"
                                        id="cell_phone">
                                    @error('cell_phone')
                                        <span class="error erro-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms"><b>WhatsApp:</b></label>
                                    <input type="text" class="form-control" placeholder="(00) 00000-0000"
                                        x-mask="(99) 99999-9999" wire:model="whatsapp"
                                        id="whatsapp">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms"><b>*E-mail:</b></label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email" wire:model="email" id="email">
                                    @error('email')
                                        <span class="error erro-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms"><b>E-mail Alternativo:</b></label>
                                    <input type="text" class="form-control"
                                        placeholder="Email Alternativo" wire:model="additional_email"
                                        id="additional_email">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms"><b>Telegram:</b></label>
                                    <input type="text" class="form-control" placeholder="Telegram"
                                        wire:model="telegram" id="telegram">
                                </div>
                            </div>
                        </div>
                    </div>                                
                </div>

                <div class="card text-muted">
                    <div class="card-header">
                        <h4>
                            <strong>Endereço</strong>
                        </h4>
                    </div>                                
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12 col-md-6 col-lg-2"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>*CEP:</b></label>
                                    <input type="text" x-mask="99.999-999" class="form-control @error('postcode') is-invalid @enderror" id="postcode" wire:model.lazy="postcode">
                                    @error('postcode')
                                        <span class="error erro-feedback">{{ $message }}</span>
                                    @enderror                                                    
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-4 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>*Estado:</b></label>
                                    <input type="text" class="form-control" id="state" wire:model="state" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-4"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>*Cidade:</b></label>
                                    <input type="text" class="form-control" id="city" wire:model="city" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>*Rua:</b></label>
                                    <input type="text" class="form-control" id="street" wire:model="street" readonly>
                                </div>
                            </div>                                            
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 col-md-4 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>*Bairro:</b></label>
                                    <input type="text" class="form-control" id="neighborhood" wire:model="neighborhood" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>Número:</b></label>
                                    <input type="text" class="form-control" placeholder="Número do Endereço" id="number" wire:model="number">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>Complemento:</b></label>
                                    <input type="text" class="form-control" id="complement" wire:model="complement">
                                </div>
                            </div>   
                        </div>
                    </div>                                
                </div>

                <div class="card text-muted">
                    <div class="card-header">
                        <h4>
                            <strong>Redes Sociais</strong>
                        </h4>
                    </div>                                
                    <div class="card-body">
                        <div class="row">                                                       
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Facebook:</b></label>
                                    <input type="text" class="form-control text-muted" placeholder="Facebook"
                                        id="facebook" wire:model="facebook">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Instagram:</b></label>
                                    <input type="text" class="form-control text-muted" placeholder="Instagram"
                                        id="instagram" wire:model="instagram">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Linkedin:</b></label>
                                    <input type="text" class="form-control text-muted" placeholder="Linkedin"
                                        id="linkedin" wire:model="linkedin">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card text-muted">
                    <div class="card-header">
                        <h4>
                            <strong>Permissões de Acesso</strong>
                        </h4>
                    </div>                                
                    <div class="card-body">
                        <div class="row">                            
                            <div class="col-sm-12 bg-gray-light mb-3">
                                <!-- checkbox -->
                                <div class="form-group p-3 mb-0">
                                    <span class="mr-3"><b>Acesso ao Sistema:</b></span>
                                    <div class="form-check d-inline mx-2">
                                        <input id="client" class="form-check-input" type="checkbox"
                                            wire:model="client" {{ $client == true ? 'checked' : null }}>
                                        <label for="client" class="form-check-label">Cliente</label>
                                    </div>
                                    <div class="form-check d-inline mx-2">
                                        <input id="editor" class="form-check-input" type="checkbox"
                                            wire:model="editor" {{ $editor == true ? 'checked' : null }}>
                                        <label for="editor" class="form-check-label">Editor</label>
                                    </div>
                                    @if (\Illuminate\Support\Facades\Auth::user()->superadmin == 1)
                                        <div class="form-check d-inline mx-2">
                                            <input id="admin" class="form-check-input" type="checkbox"
                                                wire:model="admin" {{ $admin == true ? 'checked' : null }}>
                                            <label for="admin" class="form-check-label">Administrador</label>
                                        </div>

                                        <div class="form-check d-inline mx-2">
                                            <input id="superadmin" class="form-check-input" type="checkbox"
                                                wire:model="superadmin"
                                                {{ $superadmin == true ? 'checked' : null }}>
                                            <label for="superadmin" class="form-check-label">Super Administrador</label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if (!$userId)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label class="labelforms text-muted"><b>Senha:</b></label>
                                    <div class="input-group input-group-md">                                    
                                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" wire:model="password">
                                        <span class="input-group-append">
                                            <button type="button" onclick="togglePassword('password')" class="btn btn-default btn-flat"><i class="fa fa-eye"></i></button>
                                        </span>
                                    </div>
                                    @error('password') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label class="labelforms text-muted"><b>Confirmar Senha:</b></label>
                                    <div class="input-group input-group-md">                                    
                                        <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" wire:model.lazy="password_confirmation">
                                        <span class="input-group-append">
                                            <button type="button" onclick="togglePassword('password_confirmation')" class="btn btn-default btn-flat"><i class="fa fa-eye"></i></button>
                                        </span>
                                    </div>
                                    @error('password_confirmation') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                            @endif                            
                        </div>
                    </div>
                </div>

                <div class="row text-right">
                    <div class="col-12 pb-4 mt-3">
                        <button type="submit" class="btn btn-lg btn-success p-3"><i class="nav-icon fas fa-check mr-2"></i>{{ $userId ? 'Atualizar Agora' : 'Cadastrar Agora' }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('cliente-atualizado', function() {
        Swal.fire({
            title: 'Sucesso!',
            text: "Usuário atualizado!",
            icon: 'success',
            showConfirmButton: false,
            timer: 3000 // Fecha automaticamente após 3 segundos
        });
    });

    document.addEventListener('cliente-cadastrado', function() {
        Swal.fire({
            title: 'Sucesso!',
            text: "Usuário Cadastrado!",
            icon: 'success',
            showConfirmButton: false,
            timer: 3000 // Fecha automaticamente após 3 segundos
        });
    });

    function initFlatpickr() {
            let input = document.getElementById('datepicker');
            if (!input) return;

            flatpickr(input, {
                dateFormat: "d/m/Y",
                allowInput: true,
                maxDate: "today",
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

<script>

    document.addEventListener("livewire:init", () => {

        Livewire.on("toast", (event) => {

            toastr[event.notify](event.message);

        });
    });

</script>
<script>
    // window.addEventListener('alert', event => {
    //     toastr[event.detail.type](event.detail.message,
    //         event.detail.title ?? ''), toastr.options = {
    //         "closeButton": true,
    //         "progressBar": true,
    //     }
    // });

    function togglePassword(id) {
        let input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
