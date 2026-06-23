import { useEffect, useState } from 'react'
import { router, usePage } from '@inertiajs/react'
import AppLayout from '@/Layouts/AppLayout'
import Button from '@/Components/UI/Button'
import ConfirmDeleteModal from '@/Components/UI/ConfirmDeleteModal'
import GrupoModal from '../../Components/Grupos/GrupoModal'
import axios from 'axios'


export default function Index({
    padres,
    periodos,
    niveles,
    secciones,
    filtros,
}) {
    const [openCreate, setOpenCreate] = useState(false)
    const [openDelete, setOpenDelete] = useState(false)
    const [padreToDelete, setPadreToDelete] = useState(null)
    const [processing, setProcessing] = useState(false)

    const [grados, setGrados] = useState([])

    const [localFilters, setLocalFilters] = useState(filtros)

    /* =======================
       FILTROS
    ======================= */

    const handleFilterChange = (e) => {
        const { name, value } = e.target

        setLocalFilters(prev => ({
            ...prev,
            [name]: value,
        }))
    }

    const handleNivelChange = (e) => {
        const value = e.target.value

        setLocalFilters(prev => ({
            ...prev,
            nivel_id: value,
            grado_id: ''
        }))

        cargarGrados(value)
    }

    const applyFilters = () => {
        router.get(route('grupos.index'), localFilters, {
            preserveState: true,
            replace: true,
        })
    }

    const cargarGrados = (nivelId) => {
        if (!nivelId) {
            setGrados([])
            return
        }

        axios
            .get(route('grupos.grados.por-nivel'), {
                params: { nivel_id: nivelId },
            })
            .then(res => setGrados(res.data))
    }

    const clearFilters = () => {
        const reset = {
            periodo_id: '',
            nivel_id: '',
            grado_id: '',
        }

        setLocalFilters(reset)

        setGrados([])

        router.get(route('grupos.index'), reset, {
            preserveState: true,
            replace: true,
        })
    }

    /* =======================
       ELIMINAR
    ======================= */
    const handleDelete = () => {
        setProcessing(true)

        router.delete(route('grupos.destroy', padreToDelete.id), {
            onFinish: () => {
                setProcessing(false)
                setOpenDelete(false)
                setPadreToDelete(null)
            },
        })
    }

    return (
        <AppLayout header={<h2>Grupos</h2>}>

            {/* HEADER */}
            <div className="flex justify-between mb-6">
                <div>
                    <p className="text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                        [ GRID / GRUPOS ]
                    </p>
                    <h3 className="mt-1 text-lg font-bold uppercase text-[#0A1718]">
                        Grupos por sección
                    </h3>
                </div>

                <Button color="teal" onClick={() => setOpenCreate(true)}>
                    Crear Grupo
                </Button>
            </div>

            {/* FILTROS */}
            <div className="border-2 border-[#0A1718] p-5 mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">

                {/* PERIODO */}
                <select
                    name="periodo_id"
                    value={localFilters.periodo_id}
                    onChange={handleFilterChange}
                    className="border-2 border-[#0A1718] px-3 py-2"
                >
                    <option value="">Todos los periodos</option>
                    {periodos.map(p => (
                        <option key={p.id} value={p.id}>
                            {p.nombre_periodo}
                        </option>
                    ))}
                </select>

                {/* NIVEL */}
                <select
                    name="nivel_id"
                    value={localFilters.nivel_id}
                    onChange={handleNivelChange}
                    className="border-2 border-[#0A1718] px-3 py-2"
                >
                    <option value="">Todos los niveles</option>
                    {niveles.map(n => (
                        <option key={n.id} value={n.id}>
                            {n.nombre_nivel}
                        </option>
                    ))}
                </select>

                {/* GRADO (solo visual por ahora, luego lo hacemos dinámico) */}
                <select
                    name="grado_id"
                    value={localFilters.grado_id}
                    onChange={handleFilterChange}
                    className="border-2 border-[#0A1718] px-3 py-2"
                >
                    <option value="">Todos los grados</option>

                    {grados.map(g => (
                        <option key={g.id} value={g.id}>
                            {g.nombre_grado}
                        </option>
                    ))}
                </select>

                <div className="md:col-span-3 flex gap-2 mt-2">
                    <Button color="teal" onClick={applyFilters}>
                        Filtrar
                    </Button>

                    <button
                        onClick={clearFilters}
                        className="border-2 border-red-500 px-4 py-2 text-sm font-bold uppercase text-red-500 hover:bg-red-500 hover:text-white"
                    >
                        Limpiar
                    </button>
                </div>
            </div>

            {/* CONTENIDO */}
            <div className="space-y-6">

                {padres.length === 0 && (
                    <p className="text-center text-sm text-[#5C6F72]">
                        No hay registros
                    </p>
                )}

                {padres.map(padre => (
                    <div
                        key={padre.id}
                        className="border-2 border-[#0A1718] p-4"
                    >
                        <div className="flex justify-between items-center">
                            <div>
                                <h4 className="font-bold uppercase">
                                    {padre.grado.nombre_grado} - {padre.seccion.nombre_seccion}
                                </h4>

                                <p className="text-xs text-[#5C6F72]">
                                    Periodo: {padre.periodo.nombre_periodo} |
                                    Nivel: {padre.grado.nivel.nombre_nivel}
                                </p>

                                <p className="text-xs mt-1">
                                    Cursos asignados: <strong>{padre.grupos.length}</strong>
                                </p>
                            </div>

                            <div className="flex gap-2">
                                <Button
                                    color="teal"
                                    onClick={() =>
                                        router.visit(route('padre-grupos.cursos.index', padre.id))
                                    }
                                >
                                    Ver cursos
                                </Button>

                                <button
                                    onClick={() => {
                                        setPadreToDelete(padre)
                                        setOpenDelete(true)
                                    }}
                                    className="border-2 border-red-500 px-3 py-2 text-xs font-bold uppercase text-red-500 hover:bg-red-500 hover:text-white"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>

                    </div>
                ))}
            </div>

            {/* MODAL CREAR */}
            <GrupoModal
                open={openCreate}
                onClose={() => setOpenCreate(false)}
                periodos={periodos}
                niveles={niveles}
                secciones={secciones}
            />

            {/* MODAL ELIMINAR */}
            <ConfirmDeleteModal
                open={openDelete}
                itemName={
                    padreToDelete
                        ? `${padreToDelete.grado.nombre_grado} - ${padreToDelete.seccion.nombre_seccion}`
                        : ''
                }
                processing={processing}
                onClose={() => setOpenDelete(false)}
                onConfirm={handleDelete}
            />

        </AppLayout>
    )
}