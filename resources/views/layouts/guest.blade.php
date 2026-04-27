<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Portal DevTech') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen bg-slate-950">
            <div class="grid min-h-screen lg:grid-cols-[1.05fr_0.95fr]">
                <section class="relative hidden overflow-hidden lg:block">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_25%_20%,rgba(20,184,166,0.35),transparent_28%),linear-gradient(135deg,#0f172a_0%,#111827_48%,#064e3b_100%)]"></div>
                    <div class="relative flex h-full flex-col justify-between p-12 text-white">
                        <a href="/" class="inline-flex items-center gap-3">
                            <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-teal-400 text-lg font-black text-slate-950">DT</span>
                            <span class="text-xl font-semibold">Portal DevTech</span>
                        </a>

                        <div class="max-w-xl">
                            <p class="text-sm font-semibold uppercase text-teal-200">Tecnologia, carreira e inovação</p>
                            <h1 class="mt-5 text-5xl font-bold leading-tight">Entre para acompanhar as novidades que movem o mundo dev.</h1>
                            <p class="mt-5 text-lg leading-8 text-slate-200">Acesse sua conta, salve preferências e participe de uma comunidade feita para quem constrói com tecnologia.</p>
                        </div>

                        <div class="grid grid-cols-3 gap-4 text-sm text-slate-200">
                            <div class="border-t border-white/20 pt-4">
                                <strong class="block text-2xl text-white">Tech</strong>
                                Notícias selecionadas
                            </div>
                            <div class="border-t border-white/20 pt-4">
                                <strong class="block text-2xl text-white">Dev</strong>
                                Conteúdo prático
                            </div>
                            <div class="border-t border-white/20 pt-4">
                                <strong class="block text-2xl text-white">IA</strong>
                                Tendências atuais
                            </div>
                        </div>
                    </div>
                </section>

                <main class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-8 sm:px-6 lg:px-12">
                    <div class="w-full max-w-md">
                        <div class="mb-8 flex items-center justify-between lg:hidden">
                            <a href="/" class="inline-flex items-center gap-3">
                                <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-teal-500 text-base font-black text-white">DT</span>
                                <span class="text-lg font-semibold text-slate-950">Portal DevTech</span>
                            </a>
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-white px-6 py-7 shadow-sm sm:px-8">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
