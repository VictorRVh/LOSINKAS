<option value="">Seleccione una sección</option>

@forelse ($secciones as $seccion)
    <option value="{{ $seccion->id }}">
        {{ $seccion->nombre_seccion }}
    </option>
@empty
    <option value="" disabled>
        No hay secciones disponibles
    </option>
@endforelse