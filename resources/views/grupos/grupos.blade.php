<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase tracking-[0.14em]">
            Grupos
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <section class="border-2 border-[#0A1718] bg-white">
                <div class="flex items-center justify-between gap-4 border-b border-[#5C6F72]/30 px-5 py-4">
                    <div>
                        <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            [ TABLA / GRUPOS ]
                        </p>
                    </div>

                    <x-ui.button
                        type="button"
                        color="teal"
                        class="px-3 py-1"
                        x-data
                        x-on:click="$dispatch('open-modal', 'create-grupo')">
                        Crear Grupo
                    </x-ui.button>

                </div>

                <div class="overflow-x-auto">
                    @if(session('status'))
                    <div class="mb-4 border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 text-sm font-bold text-[#008080]">
                        {{ session('status') }}
                    </div>
                    @endif

                    @if($periodos->isEmpty() || $cursos->isEmpty() || $secciones->isEmpty())
                    <div class="border-b border-[#5C6F72]/30 p-5 text-sm text-[#0A1718]">
                        Para crear grupos debes tener al menos un Periodo, un Curso y una Sección registrados.
                    </div>
                    @endif

                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-[#F4F7F7]">
                                <th class="border-b border-r border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">ID</th>
                                <th class="border-b border-r border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">Nombre</th>
                                <th class="border-b border-r border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">Periodo</th>
                                <th class="border-b border-r border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">Curso</th>
                                <th class="border-b border-r border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">Sección</th>
                                <th class="border-b border-r border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">Estado</th>
                                <th class="border-b border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grupos as $grupo)
                            <tr class="hover:bg-[#F4F7F7]">
                                <td class="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm">{{ $grupo->id }}</td>
                                <td class="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm">{{ $grupo->nombre_grupo }}</td>
                                <td class="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm">{{ $grupo->periodo?->nombre_periodo ?? '-' }}</td>
                                <td class="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm">{{ $grupo->curso?->nombre_curso ?? '-' }}</td>
                                <td class="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm">{{ $grupo->seccion?->nombre_seccion ?? '-' }}</td>
                                <td class="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm">{{ $grupo->activo ? 'Activo' : 'Inactivo' }}</td>
                                <td class="border-b border-[#5C6F72]/30 px-3 py-2">
                                    <div class="flex flex-wrap gap-2">
                                        <x-ui.action-button
                                            type="button"
                                            x-on:click="$dispatch('open-modal', 'edit-grupo-{{ $grupo->id }}')"
                                            color="outline">
                                            Editar
                                        </x-ui.action-button>
                                        <x-ui.action-button
                                            type="button"
                                            x-on:click="$dispatch('open-modal', 'delete-grupo-{{ $grupo->id }}')"
                                            color="coral">
                                            Eliminar
                                        </x-ui.action-button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center">No hay grupos registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-[#5C6F72]/30 px-5 py-4">
                    {{ $grupos->links() }}
                </div>
            </section>
        </div>
    </div>

    <x-ui.modal name="create-grupo" title="[ GRUPOS / NUEVO ]" :show="$errors->any()">
        <form
            x-data='{
        niveles: @json($nivelesForJs),

        cursos: @json(
            $cursos->map(fn($c) => [
                "id" => $c->id,
                "nombre_curso" => $c->nombre_curso,
                "grado_area_id" => $c->grado_area_id
            ])->values()
        ),

        selectedNivel: null,
        selectedGrado: null,
        selectedCursos: [],

        gradosForSelectedNivel() {
            const nivel = this.niveles.find(
                n => Number(n.id) === Number(this.selectedNivel)
            );

            return nivel ? nivel.gradoAreas : [];
        },

        cursosForSelectedGrado() {
            return this.cursos.filter(
                c => Number(c.grado_area_id) === Number(this.selectedGrado)
            );
        }
    }'
            x-on:submit="if (selectedCursos.length === 0) { $event.preventDefault(); alert('Selecciona al menos un curso.'); }"
            method="POST"
            action="{{ route('grupos.store') }}"
            class="space-y-5 p-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Nivel --}}
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                        Nivel
                    </label>

                    <select
                        x-model="selectedNivel"
                        class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3">
                        <option value="">Seleccione un nivel</option>

                        <template x-for="n in niveles" :key="n.id">
                            <option :value="n.id" x-text="n.nombre_nivel"></option>
                        </template>
                    </select>
                </div>

                {{-- Grado --}}
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                        Grado
                    </label>

                    <select
                        x-model="selectedGrado"
                        class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3">
                        <option value="">Seleccione un grado</option>

                        <template x-for="g in gradosForSelectedNivel()" :key="g.id">
                            <option :value="g.id" x-text="g.nombre_grado"></option>
                        </template>
                    </select>
                </div>

                {{-- Periodo --}}
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                        Periodo
                    </label>

                    <select
                        name="periodo_id"
                        class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3">
                        <option value="">Seleccione un periodo</option>

                        @foreach($periodos as $periodo)
                        <option value="{{ $periodo->id }}">
                            {{ $periodo->nombre_periodo }}
                        </option>
                        @endforeach
                    </select>

                    @error('periodo_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sección --}}
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                        Sección
                    </label>

                    <select
                        name="seccion_id"
                        class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3">
                        <option value="">Seleccione una sección</option>

                        @foreach($secciones as $seccion)
                        <option value="{{ $seccion->id }}">
                            {{ $seccion->nombre_seccion }}
                        </option>
                        @endforeach
                    </select>

                    @error('seccion_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Cursos --}}
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                    Cursos Disponibles
                </label>

                <div class="border-2 border-[#0A1718] bg-[#F4F7F7] p-4 min-h-[120px]">

                    <template x-if="selectedGrado">

                        <div class="flex flex-wrap gap-3">

                            <template
                                x-for="c in cursosForSelectedGrado()"
                                :key="c.id">
                                <label
                                    class="flex items-center gap-2 border border-[#0A1718] bg-white px-3 py-2 cursor-pointer hover:bg-gray-50">
                                    <input
                                        type="checkbox"
                                        :value="c.id"
                                        x-model="selectedCursos"
                                        name="curso_ids[]">

                                    <span x-text="c.nombre_curso"></span>
                                </label>
                            </template>

                        </div>

                    </template>

                    <template x-if="!selectedGrado">
                        <p class="text-sm text-[#5C6F72]">
                            Selecciona un grado para ver sus cursos.
                        </p>
                    </template>

                </div>
            </div>

            {{-- Activo --}}
            <label class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                <input
                    type="checkbox"
                    name="activo"
                    value="1"
                    checked
                    class="h-5 w-5 rounded-none border-2 border-[#0A1718]">
                Activo
            </label>

            <x-ui.button
                type="submit"
                color="teal"
                class="w-full">
                Crear Grupo(s)
            </x-ui.button>

        </form>
    </x-ui.modal>

    @foreach($grupos as $grupo)
    <x-ui.modal name="edit-grupo-{{ $grupo->id }}" title="[ GRUPOS / EDITAR ]">
        <form class="space-y-5 p-5" method="POST" action="{{ route('grupos.update', $grupo) }}">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">Nombre</label>
                <input name="nombre_grupo" value="{{ old('nombre_grupo', $grupo->nombre_grupo) }}" type="text" class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">
                @error('nombre_grupo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">Periodo</label>
                <select name="periodo_id" class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">
                    @foreach($periodos as $periodo)
                    <option value="{{ $periodo->id }}" {{ old('periodo_id', $grupo->periodo_id) == $periodo->id ? 'selected' : '' }}>{{ $periodo->nombre_periodo }}</option>
                    @endforeach
                </select>
                @error('periodo_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">Curso</label>
                <select name="curso_id" class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">
                    @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}" {{ old('curso_id', $grupo->curso_id) == $curso->id ? 'selected' : '' }}>{{ $curso->nombre_curso }}</option>
                    @endforeach
                </select>
                @error('curso_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">Sección</label>
                <select name="seccion_id" class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">
                    @foreach($secciones as $seccion)
                    <option value="{{ $seccion->id }}" {{ old('seccion_id', $grupo->seccion_id) == $seccion->id ? 'selected' : '' }}>{{ $seccion->nombre_seccion }}</option>
                    @endforeach
                </select>
                @error('seccion_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <label class="flex items-center gap-3 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                <input type="checkbox" name="activo" value="1" {{ old('activo', $grupo->activo) ? 'checked' : '' }} class="h-5 w-5 rounded-none border-2 border-[#0A1718] text-[#008080]">
            </label>

            <x-ui.button type="submit" color="teal" class="w-full">
                Guardar Cambios
            </x-ui.button>
        </form>
    </x-ui.modal>

    <x-ui.delete-modal
        name="delete-grupo-{{ $grupo->id }}"
        title="[ GRUPOS / ELIMINAR ]"
        :item-name="$grupo->nombre_grupo"
        :action="route('grupos.destroy', $grupo)" />
    @endforeach
</x-app-layout>