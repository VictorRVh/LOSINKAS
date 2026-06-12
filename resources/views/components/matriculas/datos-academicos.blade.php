<div class="grid md:grid-cols-4 gap-4">

    {{-- PERIODO --}}
    <div>
        <label class="label">Periodo</label>

        <select
            name="periodo_id"
            x-model="selectedPeriodo"
            class="input">

            <option value="">Seleccione</option>

            @foreach($periodos as $periodo)
            <option value="{{ $periodo->id }}">
                {{ $periodo->nombre_periodo }}
            </option>
            @endforeach

        </select>
    </div>

    {{-- NIVEL --}}
    <div>
        <label class="label">Nivel</label>

        <select
            name="nivel_id"
            x-model="selectedNivel"
            class="input">

            <option value="">Seleccione</option>

            @foreach($niveles as $nivel)
            <option value="{{ $nivel->id }}">
                {{ $nivel->nombre_nivel }}
            </option>
            @endforeach

        </select>
    </div>

    {{-- GRADO --}}
    <div>
        <label class="label">Grado</label>

        <select
            name="grado_id"
            x-model="selectedGrado"
            class="input">

            <option value="">Seleccione</option>

            <template
                x-for="grado in gradosDisponibles()"
                :key="grado.id">

                <option
                    :value="grado.id"
                    x-text="grado.nombre_grado">
                </option>

            </template>

        </select>
    </div>

    {{-- SECCION --}}
    <div>
        <label class="label">Sección</label>

        <select
            name="seccion_id"
            x-model="selectedSeccion"
            class="input">

            <option value="">Seleccione</option>

            <template
                x-for="seccion in seccionesDisponibles()"
                :key="seccion.id">

                <option
                    :value="seccion.id"
                    x-text="seccion.nombre_seccion">
                </option>

            </template>

        </select>
    </div>

</div>
<div class="border rounded p-4 mt-4">

    <h3 class="font-semibold mb-2">
        Cursos de la sección
    </h3>

    <template
        x-for="g in gruposFiltrados()"
        :key="g.id">

        <div class="py-1">
            • <span x-text="g.nombre_grupo"></span>
        </div>

    </template>

</div>