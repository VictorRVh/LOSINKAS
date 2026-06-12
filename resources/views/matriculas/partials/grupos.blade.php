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


                </div>

                {{-- tabla --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">

                    @forelse($gruposPadre as $grupo)

                    <div class="border-2 border-[#0A1718] bg-white p-4">

                        <h3 class="font-bold text-lg">
                            GRUPO {{ $grupo->seccion->nombre_seccion }}
                        </h3>

                        
                       


                        <div class="mt-4 border-t pt-3">

                         <p class="text-xs uppercase text-gray-500">
                            N° alumnos  {{ $grupo->matriculas_count }}
                        </p>
                            <div class="mt-4 border-t pt-3">

                                <div class="flex items-center justify-between">
                       

                                    <button
                                        type="button"
                                        class="px-3 py-2 text-sm border-2 border-[#0A1718] hover:bg-[#0A1718] hover:text-white">

                                        Ver alumnos

                                    </button>

                                </div>

                            </div>
                        </div>

                    </div>

                    @empty

                    <div class="col-span-full text-center py-8">

                        No hay grupos registrados.

                    </div>

                    @endforelse

                </div>


            </section>

        </div>
    </div>

</div>