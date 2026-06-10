<div id="grupos-module">
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session('status') || session('success'))
            <div class="mb-4 border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 text-sm font-bold text-[#008080]">
                {{ session('status') ?? session('success') }}
            </div>
            @endif

            <section class="border-2 border-[#0A1718] bg-white">

                {{-- HEADER --}}
                <div class="flex flex-col gap-4 border-b border-[#5C6F72]/30 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">[ GRID / GRUPOS ]</p>
                        <h3 class="mt-1 text-lg font-bold uppercase text-[#0A1718]">Todos los grupos</h3>
                    </div>
                    <x-ui.button type="button" color="teal" x-data x-on:click="$dispatch('open-modal', 'create-grupo')">
                        Crear Grupo
                    </x-ui.button>
                </div>

                {{-- FILTROS --}}
                <form
                    id="filtros-form"
                    hx-get="{{ route('grupos.index') }}"
                    hx-target="#grupos-module"
                    hx-swap="outerHTML"
                    hx-push-url="true"
                    class="border-b border-[#5C6F72]/30 p-5">

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                        {{-- PERIODO --}}
                        <select
                            id="periodo_id"
                            name="periodo_id"
                            hx-get="{{ route('grupos.secciones-disponibles') }}"
                            hx-target="#seccion_id"
                            hx-swap="innerHTML"
                            hx-trigger="change"
                            hx-include="#grado_id"
                            class="border-2 border-[#0A1718] px-3 py-2">
                            <option value="">Todos los periodos</option>
                            @foreach($periodos as $periodo)
                            <option value="{{ $periodo->id }}"
                                @selected(($filtros['periodo_id'] ?? null)==$periodo->id)>
                                {{ $periodo->nombre_periodo }}
                            </option>
                            @endforeach
                        </select>

                        {{-- NIVEL --}}
                        <select
                            name="nivel_id"
                            hx-get="{{ route('grupos.grados-disponibles') }}"
                            hx-target="#grado_id"
                            hx-swap="innerHTML"
                            hx-trigger="change"
                            class="border-2 border-[#0A1718] px-3 py-2">
                            <option value="">Todos los niveles</option>
                            @foreach($niveles as $nivel)
                            <option value="{{ $nivel->id }}"
                                @selected(($filtros['nivel_id'] ?? null)==$nivel->id)>
                                {{ $nivel->nombre_nivel }}
                            </option>
                            @endforeach
                        </select>

                        {{-- GRADO --}}
                        <select
                            id="grado_id"
                            name="grado_id"
                            hx-get="{{ route('grupos.secciones-disponibles') }}"
                            hx-target="#seccion_id"
                            hx-swap="innerHTML"
                            hx-trigger="change"
                            hx-include="#periodo_id"
                            class="border-2 border-[#0A1718] px-3 py-2">
                            <option value="">Todos los grados</option>
                            @foreach($grados_filtro as $grado)
                            <option value="{{ $grado->id }}" @selected(request('grado_id')==$grado->id)>
                                {{ $grado->nombre_grado }}
                            </option>
                            @endforeach
                        </select>

                        {{-- SECCION --}}
                        <select id="seccion_id" name="seccion_id" class="border-2 border-[#0A1718] px-3 py-2">
                            <option value="">Todas las secciones</option>
                            @foreach($secciones_filtro as $seccion)
                            <option value="{{ $seccion->id }}" @selected(request('seccion_id')==$seccion->id)>
                                {{ $seccion->nombre_seccion }}
                            </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="mt-4 flex gap-2">

                        {{-- BOTÓN FILTRAR --}}
                        <button
                            type="submit"
                            class="border-2 border-[#0A1718] bg-[#008080] px-4 py-2 text-sm font-bold uppercase text-white hover:bg-[#006666]">
                            Filtrar
                        </button>
                        <a

                            href="{{ route('grupos.index') }}"
                            hx-get="{{ route('grupos.index') }}"
                            hx-target="#grupos-module"
                            hx-swap="outerHTML"
                            hx-push-url="true"
                            class="border-2 border-red-500 px-4 py-2 text-sm font-bold uppercase text-red-500 hover:bg-red-500 hover:text-white">
                            Limpiar filtros
                        </a>

                    </div>
                </form>

                {{-- GRID --}}
                @include('grupos.partials.grid', ['grupos' => $grupos])

                {{-- PAGINACIÓN --}}
                <div class="border-t border-[#5C6F72]/30 px-5 py-4">
                    {{ $grupos->links() }}
                </div>

            </section>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <x-ui.modal name="edit-grupo" title="[ GRUPO / EDITAR ]">
        <div id="grupo-edit-form">
            {{-- HTMX inyecta el form aquí --}}
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