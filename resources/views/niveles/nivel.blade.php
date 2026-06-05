<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase tracking-[0.14em]">
            Niveles
        </h2>
    </x-slot>

    @include('niveles.partials.module', [
    'niveles' => $niveles,
    'buscar' => $buscar ?? ''
    ])
</x-app-layout>