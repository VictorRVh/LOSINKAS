<div id="matriculas-grupos">

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <section class="border-2 border-[#0A1718] bg-white">

                <div class="border-b border-[#5C6F72]/30 px-5 py-4">

                    <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                        [ LISTA / GRUPOS ]
                    </p>

                </div>

                {{-- filtros --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 border-b">

                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">
                            Periodo
                        </label>

                        <select
                            name="periodo_id"
                            class="w-full border-2 border-[#0A1718] px-3 py-2">

                            <option value="">
                                Seleccione periodo
                            </option>

                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo->id }}">
                                    {{ $periodo->nombre_periodo }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">
                            Nivel
                        </label>

                        <select
                            name="nivel_id"
                            class="w-full border-2 border-[#0A1718] px-3 py-2">

                            <option value="">
                                Seleccione nivel
                            </option>

                            @foreach($niveles as $nivel)
                                <option value="{{ $nivel->id }}">
                                    {{ $nivel->nombre_nivel }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">
                            Grado
                        </label>

                        <select
                            name="grado_id"
                            class="w-full border-2 border-[#0A1718] px-3 py-2">

                            <option value="">
                                Seleccione grado
                            </option>

                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">
                            Sección
                        </label>

                        <select
                            name="seccion_id"
                            class="w-full border-2 border-[#0A1718] px-3 py-2">

                            <option value="">
                                Seleccione sección
                            </option>

                        </select>
                    </div>

                </div>

                {{-- tabla --}}
                <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Email</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($estudiantes as $estudiante)

                                <tr>
                                    <td>{{ $estudiante->dni }}</td>
                                    <td>{{ $estudiante->nombres }}</td>
                                    <td>{{ $estudiante->apellidos }}</td>
                                    <td>{{ $estudiante->email }}</td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        No hay estudiantes matriculados.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="border-t px-5 py-4">
                    {{ $estudiantes->links() }}
                </div>

            </section>

        </div>
    </div>

</div>