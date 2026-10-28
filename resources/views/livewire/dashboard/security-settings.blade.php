<div>
    @section('title', $title)

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-shield-alt mr-2"></i> Segurança</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Segurança</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 lg:col-span-2">
            <h2 class="text-base font-bold text-gray-800 mb-1">Verificação em duas etapas</h2>
            <p class="text-sm text-gray-500 mb-4">
                Todo acesso ao painel administrativo exige um código de 6 dígitos enviado para
                <strong>{{ auth()->user()->email }}</strong>.
            </p>

            <div class="flex items-center gap-3 p-3 rounded-lg bg-green-50 border border-green-200 mb-4">
                <i class="fas fa-check-circle text-green-600"></i>
                <div class="text-sm text-green-800">
                    <strong>Ativo</strong>
                    @if ($confirmedAt)
                        — último acesso verificado em {{ $confirmedAt->format('d/m/Y \à\s H:i') }}
                    @else
                        — será confirmado no seu próximo acesso
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Códigos de recuperação</p>
                        <p class="text-sm text-gray-500">
                            {{ $remainingCodes }} de {{ \App\Services\TwoFactorService::RECOVERY_CODES_COUNT }} códigos disponíveis.
                            Cada código pode ser usado uma única vez.
                        </p>
                    </div>

                    <button type="button"
                            wire:loading.attr="disabled"
                            wire:target="regenerate"
                            onclick="confirm('Gerar novos códigos invalida todos os anteriores. Continuar?') || event.stopImmediatePropagation()"
                            wire:click="regenerate"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60">
                        <i class="fas fa-rotate"></i> Gerar novos códigos
                    </button>
                </div>

                <div class="mt-3">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                        Confirme sua senha atual
                    </label>
                    <input id="current_password"
                           type="password"
                           wire:model="current_password"
                           autocomplete="current-password"
                           class="form-control @error('current_password') is-invalid @enderror"
                           placeholder="Sua senha">

                    @error('current_password')
                        <span class="text-sm text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-base font-bold text-gray-800 mb-2">Como funciona</h2>

            <ul class="text-sm text-gray-600 space-y-2 list-disc pl-5">
                <li>Ao entrar com e-mail e senha, enviamos um código de 6 dígitos por e-mail.</li>
                <li>O código expira em {{ \App\Services\TwoFactorService::CODE_TTL_MINUTES }} minutos e só pode ser usado uma vez.</li>
                <li>São permitidas {{ \App\Services\TwoFactorService::MAX_ATTEMPTS }} tentativas por código.</li>
                <li>Perdeu o acesso ao e-mail? Use um código de recuperação no lugar do código.</li>
            </ul>
        </div>
    </div>

    @if ($showingCodes)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Seus novos códigos de recuperação</h2>

                <p class="text-sm text-gray-600 mb-4">
                    Guarde estes códigos em local seguro. Eles <strong>não serão exibidos novamente</strong>.
                </p>

                <div class="grid grid-cols-2 gap-2 p-4 bg-gray-50 border border-gray-200 rounded-lg mb-4">
                    @foreach ($recoveryCodes as $recoveryCode)
                        <code class="text-sm text-gray-800">{{ $recoveryCode }}</code>
                    @endforeach
                </div>

                <div class="text-right">
                    <button type="button" wire:click="dismissCodes" class="px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                        Já salvei, fechar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
