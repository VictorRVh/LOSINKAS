<x-app-layout>

    <x-slot name="breadcrumb">
        <x-ui.breadcrumb
            :items="[
                ['label' => 'Matrículas'],
            ]" />
    </x-slot>


    {{-- SOLO EL MÓDULO --}}
    @include('matriculas.partials.module', [
    'matriculas' => $matriculas,
    'estudiantes' => $estudiantes,
    'grupos' => $grupos,
    ])

</x-app-layout>