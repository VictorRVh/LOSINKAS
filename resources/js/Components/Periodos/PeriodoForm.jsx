import { useForm } from '@inertiajs/react'
import { useState } from 'react'
import { z } from 'zod'
import Button from '../UI/Button'

const createPeriodoSchema = (isEdit) =>
    z.object({
        nombre_periodo: z.string().min(1, 'El periodo es obligatorio'),
    })

export default function PeriodoForm({ periodo = null, onClose }) {
    const [formErrors, setFormErrors] = useState({})
    const isEdit = Boolean(periodo)

    const {
        data,
        setData,
        post,
        put,
        processing,
        reset,
        clearErrors,
    } = useForm({
        nombre_periodo: periodo?.nombre_periodo ?? '',
    })

    const submit = (e) => {
        e.preventDefault()

        const schema = createPeriodoSchema(isEdit)
        const result = schema.safeParse(data)

        if (!result.success) {
            const formatted = result.error.flatten().fieldErrors
            setFormErrors(formatted)
            return
        }

        setFormErrors({})

        const method = isEdit ? put : post
        const url = isEdit
            ? route('periodos.update', periodo.id)
            : route('periodos.store')

        method(url, {
            preserveScroll: true,
            onSuccess: () => {
                reset()
                clearErrors()
                setFormErrors({})
                onClose()
            },
        })
    }

    return (
        <form onSubmit={submit} className="p-6 space-y-4">

            <div>
                <input
                    placeholder="Nombre"
                    value={data.nombre_periodo}
                    onChange={e => setData('nombre_periodo', e.target.value)}
                    className="border p-2 w-full"
                />

                {/* PODEMOS USAR 'ERRORS' DEL BACKEND */}

                {formErrors.nombre_periodo && (
                    <p className="text-red-500 text-sm">{formErrors.nombre_periodo}</p>
                )}
            </div>

            <div className="flex justify-end gap-3 pt-4">

                {/* Cancelar */}
                <Button
                    type="button"
                    color="white"
                    onClick={onClose}
                >
                    Cancelar
                </Button>

                {/* Submit */}
                <Button
                    type="submit"
                    color="coral"
                    disabled={processing}
                >
                    {processing
                        ? 'Guardando...'
                        : isEdit
                            ? 'Actualizar'
                            : 'Crear'}
                </Button>

            </div>
        </form>
    )
}