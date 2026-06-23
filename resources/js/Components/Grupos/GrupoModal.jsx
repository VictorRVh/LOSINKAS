import Modal from '@/Components/UI/Modal'
import GrupoForm from './GrupoForm'

export default function GrupoModal({
    open,
    onClose,
    periodos = [],
    niveles = [],
    secciones = [],
}) {
    return (
        <Modal
            show={open}
            onClose={onClose}
            title="[ GRUPO / NUEVO ]"
            maxWidth="2xl"
        >
            <GrupoForm
                periodos={periodos}
                niveles={niveles}
                secciones={secciones}
                onSuccess={onClose}
            />
        </Modal>
    )
}