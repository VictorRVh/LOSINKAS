export default function Button({
    type = 'button',
    color = 'white',
    onClick,
    children,
    className = '',
}) {
    const colors = {
        white: 'bg-[#FFFFFF] text-[#0A1718]',
        coral: 'bg-[#FF7F50] text-white',
        teal: 'bg-[#008080] text-white',
    }

    const base =
        "rounded-none border-2 border-[#0A1718] px-4 py-2 font-['Space_Grotesk',sans-serif] text-sm font-bold uppercase tracking-[0.12em] shadow-[4px_4px_0px_0px_rgba(10,23,24,1)] transition-transform active:translate-x-[4px] active:translate-y-[4px] active:shadow-none"

    return (
        <button
            type={type}
            onClick={onClick}
            className={`${base} ${colors[color] ?? colors.white} ${className}`}
        >
            {children}
        </button>
    )
}