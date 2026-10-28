<div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-slate-950/85 via-slate-900/70 to-blue-950/85"></div>

    <div class="relative w-full max-w-md">
        <div class="overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5">
            <div class="border-b border-gray-100 bg-gradient-to-r from-slate-50 via-white to-slate-50 px-8 py-7 text-center">
                <img src="{{ $config->getlogo() }}" alt="{{ $config->app_name }}" class="mx-auto h-auto max-h-16 w-auto">
            </div>

            <div class="px-8 py-7">
                @if ($showingRecoveryCodes)
                    <div class="mb-6 text-center">
                        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-amber-50 ring-1 ring-amber-200">
                            <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                            </svg>
                        </div>

                        <h1 class="text-lg font-semibold text-gray-900">Códigos de recuperação</h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Guarde em local seguro: cada código pode ser usado <strong class="font-semibold text-gray-700">uma única vez</strong>.
                        </p>
                    </div>

                    <div x-data='{
                            copied: false,
                            copy(text) {
                                const done = () => {
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 2000);
                                };

                                const fallback = () => {
                                    const field = document.createElement("textarea");
                                    field.value = text;
                                    field.style.position = "fixed";
                                    field.style.opacity = "0";
                                    document.body.appendChild(field);
                                    field.select();
                                    document.execCommand("copy");
                                    field.remove();
                                    done();
                                };

                                if (navigator.clipboard && window.isSecureContext) {
                                    navigator.clipboard.writeText(text).then(done).catch(fallback);
                                    return;
                                }

                                fallback();
                            }
                        }' class="mb-5">
                        <div class="grid grid-cols-2 gap-2 rounded-xl border border-gray-200 bg-gray-50 p-4">
                            @foreach ($recoveryCodes as $recoveryCode)
                                <code class="rounded-md bg-white px-2 py-1.5 text-center font-mono text-sm tracking-wider text-gray-800 ring-1 ring-gray-200">
                                    {{ $recoveryCode }}
                                </code>
                            @endforeach
                        </div>

                        <button type="button"
                                x-on:click="copy($el.dataset.codes)"
                                data-codes="{{ implode("\n", $recoveryCodes) }}"
                                class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/>
                            </svg>
                            <span x-show="!copied">Copiar códigos</span>
                            <span x-show="copied" x-cloak class="text-green-600">Copiado!</span>
                        </button>
                    </div>

                    <p class="mb-5 rounded-lg bg-red-50 px-3 py-2 text-xs font-medium text-red-700 ring-1 ring-red-100">
                        Estes códigos não serão exibidos novamente.
                    </p>

                    <button type="button"
                            wire:click="confirmRecoveryCodes"
                            wire:loading.attr="disabled"
                            wire:target="confirmRecoveryCodes"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                        <span wire:loading.remove wire:target="confirmRecoveryCodes">Já salvei, continuar</span>
                        <span wire:loading wire:target="confirmRecoveryCodes" class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                    </button>
                @elseif ($awaitingCode)
                    <div class="mb-6 text-center">
                        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 ring-1 ring-blue-100">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>

                        <h1 class="text-lg font-semibold text-gray-900">Verificação em duas etapas</h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Enviamos um código de 6 dígitos para
                            <strong class="font-semibold text-gray-700">{{ $maskedEmail }}</strong>.
                            Ele expira em 10 minutos.
                        </p>
                    </div>

                    <form wire:submit.prevent="verify" x-data="{ left: {{ (int) $resendIn }} }" x-init="setInterval(() => { if (left > 0) left-- }, 1000)">
                        <label for="code" class="block text-sm font-medium text-gray-700">Código de verificação</label>

                        <input id="code"
                               wire:model="code"
                               type="text"
                               inputmode="numeric"
                               autocomplete="one-time-code"
                               autofocus
                               placeholder="000000"
                               class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-center font-mono text-xl font-semibold tracking-[0.4em] text-gray-900 shadow-sm transition placeholder:text-base placeholder:font-normal placeholder:tracking-normal placeholder:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 @error('code') border-red-400 focus:border-red-500 focus:ring-red-500/30 @enderror">

                        @error('code')
                            <p class="mt-2 flex items-center gap-1 text-xs font-medium text-red-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:target="verify"
                                class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                            <span wire:loading.remove wire:target="verify">Verificar e entrar</span>
                            <span wire:loading wire:target="verify" class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                        </button>

                        <div class="mt-4 flex items-center justify-between text-sm">
                            <button type="button" wire:click="cancel"
                                    class="inline-flex items-center gap-1 font-medium text-gray-500 transition hover:text-gray-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                                </svg>
                                Voltar
                            </button>

                            <button type="button"
                                    wire:click="resend"
                                    x-bind:disabled="left > 0"
                                    class="inline-flex items-center gap-1 font-medium text-blue-600 transition hover:text-blue-700 disabled:cursor-not-allowed disabled:text-gray-400 disabled:hover:text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992V4.356m-4.992 4.992l3.182-3.182a8.25 8.25 0 10-2.29 13.06"/>
                                </svg>
                                <span x-show="left > 0" x-text="'Reenviar em ' + left + 's'"></span>
                                <span x-show="left === 0" x-cloak>Reenviar código</span>
                            </button>
                        </div>

                        <p class="mt-5 rounded-lg bg-gray-50 px-3 py-2 text-xs text-gray-500 ring-1 ring-gray-100">
                            Sem acesso ao e-mail? Informe um <strong class="font-semibold text-gray-700">código de recuperação</strong> no campo acima.
                        </p>
                    </form>
                @else
                    <div class="mb-6">
                        <h1 class="text-lg font-semibold text-gray-900">Acessar painel</h1>
                        <p class="mt-1 text-sm text-gray-500">Informe suas credenciais para continuar.</p>
                    </div>

                    <form wire:submit.prevent="login">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                            <input id="email"
                                   wire:model="email"
                                   type="email"
                                   autocomplete="username"
                                   placeholder="voce@empresa.com.br"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 @error('email') border-red-400 focus:border-red-500 focus:ring-red-500/30 @enderror">

                            @error('email')
                                <p class="mt-2 flex items-center gap-1 text-xs font-medium text-red-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mt-5">
                            <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                            <input id="password"
                                   wire:model="password"
                                   type="password"
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 @error('password') border-red-400 focus:border-red-500 focus:ring-red-500/30 @enderror">

                            @error('password')
                                <p class="mt-2 flex items-center gap-1 text-xs font-medium text-red-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        @error('login_failed')
                            <p class="mt-4 rounded-lg bg-red-50 px-3 py-2 text-xs font-medium text-red-700 ring-1 ring-red-100">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:target="login"
                                class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                            <span wire:loading.remove wire:target="login">Entrar</span>
                            <span wire:loading wire:target="login" class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                        </button>
                    </form>

                    <p class="mt-5 flex items-center justify-center gap-1.5 text-xs text-gray-400">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        Acesso protegido por verificação em duas etapas.
                    </p>
                @endif
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-white/70">
            &copy; {{ date('Y') }} {{ $config->app_name ?? config('app.name') }}
        </p>
    </div>
</div>
