@props([
    'matricula' => null,
    'periodos',
    'niveles',
    'grados',
    'secciones',
    'grupos',
    'action',
    'method' => 'POST',
    'buttonText' => 'Guardar',
])

<form
    x-data="{
        paso: 1,
        saving: false,

        siguientePaso() {
            this.paso = 2;
        },

        submit(e) {
            this.saving = true;
        }
    }"

    x-on:submit="submit($event)"
    x-on:htmx:after-request="saving = false"

    method="POST"
    action="{{ $action }}"
    hx-post="{{ $action }}"
    hx-target="#matriculas-module"
    hx-select="#matriculas-module"
    hx-swap="outerHTML"

    class="space-y-2 pl-4 pb-4 gap-3">

    @csrf

    @if (($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    {{-- ================= HEADER ================= --}}
    <div class="border-b border-[#5C6F72]/30 py-3">
        <div class="flex items-center gap-8">

            <h2 class="text-lg font-bold text-[#0A1718] whitespace-nowrap">
                Matricular estudiante
            </h2>

            {{-- STEPS --}}
            <div class="flex items-center">

                {{-- PASO 1 --}}
                <div class="flex items-center">
                    <div
                        :class="paso === 1
                            ? 'bg-teal-600 text-white border-teal-600'
                            : 'bg-white text-gray-400 border-gray-300'"
                        class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-sm font-bold transition">
                        1
                    </div>

                    <span
                        :class="paso === 1
                            ? 'text-[#0A1718] font-semibold'
                            : 'text-gray-400'"
                        class="ml-2 text-sm">
                        Académico
                    </span>
                </div>

                <div
                    :class="paso === 2 ? 'bg-teal-600' : 'bg-gray-300'"
                    class="w-12 h-0.5 mx-4 transition-all">
                </div>

                {{-- PASO 2 --}}
                <div class="flex items-center">
                    <div
                        :class="paso === 2
                            ? 'bg-teal-600 text-white border-teal-600'
                            : 'bg-white text-gray-400 border-gray-300'"
                        class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-sm font-bold transition">
                        2
                    </div>

                    <span
                        :class="paso === 2
                            ? 'text-[#0A1718] font-semibold'
                            : 'text-gray-400'"
                        class="ml-2 text-sm">
                        Estudiante
                    </span>
                </div>

            </div>
        </div>
    </div>

    <div class="py-2"></div>

    {{-- ================= PASO 1 ================= --}}
    <div x-show="paso === 1" class="space-y-3">

        <x-matriculas.datos-academicos
            :periodos="$periodos"
            :niveles="$niveles"
            :grados="$grados"
            :secciones="$secciones"
            :grupos="$grupos" />

    </div>

    {{-- ================= PASO 2 ================= --}}
    <div x-show="paso === 2" class="space-y-3">

        <x-matriculas.datos-estudiante />

        {{-- DNI (HTMX) --}}
        <input
            type="text"
            name="dni"
            maxlength="8"
            placeholder="DNI"
            class="border p-2 w-full"
            hx-get="/estudiantes/buscar"
            hx-trigger="keyup changed delay:500ms"
            hx-target="#estudiante-preview"
            hx-include="[name='dni']"
        >

        <div id="estudiante-preview" class="text-sm text-gray-600"></div>

    </div>

    {{-- ================= BOTONES ================= --}}
    <div class="flex justify-between pt-4">

        <button
            type="button"
            x-show="paso > 1"
            @click="paso--"
            class="px-4 py-2 border">
            Anterior
        </button>

        <button
            type="button"
            x-show="paso < 2"
            @click="siguientePaso()"
            class="px-4 py-2 bg-blue-600 text-white">
            Siguiente
        </button>

        <x-ui.button
            x-show="paso === 2"
            type="submit"
            color="teal"
            x-bind:disabled="saving">

            <span x-show="!saving">
                {{ $buttonText }}
            </span>

            <span x-show="saving">
                Guardando...
            </span>

        </x-ui.button>

    </div>

</form>