<option value="">Todos los grados</option>

@foreach($grados as $grado)
<option value="{{ $grado->id }}">
    {{ $grado->nombre_grado }}
</option>
@endforeach