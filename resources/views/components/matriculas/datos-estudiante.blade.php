<div class="space-y-4 border-2 border-[#0A1718] p-4">

    <h3 class="font-bold">
        Datos del estudiante
    </h3>

    <div>
        <label class="label">DNI</label>

        <input
            x-model="estudiante.dni"
            @blur="buscarEstudiante()"
            maxlength="8"
            type="text"
            class="input">
    </div>

    <input
        type="hidden"
        name="estudiante_id"
        x-model="estudiante.id">

    <div class="grid md:grid-cols-2 gap-4">

        <div>
            <label class="label">Nombres</label>
            <input
                name="nombres"
                x-model="estudiante.nombres"
                class="input">
        </div>

        <div>
            <label class="label">Apellidos</label>
            <input
                name="apellidos"
                x-model="estudiante.apellidos"
                class="input">
        </div>

    </div>

</div>