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

    {{-- SOLO EL MÓDULO --}}
    @include('matriculas.partials.module', [
    'matriculas' => $matriculas,
    'estudiantes' => $estudiantes,
    'grupos' => $grupos,
    ])

</x-app-layout>