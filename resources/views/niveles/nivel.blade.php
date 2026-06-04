<x-app-layout>

    <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase tracking-[0.14em]">
            Niveles
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <section class="border-2 border-[#0A1718] bg-white">

                <div class="flex items-center justify-between gap-4 border-b border-[#5C6F72]/30 px-5 py-4">
                    <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                        [ GRID / NIVELES ]
                    </p>
                </div>

                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                    @foreach ($niveles as $nivel)
                        <div class="border-2 border-[#0A1718] bg-white p-4 hover:bg-[#F4F7F7] transition">

                            <div class="flex justify-between items-start">
                                <h3 class="font-bold uppercase tracking-wide">
                                    {{ $nivel->nombre_nivel }}
                                </h3>

                                <span class="text-[10px] px-2 py-1 border border-[#5C6F72] uppercase">
                                    {{ $nivel->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>

                            <p class="text-sm text-[#5C6F72] mt-2">
                                {{ $nivel->descripcion ?? 'Sin descripción' }}
                            </p>

                            <div class="mt-3 text-xs uppercase tracking-wider text-[#5C6F72]">
                                Áreas: {{ $nivel->gradoAreas->count() }}
                            </div>

                            <div class="mt-4 flex gap-2">

                                <a href="{{ route('niveles.show', $nivel) }}"
                                   class="border-2 border-[#0A1718] px-2 py-1 text-[10px] uppercase font-bold hover:bg-[#0A1718] hover:text-white">
                                    Ver
                                </a>

                                <a href="{{ route('niveles.edit', $nivel) }}"
                                   class="border-2 border-[#0A1718] px-2 py-1 text-[10px] uppercase font-bold hover:bg-[#008080] hover:text-white">
                                    Editar
                                </a>

                                <form action="{{ route('niveles.destroy', $nivel) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button class="border-2 border-[#0A1718] px-2 py-1 text-[10px] uppercase font-bold hover:bg-red-500 hover:text-white">
                                        Borrar
                                    </button>
                                </form>

                            </div>

                        </div>
                    @endforeach

                </div>

                <div class="border-t border-[#5C6F72]/30 px-5 py-4">
                    {{ $niveles->links() }}
                </div>

            </section>

        </div>
    </div>

</x-app-layout>