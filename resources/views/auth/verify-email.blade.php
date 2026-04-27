<x-guest-layout>
    <div class="mb-6">
        <p class="text-sm font-semibold text-teal-700">Confirme seu e-mail</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-950">Quase la</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            Enviamos um link de verificacao para o seu e-mail. Clique nele para liberar o acesso completo ao Portal DevTech.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            Um novo link de verificacao foi enviado para o e-mail cadastrado.
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button class="w-full justify-center rounded-lg bg-teal-600 px-5 py-3 text-sm normal-case hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-800 focus:ring-teal-500 sm:w-auto">
                    Reenviar e-mail
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="text-sm font-medium text-slate-600 hover:text-slate-950">
                Sair
            </button>
        </form>
    </div>
</x-guest-layout>
