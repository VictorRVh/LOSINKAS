import Button from '@/Components/UI/Button'

export default function ConfirmDeleteModal({
    open,
    onClose,
    onConfirm,
    itemName,
    processing = false,
}) {
    if (!open) return null

    return (
        <div className="fixed inset-0 flex items-center justify-center bg-black/40">
            <div className="w-full max-w-md border-2 border-[#0A1718] bg-white p-5">

                <p className="text-xs font-bold uppercase tracking-[0.18em] text-[#FF7F50]">
                    Acción irreversible
                </p>

                <p className="mt-3 text-sm text-[#0A1718]/80">
                    Estas seguro de que deseas eliminar{' '}
                    <strong>{itemName}</strong>?
                </p>

                <div className="mt-5 flex justify-end gap-3">

                    <Button
                        type="button"
                        color="white"
                        onClick={onClose}
                    >
                        Cancelar
                    </Button>

                    <Button
                        type="button"
                        color="coral"
                        onClick={onConfirm}
                        disabled={processing}
                    >
                        {processing ? 'Eliminando...' : 'Sí, eliminar'}
                    </Button>

                </div>
            </div>
        </div>
    )
}