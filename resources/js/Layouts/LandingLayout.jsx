import Header from '@/Components/Landing/Header'

export default function LandingLayout({
    children,
    canLogin,
    canRegister,
    authUser,
}) {
    return (
        <>
            <Header
                canLogin={canLogin}
                canRegister={canRegister}
                authUser={authUser}
            />

            <main>
                {children}
            </main>
        </>
    )
}