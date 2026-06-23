import AppLayout from '@/Layouts/AppLayout';
import { Head } from '@inertiajs/react';

export default function Dashboard() {
    return (
        <AppLayout
            header={
                <h2 className="font-semibold text-xl text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="bg-white overflow-hidden shadow-sm rounded-lg">
                <div className="p-6 text-gray-900">
                    You're logged in!
                </div>
            </div>
        </AppLayout>
    );
}