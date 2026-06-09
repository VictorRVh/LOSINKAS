@props([
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

        selectedNivel: null,
        selectedGrado: null,
        selectedCursos: [],
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
        },

        submit(e) {
            if (this.selectedCursos.length === 0) {
                e.preventDefault()
                alert('Selecciona al menos un curso.')
                return
            }
            this.saving = true
        }
    }"
    method="POST"
    action="{{ $action }}"
    hx-post="{{ $action }}"
    hx-target="#grupos-module"
    hx-select="#grupos-module"
    hx-swap="outerHTML"
    x-on:htmx:config-request="
        if (!selectedGrado) {
            alert('Selecciona un grado')
            $event.preventDefault()
            return
        }

        if (!$event.target.seccion_id.value) {
            alert('Selecciona una sección')
            $event.preventDefault()
            return
        }

        if (!$event.target.periodo_id.value) {
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
    @if ($method !== 'POST')
    @method($method)
    @endif

    {{-- GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- NIVEL --}}
        <div>
            <label class="label">Nivel</label>
            <select x-model="selectedNivel" class="input">
                <option value="">Seleccione un nivel</option>
                <template x-for="n in niveles" :key="n.id">
                    <option :value="n.id" x-text="n.nombre_nivel"></option>
                </template>
            </select>
        </div>

        {{-- GRADO --}}
        <div>
            <label class="label">Grado</label>
            <select x-model="selectedGrado" class="input">
                <option value="">Seleccione un grado</option>
                <template x-for="g in gradosForSelectedNivel()" :key="g.id">
                    <option :value="g.id" x-text="g.nombre_grado"></option>
                </template>
            </select>
        </div>

        {{-- PERIODO --}}
        <div>
            <label class="label">Periodo</label>
            <select name="periodo_id" class="input">
                <option value="">Seleccione un periodo</option>
                @foreach ($periodos as $periodo)
                <option value="{{ $periodo->id }}">
                    {{ $periodo->nombre_periodo }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- SECCION --}}
        <div>
            <label class="label">Sección</label>
            <select name="seccion_id" class="input">
                <option value="">Seleccione una sección</option>
                @foreach ($secciones as $seccion)
                <option value="{{ $seccion->id }}">
                    {{ $seccion->nombre_seccion }}
                </option>
                @endforeach
            </select>
        </div>

        <input type="hidden" name="grado_id" x-model="selectedGrado">

    </div>

    {{-- CURSOS --}}
    <div>
        <label class="label">Cursos disponibles</label>

        <div class="border-2 border-[#0A1718] bg-[#F4F7F7] p-4 min-h-[120px]">

            <template x-if="selectedGrado">
                <div class="flex flex-wrap gap-3">
                    <template x-for="c in cursosForSelectedGrado()" :key="c.id">
                        <label
                            class="flex items-center gap-2 border border-[#0A1718] bg-white px-3 py-2 cursor-pointer hover:bg-gray-50">
                            <input
                                type="checkbox"
                                name="curso_ids[]"
                                :value="c.id"
                                x-model="selectedCursos">
                            <span x-text="c.nombre_curso"></span>
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
    <label class="flex items-center gap-3 text-xs font-bold uppercase">
        <input
            type="checkbox"
            name="activo"
            value="1"
            checked
            class="h-5 w-5 rounded-none border-2 border-[#0A1718]">
        Activo
    </label>

    {{-- BOTON --}}
    <x-ui.button
        type="submit"
        color="teal"
        class="w-full"
        x-bind:disabled="saving">
        <span x-show="!saving">{{ $buttonText }}</span>
        <span x-show="saving">Guardando...</span>
    </x-ui.button>

</form>