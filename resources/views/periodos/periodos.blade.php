<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Space_Grotesk',sans-serif] text-xl font-bold uppercase tracking-[0.14em]">
            Periodos
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <section class="border-2 border-[#0A1718] bg-white">
                <div class="flex items-center justify-between gap-4 border-b border-[#5C6F72]/30 px-5 py-4">
                    <div>
                        <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            [ TABLA / PERIODOS ]
                        </p>
                    </div>

                    <x-ui.button
                        type="button"
                        color="teal"
                        class="px-3 py-1"
                        x-data
                        x-on:click="$dispatch('open-modal', 'create-periodo')">
                        Crear Periodo
                    </x-ui.button>
                </div>

                <div class="overflow-x-auto">
                    @if(session('status'))
                    <div class="mb-4 border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 text-sm font-bold text-[#008080]">
                        {{ session('status') }}
                    </div>
                    @endif

                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-[#F4F7F7]">
                                <th class="border-b border-r border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">ID</th>
                                <th class="border-b border-r border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">Nombre</th>
                                <th class="border-b border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">Grupos</th>
                                <th class="border-b border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($periodos as $periodo)
                            <tr class="hover:bg-[#F4F7F7]">
                                <td class="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm">{{ $periodo->id }}</td>
                                <td class="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm">{{ $periodo->nombre_periodo }}</td>
                                <td class="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm">{{ $periodo->grupos->count() }}</td>
                                <td class="border-b border-[#5C6F72]/30 px-3 py-2">
                                    <div class="flex flex-wrap gap-2">
                                        <x-ui.action-button
                                            type="button"
                                            x-on:click="$dispatch('open-modal', 'edit-periodo-{{ $periodo->id }}')"
                                            color="outline">
                                            Editar
                                        </x-ui.action-button>
                                        <x-ui.action-button
                                            type="button"
                                            x-on:click="$dispatch('open-modal', 'delete-periodo-{{ $periodo->id }}')"
                                            color="coral">
                                            Eliminar
                                        </x-ui.action-button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center">No hay periodos registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-[#5C6F72]/30 px-5 py-4">
                    {{ $periodos->links() }}
                </div>
            </section>
        </div>
    </div>

    <x-ui.modal name="create-periodo" title="[ PERIODOS / NUEVO ]" :show="$errors->any()">
        <form class="space-y-5 p-5" method="POST" action="{{ route('periodos.store') }}">
            @csrf

            <div>
                <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">Nombre</label>
                <input name="nombre_periodo" value="{{ old('nombre_periodo') }}" type="text" class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">
                @error('nombre_periodo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <x-ui.button type="submit" color="teal" class="w-full">
                Crear Periodo
            </x-ui.button>
        </form>
    </x-ui.modal>

    @foreach($periodos as $periodo)
    <x-ui.modal name="edit-periodo-{{ $periodo->id }}" title="[ PERIODOS / EDITAR ]">
        <form class="space-y-5 p-5" method="POST" action="{{ route('periodos.update', $periodo) }}">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">Nombre</label>
                <input name="nombre_periodo" value="{{ old('nombre_periodo', $periodo->nombre_periodo) }}" type="text" class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">
                @error('nombre_periodo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <x-ui.button type="submit" color="teal" class="w-full">
                Guardar Cambios
            </x-ui.button>
        </form>
    </x-ui.modal>

    <x-ui.delete-modal
        name="delete-periodo-{{ $periodo->id }}"
        title="[ PERIODOS / ELIMINAR ]"
        :item-name="$periodo->nombre_periodo"
        :action="route('periodos.destroy', $periodo)" />
    @endforeach
</x-app-layout>
