@include('layout.head')
@include('layout.header')

<main class="bg-white">

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-brand-black px-5 py-20 sm:px-8 lg:px-12 lg:py-28">

        <div class="absolute inset-0 opacity-30">
            <img
                src="{{ asset('home/admin/icon-bromo2.jpg') }}"
                alt="Arum Bromo Villas"
                class="h-full w-full object-cover"
            >
        </div>

        <div class="absolute inset-0 bg-black/50"></div>

        <div class="relative z-10 mx-auto max-w-7xl">

            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/80">
                Arum Bromo Villas
            </p>

            <h1 class="mt-5 max-w-3xl font-heading text-4xl font-semibold text-white sm:text-5xl lg:text-6xl">
                Temukan Pengalaman Menginap Terbaik
            </h1>

            <p class="mt-5 max-w-2xl text-sm leading-relaxed text-white/80 sm:text-base">
                Pilih akomodasi terbaik untuk menikmati suasana alam dan kenyamanan menginap di kawasan Bromo.
            </p>

            <div class="mt-8">
                <a href="{{ route('home') }}"
                    class="text-sm font-semibold text-white hover:text-brand-red">
                    ← Kembali ke Beranda
                </a>
            </div>

        </div>

    </section>


    {{-- DAFTAR VILLA --}}
    <section class="px-5 py-16 sm:px-8 lg:px-12 lg:py-24">

        <div class="mx-auto max-w-7xl">

            <div class="mb-10">

                <p class="text-xs font-semibold uppercase tracking-widest text-brand-red">
                    Accommodation
                </p>

                <h2 class="mt-3 font-heading text-3xl font-semibold sm:text-4xl">
                    Pilihan Villa Kami
                </h2>

                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-brand-black/60">
                    Temukan villa yang sesuai dengan kebutuhan liburan dan pengalaman menginap Anda.
                </p>

            </div>


            {{-- GRID VILLA --}}
            <div class="grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-3">

                @forelse ($villas as $villa)

                    @php
                        $foto = $villa->images->firstWhere('is_primary', 1)
                            ?? $villa->images->first();
                    @endphp

                    <article class="group overflow-hidden rounded-lg border border-brand-stroke bg-white transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                        {{-- FOTO --}}
                        <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">

                            @if ($foto)

                                <img
                                    src="{{ asset('home/admin/' . $foto->image) }}"
                                    alt="{{ $villa->name }}"
                                    loading="lazy"
                                    class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                >

                            @else

                                <div class="flex h-full items-center justify-center text-sm text-gray-400">
                                    Foto villa belum tersedia
                                </div>

                            @endif

                            <span class="absolute left-4 top-4 rounded-full bg-white px-4 py-2 text-xs font-semibold text-brand-black shadow">
                                {{ $villa->rooms->count() }} Tipe Kamar
                            </span>

                        </div>


                        {{-- INFORMASI --}}
                        <div class="p-6">

                            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-brand-red">

                                <span>⌖</span>

                                {{ $villa->city }}, {{ $villa->province }}

                            </div>

                            <h3 class="mt-3 font-heading text-2xl font-semibold">
                                {{ $villa->name }}
                            </h3>

                            <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-brand-black/60">
                                {{ $villa->description }}
                            </p>


                            {{-- FASILITAS --}}
                            <div class="mt-5 flex flex-wrap gap-2">

                                @foreach ($villa->facilities->take(3) as $facility)

                                    <span class="rounded-full bg-gray-100 px-3 py-1.5 text-xs text-brand-black/70">
                                        {{ $facility->name }}
                                    </span>

                                @endforeach

                            </div>


                            {{-- BUTTON --}}
                            <div class="mt-6 border-t border-brand-stroke pt-5">

                                <a href="{{ route('villa.detail', $villa->slug) }}"
                                    class="flex w-full items-center justify-center rounded-md bg-brand-black px-5 py-3 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-brand-red">

                                    Lihat Detail Villa →

                                </a>

                            </div>

                        </div>

                    </article>

                @empty

                    <div class="col-span-full py-20 text-center">

                        <div class="text-5xl">🏡</div>

                        <h3 class="mt-5 font-heading text-2xl font-semibold">
                            Belum Ada Villa
                        </h3>

                        <p class="mt-3 text-sm text-gray-500">
                            Saat ini belum ada villa yang tersedia.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- PAGINATION --}}
            @if ($villas->hasPages())

                <div class="mt-12">
                    {{ $villas->links() }}
                </div>

            @endif

        </div>

    </section>

</main>

@include('layout.footer')