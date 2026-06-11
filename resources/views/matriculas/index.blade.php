<x-app-layout>

    <!-- <x-slot name="breadcrumb">
        <x-ui.breadcrumb
            :items="[
                ['label' => 'Matrículas'],
            ]" />
    </x-slot> -->


    <div id="matriculas-module">

        @include('matriculas.partials.submenu')

        <div id="matriculas-content">
            @include('matriculas.partials.matricular')
        </div>

    </div>
    </div>

</x-app-layout>