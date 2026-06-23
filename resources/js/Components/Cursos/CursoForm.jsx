import { useForm } from '@inertiajs/react'
import { useState } from 'react'

export default function CursoForm({ curso = null, grado, onSuccess }) {

    const isEdit = Boolean(curso)
    const [formErrors, setFormErrors] = useState({})

    const {
        data,
        setData,
        post,
        put,
        processing,
        reset,
        clearErrors,
    } = useForm({
        nombre_curso: curso?.nombre_curso ?? '',
        grado_id: grado?.id ?? curso?.grado_id ?? '',
    })

    const submit = (e) => {
        e.preventDefault()

        const url = isEdit
            ? route('cursos.update', curso.id)
            : route('cursos.store', grado.id)

        const method = isEdit ? put : post

        method(url, {
            preserveScroll: true,
            onSuccess: () => {
                reset()
                clearErrors()
                setFormErrors({})
                onSuccess()
            },
        })
    }

    return (
        <form onSubmit={submit} className="space-y-5 p-5">

            <div>
                <label className="text-xs font-bold uppercase text-[#5C6F72]">
                    Nombre del curso
                </label>

                <input
                    value={data.nombre_curso}
                    onChange={(e) => setData('nombre_curso', e.target.value)}
                    className="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-2"
                />
            </div>

            <div>
                <label className="text-xs font-bold uppercase text-[#5C6F72]">
                    Grado
                </label>

                <input
                    value={grado?.nombre_grado ?? ''}
                    disabled
                    className="w-full border-2 border-[#0A1718] bg-gray-100 px-4 py-2"
                />
            </div>

            <div className="flex justify-end gap-3 pt-4">

                <button
                    type="button"
                    onClick={onSuccess}
                    className="border px-4 py-2 text-xs font-bold uppercase"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    disabled={processing}
                    className="border-2 border-[#0A1718] bg-[#008080] px-4 py-2 text-xs font-bold uppercase text-white"
                >
                    {processing
                        ? 'Guardando...'
                        : isEdit
                            ? 'Actualizar'
                            : 'Crear'}
                </button>

            </div>

        </form>
    )
}