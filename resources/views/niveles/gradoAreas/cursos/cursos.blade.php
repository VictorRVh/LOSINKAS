<x-app-layout>

    <x-slot name="breadcrumb">
        <x-ui.breadcrumb
            :items="[
                ['label' => 'Niveles', 'href' => route('niveles.index')],
                ['label' => $gradoArea->nivel->nombre_nivel, 'href' => route('niveles.grado-areas', $gradoArea->nivel)],
                ['label' => $gradoArea->nombre_grado],
            ]"
            back-url="javascript:history.back()"
            back-label="Volver" />
    </x-slot>

    <x-slot name="header"></x-slot>

    <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase">
        {{ $gradoArea->nombre_grado }}
    </h2>

    {{-- 🔹 SOLO INCLUYE EL MÓDULO --}}
    @include('niveles.gradoAreas.cursos.partials.module', [
    'gradoArea' => $gradoArea,
    'cursos' => $cursos
    ])

</x-app-layout>