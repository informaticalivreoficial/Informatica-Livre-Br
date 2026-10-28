<div class="container-fluid d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="bg-white p-4 rounded shadow-lg" style="width: 25rem;">
        <img width="{{env('LOGOMARCA_GERENCIADOR_WIDTH')}}" height="{{env('LOGOMARCA_GERENCIADOR_HEIGHT')}}"
            src="{{$config->getlogo()}}" alt="{{$config->app_name}}"
                class="mx-auto d-block mb-4 cursor-pointer" />

        @if ($showingRecoveryCodes)
            <h2 class="h5 font-weight-bold text-center mb-3">Códigos de recuperação</h2>

            <p class="text-sm text-muted">
                Guarde estes códigos em local seguro. Cada um pode ser usado <strong>uma única vez</strong>,
                caso você não consiga receber o código por e-mail.
            </p>

            <div class="bg-light border rounded p-3 mb-3">
                <div class="row">
                    @foreach ($recoveryCodes as $recoveryCode)
                        <div class="col-6 mb-1">
                            <code>{{ $recoveryCode }}</code>
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="text-sm text-danger mb-3">Eles não serão exibidos novamente.</p>

            <button type="button"
                    wire:click="confirmRecoveryCodes"
                    class="btn btn-primary w-100">
                Já salvei, continuar
            </button>
        @elseif ($awaitingCode)
            <form wire:submit.prevent="verify">
                <h2 class="h5 font-weight-bold text-center mb-2">Verificação em duas etapas</h2>

                <p class="text-sm text-muted text-center mb-4">
                    Enviamos um código de 6 dígitos para <strong>{{ $maskedEmail }}</strong>.
                    Ele expira em 10 minutos.
                </p>

                <div class="form-group mb-4">
                    <label for="code" class="text-sm font-weight-bold text-gray-700 mb-1">Código de verificação</label>
                    <input wire:model="code" type="text" inputmode="numeric" autocomplete="one-time-code"
                           class="form-control text-center" placeholder="000000">

                    @error('code')
                        <p class="text-sm text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="verify"
                        class="btn btn-primary w-100">
                    <span wire:loading.remove wire:target="verify">Verificar e entrar</span>
                    <span wire:loading wire:target="verify" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                </button>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" wire:click="cancel" class="btn btn-link p-0 text-sm">
                        Voltar
                    </button>

                    <button type="button" wire:click="resend" class="btn btn-link p-0 text-sm">
                        Reenviar código
                    </button>
                </div>

                <p class="text-sm text-muted mt-3 mb-0">
                    Sem acesso ao e-mail? Use um código de recuperação no campo acima.
                </p>
            </form>
        @else
            <form>
                <div class="form-group mb-4">
                    <label for="email" class="text-sm font-weight-bold text-gray-700 mb-1">Email</label>
                    <input wire:model="email" type="email" class="form-control">

                    @error('email')
                        <p class="text-sm text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="text-sm font-weight-bold text-gray-700 mb-1">Password</label>
                    <input wire:model="password" type="password" class="form-control">

                    @error('password')
                        <p class="text-sm text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @error('login_failed')
                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                @enderror

                <button type="button"
                        wire:loading.attr="disabled"
                        wire:target="login"
                        wire:click="login"
                        class="btn btn-primary w-100">
                        <span wire:loading.remove wire:target="login">Entrar</span>
                        <span wire:loading wire:target="login" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                </button>
            </form>
        @endif
    </div>
</div>
