<x-guest-layout>
    <main class="min-h-screen px-4 py-10 lg:px-8">
        <div class="mx-auto grid w-full max-w-6xl grid-cols-1 border-2 border-[#0A1718] bg-[#FFFFFF] lg:grid-cols-[0.95fr_1.05fr]">
            <div class="border-b border-[#5C6F72]/30 px-6 py-10 lg:border-b-0 lg:border-r lg:px-8">
                <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.24em] text-[#008080]">
                    Inkascan / Registro
                </p>

                <h1 class="mt-4 max-w-[10ch] font-['Space_Grotesk',sans-serif] text-4xl font-bold uppercase leading-none tracking-[-0.05em] lg:text-6xl">
                    Registro de usuarios
                </h1>

                <p class="mt-6 max-w-xl border-l border-[#5C6F72]/30 pl-4 text-sm leading-7 text-[#0A1718]/80">
                    Crea credenciales para nuevos usuarios del panel técnico de <strong>inkascan</strong>.
                </p>
            </div>

            <div class="px-6 py-10 lg:px-8">
                <div class="border-2 border-[#0A1718] bg-[#F4F7F7] p-6">
                    <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.2em] text-[#5C6F72]">
                        [ AUTH / REGISTER ]
                    </p>

                    <form class="mt-6 space-y-5" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div>
                            <label for="name" class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                Nombre
                            </label>

                            <input
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                type="text"
                                required
                                autofocus
                                autocomplete="name"
                                class="w-full rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-3 outline-none">

                            @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                Email
                            </label>

                            <input
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                type="email"
                                required
                                autocomplete="username"
                                class="w-full rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-3 outline-none">

                            @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                Clave
                            </label>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-3 outline-none">

                            @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                Confirmar clave
                            </label>

                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-3 outline-none">

                            @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-4 pt-2 sm:flex-row sm:items-center sm:justify-between">
                            <a
                                href="{{ route('login') }}"
                                class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.14em] text-[#008080] underline underline-offset-4">
                                Ya tengo cuenta
                            </a>

                            <x-ui.button type="submit" color="coral">
                                Crear usuario
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-guest-layout>