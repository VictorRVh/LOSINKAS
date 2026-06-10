<x-app-layout>

    <x-slot name="breadcrumb">
        <x-ui.breadcrumb
            :items="[
                ['label' => 'Matrículas'],
            ]" />
    </x-slot>

    <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase tracking-[0.14em]">
            Matrículas
        </h2>
    </x-slot>

    {{-- Submenú --}}
    @include('matriculas.partials.submenu')

    {{-- Contenido dinámico --}}
    <div id="matriculas-content">

        @include('matriculas.partials.matricular', [
            'estudiantes' => $estudiantes,
            'periodos' => $periodos,
            'grados' => $grados,
            'secciones' => $secciones,
            'grupos' => $grupos,
        ])

    </div>

</x-app-layout>