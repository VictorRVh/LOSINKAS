<div id="grupos-module">
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- MENSAJES --}}
            @if (session('success'))
            <div class="mb-4 border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 text-sm font-bold text-[#008080]">
                {{ session('success') }}
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
                            Grupos por sección
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

                {{-- FILTROS --}}
                <form
                    hx-get="{{ route('grupos.index') }}"
                    hx-target="#grupos-module"
                    hx-swap="outerHTML"
                    hx-push-url="true"
                    class="border-b border-[#5C6F72]/30 p-5">

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                        {{-- PERIODO --}}
                        <select
                            name="periodo_id"
                            class="border-2 border-[#0A1718] px-3 py-2">
                            <option value="">Todos los periodos</option>
                            @foreach ($periodos as $periodo)
                            <option
                                value="{{ $periodo->id }}"
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

                            @foreach ($niveles as $nivel)
                            <option
                                value="{{ $nivel->id }}"
                                @selected(($filtros['nivel_id'] ?? null)==$nivel->id)>
                                {{ $nivel->nombre_nivel }}
                            </option>
                            @endforeach
                        </select>

                        {{-- GRADO --}}
                        <select
                            id="grado_id"
                            name="grado_id"
                            class="border-2 border-[#0A1718] px-3 py-2">

                            <option value="">Todos los grados</option>
                        </select>

                    </div>

                    <div class="mt-4 flex gap-2">
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
                            Limpiar
                        </a>
                    </div>
                </form>

                {{-- CONTENIDO --}}
                <div class="p-5 space-y-6">

                    @forelse ($padres as $padre)
                    <div class="border-2 border-[#0A1718] p-4">

                        {{-- CABECERA --}}
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <h4 class="font-bold uppercase">
                                    {{ $padre->grado->nombre_grado }} -
                                    {{ $padre->seccion->nombre_seccion }}
                                </h4>
                                <p class="text-xs text-[#5C6F72]">
                                    Periodo: {{ $padre->periodo->nombre_periodo }}
                                    | Nivel: {{ $padre->grado->nivel->nombre_nivel }}
                                </p>
                            </div>

                            {{-- EDITAR --}}
                            <div class="flex gap-2">

                                {{-- EDITAR CURSOS --}}
                                <button
                                    hx-get="{{ route('padre-grupos.edit', $padre) }}"
                                    hx-target="#padre-grupo-edit-form"
                                    hx-swap="innerHTML"
                                    x-data
                                    x-on:click="$dispatch('open-modal', 'edit-padre-grupo')"
                                    class="border-2 border-[#0A1718] px-3 py-2 text-xs font-bold uppercase
               hover:bg-[#008080] hover:text-white">
                                    Editar cursos
                                </button>

                                {{-- ELIMINAR SECCIÓN --}}
                                <button
                                    hx-delete="{{ route('padre-grupos.destroy', $padre) }}"
                                    hx-confirm="¿Eliminar esta sección y todos sus cursos?"
                                    hx-target="#grupos-module"
                                    hx-swap="outerHTML"
                                    class="border-2 border-red-500 px-3 py-2 text-xs font-bold uppercase
               text-red-500 hover:bg-red-500 hover:text-white">
                                    Eliminar
                                </button>

                            </div>
                        </div>

                        {{-- CURSOS --}}
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($padre->grupos as $grupo)
                            <div class="border-2 border-[#0A1718] p-3 bg-[#F9FBFB]">
                                <h5 class="font-bold uppercase text-sm">
                                    {{ $grupo->curso->nombre_curso }}
                                </h5>
                                <span class="text-[10px] border px-2 py-1 inline-block mt-2">
                                    {{ $grupo->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            @endforeach
                        </div>

                    </div>
                    @empty
                    <p class="text-center text-sm text-[#5C6F72]">
                        No hay registros
                    </p>
                    @endforelse

                </div>

            </section>
        </div>
    </div>

    {{-- MODAL CREATE --}}
    <x-ui.modal name="create-grupo" title="[ GRUPO / NUEVO ]">
        <x-grupos.form
            :niveles="$niveles"
            :periodos="$periodos"
            :cursos="$cursos"
            :secciones="$secciones"
            :action="route('grupos.store')"
            button-text="Crear Grupo" />
    </x-ui.modal>

    {{-- MODAL EDITAR SECCIÓN --}}
    <x-ui.modal name="edit-padre-grupo" title="[ SECCIÓN / EDITAR CURSOS ]">
        <div id="padre-grupo-edit-form">
            {{-- HTMX cargará aquí el form --}}
        </div>
    </x-ui.modal>

</div>