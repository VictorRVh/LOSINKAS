@props([
    'href' => null,
    'type' => 'button',
    'color' => 'white',
])

@php
    $colors = [
        'white' => 'bg-white text-[#0A1718] hover:bg-[#0A1718] hover:text-white',
        'teal' => 'bg-[#008080] text-white hover:opacity-90',
        'coral' => 'bg-[#FF7F50] text-white hover:opacity-90',
        'dark' => 'bg-[#0A1718] text-white hover:opacity-90',
        'outline' => 'bg-white text-[#0A1718] hover:bg-[#0A1718] hover:text-white',
    ];

    $classes = 'rounded-none border-2 border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase tracking-[0.06em] transition-colors ' . ($colors[$color] ?? $colors['white']);

    // Ensure x-data exists by default to enable Alpine $dispatch from buttons
    $needsXData = !$attributes->has('x-data');
    $attrBag = $attributes->merge(['class' => $classes]);
    if ($needsXData) {
        $attrBag = $attrBag->merge(['x-data' => '']);
    }
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attrBag }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attrBag }}>
        {{ $slot }}
    </button>
@endif
