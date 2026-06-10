@props([
    'grupo' => null,
    'action',
    'method' => 'POST',
    'buttonText' => 'Guardar',
    'niveles' => [],
    'cursos' => [],
    'periodos' => [],
    'secciones' => [],
])

<form
    x-data="{
        niveles: @js($niveles),

        cursos: @js(
            collect($cursos)->map(fn($c) => [
                'id' => $c['id'] ?? $c->id,
                'nombre_curso' => $c['nombre_curso'] ?? $c->nombre_curso,
                'grado_area_id' => $c['grado_area_id'] ?? $c->grado_area_id,
            ])->values()
        ),

        selectedNivel: @js(
            $grupo?->curso?->gradoArea?->nivel_id
        ),

        selectedGrado: @js(
            $grupo?->curso?->grado_area_id
        ),

        selectedPeriodo: @js(
            $grupo?->periodo_id
        ),

        selectedCursos: @js(
            $grupo ? [$grupo->curso_id] : []
        ),

        saving: false,

        gradosForSelectedNivel() {
            const nivel = this.niveles.find(
                n => Number(n.id) === Number(this.selectedNivel)
            )

            return nivel ? nivel.grado_areas : []
        },

        cursosForSelectedGrado() {
            return this.cursos.filter(
                c => Number(c.grado_area_id) === Number(this.selectedGrado)
            )
        }
    }"
    method="POST"
    action="{{ $action }}"
    hx-post="{{ $action }}"
    hx-target="#grupos-module"
    hx-select="#grupos-module"
    hx-swap="outerHTML"
    x-on:htmx:config-request="
        const seccion = document.getElementById('seccion_id')
        const periodo = document.getElementById('periodo_id')

        if (!selectedGrado) {
            alert('Selecciona un grado')
            $event.preventDefault()
            return
        }

        if (!seccion?.value) {
            alert('Selecciona una sección')
            $event.preventDefault()
            return
        }

        if (!periodo?.value) {
            alert('Selecciona un periodo')
            $event.preventDefault()
            return
        }

        if (selectedCursos.length === 0) {
            alert('Selecciona al menos un curso')
            $event.preventDefault()
            return
        }

        $event.detail.parameters.grado_id = selectedGrado
    "
    x-on:htmx:after-request="saving = false"
    class="space-y-5 p-5">

    @csrf

    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- NIVEL --}}
        <div>
            <label class="label">Nivel</label>

            <select
                x-model="selectedNivel"
                class="input">

                <option value="">
                    Seleccione un nivel
                </option>

                <template
                    x-for="nivel in niveles"
                    :key="nivel.id">

                    <option
                        :value="nivel.id"
                        x-text="nivel.nombre_nivel">
                    </option>

                </template>

            </select>
        </div>

        {{-- GRADO --}}
        <div>
            <label class="label">Grado</label>

            <select
                id="grado_id"
                name="grado_id"
                x-model="selectedGrado"
                class="input"
                hx-get="{{ route('grupos.index') }}"
                hx-trigger="change"
                hx-target="#seccion_id"
                hx-include="closest form [name='periodo_id']">

                <option value="">
                    Seleccione un grado
                </option>

                <template
                    x-for="grado in gradosForSelectedNivel()"
                    :key="grado.id">

                    <option
                        :value="grado.id"
                        x-text="grado.nombre_grado">
                    </option>

                </template>

            </select>
        </div>

        {{-- PERIODO --}}
        <div>
            <label class="label">Periodo</label>

            <select
                id="periodo_id"
                name="periodo_id"
                x-model="selectedPeriodo"
                class="input"
                hx-get="{{ route('grupos.index') }}"
                hx-trigger="change"
                hx-target="#seccion_id"
                hx-include="closest form [name='grado_id']">

                <option value="">
                    Seleccione un periodo
                </option>

                @foreach($periodos as $periodo)
                    <option value="{{ $periodo->id }}">
                        {{ $periodo->nombre_periodo }}
                    </option>
                @endforeach

            </select>
        </div>

        {{-- SECCION --}}
        <div>
            <label class="label">Sección</label>

            <select
                id="seccion_id"
                name="seccion_id"
                class="input">

                <option value="">
                    Seleccione una sección
                </option>

                @foreach($secciones as $seccion)
                    <option
                        value="{{ $seccion->id }}"
                        @selected($grupo?->seccion_id == $seccion->id)>
                        {{ $seccion->nombre_seccion }}
                    </option>
                @endforeach

            </select>
        </div>

    </div>

    {{-- CURSOS --}}
    <div>

        <label class="label">
            Cursos disponibles
        </label>

        <div class="border-2 border-[#0A1718] bg-[#F4F7F7] p-4 min-h-[120px]">

            <template x-if="selectedGrado">

                <div class="flex flex-wrap gap-3">

                    <template
                        x-for="curso in cursosForSelectedGrado()"
                        :key="curso.id">

                        <label
                            class="flex items-center gap-2 border border-[#0A1718] bg-white px-3 py-2 cursor-pointer">

                            <input
                                type="checkbox"
                                name="curso_ids[]"
                                :value="curso.id"
                                x-model="selectedCursos">

                            <span
                                x-text="curso.nombre_curso">
                            </span>

                        </label>

                    </template>

                </div>

            </template>

            <template x-if="!selectedGrado">

                <p class="text-sm text-[#5C6F72]">
                    Selecciona un grado para ver sus cursos.
                </p>

            </template>

        </div>

    </div>

    {{-- ACTIVO --}}
    <label
        class="flex items-center gap-3 text-xs font-bold uppercase">

        <input
            type="checkbox"
            name="activo"
            value="1"
            @checked($grupo?->activo ?? true)
            class="h-5 w-5 rounded-none border-2 border-[#0A1718]">

        Activo

    </label>

    <x-ui.button
        type="submit"
        color="teal"
        class="w-full"
        x-bind:disabled="saving">

        <span x-show="!saving">
            {{ $buttonText }}
        </span>

        <span x-show="saving">
            Guardando...
        </span>

    </x-ui.button>

</form>