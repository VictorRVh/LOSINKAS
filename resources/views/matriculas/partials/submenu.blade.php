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

        <button
            hx-get="{{ route('matriculas.tab.reservas') }}"
            hx-target="#matriculas-content"
            hx-swap="innerHTML"
            class="py-3 text-sm font-bold">
            Estudiantes con reserva
        </button>

    </nav>
</div>