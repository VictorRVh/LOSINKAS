<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Información del perfil --}}
                <div class="p-6 bg-white border-2 border-[#0A1718]">
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- Cambiar contraseña --}}
                <div class="p-6 bg-white border-2 border-[#0A1718]">
                    @include('profile.partials.update-password-form')
                </div>

                {{-- Eliminar cuenta --}}
                <div class="lg:col-span-2 p-6 bg-white border-2 border-[#0A1718]">
                    @include('profile.partials.delete-user-form')
                </div>

            </div>

        </div>
    </div>
</x-app-layout>