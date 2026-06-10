<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <section class="border-2 border-[#0A1718] bg-white">

            <div class="flex items-center justify-between gap-4 border-b border-[#5C6F72]/30 px-5 py-4">
                <p class="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                    [ TABLA / ESTUDIANTES ]
                </p>

                <x-ui.button
                    type="button"
                    color="teal"
                    class="px-3 py-1"
                    x-data
                    x-on:click="$dispatch('open-modal', 'create-estudiante')"
                >
                    Nuevo Estudiante
                </x-ui.button>
            </div>

            {{-- Buscador --}}
            <div class="p-4 border-b">
                <input
                    type="text"
                    name="buscar"
                    placeholder="Buscar por DNI, nombre o apellido"
                    class="w-full border-2 border-[#0A1718] px-3 py-2"
                >
            </div>

            {{-- Tabla --}}
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr>
                            <th>DNI</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($estudiantes as $estudiante)
                            <tr>
                                <td>{{ $estudiante->dni }}</td>
                                <td>{{ $estudiante->nombres }}</td>
                                <td>{{ $estudiante->apellidos }}</td>
                                <td>{{ $estudiante->email }}</td>
                                <td>
                                    Editar | Eliminar
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    No hay estudiantes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <x-ui.modal name="create-estudiante" title="[ ESTUDIANTES / NUEVO ]">
                    <x-slider-estudiantes.form
                        :action="route('estudiantes.store')"
                        method="POST"
                        buttonText="Crear Estudiante"
                    />
                </x-ui.modal>
            </div>

            <div class="border-t px-5 py-4">
                {{ $estudiantes->links() }}
            </div>

        </section>
    </div>
</div>