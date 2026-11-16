@include('layout.head')
@include('layout.header')

<main>
    
    {{-- HERO --}}
    <section class="relative overflow-hidden bg-brand-black pt-[var(--header-h)] text-white">
        <img src="{{ asset('home/admin/icon-bromo2.jpg') }}" alt="Arum Bromo Villas"
             class="absolute inset-0 h-full w-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-t from-brand-black via-black/40 to-black/30"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-5 py-16 sm:px-8 sm:py-20 lg:px-12 lg:py-28">
            {{-- Breadcrumb --}}
            <nav aria-label="Breadcrumb" class="text-xs uppercase tracking-widest text-white/60">
                <a href="{{ url('/') }}" class="hover:text-white">Beranda</a>
                <span class="mx-2">/</span>
                <span class="text-white">Kamar</span>
            </nav>
            <p class="mt-6 text-xs font-semibold uppercase tracking-[0.3em] text-white/80">
                <span class="mr-2 inline-block h-2 w-2 rounded-full bg-brand-red"></span>Arum Bromo Villas
            </p>
            <h1 class="mt-4 max-w-3xl font-heading text-4xl font-semibold leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl">
                Temukan Kamar untuk Pengalaman Menginap Terbaik
            </h1>
            <p class="mt-5 max-w-2xl text-sm leading-relaxed text-white/80 sm:text-base">
                Nikmati kenyamanan menginap dengan berbagai pilihan tipe kamar yang dapat disesuaikan dengan kebutuhan Anda.
            </p>
        </div>
    </section>

    <section class="relative z-20 mx-auto -mt-10 max-w-7xl px-5 sm:-mt-14 sm:px-8 lg:-mt-16 lg:px-12">

    <form action="{{ route('availability.index') }}"
          method="GET"
          class="overflow-hidden rounded-2xl border border-brand-stroke bg-white shadow-2xl shadow-black/10">

        {{-- Judul form --}}
        <div class="flex items-center justify-between gap-4 border-b border-brand-stroke bg-brand-black px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <span class="h-2.5 w-2.5 rounded-full bg-brand-red"></span>
                <h2 class="font-heading text-sm font-semibold uppercase tracking-widest text-white sm:text-base">
                    Cek Ketersediaan Kamar
                </h2>
            </div>
            <p class="hidden text-xs text-white/50 sm:block">Pilih tanggal &amp; jumlah tamu</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1.3fr_1.3fr_1fr_1fr_auto]">

            {{-- CHECK-IN --}}
            <label for="check_in"
                   class="group flex cursor-pointer items-center gap-4 border-b border-brand-stroke px-5 py-4 transition focus-within:bg-red-50/60 hover:bg-gray-50 sm:border-r lg:border-b-0">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-red-50 text-brand-red transition group-focus-within:bg-brand-red group-focus-within:text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block text-[11px] font-semibold uppercase tracking-widest text-brand-black/50">Check-in</span>
                    <input
                        type="date"
                        name="check_in"
                        id="check_in"
                        min="{{ date('Y-m-d') }}"
                        value="{{ request('check_in') }}"
                        required
                        class="mt-1 w-full bg-transparent text-sm font-semibold text-brand-black outline-none">
                </span>
            </label>

            {{-- CHECK-OUT --}}
            <label for="check_out"
                   class="group flex cursor-pointer items-center gap-4 border-b border-brand-stroke px-5 py-4 transition focus-within:bg-red-50/60 hover:bg-gray-50 lg:border-b-0 lg:border-r">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-red-50 text-brand-red transition group-focus-within:bg-brand-red group-focus-within:text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M9 16l2 2 4-4"/>
                    </svg>
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block text-[11px] font-semibold uppercase tracking-widest text-brand-black/50">Check-out</span>
                    <input
                        type="date"
                        name="check_out"
                        id="check_out"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        value="{{ request('check_out') }}"
                        required
                        class="mt-1 w-full bg-transparent text-sm font-semibold text-brand-black outline-none">
                </span>
            </label>

            {{-- DEWASA --}}
            <label class="group flex cursor-pointer items-center gap-4 border-b border-brand-stroke px-5 py-4 transition focus-within:bg-red-50/60 hover:bg-gray-50 sm:border-r lg:border-b-0">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-red-50 text-brand-red transition group-focus-within:bg-brand-red group-focus-within:text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block text-[11px] font-semibold uppercase tracking-widest text-brand-black/50">Dewasa</span>
                    <input
                        type="number"
                        name="dewasa"
                        min="1"
                        value="{{ request('dewasa', 2) }}"
                        required
                        class="mt-1 w-full bg-transparent text-sm font-semibold text-brand-black outline-none">
                </span>
            </label>

            {{-- ANAK --}}
            <label class="group flex cursor-pointer items-center gap-4 border-b border-brand-stroke px-5 py-4 transition focus-within:bg-red-50/60 hover:bg-gray-50 lg:border-b-0 lg:border-r">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-red-50 text-brand-red transition group-focus-within:bg-brand-red group-focus-within:text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="7" r="3.5"/><path d="M5 21v-1a7 7 0 0 1 14 0v1"/>
                    </svg>
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block text-[11px] font-semibold uppercase tracking-widest text-brand-black/50">Anak-anak</span>
                    <input
                        type="number"
                        name="anak"
                        min="0"
                        value="{{ request('anak', 0) }}"
                        class="mt-1 w-full bg-transparent text-sm font-semibold text-brand-black outline-none">
                </span>
            </label>

            {{-- BUTTON --}}
            <div class="p-3 sm:col-span-2 lg:col-span-1 lg:flex lg:p-3">
                <button
                    type="submit"
                    class="flex min-h-[60px] w-full items-center justify-center gap-2 rounded-xl bg-brand-red px-8 py-4 text-sm font-semibold uppercase tracking-widest text-white shadow-lg shadow-brand-red/30 transition hover:bg-red-700 hover:shadow-xl active:scale-[0.98] lg:w-auto">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                    </svg>
                    Cari Kamar
                </button>
            </div>

        </div>
    </form>

</section>

    {{-- DAFTAR KAMAR --}}
    <section class="px-5 py-14 sm:px-8 lg:px-12 lg:py-20">
        <div class="mx-auto max-w-7xl">

            {{-- Judul + jumlah --}}
            <div class="mb-10 flex flex-col gap-4 border-b border-brand-stroke pb-8 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-brand-red">Our Accommodation</p>
                    <h2 class="mt-3 font-heading text-3xl font-semibold tracking-tight sm:text-4xl">Pilihan Kamar Kami</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-brand-black/60">
                        Pilih kamar yang sesuai dengan kebutuhan perjalanan dan kenyamanan Anda.
                    </p>
                </div>

                @if (method_exists($rooms, 'total'))
                    <p class="shrink-0 text-sm text-brand-black/50">
                        Menampilkan <span class="font-semibold text-brand-black">{{ $rooms->count() }}</span>
                        dari <span class="font-semibold text-brand-black">{{ $rooms->total() }}</span> kamar
                    </p>
                @endif
            </div>


            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">

                @forelse ($rooms as $room)

                    @php
                        $foto = $room->images->firstWhere('is_primary', 1) ?? $room->images->first();
                    @endphp

                    <article class="group flex flex-col overflow-hidden rounded-xl border border-brand-stroke bg-white transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                        {{-- Foto --}}
                        <a href="{{ route('room.detail', $room->slug) }}" class="relative block aspect-[4/3] overflow-hidden bg-gray-100">
                            @if ($foto)
                                <img src="{{ asset('home/admin/' . $foto->image) }}"
                                     alt="{{ $room->name }}" loading="lazy"
                                     class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                            @else
                                <div class="flex h-full items-center justify-center text-sm text-gray-400">
                                    Foto kamar belum tersedia
                                </div>
                            @endif

                            <span class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-xs font-semibold shadow">
                                <svg class="h-3.5 w-3.5 text-brand-red" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                {{ $room->capacity }} Tamu
                            </span>
                        </a>

                        {{-- Informasi --}}
                        <div class="flex flex-1 flex-col p-6">

                            <h3 class="font-heading text-xl font-semibold leading-snug sm:text-2xl">
                                <a href="{{ route('room.detail', $room->slug) }}" class="hover:text-brand-red">{{ $room->name }}</a>
                            </h3>

                            <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-brand-black/60">
                                {{ $room->description }}
                            </p>

                            {{-- Detail --}}
                            <ul class="mt-5 flex flex-wrap gap-2 text-xs text-brand-black/70">
                                <li class="inline-flex items-center gap-1.5 rounded-full bg-[#f5f5f5] px-3 py-1.5">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    {{ $room->capacity }} Tamu
                                </li>
                                @if ($room->room_size)
                                    <li class="inline-flex items-center gap-1.5 rounded-full bg-[#f5f5f5] px-3 py-1.5">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                                        {{ $room->room_size }}
                                    </li>
                                @endif
                                <li class="inline-flex items-center gap-1.5 rounded-full bg-[#f5f5f5] px-3 py-1.5">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5V21H3z"/></svg>
                                    {{ $room->total_units }} Unit
                                </li>
                            </ul>

                            {{-- Harga + tombol (selalu rata di bawah kartu) --}}
                            <div class="mt-auto pt-6">
                                <div class="border-t border-brand-stroke pt-5">
                                    <p class="text-[11px] uppercase tracking-widest text-gray-500">Harga normal mulai dari</p>
                                    <p class="mt-1.5 font-heading text-2xl font-semibold">
                                        Rp {{ number_format($room->price_per_night, 0, ',', '.') }}
                                        <span class="text-xs font-normal text-gray-500">/ malam</span>
                                    </p>

                                    <a href="{{ route('room.detail', $room->slug) }}"
                                       class="mt-5 flex w-full items-center justify-center gap-2 rounded-md bg-brand-black px-5 py-3 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-brand-red">
                                        Lihat Detail Kamar
                                        <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </article>

                @empty

                    <div class="col-span-full rounded-xl border border-dashed border-brand-stroke py-20 text-center">
                        <div class="text-5xl">🏡</div>
                        <h3 class="mt-5 font-heading text-2xl font-semibold">Belum Ada Kamar Tersedia</h3>
                        <p class="mt-3 text-sm text-gray-500">Silakan kembali lagi untuk melihat pilihan kamar kami.</p>
                        <a href="{{ url('/') }}" class="mt-6 inline-block rounded-md bg-brand-black px-5 py-3 text-xs font-semibold uppercase tracking-widest text-white hover:bg-brand-red">
                            Kembali ke Beranda
                        </a>
                    </div>

                @endforelse

            </div>


            {{-- Pagination --}}
            @if (method_exists($rooms, 'hasPages') && $rooms->hasPages())
                <div class="mt-12">
                    {{ $rooms->links() }}
                </div>
            @endif

        </div>
    </section>

</main>

@include('layout.footer')