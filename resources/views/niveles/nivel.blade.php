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
                    <div class="border-2 border-[#0A1718] bg-white p-4 transition hover:bg-[#F4F7F7]">
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
                            <a href="{{ route('niveles.grado-areas', $nivel) }}"
                                class="border-2 border-[#0A1718] px-2 py-1 text-[10px] uppercase font-bold hover:bg-[#0A1718] hover:text-white">
                                Ver
                            </a>

                            <button
                                type="button"
                                x-data
                                x-on:click="$dispatch('open-modal', 'edit-nivel-{{ $nivel->id }}')"
                                class="border-2 border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase hover:bg-[#008080] hover:text-white">
                                Editar
                            </button>

                            <form
                                action="{{ route('niveles.destroy', $nivel) }}"
                                method="POST"
                                onsubmit="return confirm('Seguro que deseas eliminar este nivel?')">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="button"
                                    x-data
                                    x-on:click="$dispatch('open-modal', 'delete-nivel-{{ $nivel->id }}')"
                                    class="border-2 border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase hover:bg-red-500 hover:text-white">
                                    Borrar
                                </button>
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

                    <x-ui.modal name="delete-nivel-{{ $nivel->id }}" title="[ NIVELES / ELIMINAR ]">
                        <div class="space-y-5 p-5">
                            <div class="border-2 border-[#0A1718] bg-[#F4F7F7] p-4">
                                <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#FF7F50]">
                                    Accion irreversible
                                </p>

                                <p class="mt-3 text-sm leading-6 text-[#0A1718]/80">
                                    Estas seguro de que deseas eliminar el nivel
                                    <strong>{{ $nivel->nombre_nivel }}</strong>?
                                </p>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                                <button
                                    type="button"
                                    x-data
                                    x-on:click="$dispatch('close-modal', 'delete-nivel-{{ $nivel->id }}')"
                                    class="border-2 border-[#0A1718] bg-white px-4 py-2 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.14em]">
                                    Cancelar
                                </button>

                                <form method="POST" action="{{ route('niveles.destroy', $nivel) }}">
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