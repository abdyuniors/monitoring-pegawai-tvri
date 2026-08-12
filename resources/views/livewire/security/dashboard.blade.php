<div class="min-h-screen bg-gray-50" x-data="securityClock()" x-init="start()">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Dashboard Security
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    {{ now()->translatedFormat('l, d F Y') }}
                    · <span x-text="time"></span> WITA
                </p>
            </div>

            <div class="text-right">
                <p class="font-medium text-gray-900">
                    {{ auth()->user()->name }}
                </p>

                <p class="text-sm text-gray-500">
                    Security
                </p>
            </div>
        </div>

        @php
            $statusClasses = match ($workStatus['status']) {
                'working' => [
                    'container' => 'border-green-200 bg-green-50',
                    'title' => 'text-green-800',
                    'text' => 'text-green-700',
                ],

                'break' => [
                    'container' => 'border-amber-200 bg-amber-50',
                    'title' => 'text-amber-800',
                    'text' => 'text-amber-700',
                ],

                'before_work' => [
                    'container' => 'border-blue-200 bg-blue-50',
                    'title' => 'text-blue-800',
                    'text' => 'text-blue-700',
                ],

                'after_work' => [
                    'container' => 'border-gray-200 bg-gray-100',
                    'title' => 'text-gray-800',
                    'text' => 'text-gray-600',
                ],

                'holiday' => [
                    'container' => 'border-red-200 bg-red-50',
                    'title' => 'text-red-800',
                    'text' => 'text-red-700',
                ],

                default => [
                    'container' => 'border-gray-200 bg-gray-50',
                    'title' => 'text-gray-800',
                    'text' => 'text-gray-600',
                ],
            };
        @endphp

        <div class="mt-4 rounded-xl border p-4 {{ $statusClasses['container'] }}">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="font-semibold {{ $statusClasses['title'] }}">
                        {{ $workStatus['label'] }}
                    </p>

                    <p class="mt-1 text-sm {{ $statusClasses['text'] }}">
                        {{ $workStatus['message'] }}
                    </p>
                </div>

                @if ($workStatus['can_exit'])
                    <span class="rounded-full bg-green-600 px-3 py-1 text-xs font-semibold text-white">
                        Pencatatan Aktif
                    </span>
                @else
                    <span class="rounded-full bg-gray-600 px-3 py-1 text-xs font-semibold text-white">
                        Pencatatan Ditutup
                    </span>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-8 grid gap-4 sm:grid-cols-3">

            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                <p class="text-sm text-gray-500">Sedang Keluar</p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $activeExits->count() }}
                </p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                <p class="text-sm text-gray-500">Sudah Kembali</p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $todayReturned }}
                </p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                <p class="text-sm text-gray-500">Total Hari Ini</p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $todayTotal }}
                </p>
            </div>

        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    Catat Pegawai Keluar
                </h2>

                <p class="text-sm text-gray-500">
                    Cari berdasarkan nama atau NIP.
                </p>
            </div>

            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Cari nama atau NIP..."
                @disabled(! $workStatus['can_exit'])
                class="w-full rounded-lg border-gray-300 shadow-sm disabled:bg-gray-100"
            />

            @if ($employees->isNotEmpty())
                <div class="mt-2 overflow-hidden rounded-lg border border-gray-200">

                    @foreach ($employees as $employee)
                        <button
                            type="button"
                            wire:click="selectEmployee({{ $employee->id }})"
                            class="flex w-full items-center justify-between border-b px-4 py-3 text-left last:border-b-0 hover:bg-gray-50"
                        >
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ $employee->name }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    {{ $employee->nip }}
                                    ·
                                    {{ $employee->department->name }}
                                </p>
                            </div>

                            <span class="text-sm text-gray-500">
                                Pilih →
                            </span>
                        </button>
                    @endforeach

                </div>
            @endif

        </div>

        <div class="mt-8 rounded-xl bg-white shadow-sm ring-1 ring-gray-200">

            <div class="border-b px-6 py-4">
                <h2 class="font-semibold text-gray-900">
                    Pegawai Sedang Keluar
                </h2>
            </div>

            <div class="divide-y">

                @forelse ($activeExits as $record)
                    <div class="flex items-center justify-between px-6 py-4">

                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $record->employee->name }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $record->employee->department->name }}
                                ·
                                Keluar {{ $record->exit_at->format('H:i') }} WITA
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ $record->purpose }}
                                ·
                                {{ $record->destination }}
                            </p>
                        </div>

                        <button
                            type="button"
                            wire:click="recordReturn({{ $record->id }})"
                            wire:confirm="Tandai {{ $record->employee->name }} sudah kembali?"
                            class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
                        >
                            Kembali
                        </button>

                    </div>
                @empty

                    <div class="px-6 py-10 text-center text-sm text-gray-500">
                        Tidak ada pegawai yang sedang keluar.
                    </div>

                @endforelse

            </div>

        </div>

    </div>

    @if ($showExitModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">

            <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">

                <h2 class="text-lg font-semibold text-gray-900">
                    Catat Pegawai Keluar
                </h2>

                @if ($selectedEmployee)
                    <div class="mt-4 rounded-lg bg-gray-50 p-4">
                        <p class="font-medium">
                            {{ $selectedEmployee->name }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ $selectedEmployee->nip }}
                            ·
                            {{ $selectedEmployee->department->name }}
                        </p>
                    </div>
                @endif

                <div class="mt-5 space-y-4">

                    <div>
                        <label class="mb-1 block text-sm font-medium">
                            Keperluan
                        </label>

                        <input
                            type="text"
                            wire:model="purpose"
                            class="w-full rounded-lg border-gray-300 shadow-sm"
                            placeholder="Contoh: Mengurus dokumen pribadi"
                        >

                        @error('purpose')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">
                            Tujuan
                        </label>

                        <input
                            type="text"
                            wire:model="destination"
                            class="w-full rounded-lg border-gray-300 shadow-sm"
                            placeholder="Contoh: Bank"
                        >

                        @error('destination')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
                @error('exit')
                    <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                        <p class="font-medium">
                            Tidak dapat mencatat pegawai keluar
                        </p>

                        <p class="mt-1">
                            {{ $message }}
                        </p>
                    </div>
                @enderror
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        wire:click="closeExitModal"
                        class="rounded-lg border px-4 py-2 text-sm"
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        wire:click="recordExit"
                        wire:loading.attr="disabled"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white"
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
    function securityClock() {
        return {
            time: '--:--:--',

            start() {
                this.update();

                setInterval(() => {
                    this.update();
                }, 1000);
            },

            update() {
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
</script>
</div>