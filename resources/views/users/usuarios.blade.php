<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase tracking-[0.14em]">
            Usuarios
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <section class="border-2 border-[#0A1718] bg-white">

                <div class="flex items-center justify-between gap-4 border-b border-[#5C6F72]/30 px-5 py-4">
                    <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                        [ TABLA / USERS ]
                    </p>


                    <x-ui.button color="teal" class="px-3 py-1">
                        <a href="#">
                            Crear Usuario
                        </a>
                    </x-ui.button>


                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">

                        <thead>
                            <tr class="bg-[#F4F7F7]">
                                <th class="border-b border-r border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">
                                    ID
                                </th>

                                <th class="border-b border-r border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">
                                    Nombre
                                </th>

                                <th class="border-b border-r border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">
                                    Email
                                </th>

                                <th class="border-b border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($usuarios as $usuario)
                            <tr class="hover:bg-[#F4F7F7]">

                                <td class="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm">
                                    {{ $usuario->id }}
                                </td>

                                <td class="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm">
                                    {{ $usuario->name }}
                                </td>

                                <td class="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm">
                                    {{ $usuario->email }}
                                </td>

                                <td class="border-b border-[#5C6F72]/30 px-3 py-2">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-none border-2 border-[#0A1718] bg-white px-2 py-1 font-['Space_Grotesk',sans-serif] text-[10px] font-bold uppercase tracking-[0.16em] text-[#FF7F50]">

                                        Borrar
                                    </button>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center">
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <div class="border-t border-[#5C6F72]/30 px-5 py-4">
                    {{ $usuarios->links() }}
                </div>

            </section>

        </div>
    </div>
</x-app-layout>