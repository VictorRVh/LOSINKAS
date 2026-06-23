import { useState } from 'react'
import { router } from '@inertiajs/react'
import AppLayout from '@/Layouts/AppLayout'
import UserModal from '@/Components/Users/UserModal'
import Button from '../../Components/UI/Button'
import ConfirmDeleteModal from '../../Components/UI/ConfirmDeleteModal'
import DataTable from '../../Components/UI/DataTable'
import UserPagination from '../../Components/Users/UserPagination'

const columns = [
    {
        label: 'ID',
        field: 'id',
    },
    {
        label: 'Nombre',
        field: 'name',
    },
    {
        label: 'Email',
        field: 'email',
    },
]

export default function Index({ usuarios }) {
    const [open, setOpen] = useState(false)
    const [editing, setEditing] = useState(null)

    const [openDelete, setOpenDelete] = useState(false)
    const [userToDelete, setUserToDelete] = useState(null)
    const [processing, setProcessing] = useState(false)

    const handleDelete = () => {
        setProcessing(true)

        router.delete(route('users.destroy', userToDelete.id), {
            onSuccess: () => {
                setOpenDelete(false)
                setUserToDelete(null)
            },
            onFinish: () => setProcessing(false),
        })
    }

    return (
        <AppLayout header={<h2>Usuarios</h2>}>
            <div className="flex justify-between mb-4">
                <h1 className="text-xl font-bold">
                    Listado de usuarios
                </h1>

                <Button
                    color="teal"
                    onClick={() => {
                        setEditing(null)
                        setOpen(true)
                    }}
                >
                    Crear usuario
                </Button>
            </div>

            <DataTable
                columns={columns}
                data={usuarios.data}
                actions={(user) => (
                    <div className="flex gap-2">

                        <button
                            onClick={() => {
                                setEditing(user)
                                setOpen(true)
                            }}
                            className="border-2 border-[#0A1718] px-2 py-1 text-[10px] font-bold uppercase text-[#FF7F50]"
                        >
                            Editar
                        </button>

                        <button
                            onClick={() => {
                                setUserToDelete(user)
                                setOpenDelete(true)
                            }}
                            className="border-2 border-red-500 px-2 py-1 text-[10px] font-bold uppercase text-red-500"
                        >
                            Eliminar
                        </button>

                    </div>
                )}
            />

            <UserPagination links={usuarios.links} />


            {/* MODAL CREATE / EDIT */}
            <UserModal
                open={open}
                user={editing}
                onClose={() => setOpen(false)}
            />

            <ConfirmDeleteModal
                open={openDelete}
                itemName={userToDelete?.name ?? ''}
                processing={processing}
                onClose={() => setOpenDelete(false)}
                onConfirm={handleDelete}
            />
        </AppLayout>
    )
}