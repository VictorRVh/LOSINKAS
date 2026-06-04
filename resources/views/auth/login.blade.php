<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <header class="fixed left-0 top-0 z-10 w-full border-b-2 border-[#0A1718] bg-[#FFFFFF]">
        <div class="flex w-full items-center gap-4 px-4 py-3 lg:px-6">
            <!-- <img
                src="{{ asset('images/brand-logo.png') }}"
                alt="InkaScan"
                class="h-14 w-auto object-contain lg:h-16"> -->
            
                <img
                src="{{ ('img/inkascan.jpeg') }}"
                alt="InkaScan"
                class="h-14 w-auto object-contain lg:h-16">

            <div class="border-l border-[#5C6F72]/30 pl-4">
                <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.22em] text-[#008080]">
                    Inkascan / Panel
                </p>

                <p class="mt-1 font-['Space_Grotesk',sans-serif] text-lg font-bold uppercase tracking-[-0.03em]">
                    Control de Usuarios
                </p>
            </div>
        </div>
    </header>

    <main class="min-h-screen px-4 pb-10 pt-32 lg:px-8">
        <div class="mx-auto w-full max-w-6xl">
            <x-auth-session-status class="mb-4" :status="session('status')" />


            <section class="grid grid-cols-1 border-2 border-[#0A1718] bg-[#FFFFFF] lg:grid-cols-[1.05fr_0.95fr]">
                <div class="border-b border-[#5C6F72]/30 px-6 py-10 lg:border-b-0 lg:border-r lg:px-8">
                    <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.24em] text-[#008080]">
                        Acceso local / inkascan
                    </p>

                    <h1 class="mt-4 max-w-[10ch] font-['Space_Grotesk',sans-serif] text-4xl font-bold uppercase leading-none tracking-[-0.05em] lg:text-6xl">
                        Login tecnico de usuarios
                    </h1>

                    <p class="mt-6 max-w-xl border-l border-[#5C6F72]/30 pl-4 text-sm leading-7 text-[#0A1718]/80">
                        Panel simple conectado a la base de datos <strong>inkascan</strong> sobre XAMPP.
                    </p>
                </div>

                <div class="px-6 py-10 lg:px-8">
                    <div class="border-2 border-[#0A1718] bg-[#F4F7F7] p-6">
                        <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.2em] text-[#5C6F72]">
                            [ AUTH / LOGIN ]
                        </p>

                        <form class="mt-6 space-y-5" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div>
                                <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                    Email
                                </label>

                                <input
                                    name="email"
                                    value="{{ old('email') }}"
                                    type="email"
                                    class="w-full rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-3 outline-none"
                                    autocomplete="username">

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
                                    class="w-full rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-3 outline-none"
                                    autocomplete="current-password">

                                @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <x-ui.button type="submit" color="coral" class="w-full">
                                Ingresar al Panel
                            </x-ui.button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-guest-layout>