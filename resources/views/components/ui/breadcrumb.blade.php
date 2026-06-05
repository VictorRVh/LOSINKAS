@props([
'items' => [],
'routeText' => null,
'backUrl' => null,
'backLabel' => 'Volver',
])

<div class="mb-4 rounded-2xl px-4 py-3 text-slate-700 shadow-sm sm:px-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <a
            href="{{ $backUrl ?? 'javascript:history.back()' }}"
            class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-3 py-2 text-xs uppercase tracking-[0.18em] text-slate-700 transition hover:bg-slate-100">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            {{ $backLabel }}
        </a>

        <nav class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.18em] text-slate-500" aria-label="Breadcrumb">
            @foreach($items as $item)
            @if(!empty($item['href']))
            <a href="{{ $item['href'] }}" class="transition-colors hover:text-slate-900">
                {{ $item['label'] }}
            </a>
            @else
            <span class="font-semibold text-slate-900">{{ $item['label'] }}</span>
            @endif

            @unless($loop->last)
            <span>/</span>
            @endunless
            @endforeach
        </nav>


    </div>

    @if($routeText)
    <p class="mt-3 text-[11px] text-slate-500">
        Ruta activa: <span class="font-mono text-[11px] font-semibold text-slate-700">{{ $routeText }}</span>
    </p>
    @endif
</div>