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

                    <x-ui.button
                        type="button"
                        color="teal"
                        class="px-3 py-1"
                        x-data
                        x-on:click="$dispatch('open-modal', 'create-user')">
                        Crear Usuario
                    </x-ui.button>
                </div>

                <div class="overflow-x-auto">
                    @if (session('status'))
                    <div class="mb-4 border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 text-sm font-bold text-[#008080]">
                        {{ session('status') }}
                    </div>
                    @endif

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

            <x-ui.modal name="create-user" title="[ USERS / NUEVO ]" :show="$errors->any()">
                <form
                    x-data="{ saving: false }"
                    x-on:submit="saving = true"
                    class="space-y-5 p-5"
                    method="POST"
                    action="{{ route('users.store') }}">
                    @csrf

                    <div>
                        <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            Nombre
                        </label>

                        <input
                            name="name"
                            value="{{ old('name') }}"
                            type="text"
                            required
                            class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">

                        @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            Email
                        </label>

                        <input
                            name="email"
                            value="{{ old('email') }}"
                            type="email"
                            required
                            class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">

                        @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            Clave
                        </label>

                        <input
                            name="password"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">

                        @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                            Confirmar clave
                        </label>

                        <input
                            name="password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">
                    </div>

                    <x-ui.button
                        type="submit"
                        color="teal"
                        class="w-full disabled:translate-x-[4px] disabled:translate-y-[4px] disabled:cursor-not-allowed disabled:opacity-70 disabled:shadow-none"
                        x-bind:disabled="saving">
                        <span x-show="!saving">Crear Usuario</span>
                        <span x-show="saving" x-cloak>Guardando...</span>
                    </x-ui.button>
                </form>
            </x-ui.modal>

        </div>
    </div>
</x-app-layout>