import { useState } from 'react'
import { router, Link } from '@inertiajs/react'

import AppLayout from '@/Layouts/AppLayout'
import Button from '@/Components/UI/Button'

import GradoModal from '@/Components/Grados/GradoModal'
import ConfirmDeleteModal from '@/Components/UI/ConfirmDeleteModal'

export default function Index({ nivel, grados }) {

    const [open, setOpen] = useState(false)
    const [editing, setEditing] = useState(null)

    const [openDelete, setOpenDelete] = useState(false)
    const [selected, setSelected] = useState(null)
    const [processing, setProcessing] = useState(false)

    const handleDelete = () => {
        setProcessing(true)

        router.delete(route('grados.destroy', selected.id), {
            onSuccess: () => {
                setOpenDelete(false)
                setSelected(null)
            },
            onFinish: () => setProcessing(false),
        })
    }

    return (
        <AppLayout header={<h2>Grados - {nivel.nombre_nivel}</h2>}>

            {/* HEADER */}
            <div className="mb-5 flex justify-between">
                <Link
                    href={route('niveles.index')}
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
                    Agregar grado
                </Button>
            </div>

            {/* GRID */}
            <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                {grados.length === 0 ? (
                    <div className="col-span-3 border p-5 text-center text-sm">
                        No hay grados registrados
                    </div>
                ) : (
                    grados.map((grado) => (
                        <div
                            key={grado.id}
                            className="border-2 border-[#0A1718] bg-white p-4"
                        >

                            <h3 className="font-bold uppercase">
                                {grado.nombre_grado}
                            </h3>

                            {/* ACCIONES */}
                            <div className="mt-4 flex gap-2">

                                {/* VER CURSOS */}
                                <Link
                                    href={route('grados.cursos', grado.id)}
                                    className="border px-2 py-1 text-[10px] font-bold uppercase"
                                >
                                    Ver cursos
                                </Link>

                                {/* EDITAR */}
                                <button
                                    onClick={() => {
                                        setEditing(grado)
                                        setOpen(true)
                                    }}
                                    className="border px-2 py-1 text-[10px] font-bold uppercase text-[#FF7F50]"
                                >
                                    Editar
                                </button>

                                {/* ELIMINAR */}
                                <button
                                    onClick={() => {
                                        setSelected(grado)
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
            <GradoModal
                open={open}
                grado={editing}
                nivel={nivel}
                onClose={() => setOpen(false)}
            />

            {/* DELETE */}
            <ConfirmDeleteModal
                open={openDelete}
                itemName={selected?.nombre_grado}
                processing={processing}
                onClose={() => setOpenDelete(false)}
                onConfirm={handleDelete}
            />

        </AppLayout>
    )
}