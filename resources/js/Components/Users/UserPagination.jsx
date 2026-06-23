import { router } from '@inertiajs/react'

export default function UserPagination({ links }) {
    return (
        <div className="flex gap-2 mt-4">
            {links.map((link, i) => (
                <button
                    key={i}
                    disabled={!link.url}
                    onClick={() => link.url && router.visit(link.url)}
                    className={`px-3 py-1 border text-sm ${link.active
                            ? 'bg-black text-white'
                            : 'bg-white'
                        }`}
                    dangerouslySetInnerHTML={{ __html: link.label }}
                />
            ))}
        </div>
    )
}