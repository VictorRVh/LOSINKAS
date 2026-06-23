import { useState } from 'react'
import { router, Link } from '@inertiajs/react'

import AppLayout from '@/Layouts/AppLayout'
import Button from '@/Components/UI/Button'
import CursoModal from '@/Components/Cursos/CursoModal'
import ConfirmDeleteModal from '@/Components/UI/ConfirmDeleteModal'

export default function Index({ grado, cursos }) {

    const [open, setOpen] = useState(false)
    const [editing, setEditing] = useState(null)

    const [openDelete, setOpenDelete] = useState(false)
    const [selected, setSelected] = useState(null)
    const [processing, setProcessing] = useState(false)

    const handleDelete = () => {
        setProcessing(true)

        router.delete(route('cursos.destroy', selected.id), {
            onSuccess: () => {
                setOpenDelete(false)
                setSelected(null)
            },
            onFinish: () => setProcessing(false),
        })
    }

    return (
        <AppLayout header={<h2>Cursos - {grado.nombre_grado}</h2>}>

            {/* HEADER */}
            <div className="mb-5 flex justify-between">

                <Link
                    href={route('niveles.grados', grado.nivel_id)}
                    className="border px-3 py-2 text-xs font-bold uppercase"
                >
                    ← Volver
                </Link>

                <Button
                    color="teal"
                    onClick={() => {
                        setEditing(null)
                        setOpen(true)
                    }}
                >
                    Agregar curso
                </Button>
            </div>

            {/* GRID */}
            <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                {cursos.length === 0 ? (
                    <div className="col-span-3 border p-5 text-center text-sm">
                        No hay cursos registrados
                    </div>
                ) : (
                    cursos.map((curso) => (
                        <div
                            key={curso.id}
                            className="border-2 border-[#0A1718] bg-white p-4"
                        >

                            <h3 className="font-bold uppercase">
                                {curso.nombre_curso}
                            </h3>

                            <div className="mt-4 flex gap-2">

                                <button
                                    onClick={() => {
                                        setEditing(curso)
                                        setOpen(true)
                                    }}
                                    className="border px-2 py-1 text-[10px] font-bold uppercase text-[#FF7F50]"
                                >
                                    Editar
                                </button>

                                <button
                                    onClick={() => {
                                        setSelected(curso)
                                        setOpenDelete(true)
                                    }}
                                    className="border px-2 py-1 text-[10px] font-bold uppercase text-red-500"
                                >
                                    Borrar
                                </button>

                            </div>

                        </div>
                    ))
                )}

            </div>

            {/* MODAL */}
            <CursoModal
                open={open}
                curso={editing}
                grado={grado}
                onClose={() => setOpen(false)}
            />

            {/* DELETE */}
            <ConfirmDeleteModal
                open={openDelete}
                itemName={selected?.nombre_curso}
                processing={processing}
                onClose={() => setOpenDelete(false)}
                onConfirm={handleDelete}
            />

        </AppLayout>
    )
}