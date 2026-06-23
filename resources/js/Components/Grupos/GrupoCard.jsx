export default function PadreGrupoCard({ padre, onDelete }) {
    return (
        <div className="border-2 border-[#0A1718] p-4">

            {/* CABECERA */}
            <div className="mb-4 flex items-center justify-between">

                <div>
                    <h4 className="font-bold uppercase">
                        {padre.grado.nombre_grado} - {padre.seccion.nombre_seccion}
                    </h4>

                    <p className="text-xs text-[#5C6F72]">
                        Periodo: {padre.periodo.nombre_periodo}
                        {' | '}
                        Nivel: {padre.grado.nivel.nombre_nivel}
                    </p>
                </div>

                <div className="flex gap-2">

                    <button
                        className="border-2 border-[#0A1718] px-3 py-2 text-xs font-bold uppercase
                        hover:bg-[#008080] hover:text-white"
                    >
                        Editar cursos
                    </button>

                    <button
                        onClick={() => onDelete(padre)}
                        className="border-2 border-red-500 px-3 py-2 text-xs font-bold uppercase
                        text-red-500 hover:bg-red-500 hover:text-white"
                    >
                        Eliminar
                    </button>

                </div>
            </div>

            {/* CURSOS */}
            <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">

                {padre.grupos.map((grupo) => (
                    <div
                        key={grupo.id}
                        className="border-2 border-[#0A1718] bg-[#F9FBFB] p-3"
                    >
                        <h5 className="font-bold uppercase text-sm">
                            {grupo.curso.nombre_curso}
                        </h5>

                        <span className="mt-2 inline-block border px-2 py-1 text-[10px] uppercase">
                            {grupo.activo ? 'Activo' : 'Inactivo'}
                        </span>
                    </div>
                ))}

            </div>
        </div>
    )
}