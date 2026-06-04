<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <x-ui.modal name="create-nivel" title="[ NIVELES / NUEVO ]">
        <form class="space-y-5 p-5" method="POST" action="{{ route('niveles.store') }}">
            @csrf

            <div>
                <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                    Nombre
                </label>

                <input
                    name="nombre_nivel"
                    value="{{ old('nombre_nivel') }}"
                    type="text"
                    class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none"
                    required>

                @error('nombre_nivel')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                    Descripcion
                </label>

                <textarea
                    name="descripcion"
                    rows="3"
                    class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">{{ old('descripcion') }}</textarea>

                @error('descripcion')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-3 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                <input
                    type="checkbox"
                    name="activo"
                    value="1"
                    checked
                    class="h-5 w-5 rounded-none border-2 border-[#0A1718] text-[#008080]">
                Activo
            </label>

            <x-ui.button type="submit" color="teal" class="w-full">
                Crear Nivel
            </x-ui.button>
        </form>
    </x-ui.modal>

    <x-ui.button
        type="button"
        color="teal"
        x-data
        x-on:click="$dispatch('open-modal', 'create-nivel')">
        Crear Nivel
    </x-ui.button>
</x-app-layout>