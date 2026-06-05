@props([
'items' => [],
'routeText' => null,
'backUrl' => null,
'backLabel' => 'Volver',
])

<div class="  ">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
        <a
            href="{{ $backUrl ?? 'javascript:history.back()' }}"
            class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-2 py-1 text-sm font-medium text-slate-700 transition-all hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ $backLabel }}
        </a>

        <nav class="flex flex-wrap items-center text-sm text-slate-500" aria-label="Breadcrumb">

            @foreach($items as $item)

            @if(!$loop->first)
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-2 h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            @endif

            @if(!empty($item['href']))
            <a
                href="{{ $item['href'] }}"
                class="transition-colors hover:text-indigo-600">
                {{ mb_strtoupper($item['label']) }}
            </a>
            @else
            <span class="font-semibold text-slate-900">
                {{ mb_strtoupper($item['label']) }}
            </span>
            @endif

            @endforeach

        </nav>

    </div>

    @if($routeText)
    <div class="mt-4 border-t border-slate-100 pt-2">
        <span class="text-xs text-slate-400">Ruta actual</span>
        <p class="mt-1 font-mono text-xs text-slate-600">
            {{ $routeText }}
        </p>
    </div>
    @endif


</div>