import Modal from '@/Components/UI/Modal'
import NivelForm from './NivelForm'

export default function NivelModal({ open, onClose, nivel }) {

    const isEdit = Boolean(nivel)

    return (
        <Modal
            show={open}
            title={isEdit ? 'Editar Nivel' : 'Nuevo Nivel'}
            onClose={onClose}
        >
            <NivelForm
                nivel={nivel}
                onSuccess={onClose}
            />
        </Modal>
    )
}