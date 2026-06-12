<div class="border-2 border-[#0A1718] bg-white">



    

        <x-matriculas.form
            :periodos="$periodos"
            :niveles="$niveles"
            :grados="$grados"
            :secciones="$secciones"
            :grupos="$grupos"
            :action="route('matriculas.store')" />

    

</div>