<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Monitoring Pegawai - TVRI NTT</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 antialiased">

    <main class="relative isolate min-h-screen overflow-hidden">

        {{-- Background --}}
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950"></div>

            <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-emerald-500/10 blur-3xl"></div>

            <div class="absolute -bottom-40 -right-20 h-[28rem] w-[28rem] rounded-full bg-teal-400/10 blur-3xl"></div>
        </div>


        {{-- Header --}}
        <header class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-6 lg:px-8">

            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/10 shadow-lg backdrop-blur">
                    <span class="text-sm font-bold tracking-wide text-white">
                        TVRI
                    </span>
                </div>

                <div>
                    <p class="text-sm font-semibold tracking-wide text-white">
                        TVRI NTT
                    </p>

                    <p class="text-xs text-slate-400">
                        Monitoring Pegawai
                    </p>
                </div>

            </div>

            <div class="hidden items-center gap-2 text-xs text-slate-400 sm:flex">
                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                Sistem Internal
            </div>

        </header>


        {{-- Hero --}}
        <section class="mx-auto flex min-h-[calc(100vh-88px)] w-full max-w-7xl items-center px-6 pb-16 pt-10 lg:px-8">

            <div class="grid w-full items-center gap-14 lg:grid-cols-[1.05fr_.95fr]">

                {{-- Left --}}
                <div class="max-w-2xl">

                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3.5 py-2 text-xs font-medium text-emerald-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>

                        Sistem Monitoring Internal
                    </div>


                    <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                        Monitoring Pegawai
                        <span class="block text-emerald-400">
                            TVRI NTT
                        </span>
                    </h1>


                    <p class="mt-6 max-w-xl text-base leading-7 text-slate-300 sm:text-lg">
                        Sistem terpusat untuk membantu pengelolaan data pegawai,
                        pencatatan pegawai keluar dan kembali, serta pemantauan
                        aktivitas secara lebih tertib dan efisien.
                    </p>


                    {{-- Access cards --}}
                    <div class="mt-10 grid gap-4 sm:grid-cols-2">

                        {{-- Admin --}}
                        <a
                            href="{{ url('/admin') }}"
                            class="group rounded-2xl border border-white/10 bg-white/[0.06] p-5 shadow-xl backdrop-blur transition duration-200 hover:-translate-y-1 hover:border-emerald-400/30 hover:bg-white/[0.09]"
                        >

                            <div class="flex items-start justify-between">

                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-400/10 text-emerald-300">
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M10.5 6h3m-7.5 4h12M6 14h12M8 18h8M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"
                                        />
                                    </svg>
                                </div>

                                <span class="text-slate-500 transition group-hover:translate-x-1 group-hover:text-emerald-300">
                                    →
                                </span>

                            </div>


                            <h2 class="mt-5 text-base font-semibold text-white">
                                Administrator
                            </h2>

                            <p class="mt-1.5 text-sm leading-6 text-slate-400">
                                Kelola pegawai, departemen, jadwal kerja,
                                transaksi dan laporan.
                            </p>


                            <div class="mt-5 text-sm font-semibold text-emerald-300">
                                Masuk Admin
                            </div>

                        </a>


                        {{-- Security --}}
                        <a
                            href="{{ route('security.dashboard') }}"
                            class="group rounded-2xl border border-white/10 bg-white/[0.06] p-5 shadow-xl backdrop-blur transition duration-200 hover:-translate-y-1 hover:border-blue-400/30 hover:bg-white/[0.09]"
                        >

                            <div class="flex items-start justify-between">

                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-400/10 text-blue-300">
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 3l7 3v5c0 4.5-2.8 8.2-7 10-4.2-1.8-7-5.5-7-10V6l7-3z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9.5 12l1.7 1.7 3.5-3.5"
                                        />
                                    </svg>
                                </div>

                                <span class="text-slate-500 transition group-hover:translate-x-1 group-hover:text-blue-300">
                                    →
                                </span>

                            </div>


                            <h2 class="mt-5 text-base font-semibold text-white">
                                Security
                            </h2>

                            <p class="mt-1.5 text-sm leading-6 text-slate-400">
                                Mencatat pegawai keluar dan kembali
                                dengan cepat dan mudah.
                            </p>


                            <div class="mt-5 text-sm font-semibold text-blue-300">
                                Masuk Security
                            </div>

                        </a>

                    </div>


                    {{-- Footer text --}}
                    <div class="mt-10 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-slate-500">
                        <span>TVRI NTT</span>

                        <span class="h-1 w-1 rounded-full bg-slate-700"></span>

                        <span>Sistem Internal</span>

                        <span class="h-1 w-1 rounded-full bg-slate-700"></span>

                        <span>Monitoring Pegawai</span>
                    </div>

                </div>


                {{-- Right visual --}}
                <div class="hidden lg:block">

                    <div class="relative mx-auto max-w-md">

                        {{-- Glow --}}
                        <div class="absolute inset-8 rounded-[2rem] bg-emerald-400/10 blur-3xl"></div>


                        {{-- Main card --}}
                        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.06] p-6 shadow-2xl backdrop-blur-xl">

                            <div class="flex items-center justify-between">

                                <div>
                                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500">
                                        Monitoring
                                    </p>

                                    <p class="mt-1 text-lg font-semibold text-white">
                                        Aktivitas Pegawai
                                    </p>
                                </div>

                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-400/10">
                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                                </div>

                            </div>


                            {{-- Fake stats --}}
                            <div class="mt-7 grid grid-cols-2 gap-3">

                                <div class="rounded-xl border border-white/10 bg-white/[0.04] p-4">
                                    <p class="text-xs text-slate-500">
                                        Keluar Hari Ini
                                    </p>

                                    <p class="mt-2 text-2xl font-bold text-white">
                                        —
                                    </p>
                                </div>

                                <div class="rounded-xl border border-white/10 bg-white/[0.04] p-4">
                                    <p class="text-xs text-slate-500">
                                        Masih Keluar
                                    </p>

                                    <p class="mt-2 text-2xl font-bold text-amber-300">
                                        —
                                    </p>
                                </div>

                            </div>


                            {{-- Fake activity --}}
                            <div class="mt-4 space-y-3">

                                <div class="rounded-xl border border-white/10 bg-white/[0.04] p-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-400/10 text-xs font-semibold text-emerald-300">
                                            P
                                        </div>

                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-white">
                                                Pencatatan terintegrasi
                                            </p>

                                            <p class="mt-0.5 text-xs text-slate-500">
                                                Data tersimpan secara terpusat
                                            </p>
                                        </div>

                                        <span class="text-xs text-emerald-300">
                                            Aktif
                                        </span>

                                    </div>

                                </div>


                                <div class="rounded-xl border border-white/10 bg-white/[0.04] p-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-400/10 text-xs font-semibold text-blue-300">
                                            S
                                        </div>

                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-white">
                                                Akses Security
                                            </p>

                                            <p class="mt-0.5 text-xs text-slate-500">
                                                Pencatatan keluar dan kembali
                                            </p>
                                        </div>

                                        <span class="text-xs text-blue-300">
                                            Siap
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Small floating card --}}
                        <div class="absolute -bottom-6 -left-8 rounded-2xl border border-white/10 bg-slate-900/90 px-5 py-4 shadow-xl backdrop-blur">
                            <p class="text-xs text-slate-500">
                                Status Sistem
                            </p>

                            <div class="mt-1 flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>

                                <span class="text-sm font-semibold text-white">
                                    Beroperasi
                                </span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </section>

    </main>

</body>
</html>