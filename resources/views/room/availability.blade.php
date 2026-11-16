@include('layout.head')
@include('layout.header')

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
    
<main class="min-h-screen bg-gray-50 py-10">
    {{-- HERO --}}

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 mt-12">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                Ketersediaan Kamar
            </h1>

            <p class="text-gray-500 mt-2">
                Temukan kamar terbaik untuk menginap di Arum Bromo Villas.
            </p>
        </div>

        {{-- Informasi pencarian --}}
        <div class="bg-white rounded-xl shadow-sm p-5 mb-8">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <div>
                    <p class="text-sm text-gray-500">Check-in</p>
                    <p class="font-semibold text-gray-800">
                        {{ $checkIn->format('d M Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Check-out</p>
                    <p class="font-semibold text-gray-800">
                        {{ $checkOut->format('d M Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Durasi</p>
                    <p class="font-semibold text-gray-800">
                        {{ $totalNights }} Malam
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Jumlah Tamu</p>
                    <p class="font-semibold text-gray-800">
                        {{ $dewasa }} Dewasa,
                        {{ $anak }} Anak
                    </p>
                </div>

            </div>

        </div>

        {{-- Hasil kamar --}}
        @forelse($availableRooms as $item)

            @php
                $room = $item['room'];
                $primaryImage = $room->images->first();
            @endphp

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">

                <div class="grid grid-cols-1 md:grid-cols-3">

                    {{-- Gambar --}}
                    <div class="h-56 md:h-full">

                        @if($primaryImage)

                            <img
                                src="{{ asset('home/admin/' . $primaryImage->image) }}"
                                alt="{{ $room->name }}"
                                class="w-full h-full object-cover">

                        @else

                            <div class="w-full h-full min-h-56 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">
                                    Gambar tidak tersedia
                                </span>
                            </div>

                        @endif

                    </div>

                    {{-- Informasi kamar --}}
                    <div class="p-6">

                        <h2 class="text-xl font-bold text-gray-800">
                            {{ $room->name }}
                        </h2>

                        <p class="text-gray-500 text-sm mt-2">
                            {{ $room->description }}
                        </p>

                        <div class="flex flex-wrap gap-4 mt-5 text-sm text-gray-600">

                            <span>
                                Kapasitas:
                                {{ $room->capacity }} Orang
                            </span>

                            <span>
                                Luas:
                                {{ $room->room_size ?? '-' }}
                            </span>

                        </div>

                        <p class="text-green-600 font-semibold mt-5">
                            Tersedia {{ $item['available_units'] }} Unit
                        </p>

                    </div>

                    {{-- Harga --}}
                    <div class="bg-gray-50 p-6 flex flex-col justify-between">

                        <div>

                            <p class="text-sm text-gray-500 mb-2">
                                Rincian Harga
                            </p>

                            @foreach($item['nightly_prices'] as $night)

                                <div class="flex justify-between gap-3 text-sm mb-2">

                                    <span class="text-gray-600">
                                        {{ $night['date'] }}
                                        ({{ $night['season'] }})
                                    </span>

                                    <span class="font-medium text-gray-800">
                                        Rp{{ number_format($night['price'], 0, ',', '.') }}
                                    </span>

                                </div>

                            @endforeach

                            <div class="border-t mt-4 pt-4">

                                <p class="text-sm text-gray-500">
                                    Total {{ $item['total_nights'] }} Malam
                                </p>

                                <p class="text-2xl font-bold text-amber-700 mt-1">
                                    Rp{{ number_format($item['total_price'], 0, ',', '.') }}
                                </p>

                            </div>

                        </div>

                        <a href="{{ route('room.detail', $room->slug) }}"
                           class="block text-center bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 rounded-lg mt-6 transition">

                            Lihat Detail Kamar

                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="bg-white rounded-2xl shadow-sm p-10 text-center">

                <h2 class="text-xl font-bold text-gray-800">
                    Kamar Tidak Tersedia
                </h2>

                <p class="text-gray-500 mt-3">
                    Maaf, tidak ditemukan kamar yang tersedia untuk tanggal
                    dan jumlah tamu yang dipilih.
                </p>

                <a href="{{ route('room.index') }}"
                   class="inline-block mt-6 bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-lg">
                    Cari Tanggal Lain
                </a>

            </div>

        @endforelse

    </div>

</main>

@include('layout.footer')