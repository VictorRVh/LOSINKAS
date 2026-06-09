@props([
'name',
'title' => '[ ELIMINAR ]',
'itemName',
'action',
'target' => null,
])

<x-ui.modal :name="$name" :title="$title">
    <div class="space-y-5 p-5">
        <div class="border-2 border-[#0A1718] bg-[#F4F7F7] p-4">
            <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#FF7F50]">
                Accion irreversible
            </p>

            <p class="mt-3 text-sm leading-6 text-[#0A1718]/80">
                Estas seguro de que deseas eliminar
                <strong>{{ $itemName }}</strong>?
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
            <button
                type="button"
                x-on:click="$dispatch('close-modal', '{{ $name }}')"
                class="border-2 border-[#0A1718] bg-white px-4 py-2 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.14em]">
                Cancelar
            </button>

            <form
                hx-delete="{{ $action }}"
                @if($target)
                hx-target="{{ $target }}"
                hx-swap="outerHTML"
                @endif>
                @csrf

                <button
                    type="submit"
                    class="w-full border-2 border-[#0A1718] bg-red-500 px-4 py-2 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.14em] text-white shadow-[4px_4px_0px_0px_rgba(10,23,24,1)] sm:w-auto">
                    Si, eliminar
                </button>
            </form>
        </div>
    </div>
</x-ui.modal>