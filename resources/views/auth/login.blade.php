<x-guest-layout>
    <div class="mb-6">
        <p class="text-sm font-semibold text-teal-700">Bem-vindo de volta</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-950">Entrar na sua conta</h1>
        <p class="mt-2 text-sm text-slate-600">Acesse o Portal DevTech com seu e-mail e senha.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 focus:border-teal-500 focus:ring-teal-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" value="Senha" />

            <x-text-input id="password" class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 focus:border-teal-500 focus:ring-teal-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mt-5 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">Lembrar acesso</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-teal-700 hover:text-teal-900" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center rounded-lg bg-teal-600 px-5 py-3 text-sm normal-case hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-800 focus:ring-teal-500">
                Entrar
            </x-primary-button>
        </div>

        <p class="mt-6 text-center text-sm text-slate-600">
            Ainda nao tem conta?
            <a href="{{ route('register') }}" class="font-semibold text-teal-700 hover:text-teal-900">Cadastre-se</a>
        </p>
    </form>
</x-guest-layout>
