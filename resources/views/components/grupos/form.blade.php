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
        niveles: @js(
            collect($niveles)->map(fn($n) => [
                'id'          => $n->id,
                'nombre_nivel'=> $n->nombre_nivel,
                'grado_areas' => collect($n->gradoAreas ?? [])->map(fn($g) => [
                    'id'          => $g->id,
                    'nombre_grado'=> $g->nombre_grado,
                ])->values()->all(),
            ])->values()->all()
        ),

        cursos: @js(
            collect($cursos)->map(fn($c) => [
                'id'           => $c->id,
                'nombre_curso' => $c->nombre_curso,
                'grado_area_id'=> $c->grado_area_id,
            ])->values()->all()
        ),

        selectedNivel:   @js($grupo?->curso?->gradoArea?->nivel_id ?? ''),
        selectedGrado:   @js($grupo?->curso?->grado_area_id ?? ''),
        selectedPeriodo: @js($grupo?->periodo_id ?? ''),
        selectedSeccion: @js($grupo?->seccion_id ?? ''),
        selectedCursos:  @js($grupo ? [$grupo->curso_id] : []),
        saving: false,
        errors: {},

        gradosForSelectedNivel() {
            const nivel = this.niveles.find(n => Number(n.id) === Number(this.selectedNivel))
            return nivel ? nivel.grado_areas : []
        },

        cursosForSelectedGrado() {
            return this.cursos.filter(c => Number(c.grado_area_id) === Number(this.selectedGrado))
        },

        validate() {
            this.errors = {}

            if (!this.selectedPeriodo)           this.errors.periodo  = 'Selecciona un periodo.'
            if (!this.selectedGrado)             this.errors.grado    = 'Selecciona un grado.'
            if (!this.selectedSeccion)           this.errors.seccion  = 'Selecciona una sección.'
            if (this.selectedCursos.length === 0) this.errors.cursos  = 'Selecciona al menos un curso.'

            return Object.keys(this.errors).length === 0
        },

        submit(event) {
            if (!this.validate()) {
                event.preventDefault()
                return
            }
            this.saving = true
        }
    }"

    x-on:submit="submit($event)"
    x-on:htmx:after-request="saving = false"

    method="POST"
    action="{{ $action }}"
    @if($method==='PATCH' )
    hx-patch="{{ $action }}"
    @else
    hx-post="{{ $action }}"
    @endif
    hx-target="#grupos-module"
    hx-select="#grupos-module"
    hx-swap="outerHTML"
    class="space-y-5 p-5">

    @csrf

    @if($method !== 'POST')
    @method($method)
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        {{-- NIVEL --}}
        <div>
            <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                Nivel
            </label>

            <select
                name="nivel_id"
                x-model="selectedNivel"
                x-on:change="selectedGrado = ''; selectedCursos = []"
                class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">

                <option value="">Seleccione un nivel</option>

                @foreach($niveles as $nivel)
                <option value="{{ $nivel->id }}"
                    @selected($grupo?->curso?->gradoArea?->nivel_id == $nivel->id)>
                    {{ $nivel->nombre_nivel }}
                </option>
                @endforeach

            </select>
        </div>

        {{-- GRADO --}}
        <div>
            <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                Grado
            </label>

            <select
                name="grado_id"
                x-model="selectedGrado"
                x-on:change="selectedCursos = []"
                class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none"
                :class="{ 'border-red-500': errors.grado }">

                <option value="">Seleccione un grado</option>

                @foreach($niveles as $nivel)
                @foreach($nivel->gradoAreas as $grado)

                <option
                    value="{{ $grado->id }}"
                    data-nivel="{{ $nivel->id }}"
                    x-show="Number(selectedNivel) === {{ $nivel->id }}"
                    @selected($grupo?->curso?->grado_area_id == $grado->id)>

                    {{ $grado->nombre_grado }}

                </option>

                @endforeach
                @endforeach

            </select>

            <p x-show="errors.grado"
                x-text="errors.grado"
                x-cloak
                class="mt-1 text-xs text-red-600"></p>
        </div>

        {{-- PERIODO --}}
        <div>
            <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                Periodo
            </label>

            <select
                name="periodo_id"
                x-model="selectedPeriodo"
                class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none"
                :class="{ 'border-red-500': errors.periodo }">

                <option value="">Seleccione un periodo</option>

                @foreach($periodos as $periodo)
                <option value="{{ $periodo->id }}">
                    {{ $periodo->nombre_periodo }}
                </option>
                @endforeach

            </select>

            <p x-show="errors.periodo"
                x-text="errors.periodo"
                x-cloak
                class="mt-1 text-xs text-red-600"></p>
        </div>

        {{-- SECCION --}}
        <div>
            <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                Sección
            </label>

            <select
                name="seccion_id"
                x-model="selectedSeccion"
                class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none"
                :class="{ 'border-red-500': errors.seccion }">

                <option value="">Seleccione una sección</option>

                @foreach($secciones as $seccion)
                <option value="{{ $seccion->id }}"
                    @selected($grupo?->seccion_id == $seccion->id)>

                    {{ $seccion->nombre_seccion }}

                </option>
                @endforeach

            </select>

            <p x-show="errors.seccion"
                x-text="errors.seccion"
                x-cloak
                class="mt-1 text-xs text-red-600"></p>
        </div>

    </div>

    {{-- CURSOS --}}
    <div>
        <label class="label">Cursos disponibles</label>

        <div class="min-h-[120px] border-2 border-[#0A1718] bg-[#F4F7F7] p-4"
            :class="{ 'border-red-500': errors.cursos }">

            <template x-if="selectedGrado">
                <div>
                    <template x-if="cursosForSelectedGrado().length === 0">
                        <p class="text-sm text-[#5C6F72]">No hay cursos para este grado.</p>
                    </template>

                    <div class="flex flex-wrap gap-3">
                        <template x-for="curso in cursosForSelectedGrado()" :key="curso.id">
                            <label class="flex cursor-pointer items-center gap-2 border bg-white px-3 py-2 hover:border-[#008080]"
                                :class="{ 'border-[#008080] bg-teal-50': selectedCursos.includes(curso.id) }">
                                <input
                                    type="checkbox"
                                    name="curso_ids[]"
                                    :value="curso.id"
                                    x-model="selectedCursos">
                                <span x-text="curso.nombre_curso" class="text-sm"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="!selectedGrado">
                <p class="text-sm text-[#5C6F72]">Selecciona un grado para ver sus cursos.</p>
            </template>

        </div>
        <p x-show="errors.cursos" x-text="errors.cursos" x-cloak class="mt-1 text-xs text-red-600"></p>
    </div>

    {{-- ACTIVO --}}
    <label class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
        <input
            type="checkbox"
            name="activo"
            value="1"
            @checked($grupo?->activo ?? true)
        class="h-5 w-5 border-2 border-[#0A1718]">
        Activo
    </label>

    <x-ui.button
        type="submit"
        color="teal"
        class="w-full disabled:translate-x-[4px] disabled:translate-y-[4px] disabled:cursor-not-allowed disabled:opacity-70 disabled:shadow-none"
        x-bind:disabled="saving">
        <span x-show="!saving">{{ $buttonText }}</span>
        <span x-show="saving" x-cloak class="inline-flex items-center justify-center gap-2">
            <span class="h-2 w-2 animate-pulse bg-white"></span>
            Guardando...
        </span>
    </x-ui.button>

</form>