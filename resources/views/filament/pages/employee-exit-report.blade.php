<x-filament-panels::page>

    <div class="space-y-6">

        {{-- Header --}}
        <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-700 via-emerald-600 to-teal-600 p-7 text-white shadow-sm">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <div class="mb-2 flex items-center gap-2 text-sm font-medium text-emerald-100">
                        <x-filament::icon
                            icon="heroicon-o-document-chart-bar"
                            class="h-5 w-5"
                        />

                        <span>Laporan Administrasi</span>
                    </div>

                    <h2 class="text-2xl font-bold tracking-tight">
                        Laporan Pegawai Keluar
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm text-emerald-100">
                        Rekap pencatatan pegawai yang keluar selama periode tertentu.
                    </p>
                </div>

                <div class="rounded-xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-100">
                        Periode Laporan
                    </p>

                    <p class="mt-1 whitespace-nowrap text-sm font-bold">
                        {{ $this->periodLabel }}
                    </p>

                    <p class="mt-1 text-xs text-emerald-100">
                        {{ $this->selectedDepartmentName }}
                    </p>
                </div>

            </div>
        </div>


        {{-- Filter --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

            <div class="mb-5">
                <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                    Filter Laporan
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Tentukan periode dan departemen yang ingin ditampilkan.
                </p>
            </div>

            <div class="grid gap-4 md:grid-cols-3">

                <div>
                    <label
                        for="startDate"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Tanggal Mulai
                    </label>

                    <input
                        id="startDate"
                        type="date"
                        wire:model.live="startDate"
                        class="block w-full rounded-xl border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800"
                    >
                </div>


                <div>
                    <label
                        for="endDate"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Tanggal Selesai
                    </label>

                    <input
                        id="endDate"
                        type="date"
                        wire:model.live="endDate"
                        class="block w-full rounded-xl border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800"
                    >
                </div>


                <div>
                    <label
                        for="departmentId"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Departemen
                    </label>

                    <select
                        id="departmentId"
                        wire:model.live="departmentId"
                        class="block w-full rounded-xl border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800"
                    >
                        <option value="">
                            Semua Departemen
                        </option>

                        @foreach ($this->departments as $department)
                            <option value="{{ $department->id }}">
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>


        {{-- Statistik --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Total Transaksi
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-950 dark:text-white">
                            {{ $this->total }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Pencatatan dalam periode
                        </p>
                    </div>

                    <div class="rounded-xl bg-primary-50 p-2.5 text-primary-600 dark:bg-primary-500/10">
                        <x-filament::icon
                            icon="heroicon-o-document-text"
                            class="h-5 w-5"
                        />
                    </div>

                </div>
            </div>


            {{-- Kembali --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Sudah Kembali
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-success-600">
                            {{ $this->returned }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Transaksi selesai
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


            {{-- Masih di luar --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Masih di Luar
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-warning-600">
                            {{ $this->stillOut }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Belum tercatat kembali
                        </p>
                    </div>

                    <div class="rounded-xl bg-warning-50 p-2.5 text-warning-600 dark:bg-warning-500/10">
                        <x-filament::icon
                            icon="heroicon-o-clock"
                            class="h-5 w-5"
                        />
                    </div>

                </div>
            </div>


            {{-- Rata-rata --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Rata-rata Durasi
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-950 dark:text-white">
                            {{ $this->averageDuration }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Transaksi yang sudah kembali
                        </p>
                    </div>

                    <div class="rounded-xl bg-gray-100 p-2.5 text-gray-600 dark:bg-white/10 dark:text-gray-300">
                        <x-filament::icon
                            icon="heroicon-o-chart-bar"
                            class="h-5 w-5"
                        />
                    </div>

                </div>
            </div>

        </div>


        {{-- Tabel --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

            <div class="flex flex-col gap-1 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-white/10">

                <div>
                    <h3 class="font-semibold text-gray-950 dark:text-white">
                        Detail Pencatatan
                    </h3>

                    <p class="text-sm text-gray-500">
                        {{ $this->total }} transaksi ditemukan
                    </p>
                </div>

                <p class="text-sm text-gray-500">
                    {{ $this->periodLabel }}
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full min-w-[1050px] text-sm">

                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Pegawai
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Departemen
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Keperluan
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Tujuan
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Keluar
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Kembali
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Durasi
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Status
                            </th>

                        </tr>
                    </thead>


                    <tbody class="divide-y divide-gray-100 dark:divide-white/10">

                        @forelse ($this->records as $record)

                            @php
                                $minutes = $record->getDurationInMinutes();

                                if ($minutes === null) {
                                    $duration = '—';
                                } elseif ($minutes === 0) {
                                    $duration = '< 1 menit';
                                } elseif ($minutes < 60) {
                                    $duration = $minutes . ' menit';
                                } else {
                                    $hours = intdiv($minutes, 60);
                                    $remaining = $minutes % 60;

                                    $duration = $remaining === 0
                                        ? "{$hours} jam"
                                        : "{$hours} jam {$remaining} menit";
                                }
                            @endphp

                            <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-white/5">

                                <td class="px-5 py-4">

                                    <div class="font-semibold text-gray-950 dark:text-white">
                                        {{ $record->employee->name }}
                                    </div>

                                    <div class="mt-0.5 font-mono text-xs text-gray-400">
                                        {{ $record->employee->nip }}
                                    </div>

                                </td>


                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $record->employee->department->name ?? '—' }}
                                </td>


                                <td class="max-w-[220px] px-5 py-4 text-gray-600 dark:text-gray-300">
                                    <span
                                        class="block truncate"
                                        title="{{ $record->purpose }}"
                                    >
                                        {{ $record->purpose }}
                                    </span>
                                </td>


                                <td class="max-w-[180px] px-5 py-4 text-gray-600 dark:text-gray-300">
                                    <span
                                        class="block truncate"
                                        title="{{ $record->destination }}"
                                    >
                                        {{ $record->destination }}
                                    </span>
                                </td>


                                <td class="whitespace-nowrap px-5 py-4">

                                    <div class="font-semibold text-gray-950 dark:text-white">
                                        {{ $record->exit_at->format('H:i') }}
                                    </div>

                                    <div class="text-xs text-gray-400">
                                        {{ $record->exit_at->format('d/m/Y') }}
                                    </div>

                                </td>


                                <td class="whitespace-nowrap px-5 py-4">

                                    @if ($record->return_at)

                                        <div class="font-semibold text-gray-950 dark:text-white">
                                            {{ $record->return_at->format('H:i') }}
                                        </div>

                                        <div class="text-xs text-gray-400">
                                            {{ $record->return_at->format('d/m/Y') }}
                                        </div>

                                    @else

                                        <span class="text-gray-400">
                                            —
                                        </span>

                                    @endif

                                </td>


                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-700 dark:text-gray-200">
                                    {{ $duration }}
                                </td>


                                <td class="px-5 py-4">

                                    @if ($record->isReturned())

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-success-50 px-2.5 py-1 text-xs font-semibold text-success-700 dark:bg-success-500/10 dark:text-success-400">

                                            <span class="h-1.5 w-1.5 rounded-full bg-success-500"></span>

                                            Sudah Kembali

                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-warning-50 px-2.5 py-1 text-xs font-semibold text-warning-700 dark:bg-warning-500/10 dark:text-warning-400">

                                            <span class="h-1.5 w-1.5 rounded-full bg-warning-500"></span>

                                            Sedang Keluar

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="px-5 py-16">

                                    <div class="mx-auto flex max-w-sm flex-col items-center text-center">

                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-white/10">

                                            <x-filament::icon
                                                icon="heroicon-o-document-magnifying-glass"
                                                class="h-6 w-6"
                                            />

                                        </div>

                                        <h4 class="mt-4 font-semibold text-gray-950 dark:text-white">
                                            Tidak ada data
                                        </h4>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Tidak ditemukan pencatatan pegawai pada
                                            periode atau departemen yang dipilih.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-filament-panels::page>