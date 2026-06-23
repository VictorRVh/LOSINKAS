import GuestLayout from '@/Layouts/GuestLayout'
import Header from '@/Components/Landing/Header'
import Button from '../Components/UI/Button'
import LandingLayout from '../Layouts/LandingLayout'

function Stat({ label, value, color = '' }) {
    return (
        <div className="border border-[#5C6F72]/30 px-4 py-4">
            <span className="text-xs uppercase tracking-[0.16em]">{label}</span>
            <strong className={`block mt-2 text-2xl ${color}`}>{value}</strong>
        </div>
    )
}

const processingSteps = [
    {
        label: 'Paso 01',
        title: 'Preprocesamiento',
        text: 'Normalización de imagen, corrección de perspectiva y limpieza de ruido.'
    },
    {
        label: 'Paso 02',
        title: 'Detección OMR',
        text: 'Identificación precisa de marcas mediante umbrales adaptativos.'
    },
    {
        label: 'Paso 03',
        title: 'Validación',
        text: 'Verificación cruzada y generación de resultados confiables.'
    },
]

const csvSteps = [
    {
        phase: 'Fase A',
        text: 'Carga del archivo CSV con códigos únicos de postulante.'
    },
    {
        phase: 'Fase B',
        text: 'Generación automática de fichas personalizadas.'
    },
    {
        phase: 'Fase C',
        text: 'Impresión y escaneo sin errores de identificación.'
    },
]

const systemStatus = [
    { label: 'OCR Engine', value: 'v1.4.2', active: true },
    { label: 'OMR Kernel', value: 'Running', active: true },
    { label: 'Batch Queue', value: 'Idle' },
]

const documentationLinks = [
    'Guía de Uso',
    'Formato CSV',
    'API Interna',
]

const securityLinks = [
    'Política de Datos',
    'Cifrado',
    'Accesos',
]

export default function Welcome({ canLogin, canRegister, authUser }) {
    return (

        <LandingLayout
            canLogin={canLogin}
            canRegister={canRegister}
            authUser={authUser}
        >

            {/* HERO */}
            <section className="min-h-screen flex items-center justify-center">
                <main className="flex-1">

                    <section
                        id="producto"
                        className="border-b border-[#5C6F72]/30"
                    >
                        <div className="grid grid-cols-1 lg:grid-cols-2">
                            {/* TEXTO */}
                            <div className="border-b lg:border-b-0 lg:border-r border-[#5C6F72]/30 px-6 py-14 lg:px-10 lg:py-20">
                                <p className="mb-6 text-xs font-bold uppercase tracking-[0.24em] text-[#008080]">
                                    Vision artificial para lectura OMR
                                </p>

                                <h1 className="max-w-[11ch] text-5xl lg:text-7xl font-semibold leading-[1.02] tracking-[-0.04em]">
                                    Corrige exámenes con precisión de laboratorio.
                                </h1>

                                <p className="mt-8 max-w-xl border-l border-[#5C6F72]/30 pl-5">
                                    Una landing construida como tablero técnico:
                                    bloques exactos, guías visibles y control industrial.
                                </p>

                                <div className="mt-10 flex flex-col sm:flex-row gap-4">
                                    <Button
                                        type="button"
                                        color="coral"
                                    >
                                        Iniciar Escaneo
                                    </Button>

                                    <Button
                                        type="button"
                                        color="white"
                                    >
                                        Ver Arquitectura
                                    </Button>


                                </div>

                                {/* STATS */}
                                <div className="mt-12 grid grid-cols-2 border border-[#5C6F72]/30 text-sm">
                                    <Stat label="Lotes por hora" value="[ 1,200 ]" />
                                    <Stat label="Error humano" value="[ -87% ]" />
                                    <Stat label="Modo" value="ONLINE" color="text-[#008080]" />
                                    <Stat label="Estado laser" value="TRACKING" color="text-[#FF7F50]" />
                                </div>
                            </div>

                            {/* VISOR */}
                            <div className="px-6 py-14 lg:px-10 lg:py-20">
                                <div className="relative min-h-[520px] border-2 p-6">
                                    <div className="grid grid-cols-5 gap-3">
                                        {Array.from({ length: 25 }).map((_, i) => (
                                            <span
                                                key={i}
                                                className="aspect-square rounded-full border bg-[#F4F7F7]"
                                            />
                                        ))}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </main>
            </section>

            <section
                id="metricas"
                className="border-b border-[#5C6F72]/30 px-6 py-14 lg:px-10 lg:py-16"
            >
                <div className="mb-8">
                    <p className="text-xs font-bold uppercase tracking-[0.22em] text-[#5C6F72]">
                        Procesamiento de Imagen
                    </p>

                    <h2 className="mt-3 text-3xl font-bold uppercase tracking-[-0.03em]">
                        El Algoritmo
                    </h2>
                </div>

                <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    {processingSteps.map((step, index) => (
                        <article
                            key={index}
                            className="border-2 p-6 shadow-[6px_6px_0px_0px_rgba(10,23,24,1)] transition-colors"
                        >
                            <div className="flex items-start justify-between gap-4">
                                <p className="text-xs font-bold uppercase tracking-[0.18em] text-[#008080]">
                                    {step.label}
                                </p>

                                {/* Icono placeholder (luego puedes meter Heroicons React) */}
                                <span className="h-5 w-5 block bg-[#FF7F50]" />
                            </div>

                            <h3 className="mt-6 border-t border-[#5C6F72]/30 pt-5 text-2xl font-bold uppercase tracking-[-0.03em]">
                                {step.title}
                            </h3>

                            <p className="mt-5 text-sm leading-7">
                                {step.text}
                            </p>
                        </article>
                    ))}
                </div>
            </section>

            <section
                id="institutionalSection"
                className="border-b border-[#5C6F72]/30 bg-[#FAF9F5] px-6 py-14 text-[#0A1718] lg:px-10 lg:py-16"
            >
                <div className="border-y border-[#5C6F72]/30">
                    <div className="grid grid-cols-1 lg:grid-cols-[320px_1fr]">
                        <div className="border-b border-[#5C6F72]/30 px-5 py-6 lg:border-b-0 lg:border-r">
                            <p className="text-xs font-bold uppercase tracking-[0.22em] text-[#008080]">
                                [ SEGMENTO INSTITUCIONAL ]
                            </p>
                        </div>

                        <div className="px-5 py-6">
                            <h2 className="text-3xl font-bold uppercase tracking-[-0.03em]">
                                Calidad de Respuesta para Simulacros Masivos
                            </h2>

                            <p className="mt-5 max-w-5xl text-sm leading-7">
                                Diseñado para el nivel de exigencia de academias y colegios de Puno.
                                Permite calificación descentralizada desde celulares, procesamiento
                                por lotes de imágenes escaneadas y generación inmediata de reportes
                                por áreas académicas.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <footer
                id="footer"
                className="border-t border-[#0A1718] px-6 py-10 lg:px-10"
            >
                <div className="border">
                    <div className="grid grid-cols-1 lg:grid-cols-4">
                        {/* TELEMETRÍA */}
                        <div className="border-b border-[#5C6F72]/30 px-5 py-6 text-[12px] leading-6 lg:border-b-0 lg:border-r">
                            <p className="text-[11px] font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                [ 00 / TELEMETRIA ]
                            </p>

                            <div className="mt-4 space-y-3">
                                {systemStatus.map((item, index) => (
                                    <div
                                        key={index}
                                        className="flex justify-between gap-4 border-b border-[#5C6F72]/20 pb-2 last:border-b-0"
                                    >
                                        <span>{item.label}:</span>

                                        <span className="text-right">
                                            {item.value}
                                            {item.active && (
                                                <span className="ml-2 text-[#008080]">
                                                    [ OK ]
                                                </span>
                                            )}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* RECURSOS */}
                        <div className="border-b border-[#5C6F72]/30 px-5 py-6 lg:border-b-0 lg:border-r">
                            <p className="text-[11px] font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                [ 01 / RECURSOS ]
                            </p>

                            <ul className="mt-4 space-y-3 text-sm">
                                {documentationLinks.map((item, index) => (
                                    <li key={index}>
                                        <a
                                            href="#"
                                            className="hover:text-[#008080] hover:underline"
                                        >
                                            {item}
                                        </a>
                                    </li>
                                ))}
                            </ul>
                        </div>

                        {/* SEGURIDAD */}
                        <div className="border-b border-[#5C6F72]/30 px-5 py-6 lg:border-b-0 lg:border-r">
                            <p className="text-[11px] font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                [ 02 / SEGURIDAD ]
                            </p>

                            <ul className="mt-4 space-y-3 text-sm">
                                {securityLinks.map((item, index) => (
                                    <li key={index}>
                                        <a
                                            href="#"
                                            className="hover:text-[#008080] hover:underline"
                                        >
                                            {item}
                                        </a>
                                    </li>
                                ))}
                            </ul>
                        </div>

                        {/* UBICACIÓN */}
                        <div className="px-5 py-6">
                            <p className="text-[11px] font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                [ 03 / UBICACION ]
                            </p>

                            <div className="mt-4 space-y-3 text-[12px] leading-6">
                                <div className="flex justify-between border-b border-[#5C6F72]/20 pb-2">
                                    <span>LATITUD:</span>
                                    <span>15.8402 S</span>
                                </div>

                                <div className="flex justify-between border-b border-[#5C6F72]/20 pb-2">
                                    <span>LONGITUD:</span>
                                    <span>70.0219 W</span>
                                </div>

                                <div className="pt-1 text-sm font-bold uppercase tracking-[0.16em] text-[#008080]">
                                    ALTIPLANO UNIT // PUNO
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* FOOTER BOTTOM */}
                    <div className="flex flex-col gap-4 border-t border-[#5C6F72]/30 px-5 py-4 text-[11px] uppercase tracking-[0.18em] lg:flex-row lg:justify-between">
                        <p>
                            OLECTOR — SISTEMA DE LECTURA ÓPTICA POR VISIÓN COMPUTACIONAL.
                            TODOS LOS DERECHOS RESERVADOS.
                        </p>

                        <span className="font-bold text-[#008080]">
                            [ CALIBRATION OK ]
                        </span>
                    </div>
                </div>
            </footer>
        </LandingLayout>
    )
}