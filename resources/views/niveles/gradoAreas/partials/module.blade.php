<div id="grado-areas-module">

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <section class="border-2 border-[#0A1718] bg-white">

                {{-- HEADER --}}
                <div class="flex items-center justify-between border-b px-5 py-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            [ GRADOS ]
                        </p>
                        <p class="mt-1 text-sm">
                            Nivel: {{ $nivel->nombre_nivel }}
                        </p>
                    </div>

                    <x-ui.button
                        type="button"
                        color="teal"
                        x-on:click="$dispatch('open-modal', 'create-grado')">
                        Crear Grado
                    </x-ui.button>
                </div>

                {{-- GRID --}}
                <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2 lg:grid-cols-3">

                    @forelse($gradoAreas as $grado)

                    <div class="border-2 border-[#0A1718] bg-white p-5">

                        <div class="flex items-center justify-between">
                            <h3 class="font-bold uppercase">
                                {{ $grado->nombre_grado }}
                            </h3>

                            <span class="border px-2 py-1 text-[10px] font-bold uppercase">
                                {{ $grado->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <p class="mt-3 text-sm text-[#5C6F72]">
                            {{ $grado->descripcion ?: 'Sin descripción' }}
                        </p>

                        <div class="mt-3 text-xs uppercase tracking-wider text-[#5C6F72]">
                            Cursos: {{ $grado->cursos_count ?? $grado->cursos->count() }}
                        </div>

                        <div class="mt-5 flex gap-2 flex-wrap">
                            <x-ui.action-button
                                href="{{ route('grado-areas.cursos', $grado) }}">
                                Cursos
                            </x-ui.action-button>

                            <x-ui.action-button
                                x-on:click="$dispatch('open-modal', 'edit-grado-{{ $grado->id }}')"
                                color="outline">
                                Editar
                            </x-ui.action-button>

                            <x-ui.action-button
                                x-on:click="$dispatch('open-modal', 'delete-grado-{{ $grado->id }}')"
                                color="coral">
                                Eliminar
                            </x-ui.action-button>
                        </div>
                    </div>

                    {{-- MODAL EDIT --}}
                    <x-ui.modal
                        name="edit-grado-{{ $grado->id }}"
                        title="[ GRADO / EDITAR ]">
                        <x-grado-areas.form
                            :grado="$grado"
                            :action="route('grado-areas.update', $grado)"
                            method="PATCH"
                            button-text="Guardar Cambios" />
                    </x-ui.modal>

                    {{-- MODAL DELETE (HTMX) --}}
                    <x-ui.delete-modal
                        name="delete-grado-{{ $grado->id }}"
                        title="[ GRADO / ELIMINAR ]"
                        :item-name="$grado->nombre_grado"
                        :action="route('grado-areas.destroy', $grado)"
                        target="#grado-areas-module" />

                    @empty
                    <div class="col-span-full py-10 text-center">
                        No existen grados registrados.
                    </div>
                    @endforelse

                </div>

            </section>
        </div>
    </div>

    {{-- MODAL CREATE --}}
    <x-ui.modal
        name="create-grado"
        title="[ GRADO / NUEVO ]"
        :show="$errors->any()">

        <x-grado-areas.form
            :action="route('niveles.grado-areas.store', $nivel)"
            button-text="Crear Grado" />
    </x-ui.modal>

</div>