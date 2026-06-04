{{-- resources/views/components/ui/button.blade.php --}}

@props([
    'href' => null,
    'type' => 'button',
    'color' => 'white',
])

@php
    $colors = [
        'white' => 'bg-[#FFFFFF] text-[#0A1718]',
        'coral' => 'bg-[#FF7F50] text-white',
        'teal' => 'bg-[#008080] text-white',
    ];

    $classes = 'rounded-none border-2 border-[#0A1718] px-4 py-2 font-[\'Space_Grotesk\',sans-serif] text-sm font-bold uppercase tracking-[0.12em] shadow-[4px_4px_0px_0px_rgba(10,23,24,1)] transition-transform active:translate-x-[4px] active:translate-y-[4px] active:shadow-none ' . ($colors[$color] ?? $colors['white']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif