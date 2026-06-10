<div id="matriculas-module">
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- FLASH --}}
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
                            [ GRID / MATRÍCULAS ]
                        </p>

                        <h3 class="mt-1 text-lg font-bold uppercase text-[#0A1718]">
                            Todas las matrículas
                        </h3>
                    </div>

                    <x-ui.button
                        type="button"
                        color="teal"
                        x-data
                        x-on:click="$dispatch('open-modal', 'create-matricula')">
                        Nueva Matrícula
                    </x-ui.button>
                </div>

                {{-- GRID --}}
                <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 lg:grid-cols-3">

                    @forelse ($matriculas as $matricula)
                    <div class="border-2 border-[#0A1718] bg-white p-4 transition hover:-translate-y-1 hover:shadow-lg">

                        <h3 class="font-bold uppercase tracking-wide">
                            <!-- {{ $matricula->estudiante->apellido_paterno }} -->
                            {{ $matricula->estudiante->apellidos }},
                            {{ $matricula->estudiante->nombres }}
                        </h3>

                        <div class="mt-2 text-sm text-[#5C6F72] space-y-1">
                            <p>
                                <strong>Grupo:</strong>
                                {{ $matricula->grupo->nombre_grupo }}
                            </p>
                            <p>
                                <strong>Periodo:</strong>
                                {{ $matricula->grupo->periodo->nombre_periodo }}
                            </p>
                            <p>
                                <strong>Sección:</strong>
                                {{ $matricula->grupo->seccion->nombre_seccion }}
                            </p>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <button
                                type="button"
                                x-data
                                x-on:click="$dispatch('open-modal', 'delete-matricula-{{ $matricula->id }}')"
                                class="border-2 border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase hover:bg-red-500 hover:text-white">
                                Eliminar
                            </button>
                        </div>
                    </div>

                    {{-- MODAL DELETE --}}
                    <x-ui.delete-modal
                        name="delete-matricula-{{ $matricula->id }}"
                        title="[ MATRÍCULA / ELIMINAR ]"
                        :item-name="$matricula->estudiante->nombre"
                        :action="route('matriculas.destroy', $matricula)"
                        target="#matriculas-module" />

                    @empty
                    <div class="col-span-full border-2 border-[#0A1718] bg-[#F4F7F7] p-5 text-sm text-[#5C6F72]">
                        No hay matrículas registradas.
                    </div>
                    @endforelse
                </div>

                {{-- PAGINACIÓN --}}
                <div class="border-t border-[#5C6F72]/30 px-5 py-4">
                    {{ $matriculas->links() }}
                </div>
            </section>
        </div>
    </div>

    {{-- MODAL CREATE --}}
    <x-ui.modal name="create-matricula" title="[ MATRÍCULA / NUEVA ]" :show="$errors->any()">
        <x-matriculas.form
            :estudiantes="$estudiantes"
            :periodos="$periodos"
            :grados="$grados"
            :secciones="$secciones"
            :grupos="$grupos"
            :action="route('matriculas.store')" />
    </x-ui.modal>
</div>