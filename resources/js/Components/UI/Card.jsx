export default function Card({ title, subtitle, children, footer }) {
    return (
        <div className="border-2 border-[#0A1718] bg-white p-4 transition hover:-translate-y-1 hover:shadow-lg">

            <div className="flex items-start justify-between gap-3">
                <h3 className="font-bold uppercase tracking-wide">
                    {title}
                </h3>

                {subtitle && (
                    <span className="border border-[#5C6F72] px-2 py-1 text-[10px] uppercase">
                        {subtitle}
                    </span>
                )}
            </div>

            {children && (
                <div className="mt-2 text-sm text-[#5C6F72]">
                    {children}
                </div>
            )}

            {footer && (
                <div className="mt-4 flex flex-wrap gap-2">
                    {footer}
                </div>
            )}
        </div>
    )
}