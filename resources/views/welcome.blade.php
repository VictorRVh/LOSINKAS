@php
$brandLogo = 'img/inkascan.jpeg';

$navLinks = [
['label' => 'Producto', 'href' => '#producto'],
['label' => 'Algoritmo', 'href' => '#metricas'],
['label' => 'CSV', 'href' => '#flujo'],
['label' => 'Contacto', 'href' => '#footer'],
];

$processingSteps = [
[
'label' => '[ PASO 01 / REGISTRO ]',
'title' => 'Alineacion de Matriz',
'text' => 'El sistema reconoce las marcas de sincronizacion en las esquinas de la ficha. Corrige de forma automatica la rotacion, inclinacion y perspectiva de la captura en milisegundos.',
'icon' => 'scan',
],
[
'label' => '[ PASO 02 / OPTIMIZACION ]',
'title' => 'Binarizacion Dinamica',
'text' => 'Aplica filtros de umbralizacion para neutralizar sombras, corregir el contraste del papel y eliminar manchas accidentales, asegurando lecturas limpias con camaras de gama baja.',
'icon' => 'alert',
],
[
'label' => '[ PASO 03 / EXTRACCION ]',
'title' => 'Vision Computacional',
'text' => 'Nuestra IA analiza la densidad de pixeles en cada burbuja de respuesta. Determina la opcion marcada con precision matematica, descartando borrones o marcas tenues.',
'icon' => 'trend',
],
];

$csvSteps = [
[
'phase' => '[ CARGA CSV ]',
'text' => 'Sube la nomina de estudiantes desde un archivo Excel o CSV (Nombres, DNI, Aula o Area).',
'icon' => 'file',
],
[
'phase' => '[ IMPRESION ]',
'text' => 'El sistema genera fichas en PDF listas para imprimir, con el nombre del alumno y un codigo QR unico pre-impreso.',
'icon' => 'circle-stack',
],
[
'phase' => '[ ESCANEO ]',
'text' => 'La camara lee el QR de la ficha. Identifica al alumno al instante y registra su calificacion en la base de datos sin ingreso manual.',
'icon' => 'scan',
],
];

$systemStatus = [
['label' => 'STATUS', 'value' => 'ONLINE', 'active' => true],
['label' => 'ENGINE', 'value' => 'VISION v4.2.1-RELEASE'],
['label' => 'API LATENCY', 'value' => '14ms'],
['label' => 'DB SYNCHRONIZED', 'value' => 'YES'],
];

$documentationLinks = [
'Guia de Calibracion',
'Formato de Fichas (PDF)',
'API Docs',
'Manual del Evaluador',
];

$securityLinks = [
'Politicas de Privacidad',
'Terminos del Servicio',
'Proteccion de Datos',
'Auditoria de Examenes',
];

$isDarkModeManual = false;

@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Olector</title>

    @endif
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main>
            <div
                id="top"
                class="min-h-screen transition-colors duration-700 ease-in-out"
                class="">
                <div
                    class="mx-auto flex min-h-screen w-full max-w-[1440px] flex-col border-x transition-colors duration-700 ease-in-out"
                    class="">
                    <header class="border-b border-[#0A1718] transition-colors duration-700 ease-in-out dark:border-[#5C6F72]/40">
                        <div
                            class="mx-auto flex w-full max-w-[1440px] items-center justify-between gap-5 px-6 py-2 lg:px-10">
                            <a
                                href="#top"
                                class="flex items-center gap-4 border-r border-[#5C6F72]/30 pr-5">
                                <img
                                    src="{{ $brandLogo }}"
                                    alt="InkaScan"
                                    class="block h-16 w-auto object-contain lg:h-20">
                                <span class="hidden items-center gap-2 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#008080] lg:flex">

                                    Vision OMR
                                </span>
                            </a>

                            <div class="flex items-center gap-3">
                                <nav class="hidden items-center text-sm uppercase tracking-[0.14em] md:flex">
                                    @foreach($navLinks as $index => $link)
                                    <a href="{{ $link['href'] }}">
                                        {{ $link['label'] }}
                                    </a>

                                    @if($index < count($navLinks)-1)
                                        <span class="px-3 text-[#5C6F72]">|</span>
                                        @endif
                                        @endforeach
                                </nav>

                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center p-1 text-[#008080] transition-transform hover:text-[#FF7F50] active:translate-y-px"
                                    id="toggleDarkMode">

                                    @if($isDarkModeManual)
                                    <x-heroicon-o-sun class="h-5 w-5" />
                                    @else
                                    <x-heroicon-o-moon class="h-5 w-5" />
                                    @endif

                                </button>

                                @if (Route::has('login'))
                                <nav class="flex items-center justify-end gap-4">
                                    @auth
                                    <a
                                        href="{{ url('/dashboard') }}"
                                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                        Dashboard
                                    </a>
                                    @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-2 font-['Space_Grotesk',sans-serif] text-sm font-bold uppercase tracking-[0.12em] text-[#0A1718] shadow-[4px_4px_0px_0px_rgba(10,23,24,1)] transition-transform active:translate-x-[4px] active:translate-y-[4px] active:shadow-none">
                                        Log in
                                    </a>

                                    @if (Route::has('register'))
                                    <a
                                        href="{{ route('register') }}"
                                        class="rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-2 font-['Space_Grotesk',sans-serif] text-sm font-bold uppercase tracking-[0.12em] text-[#0A1718] shadow-[4px_4px_0px_0px_rgba(10,23,24,1)] transition-transform active:translate-x-[4px] active:translate-y-[4px] active:shadow-none"">
                                        Register
                                    </a>
                                    @endif
                                    @endauth
                                </nav>
                                @endif
                            </div>
                        </div>
                    </header>

                    <main class="flex-1">
                        <section
                            id="producto"
                            class="border-b border-[#5C6F72]/30">
                            <div class="grid grid-cols-1 lg:grid-cols-2">
                                <div class="border-b border-[#5C6F72]/30 px-6 py-14 lg:border-b-0 lg:border-r lg:px-10 lg:py-20">
                                    <p class="mb-6 flex items-center gap-2 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.24em] text-[#008080]">

                                        Vision artificial para lectura OMR
                                    </p>
                                    <h1
                                        class="max-w-[11ch] font-serif text-5xl font-semibold leading-[1.02] tracking-[-0.04em] lg:text-7xl">
                                        Corrige examenes con precision de laboratorio.
                                    </h1>
                                    <p
                                        class="mt-8 max-w-xl border-l border-[#5C6F72]/30 pl-5 text-base leading-7"
                                        class="">
                                        Una landing construida como tablero tecnico: bloques exactos,
                                        guias visibles y un visor que comunica control de captura,
                                        lectura y salida de datos con disciplina industrial.
                                    </p>

                                    <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                                        <button
                                            type="button"
                                            class="rounded-none border-2 border-[#0A1718] bg-[#FF7F50] px-6 py-4 font-['Space_Grotesk',sans-serif] text-sm font-bold uppercase tracking-[0.14em] text-white shadow-[4px_4px_0px_0px_rgba(10,23,24,1)] transition-transform active:translate-x-[4px] active:translate-y-[4px] active:shadow-none">
                                            Iniciar Escaneo
                                        </button>
                                        <button
                                            type="button"
                                            class="rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-6 py-4 font-['Space_Grotesk',sans-serif] text-sm font-bold uppercase tracking-[0.14em] text-[#0A1718] shadow-[4px_4px_0px_0px_rgba(10,23,24,1)] transition-transform active:translate-x-[4px] active:translate-y-[4px] active:shadow-none">
                                            Ver Arquitectura
                                        </button>
                                    </div>

                                    <div class="mt-12 grid grid-cols-2 border border-[#5C6F72]/30 text-sm sm:max-w-[32rem]">
                                        <div class="border-b border-r border-[#5C6F72]/30 px-4 py-4">
                                            <span class="block text-xs uppercase tracking-[0.16em]" class="">Lotes por hora</span>
                                            <strong class="mt-2 block font-['Space_Grotesk',sans-serif] text-2xl">[ 1,200 ]</strong>
                                        </div>
                                        <div class="border-b border-[#5C6F72]/30 px-4 py-4">
                                            <span class="block text-xs uppercase tracking-[0.16em]" class="">Error humano</span>
                                            <strong class="mt-2 block font-['Space_Grotesk',sans-serif] text-2xl">[ -87% ]</strong>
                                        </div>
                                        <div class="border-r border-[#5C6F72]/30 px-4 py-4">
                                            <span class="block text-xs uppercase tracking-[0.16em]" class="">Modo</span>
                                            <strong class="mt-2 block font-['Space_Grotesk',sans-serif] text-2xl text-[#008080]">ONLINE</strong>
                                        </div>
                                        <div class="px-4 py-4">
                                            <span class="block text-xs uppercase tracking-[0.16em]" class="">Estado laser</span>
                                            <strong class="mt-2 block font-['Space_Grotesk',sans-serif] text-2xl text-[#FF7F50]">TRACKING</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-6 py-14 lg:px-10 lg:py-20">
                                    <div
                                        class="relative mx-auto flex min-h-[520px] w-full max-w-[620px] items-center justify-center overflow-hidden rounded-none border-2 px-6 py-8 transition-colors duration-700 ease-in-out"
                                        class="">
                                        <div class="absolute inset-0 opacity-20">
                                            <div class="h-full w-full bg-[linear-gradient(to_right,rgba(92,111,114,0.22)_1px,transparent_1px),linear-gradient(to_bottom,rgba(92,111,114,0.22)_1px,transparent_1px)] bg-[size:44px_44px]" />
                                        </div>

                                        <div class="pointer-events-none absolute inset-5 border border-[#5C6F72]/30" />
                                        <div class="pointer-events-none absolute left-5 top-5 h-12 w-12 border-l-4 border-t-4 border-[#008080]" />
                                        <div class="pointer-events-none absolute right-5 top-5 h-12 w-12 border-r-4 border-t-4 border-[#008080]" />
                                        <div class="pointer-events-none absolute bottom-5 left-5 h-12 w-12 border-b-4 border-l-4 border-[#008080]" />
                                        <div class="pointer-events-none absolute bottom-5 right-5 h-12 w-12 border-b-4 border-r-4 border-[#008080]" />
                                        <div class="scan-line absolute left-5 right-5 top-16 h-[2px] bg-[#FF7F50]" />

                                        <div class="relative z-10 grid w-full max-w-[420px] grid-cols-[1fr_auto] border border-[#5C6F72]/30 bg-[#FFFFFF] text-[#0A1718]">
                                            <div class="border-r border-[#5C6F72]/30 p-5">
                                                <div class="mb-4 flex items-center justify-between border-b border-[#5C6F72]/30 pb-3">
                                                    <span class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.14em] text-[#5C6F72]">
                                                        Hoja candidata
                                                    </span>
                                                    <span class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.14em] text-[#008080]">
                                                        Stable
                                                    </span>
                                                </div>

                                                <div class="grid grid-cols-5 gap-3">
                                                    @for($index = 1; $index <= 25; $index++)
                                                        <span
                                                        class="aspect-square rounded-full border border-[#0A1718] bg-[#F4F7F7]">
                                                        </span>
                                                        @endfor
                                                </div>
                                            </div>

                                            <div class="flex w-[92px] flex-col justify-between bg-[#F4F7F7] p-4 text-[#0A1718]">
                                                <div class="border border-[#5C6F72]/30 px-3 py-2">
                                                    <span class="block text-[10px] uppercase tracking-[0.18em] text-[#5C6F72]">Frame</span>
                                                    <strong class="mt-2 block font-['Space_Grotesk',sans-serif] text-lg">248</strong>
                                                </div>
                                                <div class="border border-[#5C6F72]/30 px-3 py-2">
                                                    <span class="block text-[10px] uppercase tracking-[0.18em] text-[#5C6F72]">Focus</span>
                                                    <strong class="mt-2 block font-['Space_Grotesk',sans-serif] text-lg text-[#008080]">OK</strong>
                                                </div>
                                                <div class="border border-[#5C6F72]/30 px-3 py-2">
                                                    <span class="block text-[10px] uppercase tracking-[0.18em] text-[#5C6F72]">Laser</span>
                                                    <strong class="mt-2 block font-['Space_Grotesk',sans-serif] text-lg text-[#FF7F50]">ON</strong>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="absolute bottom-6 left-6 right-6 flex items-center justify-between border-t border-[#5C6F72]/30 pt-3 font-['Space_Grotesk',sans-serif] text-[11px] uppercase tracking-[0.16em] text-[#F4F7F7]/80">
                                            <span>X: 142.20 / Y: 087.44</span>
                                            <span class="flex items-center gap-2">

                                                OMR Reader [ Vision Lock ]
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section
                            id="metricas"
                            class="border-b border-[#5C6F72]/30 px-6 py-14 lg:px-10 lg:py-16">
                            <div class="mb-8">
                                <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.22em] text-[#5C6F72]">
                                    Procesamiento de Imagen
                                </p>
                                <h2 class="mt-3 font-['Space_Grotesk',sans-serif] text-3xl font-bold uppercase tracking-[-0.03em]">
                                    El Algoritmo
                                </h2>
                            </div>

                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                                @foreach($processingSteps as $step)
                                <article

                                    class="rounded-none border-2 p-6 shadow-[6px_6px_0px_0px_rgba(10,23,24,1)] transition-colors duration-700 ease-in-out"
                                    class="">
                                    <div class="flex items-start justify-between gap-4">
                                        <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#008080]">
                                            {{ $step['label'] }}
                                        </p>
                                        <component

                                            class="h-5 w-5 text-[#FF7F50]" />
                                    </div>
                                    <h3 class="mt-6 border-t border-[#5C6F72]/30 pt-5 font-['Space_Grotesk',sans-serif] text-2xl font-bold uppercase tracking-[-0.03em]">
                                        {{ $step['title'] }}
                                    </h3>
                                    <p class="mt-5 text-sm leading-7">
                                        {{ $step['text'] }}
                                    </p>
                                </article>
                                @endforeach
                            </div>
                        </section>

                        <section
                            id="flujo"
                            class="border-b border-[#5C6F72]/30 px-6 py-14 lg:px-10 lg:py-16">
                            <div
                                class="rounded-none border-2 p-6 transition-colors duration-700 ease-in-out lg:p-8"
                                class="">
                                <div class="border-b border-[#5C6F72]/30 pb-6">
                                    <div class="flex items-center gap-2 text-[#008080]">
                                        <x-heroicon-o-circle-stack class="h-5 w-5" />
                                        <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.22em]">
                                            Flujo de trabajo CSV
                                        </p>
                                    </div>
                                    <h2 class="mt-3 font-['Space_Grotesk',sans-serif] text-3xl font-bold uppercase tracking-[-0.03em]">
                                        Automatizacion de Fichas por CSV
                                    </h2>
                                    <p class="mt-4 text-sm leading-7">
                                        Evita errores de llenado en los codigos de postulante imprimiendo fichas personalizadas.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 gap-6 pt-6 lg:grid-cols-[1fr_auto_1fr_auto_1fr] lg:items-stretch">
                                    @foreach($csvSteps as $index => $step)
                                    <article class="rounded-none border-2 p-5 transition-colors duration-700 ease-in-out">
                                        <div class="flex items-start justify-between gap-3">
                                            <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#FF7F50]">
                                                {{ $step['phase'] }}
                                            </p>
                                        </div>

                                        <p class="mt-5 text-sm leading-7">
                                            {{ $step['text'] }}
                                        </p>
                                    </article>

                                    @if($index < count($csvSteps) - 1)
                                        <div class="hidden items-center justify-center lg:flex">
                                        <span class="font-['Space_Grotesk',sans-serif] text-xl font-bold text-[#008080]">
                                            A → B → C
                                        </span>
                                </div>
                                @endif
                                @endforeach
                            </div>
                </div>
                </section>

                <section
                    id="institutionalSection"
                    class="border-b border-[#5C6F72]/30 bg-[#FAF9F5] px-6 py-14 text-[#0A1718] transition-colors duration-700 ease-in-out lg:px-10 lg:py-16"
                    class="">
                    <div class="border-y border-[#5C6F72]/30">
                        <div class="grid grid-cols-1 lg:grid-cols-[320px_1fr]">
                            <div class="border-b border-[#5C6F72]/30 px-5 py-6 lg:border-b-0 lg:border-r">
                                <p class="flex items-center gap-2 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.22em] text-[#008080]">

                                    [ SEGMENTO INSTITUCIONAL ]
                                </p>
                            </div>

                            <div class="px-5 py-6">
                                <h2 class="font-['Space_Grotesk',sans-serif] text-3xl font-bold uppercase tracking-[-0.03em]">
                                    Calidad de Respuesta para Simulacros Masivos
                                </h2>
                                <p class="mt-5 max-w-5xl text-sm leading-7">
                                    Disenado para el nivel de exigencia de las academias y colegios de Puno. Permite calificacion descentralizada desde celulares, procesamiento por lotes de imagenes escaneadas y generacion inmediata de reportes de rendimiento por areas academicas (Biomedicas, Ingenierias, Sociales).
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
        </main>

        <footer
            id="footer"
            class="border-t border-[#0A1718] px-6 py-10 transition-colors duration-700 ease-in-out lg:px-10">
            <div
                class="border transition-colors duration-700 ease-in-out"
                class="">
                <div class="grid grid-cols-1 lg:grid-cols-4">
                    <div
                        class="border-b border-[#5C6F72]/30 px-5 py-6 font-mono text-[12px] leading-6 lg:border-b-0 lg:border-r">
                        <p class="font-['Space_Grotesk',sans-serif] text-[11px] font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            [ 00 / TELEMETRIA ]
                        </p>
                        <div class="mt-4 space-y-3">
                            @foreach($systemStatus as $item)
                            <div class="flex items-center justify-between gap-4 border-b border-[#5C6F72]/20 pb-2 last:border-b-0 last:pb-0">

                                <span>{{ $item['label'] }}:</span>

                                <span class="font-['Space_Grotesk',sans-serif] text-right">
                                    {{ $item['value'] }}

                                    @if(isset($item['active']) && $item['active'])
                                    <span class="ml-2 inline-flex items-center gap-1 text-[#008080]">
                                        [ OK ]
                                    </span>
                                    @endif
                                </span>

                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div
                        class="border-b border-[#5C6F72]/30 px-5 py-6 lg:border-b-0 lg:border-r">
                        <p class="flex items-center gap-2 font-['Space_Grotesk',sans-serif] text-[11px] font-bold uppercase tracking-[0.18em] text-[#5C6F72]">

                            [ 01 / RECURSOS ]
                        </p>
                        <ul class="mt-4 space-y-3 text-sm leading-6">
                            @foreach($documentationLinks as $item)
                            <li>
                                <a
                                    href="#"
                                    class="transition-colors hover:text-[#008080] hover:underline">
                                    {{ $item }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div
                        class="border-b border-[#5C6F72]/30 px-5 py-6 lg:border-b-0 lg:border-r">
                        <p class="flex items-center gap-2 font-['Space_Grotesk',sans-serif] text-[11px] font-bold uppercase tracking-[0.18em] text-[#5C6F72]">

                            [ 02 / SEGURIDAD ]
                        </p>
                        <ul class="mt-4 space-y-3 text-sm leading-6">
                            @foreach($securityLinks as $item)
                            <li>
                                <a
                                    href="#"
                                    class="transition-colors hover:text-[#008080] hover:underline">
                                    {{ $item }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="px-5 py-6">
                        <p class="flex items-center gap-2 font-['Space_Grotesk',sans-serif] text-[11px] font-bold uppercase tracking-[0.18em] text-[#5C6F72]">

                            [ 03 / UBICACION ]
                        </p>
                        <div class="mt-4 space-y-3 font-mono text-[12px] leading-6">
                            <div class="flex items-center justify-between gap-4 border-b border-[#5C6F72]/20 pb-2">
                                <span class="">LATITUD:</span>
                                <span>15.8402 S</span>
                            </div>
                            <div class="flex items-center justify-between gap-4 border-b border-[#5C6F72]/20 pb-2">
                                <span class="">LONGITUD:</span>
                                <span>70.0219 W</span>
                            </div>
                            <div class="pt-1 font-['Space_Grotesk',sans-serif] text-sm font-bold uppercase tracking-[0.16em] text-[#008080]">
                                ALTIPLANO UNIT // PUNO
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4 border-t border-[#5C6F72]/30 px-5 py-4 text-[11px] uppercase tracking-[0.18em] lg:flex-row lg:items-center lg:justify-between">
                    <p class="font-['Space_Grotesk',sans-serif]">
                        OLECTOR - SISTEMA DE LECTURA OPTICA POR VISION COMPUTACIONAL. TODOS LOS DERECHOS RESERVADOS.
                    </p>
                    <span class="font-['Space_Grotesk',sans-serif] font-bold text-[#008080]">
                        [ CALIBRATION OK ]
                    </span>
                </div>
            </div>
        </footer>
    </div>
    </div>
    </main>
    </div>

    @if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
    @endif
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const body = document.body;
        const darkBtn = document.getElementById('toggleDarkMode');
        const institutionalSection = document.getElementById('institutionalSection');

        let isDarkModeManual = false;
        let isDarkSection = false;

        const updateTheme = () => {
            const darkActive = isDarkModeManual || isDarkSection;

            if (darkActive) {
                body.classList.add('dark-mode');
            } else {
                body.classList.remove('dark-mode');
            }
        };

        const storedPreference = localStorage.getItem('olector-dark-mode');

        if (storedPreference !== null) {
            isDarkModeManual = storedPreference === 'true';
            updateTheme();
        }

        if (darkBtn) {
            darkBtn.addEventListener('click', () => {
                isDarkModeManual = !isDarkModeManual;

                localStorage.setItem(
                    'olector-dark-mode',
                    isDarkModeManual
                );

                updateTheme();
            });
        }

        if (institutionalSection) {
            const observer = new IntersectionObserver(
                ([entry]) => {
                    isDarkSection =
                        entry.isIntersecting &&
                        entry.intersectionRatio >= 0.3;

                    updateTheme();
                }, {
                    threshold: 0.3
                }
            );

            observer.observe(institutionalSection);
        }
    });
</script>

</html>