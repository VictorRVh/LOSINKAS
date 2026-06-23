import { useForm } from '@inertiajs/react'
import { useEffect, useState } from 'react'

export default function NivelForm({ nivel = null, onSuccess }) {

    const isEdit = Boolean(nivel)

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
        nombre_nivel: nivel?.nombre_nivel ?? '',
        descripcion: nivel?.descripcion ?? '',
        activo: nivel?.activo ?? true,
    })

    // =========================
    // SUBMIT
    // =========================
    const submit = (e) => {
        e.preventDefault()

        const url = isEdit
            ? route('niveles.update', nivel.id)
            : route('niveles.store')

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
                NOMBRE
            ========================= */}
            <div>
                <label className="mb-2 block text-xs font-bold uppercase text-[#5C6F72]">
                    Nombre del nivel
                </label>

                <input
                    type="text"
                    value={data.nombre_nivel}
                    onChange={(e) => setData('nombre_nivel', e.target.value)}
                    className="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-2"
                />

                {formErrors.nombre_nivel && (
                    <p className="mt-1 text-sm text-red-500">
                        {formErrors.nombre_nivel}
                    </p>
                )}
            </div>

            {/* =========================
                DESCRIPCIÓN
            ========================= */}
            <div>
                <label className="mb-2 block text-xs font-bold uppercase text-[#5C6F72]">
                    Descripción
                </label>

                <textarea
                    value={data.descripcion}
                    onChange={(e) => setData('descripcion', e.target.value)}
                    rows={3}
                    className="w-full border-2 border-[#0A1718] bg-[#F4F7F7] px-4 py-2"
                />

                {formErrors.descripcion && (
                    <p className="mt-1 text-sm text-red-500">
                        {formErrors.descripcion}
                    </p>
                )}
            </div>

            {/* =========================
                ACTIVO
            ========================= */}
            <label className="flex items-center gap-2 text-xs font-bold uppercase text-[#5C6F72]">
                <input
                    type="checkbox"
                    checked={data.activo}
                    onChange={(e) => setData('activo', e.target.checked)}
                />
                Activo
            </label>

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