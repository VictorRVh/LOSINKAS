<x-app-layout>

    <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase">
            {{ $gradoArea->nombre_grado }}
        </h2>
    </x-slot>

    <div
        x-data="{
            editando: false,
            cursoId: null,
            nombre: '',
            descripcion: '',
            activo: true,

            crear() {
                this.editando = false;
                this.cursoId = null;
                this.nombre = '';
                this.descripcion = '';
                this.activo = true;

                $dispatch('open-modal', 'curso-modal');
            },

            editar(curso) {
                this.editando = true;
                this.cursoId = curso.id;
                this.nombre = curso.nombre_curso;
                this.descripcion = curso.descripcion ?? '';
                this.activo = Boolean(curso.activo);

                $dispatch('open-modal', 'curso-modal');
            }
        }"
        class="py-6"
    >

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <section class="border-2 border-[#0A1718] bg-white">

                <div class="flex items-center justify-between border-b px-5 py-4">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            [ CURSOS ]
                        </p>

                        <p class="mt-1 text-sm">
                            Grado: {{ $gradoArea->nombre_grado }}
                        </p>
                    </div>

                    <x-ui.button
                        type="button"
                        color="teal"
                        x-on:click="crear()">
                        Crear Curso
                    </x-ui.button>

                </div>

                <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2 lg:grid-cols-3">

                    @forelse($cursos as $curso)

                        <div class="border-2 border-[#0A1718] bg-white p-5">

                            <div class="flex items-center justify-between">

                                <h3 class="font-bold uppercase">
                                    {{ $curso->nombre_curso }}
                                </h3>

                                <span class="border px-2 py-1 text-[10px] font-bold uppercase">
                                    {{ $curso->activo ? 'Activo' : 'Inactivo' }}
                                </span>

                            </div>

                            <p class="mt-3 text-sm text-[#5C6F72]">
                                {{ $curso->descripcion ?: 'Sin descripción' }}
                            </p>

                            <div class="mt-5 flex gap-2">

                                <x-ui.button
                                    type="button"
                                    color="teal"
                                    x-on:click='editar(@json($curso))'>
                                    Editar
                                </x-ui.button>

                                <form
                                    action="{{ route('cursos.destroy', $curso) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <x-ui.button
                                        type="submit"
                                        color="orange"
                                        onclick="return confirm('¿Eliminar este curso?')">
                                        Eliminar
                                    </x-ui.button>

                                </form>

                            </div>

                        </div>

                    @empty

                        <div class="col-span-full py-10 text-center">
                            No existen cursos registrados.
                        </div>

                    @endforelse

                </div>

            </section>

        </div>

        {{-- MODAL CREAR / EDITAR CURSO --}}

        <x-ui.modal
            name="curso-modal"
            title="[ CURSO ]">

            <form
                method="POST"
                class="space-y-5 p-5"
                :action="editando
                    ? `/cursos/${cursoId}`
                    : '{{ route('cursos.store') }}'">

                @csrf

                <template x-if="editando">
                    <input
                        type="hidden"
                        name="_method"
                        value="PUT">
                </template>

                <input
                    type="hidden"
                    name="grado_area_id"
                    value="{{ $gradoArea->id }}">

                <div>

                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                        Nombre del Curso
                    </label>

                    <input
                        x-model="nombre"
                        name="nombre_curso"
                        type="text"
                        class="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3"
                        required>

                </div>

                <div>

                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                        Descripción
                    </label>

                    <textarea
                        x-model="descripcion"
                        name="descripcion"
                        rows="3"
                        class="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3"></textarea>

                </div>

                <label class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">

                    <input
                        x-model="activo"
                        type="checkbox"
                        name="activo"
                        value="1">

                    Activo

                </label>

                <x-ui.button
                    type="submit"
                    color="teal"
                    class="w-full">

                    <span x-text="editando ? 'Actualizar Curso' : 'Crear Curso'"></span>

                </x-ui.button>

            </form>

        </x-ui.modal>

    </div>

</x-app-layout>