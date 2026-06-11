<!-- <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase tracking-[0.14em]">
            Matrículas
        </h2>
    </x-slot> -->
<div class="mb-4 border-b border-[#5C6F72]/30">
    <nav class="flex gap-6 px-2">

        <button
            hx-get="{{ route('matriculas.tab.matricular') }}"
            hx-target="#matriculas-content"
            hx-swap="innerHTML"
            class="py-3 text-sm font-bold">
            Matricular estudiante
        </button>

        <button
            hx-get="{{ route('matriculas.tab.grupos') }}"
            hx-target="#matriculas-content"
            hx-swap="innerHTML"
            class="py-3 text-sm font-bold">
            Lista por grupos
        </button>

    </nav>
</div>