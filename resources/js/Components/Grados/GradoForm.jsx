import { useForm } from '@inertiajs/react'
import { useState } from 'react'

export default function GradoForm({ grado = null, nivel, onSuccess }) {

    const isEdit = Boolean(grado)
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
        nombre_grado: grado?.nombre_grado ?? '',
        nivel_id: nivel?.id ?? grado?.nivel_id ?? '',
    })

    // =========================
    // SUBMIT
    // =========================
    const submit = (e) => {
        e.preventDefault()

        const url = isEdit
            ? route('grados.update', grado.id)
            : route('grados.store', nivel.id)

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

            {/* =========================
                NOMBRE GRADO
            ========================= */}
            <div>
                <label className="mb-2 block text-xs font-bold uppercase text-[#5C6F72]">
                    Nombre del grado
                </label>

                <input
                    type="text"
                    value={data.nombre_grado}
                    onChange={(e) => setData('nombre_grado', e.target.value)}
                    className="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-2"
                />

                {formErrors.nombre_grado && (
                    <p className="mt-1 text-sm text-red-500">
                        {formErrors.nombre_grado}
                    </p>
                )}
            </div>

            {/* =========================
                NIVEL (solo lectura)
            ========================= */}
            <div>
                <label className="mb-2 block text-xs font-bold uppercase text-[#5C6F72]">
                    Nivel
                </label>

                <input
                    type="text"
                    value={nivel?.nombre_nivel ?? ''}
                    disabled
                    className="w-full border-2 border-[#0A1718] bg-gray-100 px-4 py-2"
                />
            </div>

            {/* =========================
                BOTONES
            ========================= */}
            <div className="flex justify-end gap-3 pt-4">

                <button
                    type="button"
                    onClick={onSuccess}
                    className="border-2 border-[#0A1718] px-4 py-2 text-xs font-bold uppercase"
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