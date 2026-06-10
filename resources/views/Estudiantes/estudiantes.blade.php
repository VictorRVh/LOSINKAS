<x-app-layout>

    <x-slot name="breadcrumb">
        <x-ui.breadcrumb
            :items="[
                ['label' => 'Estudiantes'],
            ]" />
    </x-slot>

    <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase tracking-[0.14em]">
            Estudiantes
        </h2>
    </x-slot>

    {{-- SOLO EL MÓDULO --}}
    @include('estudiantes.partials.module', [
        'estudiantes' => $estudiantes,
    ])

</x-app-layout>