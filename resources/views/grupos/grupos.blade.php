
<x-app-layout>

    <x-slot name="breadcrumb">
        <x-ui.breadcrumb
            :items="[
                ['label' => 'Grupos'],
            ]" />
    </x-slot>

    <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase tracking-[0.14em]">
            Grupos
        </h2>
    </x-slot>

    {{-- SOLO SE INCLUYE EL MÓDULO --}}
    @include('grupos.partials.module')

</x-app-layout>