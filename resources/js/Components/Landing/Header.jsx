import { Link } from '@inertiajs/react'
import Button from '../UI/Button'

export default function Header({ canLogin, canRegister, authUser }) {
    return (
        <header className="border-b border-[#0A1718]">
            <div className="mx-auto flex items-center justify-between px-6 py-2 lg:px-10">

                {/* LOGO */}
                <a href="#top" className="flex items-center gap-4 border-r pr-5">
                    <img
                        src="/img/inkascan.jpeg"
                        alt="InkaScan"
                        className="h-14 w-auto object-contain lg:h-16"
                    />
                    <span className="hidden lg:flex text-xs font-bold uppercase tracking-[0.18em] text-[#008080]">
                        Vision OMR
                    </span>
                </a>

                {/* NAV + AUTH */}
                <div className="flex items-center gap-4">

                    {/* NAV INFO */}
                    <nav className="hidden md:flex text-sm uppercase tracking-[0.14em]">
                        <a href="#features">Features</a>
                        <span className="px-3">|</span>
                        <a href="#about">About</a>
                    </nav>

                    {/* AUTH */}
                    {canLogin && (
                        authUser ? (
                            <Link
                                href={route('dashboard')}
                                className="border px-5 py-1.5 text-sm"
                            >
                                Dashboard
                            </Link>
                        ) : (
                            <div className="flex gap-2">
                                <Link
                                    href={route('login')}
                                >
                                    <Button
                                        type="button"
                                        color="white"
                                    >
                                        Login
                                    </Button>
                                </Link>

                                {canRegister && (
                                    <Link
                                        href={route('register')}
                                    >
                                        <Button
                                            type="button"
                                            color="white"
                                        >
                                            Registro
                                        </Button>
                                    </Link>
                                )}
                            </div>
                        )
                    )}
                </div>
            </div>
        </header>
    )
}