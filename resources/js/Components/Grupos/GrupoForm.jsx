import { useEffect, useState } from 'react'
import { useForm } from '@inertiajs/react'
import Button from '@/Components/UI/Button'
import axios from 'axios'

export default function GrupoForm({
    periodos = [],
    niveles = [],
    onSuccess,
}) {
    const { data, setData, post, processing, errors, reset } = useForm({
        periodo_id: '',
        nivel_id: '',
        grado_id: '',
        seccion_id: '',
        cursos: [],
    })

    const [grados, setGrados] = useState([])
    const [cursos, setCursos] = useState([])
    const [secciones, setSecciones] = useState([])

    /* ===============================
       Cargar grados según nivel
    =============================== */
    useEffect(() => {
        if (!data.nivel_id) {
            setGrados([])
            setData('grado_id', '')
            return
        }

        axios
            .get(route('grupos.grados.por-nivel'), {
                params: { nivel_id: data.nivel_id },
            })
            .then(res => setGrados(res.data))
    }, [data.nivel_id])

    /* ===============================
       Cargar cursos según grado
    =============================== */
    useEffect(() => {
        if (!data.grado_id) {
            setCursos([])
            setData('cursos', [])
            return
        }

        axios
            .get(route('grupos.cursos.disponibles'), {
                params: { grado_id: data.grado_id },
            })
            .then(res => setCursos(res.data))
    }, [data.grado_id])


    // FILTRO DE SECCIONES DISPONIBLES
    useEffect(() => {
        if (!data.grado_id || !data.periodo_id) {
            setSecciones([])
            setData('seccion_id', '')
            return
        }

        axios
            .get(route('grupos.secciones.disponibles'), {
                params: {
                    grado_id: data.grado_id,
                    periodo_id: data.periodo_id,
                },
            })
            .then(res => setSecciones(res.data))
    }, [data.grado_id, data.periodo_id])


    /* ===============================
       Toggle cursos
    =============================== */
    const toggleCurso = (id) => {
        setData(
            'cursos',
            data.cursos.includes(id)
                ? data.cursos.filter(c => c !== id)
                : [...data.cursos, id]
        )
    }

    /* ===============================
       Submit
    =============================== */
    const submit = (e) => {
        e.preventDefault()

        post(route('grupos.store'), {
            onSuccess: () => {
                reset()
                onSuccess?.()
            },
        })
    }

    return (
        <form onSubmit={submit} className="space-y-5 p-5">

            {/* PERIODO */}
            <div>
                <label className="label">Periodo</label>
                <select
                    className="input"
                    value={data.periodo_id}
                    onChange={e => setData('periodo_id', e.target.value)}
                >
                    <option value="">Seleccione</option>
                    {periodos.map(p => (
                        <option key={p.id} value={p.id}>
                            {p.nombre_periodo}
                        </option>
                    ))}
                </select>
                {errors.periodo_id && <p className="error">{errors.periodo_id}</p>}
            </div>

            {/* NIVEL */}
            <div>
                <label className="label">Nivel</label>
                <select
                    className="input"
                    value={data.nivel_id}
                    onChange={e => setData('nivel_id', e.target.value)}
                >
                    <option value="">Seleccione</option>
                    {niveles.map(n => (
                        <option key={n.id} value={n.id}>
                            {n.nombre_nivel}
                        </option>
                    ))}
                </select>
                {errors.nivel_id && <p className="error">{errors.nivel_id}</p>}
            </div>

            {/* GRADO */}
            <div>
                <label className="label">Grado</label>
                <select
                    className="input"
                    value={data.grado_id}
                    onChange={e => setData('grado_id', e.target.value)}
                    disabled={!grados.length}
                >
                    <option value="">Seleccione</option>
                    {grados.map(g => (
                        <option key={g.id} value={g.id}>
                            {g.nombre_grado}
                        </option>
                    ))}
                </select>
                {errors.grado_id && <p className="error">{errors.grado_id}</p>}
            </div>

            {/* SECCIÓN */}
            <div>
                <label className="label">Sección</label>
                <select
                    className="input"
                    value={data.seccion_id}
                    onChange={e => setData('seccion_id', e.target.value)}
                >
                    <option value="">Seleccione</option>
                    {secciones.map(s => (
                        <option key={s.id} value={s.id}>
                            {s.nombre_seccion}
                        </option>
                    ))}
                </select>
                {errors.seccion_id && <p className="error">{errors.seccion_id}</p>}
            </div>

            {/* CURSOS */}
            <div>
                <label className="label">Cursos</label>

                {cursos.length === 0 && (
                    <p className="text-xs text-gray-500">
                        Seleccione un grado
                    </p>
                )}

                <div className="grid grid-cols-2 gap-2">
                    {cursos.map(curso => (
                        <label
                            key={curso.id}
                            className="flex items-center gap-2 border p-2 cursor-pointer"
                        >
                            <input
                                type="checkbox"
                                checked={data.cursos.includes(curso.id)}
                                onChange={() => toggleCurso(curso.id)}
                            />
                            {curso.nombre_curso}
                        </label>
                    ))}
                </div>

                {errors.cursos && <p className="error">{errors.cursos}</p>}
            </div>

            {/* BOTÓN */}
            <div className="flex justify-end">
                <Button
                    type="submit"
                    color="teal"
                    disabled={processing}
                >
                    {processing ? 'Guardando...' : 'Crear Grupo'}
                </Button>
            </div>

        </form>
    )
}