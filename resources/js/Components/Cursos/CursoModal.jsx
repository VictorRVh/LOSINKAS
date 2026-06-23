import Modal from '@/Components/UI/Modal'
import CursoForm from './CursoForm'

export default function CursoModal({ open, onClose, curso, grado }) {

    const isEdit = Boolean(curso)

    return (
        <Modal
            show={open}
            title={isEdit ? 'Editar Curso' : 'Nuevo Curso'}
            onClose={onClose}
        >
            <CursoForm
                curso={curso}
                grado={grado}
                onSuccess={onClose}
            />
        </Modal>
    )
}