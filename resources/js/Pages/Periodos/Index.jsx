import { useState } from 'react'
import { router } from '@inertiajs/react'
import AppLayout from '@/Layouts/AppLayout'
import Button from '../../Components/UI/Button'
import ConfirmDeleteModal from '../../Components/UI/ConfirmDeleteModal'
import DataTable from '../../Components/UI/DataTable'
import UserPagination from '../../Components/Users/UserPagination'
import PeriodoModal from '../../Components/Periodos/PeriodoModal'

const columns = [
    {
        label: 'ID',
        field: 'id',
    },
    {
        label: 'Periodo',
        field: 'nombre_periodo',
    },
]

export default function Index({ periodos }) {
    const [open, setOpen] = useState(false)
    const [editing, setEditing] = useState(null)

    const [openDelete, setOpenDelete] = useState(false)
    const [periodoToDelete, setPeriodoToDelete] = useState(null)
    const [processing, setProcessing] = useState(false)

    const handleDelete = () => {
        setProcessing(true)

        router.delete(route('periodos.destroy', periodoToDelete.id), {
            onSuccess: () => {
                setOpenDelete(false)
                setPeriodoToDelete(null)
            },
            onFinish: () => setProcessing(false),
        })
    }

    return (
        <AppLayout header={<h2>Periodos</h2>}>
            <div className="flex justify-between mb-4">
                <h1 className="text-xl font-bold">
                    Listado de Periodos
                </h1>

                <Button
                    color="teal"
                    onClick={() => {
                        setEditing(null)
                        setOpen(true)
                    }}
                >
                    Crear periodo
                </Button>
            </div>

            <DataTable
                columns={columns}
                data={periodos.data}
                actions={(periodo) => (
                    <div className="flex gap-2">

                        <button
                            onClick={() => {
                                setEditing(periodo)
                                setOpen(true)
                            }}
                            className="border-2 border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase text-[#FF7F50]"
                        >
                            Editar
                        </button>

                        <button
                            onClick={() => {
                                setPeriodoToDelete(periodo)
                                setOpenDelete(true)
                            }}
                            className="border-2 border-red-500 px-2 py-1 text-[10px] font-bold uppercase text-red-500"
                        >
                            Eliminar
                        </button>

                    </div>
                )}
            />

            {/* <UserPagination links={usuarios.links} /> */}


            {/* MODAL CREATE / EDIT */}
            <PeriodoModal
                open={open}
                periodo={editing}
                onClose={() => setOpen(false)}
            />

            <ConfirmDeleteModal
                open={openDelete}
                itemName={periodoToDelete?.nombre_periodo ?? ''}
                processing={processing}
                onClose={() => setOpenDelete(false)}
                onConfirm={handleDelete}
            />
        </AppLayout>
    )
}