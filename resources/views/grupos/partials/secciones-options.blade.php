<option value="">Todas las secciones</option>

@foreach($secciones as $seccion)
<option value="{{ $seccion->id }}">
    {{ $seccion->nombre_seccion }}
</option>
@endforeach