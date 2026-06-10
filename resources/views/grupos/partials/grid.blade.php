<div id="grupos-grid"
    class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 lg:grid-cols-3">

    @forelse ($grupos as $grupo)

    <div class="border-2 border-[#0A1718] bg-white p-4 transition hover:-translate-y-1 hover:shadow-lg">

        <div class="flex items-start justify-between gap-3">
            <h3 class="font-bold uppercase tracking-wide">{{ $grupo->nombre_grupo }}</h3>
            <span class="border px-2 py-1 text-[10px] uppercase">
                {{ $grupo->activo ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        <div class="mt-2 space-y-1 text-sm text-[#5C6F72]">
            <p><strong>Periodo:</strong> {{ $grupo->periodo->nombre_periodo }}</p>
            <p><strong>Curso:</strong> {{ $grupo->curso->nombre_curso }}</p>
            <p><strong>Sección:</strong> {{ $grupo->seccion->nombre_seccion }}</p>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            <button
                type="button"
                hx-get="{{ route('grupos.edit', $grupo) }}"
                hx-target="#grupo-edit-form"
                hx-swap="innerHTML"
                x-data
                x-on:click="$dispatch('open-modal', 'edit-grupo')"
                class="border-2 border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase hover:bg-[#008080] hover:text-white">
                Editar
            </button>

            <button
                type="button"
                x-data
                x-on:click="$dispatch('open-modal', 'delete-grupo-{{ $grupo->id }}')"
                class="border-2 border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase hover:bg-red-500 hover:text-white">
                Eliminar
            </button>
        </div>

    </div>

    {{-- MODAL DELETE (debe estar fuera del card pero dentro del loop) --}}
    <x-ui.delete-modal
        name="delete-grupo-{{ $grupo->id }}"
        title="[ GRUPO / ELIMINAR ]"
        :item-name="$grupo->nombre_grupo"
        :action="route('grupos.destroy', $grupo)"
        target="#grupos-module" />

    @empty
    <div class="col-span-full border-2 border-[#0A1718] bg-[#F4F7F7] p-5 text-sm text-[#5C6F72]">
        No hay grupos registrados.
    </div>
    @endforelse

</div>