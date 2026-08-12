<x-filament-panels::page>

    <div class="space-y-6">

        {{-- Header --}}
        <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-700 via-emerald-600 to-teal-600 p-7 text-white shadow-sm">

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <p class="text-sm font-medium text-emerald-100">
                        Sistem Monitoring Pegawai
                    </p>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight">
                        Selamat datang 👋
                    </h1>

                    <p class="mt-2 text-sm text-emerald-100">
                        Pantau aktivitas pegawai dan pencatatan keluar kantor
                        hari ini.
                    </p>

                </div>


                {{-- Status jam kerja --}}
                <div class="rounded-xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur-sm">

                    <div class="flex items-center gap-2">

                        @if ($this->workStatus['status'] === 'working')

                            <span class="h-2.5 w-2.5 rounded-full bg-green-300"></span>

                        @elseif ($this->workStatus['status'] === 'break')

                            <span class="h-2.5 w-2.5 rounded-full bg-yellow-300"></span>

                        @else

                            <span class="h-2.5 w-2.5 rounded-full bg-gray-300"></span>

                        @endif

                        <span class="text-sm font-semibold">
                            {{ $this->workStatus['label'] }}
                        </span>

                    </div>

                    <p class="mt-1 text-xs text-emerald-100">
                        {{ $this->workStatus['message'] }}
                    </p>

                </div>

            </div>

        </div>


        {{-- Statistik --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Pegawai aktif --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Pegawai Aktif
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">
                            {{ $this->activeEmployees }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Terdaftar sebagai pegawai aktif
                        </p>

                    </div>

                    <div class="rounded-xl bg-primary-50 p-2.5 text-primary-600 dark:bg-primary-500/10">

                        <x-filament::icon
                            icon="heroicon-o-users"
                            class="h-5 w-5"
                        />

                    </div>

                </div>

            </div>


            {{-- Sedang keluar --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Sedang di Luar
                        </p>

                        <p class="mt-2 text-3xl font-bold text-warning-600">
                            {{ $this->currentlyOut }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Belum tercatat kembali
                        </p>

                    </div>

                    <div class="rounded-xl bg-warning-50 p-2.5 text-warning-600 dark:bg-warning-500/10">

                        <x-filament::icon
                            icon="heroicon-o-arrow-right-on-rectangle"
                            class="h-5 w-5"
                        />

                    </div>

                </div>

            </div>


            {{-- Keluar hari ini --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Keluar Hari Ini
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">
                            {{ $this->todayTotal }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Total pencatatan hari ini
                        </p>

                    </div>

                    <div class="rounded-xl bg-blue-50 p-2.5 text-blue-600 dark:bg-blue-500/10">

                        <x-filament::icon
                            icon="heroicon-o-document-text"
                            class="h-5 w-5"
                        />

                    </div>

                </div>

            </div>


            {{-- Kembali hari ini --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Kembali Hari Ini
                        </p>

                        <p class="mt-2 text-3xl font-bold text-success-600">
                            {{ $this->todayReturned }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Transaksi sudah selesai
                        </p>

                    </div>

                    <div class="rounded-xl bg-success-50 p-2.5 text-success-600 dark:bg-success-500/10">

                        <x-filament::icon
                            icon="heroicon-o-check-circle"
                            class="h-5 w-5"
                        />

                    </div>

                </div>

            </div>

        </div>


        {{-- Aktivitas + pegawai di luar --}}
        <div class="grid gap-6 xl:grid-cols-2">


            {{-- Aktivitas hari ini --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

                <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">

                    <h2 class="font-semibold text-gray-950 dark:text-white">
                        Aktivitas Hari Ini
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Pencatatan terbaru
                    </p>

                </div>


                <div class="divide-y divide-gray-100 dark:divide-white/10">

                    @forelse ($this->todayRecords as $record)

                        <div class="flex items-center gap-4 px-5 py-4">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-50 text-sm font-bold text-primary-600 dark:bg-primary-500/10">

                                {{ strtoupper(substr($record->employee->name, 0, 1)) }}

                            </div>


                            <div class="min-w-0 flex-1">

                                <p class="truncate font-semibold text-gray-950 dark:text-white">
                                    {{ $record->employee->name }}
                                </p>

                                <p class="mt-0.5 truncate text-xs text-gray-500">

                                    {{ $record->purpose }}

                                    <span class="mx-1">•</span>

                                    {{ $record->destination }}

                                </p>

                            </div>


                            <div class="text-right">

                                <p class="font-semibold text-gray-950 dark:text-white">
                                    {{ $record->exit_at->format('H:i') }}
                                </p>

                                @if ($record->isReturned())

                                    <p class="text-xs text-success-600">
                                        Sudah kembali
                                    </p>

                                @else

                                    <p class="text-xs text-warning-600">
                                        Sedang keluar
                                    </p>

                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="px-5 py-12 text-center">

                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Belum ada aktivitas hari ini.
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Pencatatan pegawai akan muncul di sini.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- Pegawai masih di luar --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

                <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">

                    <h2 class="font-semibold text-gray-950 dark:text-white">
                        Pegawai Masih di Luar
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Pegawai yang belum tercatat kembali
                    </p>

                </div>


                <div class="divide-y divide-gray-100 dark:divide-white/10">

                    @forelse ($this->activeExits as $record)

                        <div class="flex items-center gap-4 px-5 py-4">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-warning-50 text-sm font-bold text-warning-600 dark:bg-warning-500/10">

                                {{ strtoupper(substr($record->employee->name, 0, 1)) }}

                            </div>


                            <div class="min-w-0 flex-1">

                                <p class="truncate font-semibold text-gray-950 dark:text-white">
                                    {{ $record->employee->name }}
                                </p>

                                <p class="mt-0.5 text-xs text-gray-500">

                                    {{ $record->employee->department->name ?? 'Tanpa Departemen' }}

                                </p>

                            </div>


                            <div class="text-right">

                                <p class="font-semibold text-gray-950 dark:text-white">
                                    {{ $record->exit_at->format('H:i') }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $record->destination }}
                                </p>

                            </div>

                        </div>

                    @empty

                        <div class="px-5 py-12 text-center">

                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-success-50 text-success-600 dark:bg-success-500/10">

                                <x-filament::icon
                                    icon="heroicon-o-check"
                                    class="h-5 w-5"
                                />

                            </div>

                            <p class="mt-3 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Semua pegawai berada di kantor
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Tidak ada pegawai yang sedang tercatat keluar.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Statistik departemen --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

            <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">

                <h2 class="font-semibold text-gray-950 dark:text-white">
                    Statistik Departemen
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Jumlah pegawai aktif dan yang sedang berada di luar.
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50 dark:bg-white/5">

                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Departemen
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Pegawai Aktif
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Sedang Keluar
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100 dark:divide-white/10">

                        @forelse ($this->departmentStatistics as $department)

                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">

                                <td class="px-5 py-4 font-semibold text-gray-950 dark:text-white">
                                    {{ $department['name'] }}
                                </td>

                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $department['employees'] }}
                                </td>

                                <td class="px-5 py-4">

                                    @if ($department['out'] > 0)

                                        <span class="font-semibold text-warning-600">
                                            {{ $department['out'] }}
                                        </span>

                                    @else

                                        <span class="text-gray-400">
                                            0
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="px-5 py-10 text-center text-sm text-gray-500"
                                >
                                    Belum ada data departemen.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-filament-panels::page>