import { useForm, Head } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import Button from '../../Components/UI/Button';

export default function Login({ status }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('login'));
    };

    return (
        <GuestLayout>
            <Head title="Login" />

            {/* SESSION STATUS */}
            {status && (
                <div className="mb-4 text-sm text-green-600">
                    {status}
                </div>
            )}

            <section className="grid grid-cols-1 border-2 border-[#0A1718] bg-[#FFFFFF] lg:grid-cols-[1.05fr_0.95fr]">
                {/* LEFT */}
                <div className="border-b border-[#5C6F72]/30 px-6 py-10 lg:border-b-0 lg:border-r lg:px-8">
                    <p className="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.24em] text-[#008080]">
                        Acceso local / inkascan
                    </p>

                    <h1 className="mt-4 max-w-[10ch] font-['Space_Grotesk',sans-serif] text-4xl font-bold uppercase leading-none tracking-[-0.05em] lg:text-6xl">
                        Login tecnico de usuarios
                    </h1>

                    <p className="mt-6 max-w-xl border-l border-[#5C6F72]/30 pl-4 text-sm leading-7 text-[#0A1718]/80">
                        Panel simple conectado a la base de datos <strong>inkascan</strong> sobre XAMPP.
                    </p>
                </div>

                {/* RIGHT */}
                <div className="px-6 py-10 lg:px-8">
                    <div className="border-2 border-[#0A1718] bg-[#F4F7F7] p-6">
                        <p className="font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.2em] text-[#5C6F72]">
                            [ AUTH / LOGIN ]
                        </p>

                        <form onSubmit={submit} className="mt-6 space-y-5">
                            {/* EMAIL */}
                            <div>
                                <label className="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    autoComplete="username"
                                    className="w-full rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-3 outline-none"
                                />

                                {errors.email && (
                                    <p className="mt-2 text-sm text-red-600">
                                        {errors.email}
                                    </p>
                                )}
                            </div>

                            {/* PASSWORD */}
                            <div>
                                <label className="mb-2 block font-['Space_Grotesk',sans-serif] text-xs font-bold uppercase tracking-[0.18em] text-[#5C6F72]">
                                    Clave
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    autoComplete="current-password"
                                    className="w-full rounded-none border-2 border-[#0A1718] bg-[#FFFFFF] px-4 py-3 outline-none"
                                />

                                {errors.password && (
                                    <p className="mt-2 text-sm text-red-600">
                                        {errors.password}
                                    </p>
                                )}
                            </div>

                            {/* BUTTON */}
                            <Button
                                type="submit"
                                color="coral"
                                className='w-full'
                            >
                                Iniciar Sesión
                            </Button>
                        </form>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}