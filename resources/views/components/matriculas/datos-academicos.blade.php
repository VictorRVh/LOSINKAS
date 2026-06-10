<div class="space-y-4">

    <div class="grid md:grid-cols-3 gap-4">

        <div>
            <label class="label">Periodo</label>

            <select
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

        <div>
            <label class="label">Grado</label>

            <select
                x-model="selectedGrado"
                class="input">

                @foreach($grados as $grado)
                    <option value="{{ $grado->id }}">
                        {{ $grado->nombre_grado }}
                    </option>
                @endforeach

            </select>
        </div>

        <div>
            <label class="label">Sección</label>

            <select
                x-model="selectedSeccion"
                class="input">

                @foreach($secciones as $seccion)
                    <option value="{{ $seccion->id }}">
                        {{ $seccion->nombre_seccion }}
                    </option>
                @endforeach

            </select>
        </div>

    </div>

    <div class="border p-4">

        <template x-for="g in gruposFiltrados()">

            <label class="flex gap-2">

                <input
                    type="checkbox"
                    name="grupo_ids[]"
                    :value="g.id"
                    x-model="gruposSeleccionados">

                <span x-text="g.nombre_grupo"></span>

            </label>

        </template>

    </div>

</div>