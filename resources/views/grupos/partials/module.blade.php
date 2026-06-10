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

                {{-- ========================= --}}
                {{-- FILTROS (HTMX CORREGIDO) --}}
                {{-- ========================= --}}
                <form
                    hx-get="{{ route('grupos.index') }}"
                    hx-trigger="change"
                    hx-target="#grupos-grid"
                    hx-push-url="true"
                    class="border-b border-[#5C6F72]/30 p-5">

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                        {{-- PERIODO --}}
                        <select
                            name="periodo_id"
                            class="border-2 border-[#0A1718] px-3 py-2">

                            <option value="">Todos los periodos</option>

                            @foreach($periodos as $periodo)
                            <option value="{{ $periodo->id }}"
                                @selected(request('periodo_id')==$periodo->id)>
                                {{ $periodo->nombre_periodo }}
                            </option>
                            @endforeach

                        </select>

                        {{-- NIVEL --}}
                        <select
                            name="nivel_id"
                            class="border-2 border-[#0A1718] px-3 py-2">

                            <option value="">Todos los niveles</option>

                            @foreach($niveles as $nivel)
                            <option value="{{ $nivel->id }}"
                                @selected(request('nivel_id')==$nivel->id)>
                                {{ $nivel->nombre_nivel }}
                            </option>
                            @endforeach

                        </select>

                        {{-- GRADO --}}
                        <select
                            name="grado_id"
                            class="border-2 border-[#0A1718] px-3 py-2">

                            <option value="">Todos los grados</option>

                            @foreach($grados as $grado)
                            @if(!request('nivel_id') || $grado->nivel_id == request('nivel_id'))
                            <option value="{{ $grado->id }}"
                                @selected(request('grado_id')==$grado->id)>
                                {{ $grado->nombre_grado }}
                            </option>
                            @endif
                            @endforeach

                        </select>

                        {{-- SECCION --}}
                        <select
                            name="seccion_id"
                            class="border-2 border-[#0A1718] px-3 py-2">

                            <option value="">Todas las secciones</option>

                            @foreach($secciones as $seccion)
                            <option value="{{ $seccion->id }}"
                                @selected(request('seccion_id')==$seccion->id)>
                                {{ $seccion->nombre_seccion }}
                            </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="mt-4 flex gap-2">

                        <a
                            href="{{ route('grupos.index') }}"
                            hx-get="{{ route('grupos.index') }}"
                            hx-target="#grupos-module"
                            class="border-2 border-red-500 px-4 py-2 text-sm font-bold uppercase text-red-500 hover:bg-red-500 hover:text-white">
                            Limpiar filtros
                        </a>

                    </div>

                </form>

                {{-- ========================= --}}
                {{-- GRID (TARGET HTMX REAL) --}}
                {{-- ========================= --}}
                <div id="grupos-grid"
                    class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 lg:grid-cols-3">

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
                                hx-get="{{ route('grupos.update', $grupo) }}"
                                hx-target="#grupo-edit-form"
                                hx-swap="innerHTML"
                                x-on:click="$dispatch('open-modal', 'edit-grupo')"
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
                    <!-- <x-ui.modal name="edit-grupo-{{ $grupo->id }}" title="[ GRUPO / EDITAR ]">
                        <x-grupos.form
                            :grupo="$grupo"
                            :niveles="$niveles"
                            :cursos="$cursos"
                            :periodos="$periodos"
                            :secciones="$secciones"
                            :action="route('grupos.update', $grupo)"
                            method="PUT"
                            button-text="Guardar Cambios" />
                    </x-ui.modal> -->

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

    <x-ui.modal name="edit-grupo" title="[ GRUPO / EDITAR ]">

        <div id="grupo-edit-form">
            {{-- aquí HTMX inyecta el form --}}
        </div>

    </x-ui.modal>

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