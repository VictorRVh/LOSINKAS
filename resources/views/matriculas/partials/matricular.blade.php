

<div class="border-2 border-[#0A1718] bg-white">

    <div class="border-b border-[#5C6F72]/30 px-5 py-4">
        <h3 class="font-bold uppercase">
            Matricular estudiante
        </h3>
    </div>

    <div class="p-5">

        <x-matriculas.form
            :periodos="$periodos"
            :grados="$grados"
            :secciones="$secciones"
            :grupos="$grupos"
            :action="route('matriculas.store')" />

    </div>

</div>