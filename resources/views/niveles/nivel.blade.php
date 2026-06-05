<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase tracking-[0.14em]">
            Niveles
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session('status'))
            <div class="mb-4 border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 text-sm font-bold text-[#008080]">
                {{ session('status') }}
            </div>
            @endif

            <section class="border-2 border-[#0A1718] bg-white">
                <div class="flex flex-col gap-4 border-b border-[#5C6F72]/30 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            [ GRID / NIVELES ]
                        </p>

                        <h3 class="mt-1 font-['Space_Grotesk',sans-serif] text-lg font-bold uppercase tracking-[-0.03em] text-[#0A1718]">
                            Todos los niveles
                        </h3>
                    </div>

                    <button
                        type="button"
                        x-data
                        x-on:click="$dispatch('open-modal', 'create-nivel')"
                        class="border-2 border-[#0A1718] bg-[#008080] px-4 py-2 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.16em] text-white shadow-[4px_4px_0px_0px_rgba(10,23,24,1)] transition-transform active:translate-x-[4px] active:translate-y-[4px] active:shadow-none">
                        Agregar Nivel
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($niveles as $nivel)
                    <div class="border-2 border-[#0A1718] bg-white p-4 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="font-bold uppercase tracking-wide">
                                {{ $nivel->nombre_nivel }}
                            </h3>

                            <span class="border border-[#5C6F72] px-2 py-1 text-[10px] uppercase">
                                {{ $nivel->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-[#5C6F72]">
                            {{ $nivel->descripcion ?? 'Sin descripcion' }}
                        </p>

                        <div class="mt-3 text-xs uppercase tracking-wider text-[#5C6F72]">
                            Areas: {{ $nivel->gradoAreas->count() }}
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <x-ui.action-button href="{{ route('niveles.grado-areas', $nivel) }}" class="">Ver</x-ui.action-button>

                            <x-ui.action-button
                                type="button"
                                x-data
                                x-on:click="$dispatch('open-modal', 'edit-nivel-{{ $nivel->id }}')"
                                color="outline">
                                Editar
                            </x-ui.action-button>

                            <form
                                action="{{ route('niveles.destroy', $nivel) }}"
                                method="POST"
                                onsubmit="return confirm('Seguro que deseas eliminar este nivel?')">
                                @csrf
                                @method('DELETE')

                                <x-ui.action-button
                                    type="button"
                                    x-data
                                    x-on:click="$dispatch('open-modal', 'delete-nivel-{{ $nivel->id }}')"
                                    color="coral">
                                    Borrar
                                </x-ui.action-button>
                            </form>
                        </div>
                    </div>

                    <x-ui.modal name="edit-nivel-{{ $nivel->id }}" title="[ NIVELES / EDITAR ]">
                        <x-niveles.form
                            :nivel="$nivel"
                            :action="route('niveles.update', $nivel)"
                            method="PATCH"
                            button-text="Guardar Cambios" />
                    </x-ui.modal>

                    <x-ui.delete-modal
                        name="delete-nivel-{{ $nivel->id }}"
                        title="[ NIVELES / ELIMINAR ]"
                        :item-name="$nivel->nombre_nivel"
                        :action="route('niveles.destroy', $nivel)" />

                    @empty
                    <div class="border-2 border-[#0A1718] bg-[#F4F7F7] p-5 text-sm text-[#5C6F72] sm:col-span-2 lg:col-span-3">
                        No hay niveles registrados.
                    </div>
                    @endforelse
                </div>

                <div class="border-t border-[#5C6F72]/30 px-5 py-4">
                    {{ $niveles->links() }}
                </div>
            </section>
        </div>
    </div>

    <x-ui.modal name="create-nivel" title="[ NIVELES / NUEVO ]" :show="$errors->any()">
        <x-niveles.form
            :action="route('niveles.store')"
            button-text="Crear Nivel" />
    </x-ui.modal>
</x-app-layout>