<form
    hx-post="{{ route('padre-grupos.cursos.agregar', $padreGrupo) }}"
    hx-target="#padre-grupo-edit-form"
    hx-swap="innerHTML"
    class="space-y-5">

    <h4 class="font-bold uppercase text-sm">
        Cursos asignados
    </h4>

    @forelse ($padreGrupo->grupos as $grupo)
    <div class="flex justify-between border-2 p-2">
        <span>{{ $grupo->curso->nombre_curso }}</span>

        <button
            type="button"
            hx-delete="{{ route('padre-grupos.destroy', $grupo) }}"
            hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
            hx-include="none"
            hx-confirm="¿Quitar este curso?"
            hx-target="#padre-grupo-edit-form"
            hx-swap="innerHTML"
            class="text-xs font-bold text-red-500">
            Quitar
        </button>
    </div>
    @empty
    <p class="text-xs text-[#5C6F72]">
        No hay cursos asignados
    </p>
    @endforelse

    <hr>

    <h4 class="font-bold uppercase text-sm">
        Agregar curso
    </h4>

    <select
        name="curso_id"
        required
        class="border-2 w-full px-3 py-2">
        <option value="">Seleccione un curso</option>
        @foreach ($cursosDisponibles as $curso)
        <option value="{{ $curso->id }}">
            {{ $curso->nombre_curso }}
        </option>
        @endforeach
    </select>

    <div class="flex justify-end gap-2">
        <button
            type="button"
            x-on:click="$dispatch('close-modal', 'edit-padre-grupo')"
            class="border-2 px-3 py-2 text-xs font-bold uppercase">
            Cerrar
        </button>

        <button
            type="submit"
            class="border-2 px-3 py-2 text-xs font-bold uppercase bg-[#008080] text-white">
            Agregar
        </button>
    </div>

</form>