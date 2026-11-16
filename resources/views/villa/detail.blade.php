@include('layout.head')
@include('layout.header')

<main class="bg-white">

    {{-- BREADCRUMB --}}
    <section class="px-5 pt-8 sm:px-8 lg:px-12">

        <div class="mx-auto max-w-7xl">

            <a href="{{ route('villa.index') }}"
                class="text-sm text-brand-black/60 hover:text-brand-red">
                ← Kembali ke Daftar Villa
            </a>

        </div>

    </section>


    {{-- INFORMASI VILLA --}}
    <section class="px-5 py-10 sm:px-8 lg:px-12 lg:py-16">

        <div class="mx-auto max-w-7xl">

            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">

                {{-- GALERI FOTO --}}
                <div>

                    @php
                        $fotoUtama = $villa->images->firstWhere('is_primary', 1)
                            ?? $villa->images->first();
                    @endphp

                    <div class="aspect-[4/3] overflow-hidden rounded-lg bg-gray-100">

                        @if ($fotoUtama)

                            <img
                                src="{{ asset('home/admin/' . $fotoUtama->image) }}"
                                alt="{{ $villa->name }}"
                                class="h-full w-full object-cover"
                            >

                        @else

                            <div class="flex h-full items-center justify-center text-gray-400">
                                Foto villa belum tersedia
                            </div>

                        @endif

                    </div>


                    {{-- FOTO LAINNYA --}}
                    @if ($villa->images->count() > 1)

                        <div class="mt-4 grid grid-cols-3 gap-3">

                            @foreach ($villa->images->where('id', '!=', optional($fotoUtama)->id)->take(3) as $image)

                                <div class="aspect-[4/3] overflow-hidden rounded-md">

                                    <img
                                        src="{{ asset('home/admin/' . $image->image) }}"
                                        alt="{{ $villa->name }}"
                                        loading="lazy"
                                        class="h-full w-full object-cover"
                                    >

                                </div>

                            @endforeach

                        </div>

                    @endif

                </div>


                {{-- DESKRIPSI --}}
                <div>

                    <p class="text-xs font-semibold uppercase tracking-widest text-brand-red">
                        Arum Bromo Villas
                    </p>

                    <h1 class="mt-4 font-heading text-4xl font-semibold sm:text-5xl">
                        {{ $villa->name }}
                    </h1>

                    <p class="mt-4 text-sm text-brand-black/60">
                        ⌖ {{ $villa->address }}, {{ $villa->city }}, {{ $villa->province }}
                    </p>

                    <div class="mt-7 h-px bg-brand-stroke"></div>

                    <h2 class="mt-7 font-heading text-2xl font-semibold">
                        Tentang Villa
                    </h2>

                    <p class="mt-4 whitespace-pre-line text-sm leading-8 text-brand-black/70">
                        {{ $villa->description }}
                    </p>


                    {{-- WAKTU CHECK IN --}}
                    <div class="mt-8 grid grid-cols-2 gap-4">

                        <div class="rounded-lg bg-gray-50 p-5">

                            <p class="text-xs uppercase tracking-widest text-gray-500">
                                Check In
                            </p>

                            <p class="mt-2 font-heading text-xl font-semibold">
                                {{ \Carbon\Carbon::parse($villa->check_in_time)->format('H:i') }}
                            </p>

                        </div>


                        <div class="rounded-lg bg-gray-50 p-5">

                            <p class="text-xs uppercase tracking-widest text-gray-500">
                                Check Out
                            </p>

                            <p class="mt-2 font-heading text-xl font-semibold">
                                {{ \Carbon\Carbon::parse($villa->check_out_time)->format('H:i') }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- FASILITAS --}}
            <div class="mt-16 border-t border-brand-stroke pt-12">

                <p class="text-xs font-semibold uppercase tracking-widest text-brand-red">
                    Facilities
                </p>

                <h2 class="mt-3 font-heading text-3xl font-semibold">
                    Fasilitas Villa
                </h2>


                <div class="mt-7 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">

                    @forelse ($villa->facilities as $facility)

                        <div class="rounded-lg border border-brand-stroke p-5">

                            <span class="text-brand-red">✦</span>

                            <p class="mt-3 text-sm font-medium">
                                {{ $facility->name }}
                            </p>

                        </div>

                    @empty

                        <p class="col-span-full text-sm text-gray-500">
                            Informasi fasilitas belum tersedia.
                        </p>

                    @endforelse

                </div>

            </div>

        </div>

    </section>


    {{-- PILIHAN KAMAR --}}
    <section id="kamar" class="scroll-mt-24 bg-gray-50 px-5 py-16 sm:px-8 lg:px-12 lg:py-24">

        <div class="mx-auto max-w-7xl">

            <p class="text-xs font-semibold uppercase tracking-widest text-brand-red">
                Accommodation
            </p>

            <h2 class="mt-3 font-heading text-3xl font-semibold sm:text-4xl">
                Pilihan Kamar
            </h2>

            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-brand-black/60">
                Pilih tipe kamar yang sesuai dengan kebutuhan dan jumlah tamu Anda.
            </p>


            <div class="mt-10 grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-3">

                @forelse ($villa->rooms as $room)

                    @php
                        $roomImage = $room->images->firstWhere('is_primary', 1)
                            ?? $room->images->first();
                    @endphp

                    <article class="group overflow-hidden rounded-lg border border-brand-stroke bg-white transition hover:shadow-xl">

                        {{-- FOTO KAMAR --}}
                        <div class="aspect-[4/3] overflow-hidden bg-gray-100">

                            @if ($roomImage)

                                <img
                                    src="{{ asset('home/admin/' . $roomImage->image) }}"
                                    alt="{{ $room->name }}"
                                    loading="lazy"
                                    class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                >

                            @else

                                <div class="flex h-full items-center justify-center text-sm text-gray-400">
                                    Foto kamar belum tersedia
                                </div>

                            @endif

                        </div>


                        {{-- INFORMASI KAMAR --}}
                        <div class="p-6">

                            <h3 class="font-heading text-2xl font-semibold">
                                {{ $room->name }}
                            </h3>

                            <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-brand-black/60">
                                {{ $room->description }}
                            </p>


                            <div class="mt-5 flex flex-wrap gap-4 text-sm text-brand-black/60">

                                <span>
                                    ♙ {{ $room->capacity }} Tamu
                                </span>

                                @if ($room->room_size)
                                    <span>
                                        ◈ {{ $room->room_size }}
                                    </span>
                                @endif

                                <span>
                                    {{ $room->total_units }} Unit
                                </span>

                            </div>


                            {{-- HARGA --}}
                            <div class="mt-6 border-t border-brand-stroke pt-5">

                                <p class="text-xs uppercase tracking-widest text-gray-500">
                                    Harga mulai dari
                                </p>

                                <p class="mt-2 font-heading text-2xl font-semibold">
                                    Rp {{ number_format($room->price_per_night, 0, ',', '.') }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    per malam
                                </p>


                                <a href="#reservasi"
                                    class="mt-5 block rounded-md bg-brand-black px-5 py-3 text-center text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-brand-red">

                                    Pilih Kamar

                                </a>

                            </div>

                        </div>

                    </article>

                @empty

                    <div class="col-span-full py-16 text-center">

                        <h3 class="font-heading text-xl font-semibold">
                            Belum Ada Kamar Tersedia
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Belum ada kamar aktif pada villa ini.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </section>


    {{-- CTA --}}
    <section class="bg-brand-black px-5 py-16 text-center text-white sm:px-8 lg:py-20">

        <div class="mx-auto max-w-3xl">

            <h2 class="font-heading text-3xl font-semibold sm:text-4xl">
                Nikmati Pengalaman Menginap Bersama Kami
            </h2>

            <p class="mt-5 text-sm leading-relaxed text-white/70">
                Rencanakan liburan Anda dan temukan kenyamanan menginap di Arum Bromo Villas.
            </p>

            <a href="{{ route('home') }}"
                class="mt-8 inline-block rounded-md bg-brand-red px-8 py-3.5 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-red-700">

                Kembali ke Beranda

            </a>

        </div>

    </section>

</main>

@include('layout.footer')