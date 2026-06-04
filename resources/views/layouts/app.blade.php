<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'InkaScan') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#ECECEC] text-[#0A1718]">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-[260px] border-r-2 border-[#0A1718] bg-[#F5F5F5] flex flex-col">

        <!-- LOGO -->
        <div class="border-b-2 border-[#0A1718] p-5">
            <h1 class="font-bold tracking-[0.2em] uppercase text-[#008080]">
                INKASCAN / PANEL
            </h1>
        </div>

        <!-- MENU -->
        <nav class="flex-1 p-4 space-y-3">

            <a href="{{ route('dashboard') }}"
               class="flex items-center border-2 border-[#0A1718] px-4 py-3 font-bold uppercase tracking-[0.12em] hover:bg-[#008080] hover:text-white">
                Dashboard
            </a>

            <a href="{{ route('usuarios') }}"
               class="flex items-center border-2 border-[#0A1718] px-4 py-3 font-bold uppercase tracking-[0.12em] hover:bg-[#008080] hover:text-white">
                Usuarios
            </a>

            <a href="#"
               class="flex items-center border-2 border-[#0A1718] px-4 py-3 font-bold uppercase tracking-[0.12em] hover:bg-[#008080] hover:text-white">
                Exámenes
            </a>

            <a href="#"
               class="flex items-center border-2 border-[#0A1718] px-4 py-3 font-bold uppercase tracking-[0.12em] hover:bg-[#008080] hover:text-white">
                Resultados
            </a>

        </nav>

        <!-- USUARIO -->
        <div class="border-t-2 border-[#0A1718] p-4">

            <p class="font-semibold">
                {{ Auth::user()->name }}
            </p>

            <p class="text-sm text-gray-500">
                {{ Auth::user()->email }}
            </p>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf

                <button
                    type="submit"
                    class="w-full border-2 border-[#0A1718] bg-white py-2 font-bold uppercase shadow-[4px_4px_0px_0px_rgba(10,23,24,1)]">
                    Salir
                </button>
            </form>

        </div>

    </aside>

    <!-- CONTENIDO -->
    <div class="flex-1 flex flex-col">

        <!-- HEADER -->
        <header class="border-b-2 border-[#0A1718] bg-[#F5F5F5]">

            <div class="px-6 py-5">

                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#008080]">
                    MÓDULO ACTIVO
                </p>

                <h2 class="text-3xl font-bold uppercase">
                    {{ $header ?? 'Panel' }}
                </h2>

            </div>

        </header>

        <!-- CONTENIDO DE CADA VISTA -->
        <main class="p-6">

            {{ $slot }}

        </main>

    </div>

</div>

</body>
</html>