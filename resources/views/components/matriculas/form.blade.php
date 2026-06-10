@props([
'matricula' => null,
'estudiantes',
'periodos',
'grados',
'secciones',
'grupos',
'action',
'method' => 'POST',
'buttonText' => 'Guardar',
])

<form
    x-data="{
        saving: false,

        grupos: @js($grupos),

        selectedPeriodo: '',
        selectedGrado: '',
        selectedSeccion: '',

        gruposSeleccionados: [],

        gruposFiltrados() {
            if (!this.selectedPeriodo || !this.selectedGrado || !this.selectedSeccion) {
                return []
            }

            return this.grupos.filter(g =>
                g.periodo_id == this.selectedPeriodo &&
                g.grado_id == this.selectedGrado &&
                g.seccion_id == this.selectedSeccion
            )
        },
        submit(e) {
            if (!$refs.estudiante.value) {
                e.preventDefault()
                alert('Selecciona un estudiante')
                return
            }

            if (this.gruposSeleccionados.length === 0) {
                e.preventDefault()
                alert('Selecciona al menos un grupo')
                return
            }

            this.saving = true
        }
    }"
    x-on:submit="submit($event)"
    x-on:htmx:after-request="saving = false"
    class="space-y-5 p-5"

    method="POST"
    action="{{ $action }}"
    hx-post="{{ $action }}"
    hx-target="#matriculas-module"
    hx-select="#matriculas-module"
    hx-swap="outerHTML">
    @csrf

    @if ($method !== 'POST')
    @method($method)
    @endif

    {{-- ================= ESTUDIANTE ================= --}}
    <div>
        <label class="label">Estudiante</label>

        <select
            x-ref="estudiante"
            name="estudiante_id"
            class="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3">
            <option value="">Seleccione...</option>

            @foreach ($estudiantes as $estudiante)
            <option value="{{ $estudiante->id }}">
                {{ $estudiante->apellidos }}, {{ $estudiante->nombres }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- ================= FILTROS ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div>
            <label class="label">Periodo</label>
            <select x-model="selectedPeriodo" class="input">
                <option value="">Todos</option>
                @foreach ($periodos as $periodo)
                <option value="{{ $periodo->id }}">
                    {{ $periodo->nombre_periodo }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="label">Grado</label>
            <select x-model="selectedGrado" class="input">
                <option value="">Todos</option>
                @foreach ($grados as $grado)
                <option value="{{ $grado->id }}">
                    {{ $grado->nombre_grado }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="label">Sección</label>
            <select x-model="selectedSeccion" class="input">
                <option value="">Todos</option>
                @foreach ($secciones as $seccion)
                <option value="{{ $seccion->id }}">
                    {{ $seccion->nombre_seccion }}
                </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- ================= GRUPOS ================= --}}
    <div class="border-2 border-[#0A1718] bg-[#F4F7F7] p-4 min-h-[120px]">

        <template x-if="gruposFiltrados().length > 0">
            <div class="flex flex-wrap gap-3">

                <template x-for="g in gruposFiltrados()" :key="g.id">
                    <label class="flex items-center gap-2 border border-[#0A1718] bg-white px-3 py-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="grupo_ids[]"
                            :value="g.id"
                            x-model="gruposSeleccionados">
                        <span x-text="g.nombre_grupo"></span>
                    </label>
                </template>

            </div>
        </template>

        <template x-if="gruposFiltrados().length === 0">
            <p class="text-sm text-[#5C6F72]">
                No hay grupos para los filtros seleccionados.
            </p>
        </template>

    </div>

    {{-- ================= BOTÓN ================= --}}
    <x-ui.button
        type="submit"
        color="teal"
        class="w-full"
        x-bind:disabled="saving">
        <span x-show="!saving">{{ $buttonText }}</span>

        <span x-show="saving" x-cloak>
            Matriculando...
        </span>
    </x-ui.button>

</form>