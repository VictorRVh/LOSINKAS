import { useState } from 'react'
import { router, Link } from '@inertiajs/react'

import AppLayout from '@/Layouts/AppLayout'
import Button from '@/Components/UI/Button'
import Card from '@/Components/UI/Card'
import ConfirmDeleteModal from '@/Components/UI/ConfirmDeleteModal'

import NivelModal from '@/Components/Niveles/NivelModal'

export default function Index({ niveles }) {

    // =========================
    // MODAL CREATE / EDIT
    // =========================
    const [open, setOpen] = useState(false)
    const [editing, setEditing] = useState(null)

    // =========================
    // DELETE MODAL
    // =========================
    const [openDelete, setOpenDelete] = useState(false)
    const [selected, setSelected] = useState(null)
    const [processing, setProcessing] = useState(false)

    // =========================
    // DELETE ACTION
    // =========================
    const handleDelete = () => {
        setProcessing(true)

        router.delete(route('niveles.destroy', selected.id), {
            onSuccess: () => {
                setOpenDelete(false)
                setSelected(null)
            },
            onFinish: () => setProcessing(false),
        })
    }

    return (
        <AppLayout header={<h2>Niveles</h2>}>

            {/* =========================
                HEADER
            ========================= */}
            <div className="mb-5 flex justify-between">
                <h1 className="text-xl font-bold uppercase">
                    Listado de niveles
                </h1>

                <Button
                    color="teal"
                    onClick={() => {
                        setEditing(null)
                        setOpen(true)
                    }}
                >
                    Agregar nivel
                </Button>
            </div>

            {/* =========================
                GRID DE CARDS
            ========================= */}
            <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                {niveles.data.length === 0 ? (
                    <div className="col-span-3 border-2 p-5 text-center text-sm">
                        No hay niveles registrados
                    </div>
                ) : (
                    niveles.data.map((nivel) => (
                        <Card
                            key={nivel.id}
                            title={nivel.nombre_nivel}
                            subtitle={nivel.activo ? 'Activo' : 'Inactivo'}
                        >

                            {/* DESCRIPCIÓN */}
                            <p className="text-sm text-[#5C6F72]">
                                {nivel.descripcion ?? 'Sin descripción'}
                            </p>

                            {/* INFO EXTRA */}
                            <div className="mt-2 text-xs uppercase tracking-wider text-[#5C6F72]">
                                Grados: {nivel.grados_count ?? 0}
                            </div>

                            {/* ACCIONES */}
                            <div className="mt-4 flex flex-wrap gap-2">

                                {/* VER GRADOS */}
                                <Link
                                    href={route('niveles.grados', nivel.id)}
                                    className="border-2 border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase hover:bg-[#0A1718] hover:text-white"
                                >
                                    Ver grados
                                </Link>

                                {/* EDITAR */}
                                <button
                                    onClick={() => {
                                        setEditing(nivel)
                                        setOpen(true)
                                    }}
                                    className="border-2 border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase text-[#FF7F50] hover:bg-[#008080] hover:text-white"
                                >
                                    Editar
                                </button>

                                {/* ELIMINAR */}
                                <button
                                    onClick={() => {
                                        setSelected(nivel)
                                        setOpenDelete(true)
                                    }}
                                    className="border-2 border-red-500 px-2 py-1 text-[10px] font-bold uppercase text-red-500 hover:bg-red-500 hover:text-white"
                                >
                                    Borrar
                                </button>

                            </div>

                        </Card>
                    ))
                )}

            </div>

            {/* =========================
                MODAL CREATE / EDIT
            ========================= */}
            <NivelModal
                open={open}
                nivel={editing}
                onClose={() => setOpen(false)}
            />

            {/* =========================
                MODAL DELETE
            ========================= */}
            <ConfirmDeleteModal
                open={openDelete}
                itemName={selected?.nombre_nivel}
                processing={processing}
                onClose={() => setOpenDelete(false)}
                onConfirm={handleDelete}
            />

        </AppLayout>
    )
}