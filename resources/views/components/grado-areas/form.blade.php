@props([
'grado' => null,
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

            const nombreGrado = this.$refs.nombre_grado.value.trim()
            const descripcion = this.$refs.descripcion.value.trim()

            if (!nombreGrado) {
                this.errors.nombre_grado = 'El nombre del grado es obligatorio.'
            } else if (nombreGrado.length < 3) {
                this.errors.nombre_grado = 'El nombre del grado debe tener al menos 3 caracteres.'
            } else if (nombreGrado.length > 80) {
                this.errors.nombre_grado = 'El nombre del grado no debe superar los 80 caracteres.'
            }

            if (descripcion.length > 255) {
                this.errors.descripcion = 'La descripcion no debe superar los 255 caracteres.'
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
    hx-target="#grado-areas-module"
    hx-select="#grado-areas-module"
    hx-swap="outerHTML">
    @csrf

    @if ($method !== 'POST')
    @method($method)
    @endif

    <div>
        <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
            Nombre
        </label>

        <input
            x-ref="nombre_grado"
            x-on:input="delete errors.nombre_grado"
            name="nombre_grado"
            value="{{ old('nombre_grado', $grado?->nombre_grado) }}"
            type="text"
            class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">

        <p x-show="errors.nombre_grado" x-text="errors.nombre_grado" x-cloak class="mt-2 text-sm text-red-600"></p>

        @error('nombre_grado')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
            Descripcion
        </label>

        <textarea
            x-ref="descripcion"
            x-on:input="delete errors.descripcion"
            name="descripcion"
            rows="3"
            class="w-full rounded-none border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-3 outline-none">{{ old('descripcion', $grado?->descripcion) }}</textarea>

        <p x-show="errors.descripcion" x-text="errors.descripcion" x-cloak class="mt-2 text-sm text-red-600"></p>

        @error('descripcion')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <label class="flex items-center gap-3 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
        <input
            type="checkbox"
            name="activo"
            value="1"
            @checked(old('activo', $grado?->activo ?? true))
        class="h-5 w-5 rounded-none border-2 border-[#0A1718] text-[#008080]">
        Activo
    </label>

    <x-ui.button
        type="submit"
        color="teal"
        class="w-full disabled:translate-x-[4px] disabled:translate-y-[4px] disabled:cursor-not-allowed disabled:opacity-70 disabled:shadow-none"
        x-bind:disabled="saving">
        <span x-show="!saving">{{ $buttonText }}</span>

        <span x-show="saving" x-cloak class="inline-flex items-center justify-center gap-2">
            <span class="h-2 w-2 animate-pulse bg-white"></span>
            Guardando...
        </span>
    </x-ui.button>
</form>