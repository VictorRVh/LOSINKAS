<x-app-layout>

    <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase">
            {{ $gradoArea->nombre_grado }}
        </h2>
    </x-slot>

    <div x-data="{}" class="py-6">

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
                        x-on:click="$dispatch('open-modal', 'create-curso')">
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

                                <x-ui.action-button                    
                                    x-on:click="$dispatch('open-modal', 'edit-curso-{{ $curso->id }}')"
                                    color="outline">
                                    Editar
                                </x-ui.action-button>

                                <x-ui.action-button
                                    x-on:click="$dispatch('open-modal', 'delete-curso-{{ $curso->id }}')"
                                    color="coral">
                                    Eliminar
                                </x-ui.action-button>

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

        {{-- MODAL CREAR CURSO --}}
        <x-ui.modal name="create-curso" title="[ CURSO / NUEVO ]" :show="$errors->any()">
            <x-cursos.form :action="route('cursos.store')" :grado-area="$gradoArea" button-text="Crear Curso" />
        </x-ui.modal>

        {{-- MODALES EDITAR POR CURSO --}}
        @foreach($cursos as $curso)
            <x-ui.modal name="edit-curso-{{ $curso->id }}" title="[ CURSO / EDITAR ]">
                <x-cursos.form :curso="$curso" :action="route('cursos.update', $curso)" method="PUT" button-text="Guardar Cambios" />
            </x-ui.modal>

            <x-ui.delete-modal
                name="delete-curso-{{ $curso->id }}"
                title="[ CURSO / ELIMINAR ]"
                :item-name="$curso->nombre_curso"
                :action="route('cursos.destroy', $curso)" />
        @endforeach

    </div>

</x-app-layout>