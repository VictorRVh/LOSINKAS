<!-- @props([
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

        estudianteEncontrado: false,
        buscandoEstudiante: false,

        estudiante: {
        id: '',
        dni: '',
        nombres: '',
        apellidos: '',
        email: '',
        telefono: '',
        fecha_nacimiento: ''
        },

        async buscarEstudiante() {

        if (this.estudiante.dni.length !== 8) {
        return;
        }

        this.buscandoEstudiante = true;

        try {

        const response = await fetch(
        `/estudiantes/buscar/${this.estudiante.dni}`
        );

        const data = await response.json();

        if (data.existe) {

        this.estudianteEncontrado = true;

        this.estudiante.id = data.estudiante.id;
        this.estudiante.nombres = data.estudiante.nombres;
        this.estudiante.apellidos = data.estudiante.apellidos;
        this.estudiante.email = data.estudiante.email ?? '';
        this.estudiante.telefono = data.estudiante.telefono ?? '';
        this.estudiante.fecha_nacimiento = data.estudiante.fecha_nacimiento ?? '';

        } else {

        this.estudianteEncontrado = false;

        this.estudiante.id = '';
        this.estudiante.nombres = '';
        this.estudiante.apellidos = '';
        this.estudiante.email = '';
        this.estudiante.telefono = '';
        this.estudiante.fecha_nacimiento = '';

        }

        } finally {
        this.buscandoEstudiante = false;
        }
        },

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

        if (!this.estudiante.dni) {
        e.preventDefault()
        alert('Ingrese DNI')
        return
        }

        if (!this.estudiante.nombres) {
        e.preventDefault()
        alert('Ingrese nombres')
        return
        }

        if (!this.estudiante.apellidos) {
        e.preventDefault()
        alert('Ingrese apellidos')
        return
        }

        if (this.gruposSeleccionados.length === 0) {
        e.preventDefault()
        alert('Seleccione al menos un grupo')
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
    {{-- ================= ESTUDIANTE ================= --}}
    <div class="space-y-4 border-2 border-[#0A1718] p-4">

        <h3 class="font-bold">
            Datos del estudiante
        </h3>

        {{-- DNI --}}
        <div>
            <label class="label">DNI</label>

            <input
                x-model="estudiante.dni"
                @blur="buscarEstudiante()"
                maxlength="8"
                type="text"
                class="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3">

            <p
                x-show="buscandoEstudiante"
                class="text-sm text-blue-600">
                Buscando estudiante...
            </p>

            <p
                x-show="estudianteEncontrado"
                class="text-sm text-green-600">
                Estudiante encontrado.
            </p>

            <p
                x-show="!estudianteEncontrado && estudiante.dni.length === 8 && !buscandoEstudiante"
                class="text-sm text-orange-600">
                Estudiante no registrado. Complete los datos.
            </p>
        </div>

        <input
            type="hidden"
            name="estudiante_id"
            x-model="estudiante.id">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="label">Nombres</label>

                <input
                    name="nombres"
                    x-model="estudiante.nombres"
                    type="text"
                    class="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3">
            </div>

            <div>
                <label class="label">Apellidos</label>

                <input
                    name="apellidos"
                    x-model="estudiante.apellidos"
                    type="text"
                    class="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3">
            </div>

            <div>
                <label class="label">Correo</label>

                <input
                    name="email"
                    x-model="estudiante.email"
                    type="email"
                    class="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3">
            </div>

            <div>
                <label class="label">Teléfono</label>

                <input
                    name="telefono"
                    x-model="estudiante.telefono"
                    type="text"
                    class="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3">
            </div>

            <div>
                <label class="label">Fecha nacimiento</label>

                <input
                    name="fecha_nacimiento"
                    x-model="estudiante.fecha_nacimiento"
                    type="date"
                    class="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3">
            </div>

        </div>

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

</form> -->
x-data="{
paso: 1
}"
@props([
    'matricula' => null,
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
        paso: 1,
        saving: false,

        grupos: @js($grupos),

        selectedPeriodo: '',
        selectedGrado: '',
        selectedSeccion: '',

        gruposSeleccionados: [],

        estudianteEncontrado: false,
        buscandoEstudiante: false,

        estudiante: {
            id: '',
            dni: '',
            nombres: '',
            apellidos: '',
            email: '',
            telefono: '',
            fecha_nacimiento: ''
        },

        async buscarEstudiante() {

            if (this.estudiante.dni.length !== 8) return;

            this.buscandoEstudiante = true;

            try {

                const response = await fetch(
                    `/estudiantes/buscar/${this.estudiante.dni}`
                );

                const data = await response.json();

                if (data.existe) {

                    this.estudianteEncontrado = true;

                    Object.assign(this.estudiante, data.estudiante);

                } else {

                    this.estudianteEncontrado = false;

                    this.estudiante.id = '';
                    this.estudiante.nombres = '';
                    this.estudiante.apellidos = '';
                    this.estudiante.email = '';
                    this.estudiante.telefono = '';
                    this.estudiante.fecha_nacimiento = '';
                }

            } finally {
                this.buscandoEstudiante = false;
            }
        },

        gruposFiltrados() {

            if (
                !this.selectedPeriodo ||
                !this.selectedGrado ||
                !this.selectedSeccion
            ) {
                return [];
            }

            return this.grupos.filter(g =>
                g.periodo_id == this.selectedPeriodo &&
                g.grado_id == this.selectedGrado &&
                g.seccion_id == this.selectedSeccion
            );
        },

        siguientePaso() {

            if (this.paso === 1) {

                if (this.gruposSeleccionados.length === 0) {
                    alert('Seleccione al menos un grupo');
                    return;
                }

                this.paso = 2;
            }
        },

        submit(e) {

            if (!this.estudiante.dni) {
                e.preventDefault();
                return;
            }

            if (!this.estudiante.nombres) {
                e.preventDefault();
                return;
            }

            if (!this.estudiante.apellidos) {
                e.preventDefault();
                return;
            }

            this.saving = true;
        }
    }"

    x-on:submit="submit($event)"
    x-on:htmx:after-request="saving = false"

    method="POST"
    action="{{ $action }}"

    hx-post="{{ $action }}"
    hx-target="#matriculas-module"
    hx-select="#matriculas-module"
    hx-swap="outerHTML"

    class="space-y-6 p-5"
>
    @csrf

    @if (($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    {{-- PASO 1 --}}
    <div x-show="paso === 1">
        <x-matriculas.datos-academicos
            :periodos="$periodos"
            :grados="$grados"
            :secciones="$secciones"
            :grupos="$grupos"
        />
    </div>

    {{-- PASO 2 --}}
    <div x-show="paso === 2">
        <x-matriculas.datos-estudiante />
    </div>

    <div class="flex justify-between pt-4">

        <button
            type="button"
            x-show="paso > 1"
            @click="paso--"
            class="px-4 py-2 border"
        >
            Anterior
        </button>

        <button
            type="button"
            x-show="paso < 2"
            @click="siguientePaso()"
            class="px-4 py-2 bg-blue-600 text-white"
        >
            Siguiente
        </button>

        <x-ui.button
            x-show="paso === 2"
            type="submit"
            color="teal"
            x-bind:disabled="saving"
        >
            <span x-show="!saving">
                {{ $buttonText }}
            </span>

            <span x-show="saving">
                Guardando...
            </span>
        </x-ui.button>

    </div>

</form>