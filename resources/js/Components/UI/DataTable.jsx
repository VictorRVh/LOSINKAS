export default function DataTable({
    columns = [],
    data = [],
    onRowClick,
    actions,
}) {
    return (
        <div className="overflow-x-auto border border-[#5C6F72]/30">
            <table className="w-full border-collapse">

                {/* HEADER */}
                <thead>
                    <tr className="bg-[#F4F7F7]">
                        {columns.map((col, index) => (
                            <th
                                key={index}
                                className="border-b border-r border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase"
                            >
                                {col.label}
                            </th>
                        ))}

                        {actions && (
                            <th className="border-b border-[#5C6F72]/30 px-3 py-3 text-left text-xs font-bold uppercase">
                                Acciones
                            </th>
                        )}
                    </tr>
                </thead>

                {/* BODY */}
                <tbody>
                    {data.length === 0 ? (
                        <tr>
                            <td
                                colSpan={columns.length + (actions ? 1 : 0)}
                                className="px-4 py-8 text-center"
                            >
                                No hay registros.
                            </td>
                        </tr>
                    ) : (
                        data.map((row) => (
                            <tr
                                key={row.id}
                                className="hover:bg-[#F4F7F7]"
                            >
                                {columns.map((col, index) => (
                                    <td
                                        key={index}
                                        className="border-b border-r border-[#5C6F72]/30 px-3 py-2 text-sm"
                                    >
                                        {col.render
                                            ? col.render(row)
                                            : row[col.field]}
                                    </td>
                                ))}

                                {actions && (
                                    <td className="border-b border-[#5C6F72]/30 px-3 py-2">
                                        {actions(row)}
                                    </td>
                                )}
                            </tr>
                        ))
                    )}
                </tbody>

            </table>
        </div>
    )
}