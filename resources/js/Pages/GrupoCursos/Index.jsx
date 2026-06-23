import { useEffect, useState } from 'react'
import { router } from '@inertiajs/react'
import AppLayout from '@/Layouts/AppLayout'
import Button from '@/Components/UI/Button'
import axios from 'axios'

export default function Index({ padreGrupo, cursosAsignados }) {
    const [asignados, setAsignados] = useState(cursosAsignados)
    const [disponibles, setDisponibles] = useState([])
    const [loading, setLoading] = useState(false)

    /* ======================
       CARGAR DISPONIBLES
    ====================== */
    useEffect(() => {
        axios
            .get(route('padre-grupos.cursos.cursos-disponibles', padreGrupo.id))
            .then(res => setDisponibles(res.data))
    }, [])

    /* ======================
       AGREGAR CURSO
    ====================== */
    const addCurso = (cursoId) => {
        setLoading(true)

        axios
            .post(route('padre-grupos.cursos.store', padreGrupo.id), {
                curso_id: cursoId,
            })
            .then(() => {
                refreshLists()
            })
            .finally(() => setLoading(false))
    }

    /* ======================
       QUITAR CURSO
    ====================== */
    const removeCurso = (grupoId) => {
        setLoading(true)

        axios
            .delete(route('padre-grupos.grupos.destroy', grupoId))
            .then(() => {
                refreshLists()
            })
            .finally(() => setLoading(false))
    }

    /* ======================
       REFRESCAR LISTAS
    ====================== */
    const refreshLists = () => {
        axios
            .get(route('padre-grupos.cursos.cursos-disponibles', padreGrupo.id))
            .then(res => setDisponibles(res.data))

        axios
            .get(route('padre-grupos.cursos.json', padreGrupo.id))
            .then(res => setAsignados(res.data))
    }

    return (
        <AppLayout header={<h2>Cursos del Grupo</h2>}>

            {/* INFO */}
            <div className="border-2 border-[#0A1718] p-4 mb-6">
                <h3 className="font-bold uppercase">
                    {padreGrupo.grado.nombre_grado} - {padreGrupo.seccion.nombre_seccion}
                </h3>

                <p className="text-xs text-[#5C6F72]">
                    Periodo: {padreGrupo.periodo.nombre_periodo}
                </p>
            </div>

            {/* COLUMNAS */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">

                {/* ASIGNADOS */}
                <div className="border-2 border-[#0A1718] p-4">
                    <h4 className="font-bold mb-3">Cursos asignados</h4>

                    {asignados.length === 0 && (
                        <p className="text-sm text-[#5C6F72]">
                            No hay cursos asignados
                        </p>
                    )}

                    <ul className="space-y-2">
                        {asignados.map(grupo => (
                            <li
                                key={grupo.id}
                                className="flex justify-between items-center border p-2"
                            >
                                <span>{grupo.curso.nombre_curso}</span>

                                <button
                                    disabled={loading}
                                    onClick={() => removeCurso(grupo.id)}
                                    className="text-red-500 font-bold"
                                >
                                    ✖
                                </button>
                            </li>
                        ))}
                    </ul>
                </div>

                {/* DISPONIBLES */}
                <div className="border-2 border-[#0A1718] p-4">
                    <h4 className="font-bold mb-3">Cursos disponibles</h4>

                    {disponibles.length === 0 && (
                        <p className="text-sm text-[#5C6F72]">
                            No hay cursos disponibles
                        </p>
                    )}

                    <ul className="space-y-2">
                        {disponibles.map(curso => (
                            <li
                                key={curso.id}
                                className="flex justify-between items-center border p-2"
                            >
                                <span>{curso.nombre_curso}</span>

                                <button
                                    disabled={loading}
                                    onClick={() => addCurso(curso.id)}
                                    className="text-green-600 font-bold"
                                >
                                    +
                                </button>
                            </li>
                        ))}
                    </ul>
                </div>

            </div>

            {/* VOLVER */}
            <div className="mt-6">
                <Button onClick={() => router.visit(route('grupos.index'))}>
                    Volver
                </Button>
            </div>

        </AppLayout>
    )
}