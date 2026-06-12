<div class="border-2 border-[#0A1718] bg-white p-5 space-y-5">

    <div class="border-b pb-3">
        <h3 class="text-lg font-bold text-[#0A1718]">
            Datos del estudiante
        </h3>

        <p class="text-sm text-gray-500">
            Busque al estudiante por DNI o complete los datos manualmente.
        </p>
    </div>

    {{-- DNI --}}
    <div>
        <label class="block text-sm font-semibold mb-2">
            DNI
        </label>

        <div class="flex">

            <input
                name="dni"
                x-model="estudiante.dni"
                maxlength="8"
                type="text"
                placeholder="Ingrese DNI"
                class="flex-1 border-2 border-r-0 border-[#0A1718] px-3 py-2 focus:outline-none">

            <button
                type="button"
                @click="buscarEstudiante()"
                class="px-4 border-2 border-[#0A1718] bg-[#0A1718] text-white hover:bg-teal-700 transition">

                🔍

            </button>

        </div>

        <p
            x-show="buscandoEstudiante"
            class="text-xs text-blue-600 mt-1">

            Buscando estudiante...

        </p>

    </div>

    <input
        type="hidden"
        name="estudiante_id"
        x-model="estudiante.id">

    {{-- Datos --}}
    <div class="grid md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm font-semibold mb-2">
                Nombres
            </label>

            <input
                name="nombres"
                x-model="estudiante.nombres"
                class="w-full border-2 border-[#0A1718] px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">
                Apellidos
            </label>

            <input
                name="apellidos"
                x-model="estudiante.apellidos"
                class="w-full border-2 border-[#0A1718] px-3 py-2">
        </div>

    </div>

</div>