import { useForm, Head } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import Button from '@/Components/UI/Button';
import { Link } from '@inertiajs/react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Registro" />

            <section className="grid grid-cols-1 border-2 border-[#0A1718] bg-[#FFFFFF] lg:grid-cols-[0.95fr_1.05fr]">

                {/* LEFT */}
                <div className="border-b border-[#5C6F72]/30 px-6 py-10 lg:border-b-0 lg:border-r lg:px-8">
                    <p className="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.24em] text-[#008080]">
                        Inkascan / Registro
                    </p>

                    <h1 className="mt-4 max-w-[10ch] font-['Space_Grotesk',sans-serif] text-4xl font-bold uppercase leading-none tracking-[-0.05em] lg:text-6xl">
                        Registro de usuarios
                    </h1>

                    <p className="mt-6 max-w-xl border-l border-[#5C6F72]/30 pl-4 text-sm leading-7 text-[#0A1718]/80">
                        Crea credenciales para nuevos usuarios del panel técnico de <strong>inkascan</strong>.
                    </p>
                </div>

                {/* RIGHT */}
                <div className="px-6 py-10 lg:px-8">
                    <div className="border-2 border-[#0A1718] bg-[#F4F7F7] p-6">
                        <p className="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.2em] text-[#5C6F72]">
                            [ AUTH / REGISTER ]
                        </p>

                        <form onSubmit={submit} className="mt-6 space-y-5">

                            {/* NAME */}
                            <div>
                                <label className="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                    Nombre
                                </label>

                                <input
                                    type="text"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    className="w-full rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-3 outline-none"
                                />

                                {errors.name && (
                                    <p className="mt-2 text-sm text-red-600">{errors.name}</p>
                                )}
                            </div>

                            {/* EMAIL */}
                            <div>
                                <label className="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    autoComplete="username"
                                    className="w-full rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-3 outline-none"
                                />

                                {errors.email && (
                                    <p className="mt-2 text-sm text-red-600">{errors.email}</p>
                                )}
                            </div>

                            {/* PASSWORD */}
                            <div>
                                <label className="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                    Clave
                                </label>

                                <input
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    autoComplete="new-password"
                                    className="w-full rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-3 outline-none"
                                />

                                {errors.password && (
                                    <p className="mt-2 text-sm text-red-600">{errors.password}</p>
                                )}
                            </div>

                            {/* CONFIRM PASSWORD */}
                            <div>
                                <label className="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                    Confirmar clave
                                </label>

                                <input
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                    autoComplete="new-password"
                                    className="w-full rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-3 outline-none"
                                />

                                {errors.password_confirmation && (
                                    <p className="mt-2 text-sm text-red-600">
                                        {errors.password_confirmation}
                                    </p>
                                )}
                            </div>

                            {/* FOOTER */}
                            <div className="flex flex-col gap-4 pt-2 sm:flex-row sm:items-center sm:justify-between">
                                <Link
                                    href={route('login')}
                                    className="text-xs font-bold uppercase tracking-[0.14em] text-[#008080] underline underline-offset-4"
                                >
                                    Ya tengo cuenta
                                </Link>

                                <Button
                                    type="submit"
                                    color="coral"
                                    disabled={processing}
                                >
                                    Crear usuario
                                </Button>
                            </div>

                        </form>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}