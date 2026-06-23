import Modal from '@/Components/UI/Modal'
import GradoForm from './GradoForm'

export default function GradoModal({ open, onClose, grado, nivel }) {

    const isEdit = Boolean(grado)

    return (
        <Modal
            show={open}
            title={isEdit ? 'Editar Grado' : 'Nuevo Grado'}
            onClose={onClose}
        >
            <GradoForm
                grado={grado}
                nivel={nivel}
                onSuccess={onClose}
            />
        </Modal>
    )
}