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
        class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <section class="border-2 border-[#0A1718] bg-white">

                <div class="flex items-center justify-between border-b border-[#5C6F72]/30 px-5 py-4">

                    <div>
                        <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            [ GRADOS ]
                        </p>

                        <!-- <p class="mt-1 text-sm">
                            Nivel: {{ $nivel->nombre_nivel }}
                        </p> -->

                        <h3 class="mt-1 font-['Space_Grotesk',sans-serif] text-lg font-bold uppercase tracking-[-0.03em] text-[#0A1718]">
                            Nivel: {{ $nivel->nombre_nivel }}
                        </h3>

                    </div>

                    <button
                        type="button"
                        x-data
                        x-on:click="$dispatch('open-modal', 'create-grado')"
                        class="border-2 border-[#0A1718] bg-[#008080] px-4 py-2 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.16em] text-white shadow-[4px_4px_0px_0px_rgba(10,23,24,1)] transition-transform active:translate-x-[4px] active:translate-y-[4px] active:shadow-none">
                        Agregar Grado
                    </button>

                </div>

                <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($gradoAreas as $grado)
                    <div class="border-2 border-[#0A1718] bg-white p-4 transition hover:bg-[#F4F7F7]">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="font-bold uppercase tracking-wide">
                                {{ $grado->nombre_grado }}
                            </h3>

                            <span class="border border-[#5C6F72] px-2 py-1 text-[10px] uppercase">
                                {{ $grado->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <p class="mt-2 min-h-[40px] text-sm text-[#5C6F72]">
                            {{ $grado->descripcion ?: 'Sin descripcion' }}
                        </p>

                        <div class="mt-3 text-xs uppercase tracking-wider text-[#5C6F72]">
                            Cursos: {{ $grado->cursos_count ?? $grado->cursos->count() ?? 0 }}
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('grado-areas.cursos', $grado) }}">
                                <x-ui.button
                                    type="button"
                                    color="orange">
                                    Cursos
                                </x-ui.button>
                            </a>

                            <button
                                type="button"
                                x-data
                                x-on:click="$dispatch('open-modal', 'edit-grado-{{ $grado->id }}')"
                                class="border-2 border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase hover:bg-[#008080] hover:text-white">
                                Editar
                            </button>

                            <button
                                type="button"
                                x-data
                                x-on:click="$dispatch('open-modal', 'delete-grado-{{ $grado->id }}')"
                                class="border-2 border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase hover:bg-red-500 hover:text-white">
                                Borrar
                            </button>
                        </div>
                    </div>

                    <x-ui.modal name="edit-grado-{{ $grado->id }}" title="[ GRADOS / EDITAR ]">
                        <x-grado-areas.form
                            :grado="$grado"
                            :action="route('grado-areas.update', $grado)"
                            method="PATCH"
                            button-text="Guardar Cambios" />
                    </x-ui.modal>

                    <x-ui.modal name="delete-grado-{{ $grado->id }}" title="[ GRADOS / ELIMINAR ]">
                        <div class="space-y-5 p-5">
                            <div class="border-2 border-[#0A1718] bg-[#F4F7F7] p-4">
                                <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#FF7F50]">
                                    Accion irreversible
                                </p>

                                <p class="mt-3 text-sm leading-6 text-[#0A1718]/80">
                                    Estas seguro de que deseas eliminar el grado
                                    <strong>{{ $grado->nombre_grado }}</strong>?
                                </p>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                                <button
                                    type="button"
                                    x-data
                                    x-on:click="$dispatch('close-modal', 'delete-grado-{{ $grado->id }}')"
                                    class="border-2 border-[#0A1718] bg-white px-4 py-2 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.14em]">
                                    Cancelar
                                </button>

                                <form method="POST" action="{{ route('grado-areas.destroy', $grado) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="w-full border-2 border-[#0A1718] bg-red-500 px-4 py-2 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.14em] text-white shadow-[4px_4px_0px_0px_rgba(10,23,24,1)] sm:w-auto">
                                        Si, eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </x-ui.modal>
                    @empty
                    <div class="border-2 border-[#0A1718] bg-[#F4F7F7] p-5 text-sm text-[#5C6F72] sm:col-span-2 lg:col-span-3">
                        No existen grados registrados.
                    </div>
                    @endforelse
                </div>
            </section>
        </div>

    </div>

    <x-ui.modal name="create-grado" title="[ GRADOS / NUEVO ]" :show="$errors->any()">
        <x-grado-areas.form
            :action="route('niveles.grado-areas.store', $nivel)"
            button-text="Crear Grado" />
    </x-ui.modal>

</x-app-layout>