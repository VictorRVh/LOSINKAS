import React from 'react';

export default function GuestLayout({ children }) {
    return (
        <>
            {/* HEADER */}
            <header className="fixed left-0 top-0 z-10 w-full border-b-2 border-[#0A1718] bg-[#FFFFFF]">
                <div className="flex w-full items-center gap-4 px-4 py-3 lg:px-6">
                    <img
                        src="/img/inkascan.jpeg"
                        alt="InkaScan"
                        className="h-14 w-auto object-contain lg:h-16"
                    />

                    <div className="border-l border-[#5C6F72]/30 pl-4">
                        <p className="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.22em] text-[#008080]">
                            Inkascan / Panel
                        </p>

                        <p className="mt-1 font-['Space_Grotesk',sans-serif] text-lg font-bold uppercase tracking-[-0.03em]">
                            Control de Usuarios
                        </p>
                    </div>
                </div>
            </header>

            {/* MAIN */}
            <main className="min-h-screen px-4 pb-10 pt-32 lg:px-8">
                <div className="mx-auto w-full max-w-6xl">
                    {children}
                </div>
            </main>
        </>
    );
}