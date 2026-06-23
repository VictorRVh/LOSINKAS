import { usePage } from '@inertiajs/react'
import Sidebar from '@/Layouts/Sidebar'

export default function AppLayout({ header, children }) {
    const { props } = usePage()
    const user = props.auth?.user

    return (
        <div className="flex min-h-screen bg-gray-100">
            {/* SIDEBAR */}
            {user && <Sidebar user={user} />}
            {/* CONTENT */}
            <div className="flex-1 flex flex-col">
                {/* HEADER */}
                {header && (
                    <header className="bg-white shadow px-6 py-4">
                        {header}
                    </header>
                )}

                {/* PAGE CONTENT */}
                <main className="flex-1 p-6">
                    {children}
                </main>
            </div>
        </div>
    )
}