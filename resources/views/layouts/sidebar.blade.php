<aside class="w-[260px] border-r-2 border-[#0A1718] bg-[#F5F5F5] flex flex-col">

    {{-- LOGO --}}
    <div class="border-b-2 border-[#0A1718] p-5">
        <h1 class="font-bold tracking-[0.2em] uppercase text-[#008080]">
            INKASCAN / PANEL
        </h1>
    </div>

    {{-- MENU --}}
    <nav class="flex-1 p-4 space-y-3">

        <!-- <a href="{{ route('dashboard') }}"
            class="flex items-center border-2 border-[#0A1718] px-4 py-3 font-bold uppercase tracking-[0.12em]
           {{ request()->routeIs('dashboard') ? 'bg-[#008080] text-white' : 'hover:bg-[#008080] hover:text-white' }}">
            Dashboard
        </a> -->

        <a href="{{ route('users.index') }}"
            class="flex items-center border-2 border-[#0A1718] px-4 py-3 font-bold uppercase tracking-[0.12em]
           {{ request()->routeIs('users.index') ? 'bg-[#008080] text-white' : 'hover:bg-[#008080] hover:text-white' }}">
            Usuarios
        </a>

        <a href="{{ route('niveles.index') }}"
            class="flex items-center border-2 border-[#0A1718] px-4 py-3 font-bold uppercase tracking-[0.12em]
           {{ request()->routeIs('niveles') ? 'bg-[#008080] text-white' : 'hover:bg-[#008080] hover:text-white' }}">
            Instituciones
        </a>

        <!-- <a href="#"
            class="flex items-center border-2 border-[#0A1718] px-4 py-3 font-bold uppercase tracking-[0.12em] hover:bg-[#008080] hover:text-white">
            Exámenes
        </a>

        <a href="#"
            class="flex items-center border-2 border-[#0A1718] px-4 py-3 font-bold uppercase tracking-[0.12em] hover:bg-[#008080] hover:text-white">
            Resultados
        </a> -->

        <a href="{{ route('grupos.index') }}"
            class="flex items-center border-2 border-[#0A1718] px-4 py-3 font-bold uppercase tracking-[0.12em]
           {{ request()->routeIs('grupos.index') ? 'bg-[#008080] text-white' : 'hover:bg-[#008080] hover:text-white' }}">
            Grupos
        </a>

        <a href="{{ route('periodos.index') }}"
            class="flex items-center border-2 border-[#0A1718] px-4 py-3 font-bold uppercase tracking-[0.12em]
           {{ request()->routeIs('periodos.index') ? 'bg-[#008080] text-white' : 'hover:bg-[#008080] hover:text-white' }}">
            Periodos
        </a>

    </nav>

    {{-- USUARIO --}}
    <div class="border-t-2 border-[#0A1718] p-4 bg-[#F5F5F5]">

        {{-- Avatar --}}
        <div class="flex items-center gap-3 mb-4">

            <div class="flex h-12 w-12 items-center justify-center border-2 border-[#0A1718] bg-[#008080] text-white font-bold text-lg">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>

            <div>
                <p class="font-bold text-[#0A1718]">
                    {{ Auth::user()->name }}
                </p>

                <p class="text-xs text-[#5C6F72]">
                    {{ Auth::user()->email }}
                </p>
            </div>

        </div>

        {{-- Acciones --}}
        <div class="space-y-2">

            <a href="{{ route('profile.edit') }}"
                class="flex items-center justify-center border-2 border-[#0A1718]
                  bg-white py-2 font-bold uppercase tracking-[0.12em]
                  shadow-[4px_4px_0px_0px_rgba(10,23,24,1)]
                  hover:bg-[#008080] hover:text-white">
                Mi Perfil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="w-full border-2 border-[#0A1718]
                       bg-[#FF7F50] text-white py-2 font-bold uppercase
                       shadow-[4px_4px_0px_0px_rgba(10,23,24,1)]
                       hover:translate-x-[2px]
                       hover:translate-y-[2px]
                       hover:shadow-none">
                    Salir
                </button>
            </form>

        </div>

    </div>
</aside>