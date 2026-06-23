export default function Modal({ show, title, onClose, children }) {
    if (!show) return null

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center px-4">
            {/* BACKDROP */}
            <div
                className="absolute inset-0 bg-[#0A1718]/50"
                onClick={onClose}
            />

            {/* MODAL */}
            <section
                className="relative z-10 w-full max-w-xl border-2 border-[#0A1718]
                           bg-white shadow-[8px_8px_0px_0px_rgba(10,23,24,1)]"
                onClick={e => e.stopPropagation()}
            >
                {title && (
                    <header className="flex items-center justify-between
                                      border-b-2 border-[#0A1718]
                                      px-5 py-4">
                        <p className="text-xs font-bold uppercase tracking-[0.2em] text-[#5C6F72]">
                            {title}
                        </p>

                        <button
                            type="button"
                            onClick={onClose}
                            className="border-2 border-[#0A1718] px-3 py-1
                                       text-xs font-bold uppercase"
                        >
                            X
                        </button>
                    </header>
                )}

                {children}
            </section>
        </div>
    )
}