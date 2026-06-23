import Modal from '@/Components/UI/Modal'
import PeriodoForm from './PeriodoForm'

export default function PeriodoModal({ open, onClose, periodo }) {
    return (
        <Modal
            show={open}
            title={periodo ? 'Editar Periodo' : 'Nuevo Periodo'}
            onClose={onClose}
        >
            <PeriodoForm
                periodo={periodo}
                onClose={onClose}
            />
        </Modal>
    )
}