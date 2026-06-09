<div id="grupos-module">
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session('status'))
            <div class="mb-4 border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 text-sm font-bold text-[#008080]">
                {{ session('status') }}
            </div>
            @endif

            <section class="border-2 border-[#0A1718] bg-white">

                {{-- HEADER --}}
                <div class="flex flex-col gap-4 border-b border-[#5C6F72]/30 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            [ GRID / GRUPOS ]
                        </p>

                        <h3 class="mt-1 text-lg font-bold uppercase text-[#0A1718]">
                            Todos los grupos
                        </h3>
                    </div>

                    <x-ui.button
                        type="button"
                        color="teal"
                        x-data
                        x-on:click="$dispatch('open-modal', 'create-grupo')">
                        Crear Grupo
                    </x-ui.button>
                </div>

                {{-- GRID --}}
                <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 lg:grid-cols-3">

                    @forelse ($grupos as $grupo)
                    <div class="border-2 border-[#0A1718] bg-white p-4 transition hover:-translate-y-1 hover:shadow-lg">

                        <div class="flex items-start justify-between gap-3">
                            <h3 class="font-bold uppercase tracking-wide">
                                {{ $grupo->nombre_grupo }}
                            </h3>

                            <span class="border px-2 py-1 text-[10px] uppercase">
                                {{ $grupo->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <div class="mt-2 text-sm text-[#5C6F72] space-y-1">
                            <p><strong>Periodo:</strong> {{ $grupo->periodo->nombre_periodo }}</p>
                            <p><strong>Curso:</strong> {{ $grupo->curso->nombre_curso }}</p>
                            <p><strong>Sección:</strong> {{ $grupo->seccion->nombre_seccion }}</p>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <button
                                type="button"
                                x-data
                                x-on:click="$dispatch('open-modal', 'edit-grupo-{{ $grupo->id }}')"
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

                    {{-- MODAL EDIT --}}
                    <x-ui.modal name="edit-grupo-{{ $grupo->id }}" title="[ GRUPO / EDITAR ]">
                        <x-grupos.form
                            :grupo="$grupo"
                            :niveles="$niveles"
                            :cursos="$cursos"
                            :periodos="$periodos"
                            :secciones="$secciones"
                            :action="route('grupos.update', $grupo)"
                            method="PUT"
                            button-text="Guardar Cambios" />
                    </x-ui.modal>

                    {{-- MODAL DELETE --}}
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

                {{-- PAGINACIÓN --}}
                <div class="border-t border-[#5C6F72]/30 px-5 py-4">
                    {{ $grupos->links() }}
                </div>
            </section>
        </div>
    </div>

    {{-- MODAL CREATE --}}
    <x-ui.modal name="create-grupo" title="[ GRUPO / NUEVO ]" :show="$errors->any()">
        <x-grupos.form
            :niveles="$niveles"
            :cursos="$cursos"
            :periodos="$periodos"
            :secciones="$secciones"
            :action="route('grupos.store')"
            button-text="Crear Grupo" />
    </x-ui.modal>
</div>