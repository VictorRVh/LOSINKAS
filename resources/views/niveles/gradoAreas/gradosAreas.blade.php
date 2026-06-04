<x-app-layout>

    <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase tracking-[0.14em]">
            {{ $nivel->nombre_nivel }}
        </h2>
    </x-slot>

    <div
        x-data="{
            editando: false,
            gradoId: null,
            nombre: '',
            descripcion: '',
            activo: true,

            crear() {
                this.editando = false;
                this.gradoId = null;
                this.nombre = '';
                this.descripcion = '';
                this.activo = true;

                $dispatch('open-modal', 'grado-modal');
            },

            editar(grado) {
                this.editando = true;
                this.gradoId = grado.id;
                this.nombre = grado.nombre_grado;
                this.descripcion = grado.descripcion ?? '';
                this.activo = Boolean(grado.activo);

                $dispatch('open-modal', 'grado-modal');
            }
        }"
        class="py-6"
    >

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <section class="border-2 border-[#0A1718] bg-white">

                <div class="flex items-center justify-between border-b border-[#5C6F72]/30 px-5 py-4">

                    <div>
                        <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            [ GRADOS ]
                        </p>

                        <p class="mt-1 text-sm">
                            Nivel: {{ $nivel->nombre_nivel }}
                        </p>
                    </div>

                    <x-ui.button
                        type="button"
                        color="teal"
                        x-on:click="crear()">
                        Crear Grado
                    </x-ui.button>

                </div>

                <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2 lg:grid-cols-3">

                    @forelse($gradoAreas as $grado)

                        <div class="border-2 border-[#0A1718] bg-white p-5 transition hover:-translate-y-1 hover:shadow-lg">

                            <div class="flex items-start justify-between">

                                <h3 class="font-['Space_Grotesk',sans-serif] text-lg font-bold uppercase">
                                    {{ $grado->nombre_grado }}
                                </h3>

                                <span class="border border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase">
                                    {{ $grado->activo ? 'Activo' : 'Inactivo' }}
                                </span>

                            </div>

                            <p class="mt-3 min-h-[60px] text-sm text-[#5C6F72]">
                                {{ $grado->descripcion ?: 'Sin descripción' }}
                            </p>

                            <div class="mt-5 flex gap-2">
                                <x-ui.button
                                    type="button"
                                    color="orange"
                                    x-on:click='editar(@json($grado))'>
                                    Cursos
                                </x-ui.button>

                                <x-ui.button
                                    type="button"
                                    color="teal"
                                    x-on:click='editar(@json($grado))'>
                                    Editar
                                </x-ui.button>

                                <form
                                    action="{{ route('grado-areas.destroy', $grado) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <x-ui.button
                                        type="submit"
                                        color="orange"
                                        onclick="return confirm('¿Eliminar este grado?')">
                                        Eliminar
                                    </x-ui.button>

                                </form>

                            </div>

                        </div>

                    @empty

                        <div class="col-span-full py-10 text-center">
                            No existen grados registrados.
                        </div>

                    @endforelse

                </div>

            </section>

        </div>

        {{-- MODAL CREAR / EDITAR --}}

        <x-ui.modal
            name="grado-modal"
            title="[ GRADO ]">

            <form
                method="POST"
                class="space-y-5 p-5"
                :action="editando
                    ? `/grado-areas/${gradoId}`
                    : '{{ route('grado-areas.store') }}'">

                @csrf

                <template x-if="editando">
                    <input
                        type="hidden"
                        name="_method"
                        value="PUT">
                </template>

                <input
                    type="hidden"
                    name="nivel_id"
                    value="{{ $nivel->id }}">

                <div>

                    <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                        Nombre del grado
                    </label>

                    <input
                        x-model="nombre"
                        name="nombre_grado"
                        type="text"
                        class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none"
                        required>

                </div>

                <div>

                    <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                        Descripción
                    </label>

                    <textarea
                        x-model="descripcion"
                        name="descripcion"
                        rows="3"
                        class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none"></textarea>

                </div>

                <label class="flex items-center gap-3 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">

                    <input
                        x-model="activo"
                        type="checkbox"
                        name="activo"
                        value="1"
                        class="h-5 w-5 rounded-none border-2 border-[#0A1718]">

                    Activo

                </label>

                <x-ui.button
                    type="submit"
                    color="teal"
                    class="w-full">

                    <span x-text="editando ? 'Actualizar Grado' : 'Crear Grado'"></span>

                </x-ui.button>

            </form>

        </x-ui.modal>

    </div>

</x-app-layout>