<x-guest-layout>
    <div class="mb-6">
        <p class="text-sm font-semibold text-teal-700">Crie seu acesso</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-950">Cadastrar no Portal DevTech</h1>
        <p class="mt-2 text-sm text-slate-600">Use um e-mail valido para receber o link de confirmacao.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nome" />
            <x-text-input id="name" class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 focus:border-teal-500 focus:ring-teal-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-5">
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 focus:border-teal-500 focus:ring-teal-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" value="Senha" />

            <x-text-input id="password" class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 focus:border-teal-500 focus:ring-teal-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5">
            <x-input-label for="password_confirmation" value="Confirmar senha" />

            <x-text-input id="password_confirmation" class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 focus:border-teal-500 focus:ring-teal-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center rounded-lg bg-teal-600 px-5 py-3 text-sm normal-case hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-800 focus:ring-teal-500">
                Cadastrar
            </x-primary-button>
        </div>

        <p class="mt-6 text-center text-sm text-slate-600">
            Ja tem uma conta?
            <a href="{{ route('login') }}" class="font-semibold text-teal-700 hover:text-teal-900">Entrar</a>
        </p>
    </form>
</x-guest-layout>
