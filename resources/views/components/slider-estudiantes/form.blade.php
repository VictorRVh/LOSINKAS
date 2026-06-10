@props([
'estudiante' => null,
'action',
'method' => 'POST',
'buttonText' => 'Guardar',
])

<form
    x-data="{
        saving: false,
        errors: {},

        validate() {
            this.errors = {}

            const dni = this.$refs.dni.value.trim()
            const nombres = this.$refs.nombres.value.trim()
            const apellidos = this.$refs.apellidos.value.trim()
            const email = this.$refs.email.value.trim()

            if (!dni) {
                this.errors.dni = 'El DNI es obligatorio.'
            } else if (!/^[0-9]{8}$/.test(dni)) {
                this.errors.dni = 'El DNI debe tener 8 dígitos.'
            }

            if (!nombres) {
                this.errors.nombres = 'Los nombres son obligatorios.'
            } else if (nombres.length < 3) {
                this.errors.nombres = 'Los nombres deben tener al menos 3 caracteres.'
            }

            if (!apellidos) {
                this.errors.apellidos = 'Los apellidos son obligatorios.'
            } else if (apellidos.length < 3) {
                this.errors.apellidos = 'Los apellidos deben tener al menos 3 caracteres.'
            }

            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                this.errors.email = 'Ingrese un correo válido.'
            }

            return Object.keys(this.errors).length === 0
        },

        submit(event) {
            if (!this.validate()) {
                event.preventDefault()
                return
            }

            this.saving = true
        }
    }"
    x-on:submit="submit($event)"
    x-on:htmx:after-request="saving = false"
    class="space-y-5 p-5"
    method="POST"
    action="{{ $action }}"
    hx-post="{{ $action }}"
    hx-target="#estudiantes-module"
    hx-select="#estudiantes-module"
    hx-swap="outerHTML">

    @csrf

    @if ($method !== 'POST')
    @method($method)
    @endif
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        {{-- DNI --}}
        <div>
            <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                DNI
            </label>

            <input
                x-ref="dni"
                x-on:input="delete errors.dni"
                name="dni"
                value="{{ old('dni', $estudiante?->dni) }}"
                type="text"
                maxlength="8"
                class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">

            <p x-show="errors.dni" x-text="errors.dni" x-cloak class="mt-2 text-sm text-red-600"></p>

            @error('dni')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- NOMBRES --}}
        <div>
            <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                Nombres
            </label>

            <input
                x-ref="nombres"
                x-on:input="delete errors.nombres"
                name="nombres"
                value="{{ old('nombres', $estudiante?->nombres) }}"
                type="text"
                class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">

            <p x-show="errors.nombres" x-text="errors.nombres" x-cloak class="mt-2 text-sm text-red-600"></p>

            @error('nombres')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- APELLIDOS --}}
        <div>
            <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                Apellidos
            </label>

            <input
                x-ref="apellidos"
                x-on:input="delete errors.apellidos"
                name="apellidos"
                value="{{ old('apellidos', $estudiante?->apellidos) }}"
                type="text"
                class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">

            <p x-show="errors.apellidos" x-text="errors.apellidos" x-cloak class="mt-2 text-sm text-red-600"></p>

            @error('apellidos')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- FECHA NACIMIENTO --}}
        <div>
            <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                Fecha de Nacimiento
            </label>

            <input
                name="fecha_nacimiento"
                value="{{ old('fecha_nacimiento', $estudiante?->fecha_nacimiento) }}"
                type="date"
                class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">
        </div>

        {{-- TELEFONO --}}
        <div>
            <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                Teléfono
            </label>

            <input
                name="telefono"
                value="{{ old('telefono', $estudiante?->telefono) }}"
                type="text"
                class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">
        </div>

        {{-- EMAIL --}}
        <div>
            <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                Correo Electrónico
            </label>

            <input
                x-ref="email"
                x-on:input="delete errors.email"
                name="email"
                value="{{ old('email', $estudiante?->email) }}"
                type="email"
                class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">

            <p x-show="errors.email" x-text="errors.email" x-cloak class="mt-2 text-sm text-red-600"></p>

            @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- ACTIVO --}}
        <label class="flex items-center gap-3 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
            <input
                type="checkbox"
                name="activo"
                value="1"
                @checked(old('activo', $estudiante?->activo ?? true))
            class="h-5 w-5 rounded-none border-2 border-[#0A1718] text-[#008080']">

            Activo
        </label>

    </div>

    <x-ui.button
        type="submit"
        color="teal"
        class="w-full disabled:translate-x-[4px] disabled:translate-y-[4px] disabled:cursor-not-allowed disabled:opacity-70 disabled:shadow-none"
        x-bind:disabled="saving">

        <span x-show="!saving">
            {{ $buttonText }}
        </span>

        <span x-show="saving" x-cloak class="inline-flex items-center justify-center gap-2">
            <span class="h-2 w-2 animate-pulse bg-white"></span>
            Guardando...
        </span>

    </x-ui.button>

</form>