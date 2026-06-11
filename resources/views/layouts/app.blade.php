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

   @include('layouts.sidebar')
    <!-- CONTENIDO -->
    <div class="flex-1 flex flex-col">

        <!-- HEADER -->
        <header class="border-b-2 border-[#0A1718] bg-[#F5F5F5]">

            <div class="px-6 py-5">

                

                <!-- <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#008080]">
                    MÓDULO ACTIVO
                </p> -->
                {{ $breadcrumb ?? '' }}

            </div>

        </header>

        <!-- CONTENIDO DE CADA VISTA -->
        <main class="p-6">
            
                <h2 class="text-3xl font-bold uppercase">
                    {{ $header ?? 'Panel' }}
                </h2>


            {{ $slot }}

        </main>

    </div>

</div>

</body>
</html>