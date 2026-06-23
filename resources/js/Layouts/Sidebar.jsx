import { Link, usePage } from '@inertiajs/react';

export default function Sidebar({ user }) {
    if (!user) return null
    const { url } = usePage();

    const isActive = (route) => url.startsWith(route);

    return (
        <aside className="w-[260px] border-r-2 border-[#0A1718] bg-[#F5F5F5] flex flex-col">
            {/* LOGO */}
            <div className="border-b-2 border-[#0A1718] p-5">
                <h1 className="font-bold tracking-[0.2em] uppercase text-[#008080]">
                    INKASCAN / PANEL
                </h1>
            </div>

            {/* MENU */}
            <nav className="flex-1 p-4 space-y-3">
                <SidebarLink href={route('dashboard')} active={route().current('dashboard')}>
                    Dashboard
                </SidebarLink>

                <SidebarLink href={route('users.index')} active={route().current('users.*')}>
                    Usuarios
                </SidebarLink>

                <SidebarLink href={route('niveles.index')} active={route().current('niveles.*')}>
                    Instituciones
                </SidebarLink>

                <SidebarLink href={route('grupos.index')} active={route().current('grupos.*')}>
                    Grupos
                </SidebarLink>

                <SidebarLink href={route('periodos.index')} active={route().current('periodos.*')}>
                    Periodos
                </SidebarLink>

                <SidebarLink href={route('matriculas.index')} active={route().current('matriculas.*')}>
                    Matriculas
                </SidebarLink>

                {/* <SidebarLink href={route('estudiantes.index')} active={route().current('estudiantes.*')}>
                    Estudiantes
                </SidebarLink> */}
            </nav>

            {/* USER */}
            <div className="border-t-2 border-[#0A1718] p-4">
                <div className="flex items-center gap-3 mb-4">
                    <div className="flex h-12 w-12 items-center justify-center border-2 border-[#0A1718] bg-[#008080] text-white font-bold text-lg">
                        {user.name.charAt(0).toUpperCase()}
                    </div>

                    <div>
                        <p className="font-bold">{user.name}</p>
                        <p className="text-xs text-[#5C6F72]">{user.email}</p>
                    </div>
                </div>

                <Link
                    href={route('profile.edit')}
                    className="block text-center border-2 border-[#0A1718] bg-white py-2 font-bold uppercase tracking-[0.12em] hover:bg-[#008080] hover:text-white mb-2"
                >
                    Mi Perfil
                </Link>

                <Link
                    method="post"
                    href={route('logout')}
                    as="button"
                    className="w-full border-2 border-[#0A1718] bg-[#FF7F50] text-white py-2 font-bold uppercase"
                >
                    Salir
                </Link>
            </div>
        </aside>
    );
}

function SidebarLink({ href, active, children }) {
    return (
        <Link
            href={href}
            className={`flex items-center border-2 border-[#0A1718] px-4 py-3 font-bold uppercase tracking-[0.12em]
            ${active ? 'bg-[#008080] text-white' : 'hover:bg-[#008080] hover:text-white'}`}
        >
            {children}
        </Link>
    );
}