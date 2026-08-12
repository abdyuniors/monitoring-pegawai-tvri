<div
    class="min-h-screen bg-slate-50"
    x-data="securityDashboard()"
    x-init="init()"
>
    {{-- HEADER --}}
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-700 text-sm font-bold text-white">
                        TVRI
                    </div>

                    <div>
                        <h1 class="text-lg font-bold tracking-tight text-slate-900">
                            Monitoring Pegawai
                        </h1>

                        <p class="text-sm text-slate-500">
                            TVRI NTT · Pos Security
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-6 sm:justify-end">
                    <div class="text-left sm:text-right">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Waktu WITA
                        </p>

                        <p
                            class="font-mono text-xl font-bold tracking-tight text-slate-900"
                            x-text="time"
                        >
                            --:--:--
                        </p>
                    </div>

                    <div class="hidden h-10 w-px bg-slate-200 sm:block"></div>

                    <div class="flex items-center gap-3">

                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-900">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-slate-500">
                            Petugas Security
                        </p>
                    </div>

                    <div class="h-8 w-px bg-slate-200"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900"
                        >
                            Keluar
                        </button>
                    </form>

                </div>
                </div>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- DATE --}}
        <div class="mb-5">
            <p class="text-sm text-slate-500">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        {{-- WORK STATUS --}}
        @php
            $status = match ($workStatus['status']) {
                'working' => [
                    'wrapper' => 'border-emerald-200 bg-emerald-50',
                    'icon' => 'bg-emerald-500',
                    'title' => 'text-emerald-900',
                    'text' => 'text-emerald-700',
                    'badge' => 'bg-emerald-600 text-white',
                ],

                'break' => [
                    'wrapper' => 'border-amber-200 bg-amber-50',
                    'icon' => 'bg-amber-500',
                    'title' => 'text-amber-900',
                    'text' => 'text-amber-700',
                    'badge' => 'bg-amber-600 text-white',
                ],

                'before_work' => [
                    'wrapper' => 'border-blue-200 bg-blue-50',
                    'icon' => 'bg-blue-500',
                    'title' => 'text-blue-900',
                    'text' => 'text-blue-700',
                    'badge' => 'bg-blue-600 text-white',
                ],

                'after_work' => [
                    'wrapper' => 'border-slate-200 bg-slate-100',
                    'icon' => 'bg-slate-500',
                    'title' => 'text-slate-900',
                    'text' => 'text-slate-600',
                    'badge' => 'bg-slate-600 text-white',
                ],

                'holiday' => [
                    'wrapper' => 'border-red-200 bg-red-50',
                    'icon' => 'bg-red-500',
                    'title' => 'text-red-900',
                    'text' => 'text-red-700',
                    'badge' => 'bg-red-600 text-white',
                ],

                default => [
                    'wrapper' => 'border-slate-200 bg-white',
                    'icon' => 'bg-slate-500',
                    'title' => 'text-slate-900',
                    'text' => 'text-slate-600',
                    'badge' => 'bg-slate-600 text-white',
                ],
            };
        @endphp

        <div class="mb-6 rounded-2xl border {{ $status['wrapper'] }} p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-3">
                    <span class="mt-1 flex h-3 w-3 shrink-0 rounded-full {{ $status['icon'] }}"></span>

                    <div>
                        <h2 class="font-semibold {{ $status['title'] }}">
                            {{ $workStatus['label'] }}
                        </h2>

                        <p class="mt-1 text-sm {{ $status['text'] }}">
                            {{ $workStatus['message'] }}
                        </p>
                    </div>
                </div>

                <span class="w-fit rounded-full px-3 py-1.5 text-xs font-semibold {{ $status['badge'] }}">
                    {{ $workStatus['can_exit'] ? 'Pencatatan Aktif' : 'Pencatatan Ditutup' }}
                </span>
            </div>
        </div>

        {{-- FLASH SUCCESS --}}
        @if (session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition
                class="mb-6 flex items-start justify-between gap-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
            >
                <div>
                    <p class="font-semibold">
                        Berhasil
                    </p>

                    <p class="mt-0.5">
                        {{ session('success') }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="show = false"
                    class="text-emerald-600 hover:text-emerald-900"
                >
                    ×
                </button>
            </div>
        @endif

        {{-- STATISTICS --}}
        <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        Sedang Keluar
                    </p>

                    <span class="rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                        Aktif
                    </span>
                </div>

                <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                    {{ $activeExits->count() }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Pegawai berada di luar kantor
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        Sudah Kembali
                    </p>

                    <span class="rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                        Selesai
                    </span>
                </div>

                <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                    {{ $todayReturned }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Transaksi selesai hari ini
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        Total Hari Ini
                    </p>

                    <span class="rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                        Hari ini
                    </span>
                </div>

                <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                    {{ $todayTotal }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Seluruh pencatatan keluar
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <p class="text-sm font-medium text-slate-500">
                        Masih Keluar Hari Ini
                    </p>

                    <span class="rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                        Aktif
                    </span>

                </div>

                <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                    {{ $todayOut }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Belum tercatat kembali
                </p>

            </div>

        </div>

        {{-- SEARCH --}}
        <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5">
                <h2 class="text-lg font-semibold text-slate-900">
                    Catat Pegawai Keluar
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Cari pegawai berdasarkan nama atau NIP.
                </p>
            </div>

            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
                        />
                    </svg>
                </div>

                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ $workStatus['can_exit'] ? 'Cari nama atau NIP...' : 'Pencatatan sedang ditutup' }}"
                    @disabled(! $workStatus['can_exit'])
                    class="w-full rounded-xl border-slate-300 py-3 pl-11 pr-4 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-blue-500 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400"
                >
            </div>
            @error('search')
                <div class="mt-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">
                        Tidak dapat memilih pegawai
                    </p>

                    <p class="mt-1">
                        {{ $message }}
                    </p>
                </div>
            @enderror
            @if ($employees->isNotEmpty())
                <div class="mt-3 overflow-hidden rounded-xl border border-slate-200">
                    @foreach ($employees as $employee)
                        <button
                            type="button"
                            wire:click="selectEmployee({{ $employee->id }})"
                            class="flex w-full items-center justify-between gap-4 border-b border-slate-100 px-4 py-4 text-left transition last:border-b-0 hover:bg-slate-50"
                        >
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-700">
                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-slate-900">
                                        {{ $employee->name }}
                                    </p>

                                    <p class="truncate text-sm text-slate-500">
                                        {{ $employee->nip }}
                                        ·
                                        {{ $employee->department->name }}
                                    </p>
                                </div>
                            </div>

                            <span class="shrink-0 text-sm font-medium text-blue-600">
                                Pilih →
                            </span>
                        </button>
                    @endforeach
                </div>
            @elseif (strlen(trim($search)) >= 2 && $workStatus['can_exit'])
                <div class="mt-4 rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center">
                    <p class="font-medium text-slate-700">
                        Pegawai tidak ditemukan
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Periksa kembali nama atau NIP yang dimasukkan.
                    </p>
                </div>
            @endif
        </section>

        {{-- ACTIVE EXITS --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="font-semibold text-slate-900">
                        Pegawai Sedang Keluar
                    </h2>

                    <p class="mt-0.5 text-sm text-slate-500">
                        Daftar pegawai yang belum tercatat kembali.
                    </p>
                </div>

                <span class="w-fit rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                    {{ $activeExits->count() }} orang
                </span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($activeExits as $record)

                    <div class="px-5 py-4 sm:px-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                            <div class="flex min-w-0 items-start gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-slate-100 text-sm font-bold text-slate-600">
                                    {{ strtoupper(substr($record->employee->name, 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-900">
                                        {{ $record->employee->name }}
                                    </p>

                                    <p class="mt-0.5 text-sm text-slate-500">
                                        {{ $record->employee->nip }}
                                        ·
                                        {{ $record->employee->department->name }}
                                    </p>

                                    <div class="mt-2 flex flex-wrap gap-x-3 gap-y-1 text-sm">
                                        <span class="text-slate-600">
                                            <span class="font-medium">Keperluan:</span>
                                            {{ $record->purpose }}
                                        </span>

                                        <span class="text-slate-600">
                                            <span class="font-medium">Tujuan:</span>
                                            {{ $record->destination }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center justify-between gap-4 lg:justify-end">
                                <div
                                    class="text-left lg:text-right"
                                    x-data="exitDuration('{{ $record->exit_at->toIso8601String() }}')"
                                    x-init="start()"
                                >
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                        Keluar
                                    </p>

                                    <p class="font-mono text-sm font-semibold text-slate-800">
                                        {{ $record->exit_at->format('H:i') }} WITA
                                    </p>

                                    <p
                                        class="mt-1 text-xs font-semibold text-slate-500"
                                        x-text="duration"
                                    >
                                        0 menit
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    wire:click="recordReturn({{ $record->id }})"
                                    wire:confirm="Tandai {{ $record->employee->name }} sudah kembali?"
                                    wire:loading.attr="disabled"
                                    class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    <span wire:loading.remove wire:target="recordReturn({{ $record->id }})">
                                        Kembali
                                    </span>

                                    <span wire:loading wire:target="recordReturn({{ $record->id }})">
                                        Memproses...
                                    </span>
                                </button>
                            </div>

                        </div>
                    </div>

                @empty

                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"
                                />
                            </svg>
                        </div>

                        <p class="mt-3 font-medium text-slate-700">
                            Tidak ada pegawai yang sedang keluar
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Semua pegawai tercatat berada di kantor.
                        </p>
                    </div>

                @endforelse
            </div>

        </section>
    </main>

    {{-- EXIT MODAL --}}
    @if ($showExitModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 px-4 py-6 backdrop-blur-sm"
            wire:key="exit-modal"
        >
            <div
                class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl"
                @click.stop
            >
                <div class="border-b border-slate-200 px-6 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">
                                Catat Pegawai Keluar
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Lengkapi informasi sebelum pegawai meninggalkan kantor.
                            </p>
                        </div>

                        <button
                            type="button"
                            wire:click="closeExitModal"
                            class="rounded-lg p-1 text-xl leading-none text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                        >
                            ×
                        </button>
                    </div>
                </div>

                <div class="px-6 py-5">

                    @if ($selectedEmployee)
                        <div class="mb-5 flex items-center gap-3 rounded-xl bg-slate-50 p-4">
                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                                {{ strtoupper(substr($selectedEmployee->name, 0, 1)) }}
                            </div>

                            <div>
                                <p class="font-semibold text-slate-900">
                                    {{ $selectedEmployee->name }}
                                </p>

                                <p class="text-sm text-slate-500">
                                    {{ $selectedEmployee->nip }}
                                    ·
                                    {{ $selectedEmployee->department->name }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-4">

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Keperluan
                            </label>

                            <input
                                type="text"
                                wire:model="purpose"
                                class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Contoh: Mengurus dokumen pribadi"
                            >

                            @error('purpose')
                                <p class="mt-1.5 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Tujuan
                            </label>

                            <input
                                type="text"
                                wire:model="destination"
                                class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Contoh: Bank"
                            >

                            @error('destination')
                                <p class="mt-1.5 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    @error('exit')
                        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <p class="font-semibold">
                                Tidak dapat mencatat pegawai keluar
                            </p>

                            <p class="mt-1">
                                {{ $message }}
                            </p>
                        </div>
                    @enderror

                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4">
                    <button
                        type="button"
                        wire:click="closeExitModal"
                        class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        wire:click="recordExit"
                        wire:loading.attr="disabled"
                        class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <span wire:loading.remove wire:target="recordExit">
                            Catat Keluar
                        </span>

                        <span wire:loading wire:target="recordExit">
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script>
        function securityDashboard() {
            return {
                time: '--:--:--',
                timer: null,

                init() {
                    this.updateClock();

                    this.timer = setInterval(() => {
                        this.updateClock();
                    }, 1000);
                },

                updateClock() {
                    const now = new Date();

                    this.time = now.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false,
                        timeZone: 'Asia/Makassar',
                    });
                },
            };
        }
        function exitDuration(exitAt) {
            return {
                duration: '0 menit',
                timer: null,

                start() {
                    this.update();

                    this.timer = setInterval(() => {
                        this.update();
                    }, 1000);
                },

                update() {
                    const start = new Date(exitAt);
                    const now = new Date();

                    const difference = Math.max(
                        0,
                        Math.floor((now - start) / 1000)
                    );

                    const hours = Math.floor(difference / 3600);
                    const minutes = Math.floor((difference % 3600) / 60);
                    const seconds = difference % 60;

                    if (hours > 0) {
                        this.duration =
                            `${hours} jam ${minutes} menit`;
                    } else if (minutes > 0) {
                        this.duration =
                            `${minutes} menit ${seconds} detik`;
                    } else {
                        this.duration =
                            `${seconds} detik`;
                    }
                },
            };
        }
    </script>
</div>