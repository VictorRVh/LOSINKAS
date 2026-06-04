@props([
    'name',
    'title' => null,
    'show' => false,
])

<div
    x-data="{ open: @js($show) }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center px-4"
>
    <div
        x-show="open"
        x-transition.opacity.duration.200ms
        x-on:click="$dispatch('close-modal', '{{ $name }}')"
        class="absolute inset-0 bg-[#0A1718]/50"
    ></div>

    <section
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
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