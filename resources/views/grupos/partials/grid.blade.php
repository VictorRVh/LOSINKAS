<div id="grupos-grid"
    class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 lg:grid-cols-3">

    @forelse ($grupos as $grupo)

    <div class="border-2 border-[#0A1718] bg-white p-4">

        <h3 class="font-bold uppercase">
            {{ $grupo->nombre_grupo }}
        </h3>

        <p class="text-sm">
            <strong>Periodo:</strong> {{ $grupo->periodo->nombre_periodo }}
        </p>

        <p class="text-sm">
            <strong>Curso:</strong> {{ $grupo->curso->nombre_curso }}
        </p>

        <p class="text-sm">
            <strong>Sección:</strong> {{ $grupo->seccion->nombre_seccion }}
        </p>

    </div>

    @empty
    <div class="col-span-full text-center text-gray-500">
        No hay grupos registrados
    </div>
    @endforelse
</div>