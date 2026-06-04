@props([
    'name',
    'title' => null,
])

<div
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-[#0A1718]/50 px-4"
>
    <div
        x-on:click="$dispatch('close-modal', '{{ $name }}')"
        class="absolute inset-0"
    ></div>

    <section
        x-on:click.stop
        class="relative z-10 w-full max-w-xl border-2 border-[#0A1718] bg-[#FFFFFF] shadow-[8px_8px_0px_0px_rgba(10,23,24,1)]"
    >
        @if ($title)
            <header class="flex items-center justify-between border-b-2 border-[#0A1718] px-5 py-4">
                <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.2em] text-[#5C6F72]">
                    {{ $title }}
                </p>

                <button
                    type="button"
                    x-on:click="$dispatch('close-modal', '{{ $name }}')"
                    class="border-2 border-[#0A1718] bg-[#FFFFFF] px-3 py-1 font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase"
                >
                    X
                </button>
            </header>
        @endif

        {{ $slot }}
    </section>
</div>