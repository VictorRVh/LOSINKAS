import { useForm } from '@inertiajs/react'
import { useState } from 'react'
import { z } from 'zod'
import Button from '../UI/Button'

const createUserSchema = (isEdit) =>
    z.object({
        name: z.string().min(1, 'El nombre es obligatorio'),
        email: z.string().email('Email inválido'),

        password: isEdit
            ? z.string().optional()
            : z.string().min(8, 'Mínimo 8 caracteres'),

        password_confirmation: isEdit
            ? z.string().optional()
            : z.string(),
    }).refine((data) => {
        if (isEdit) return true
        return data.password === data.password_confirmation
    }, {
        message: 'Las contraseñas no coinciden',
        path: ['password_confirmation'],
    })

export default function UserForm({ user = null, onClose }) {
    const [formErrors, setFormErrors] = useState({})
    const isEdit = Boolean(user)

    const {
        data,
        setData,
        post,
        put,
        processing,
        reset,
        clearErrors,
    } = useForm({
        name: user?.name ?? '',
        email: user?.email ?? '',
        password: '',
        password_confirmation: '',
    })

    const submit = (e) => {
        e.preventDefault()

        const schema = createUserSchema(isEdit)
        const result = schema.safeParse(data)

        if (!result.success) {
            const formatted = result.error.flatten().fieldErrors
            setFormErrors(formatted)
            return
        }

        setFormErrors({})

        const method = isEdit ? put : post
        const url = isEdit
            ? route('users.update', user.id)
            : route('users.store')

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
                    value={data.name}
                    onChange={e => setData('name', e.target.value)}
                    className="border p-2 w-full"
                />

                {/* PODEMOS USAR 'ERRORS' DEL BACKEND */}

                {formErrors.name && (
                    <p className="text-red-500 text-sm">{formErrors.name}</p>
                )}
            </div>

            <div>
                <input
                    placeholder="Email"
                    value={data.email}
                    onChange={e => setData('email', e.target.value)}
                    className="border p-2 w-full"
                />
                {formErrors.email && (
                    <p className="text-red-500 text-sm">{formErrors.email}</p>
                )}
            </div>

            {!isEdit && (
                <>
                    <input
                        type="password"
                        placeholder="Contraseña"
                        value={data.password}
                        onChange={e => setData('password', e.target.value)}
                        className="border p-2 w-full"
                    />
                    {formErrors.password && (
                        <p className="text-red-500 text-sm">{formErrors.password}</p>
                    )}

                    <input
                        type="password"
                        placeholder="Confirmar contraseña"
                        value={data.password_confirmation}
                        onChange={e =>
                            setData('password_confirmation', e.target.value)
                        }
                        className="border p-2 w-full"
                    />
                    {formErrors.password_confirmation && (
                        <p className="text-red-500 text-sm">
                            {formErrors.password_confirmation}
                        </p>
                    )}
                </>
            )}

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