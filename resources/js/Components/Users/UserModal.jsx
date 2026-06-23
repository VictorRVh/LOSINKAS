import Modal from '@/Components/UI/Modal'
import UserForm from './UserForm'

export default function UserModal({ open, onClose, user }) {
    return (
        <Modal
            show={open}
            title={user ? 'Editar Usuario' : 'Nuevo Usuario'}
            onClose={onClose}
        >
            <UserForm
                user={user}
                onClose={onClose}
            />
        </Modal>
    )
}