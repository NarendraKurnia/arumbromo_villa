@include('layout.head')
@include('layout.header')
@php
    // Fasilitas bisa berupa array atau string JSON
    $facilities = $room->facilities;
    if (is_string($facilities)) {
        $decoded = json_decode($facilities, true);
        $facilities = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $facilities)));
    }
    $facilities = is_iterable($facilities) ? collect($facilities)->filter()->values() : collect();

    $specs = [
        ['Kapasitas',     $room->capacity . ' Tamu',                          'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75'],
        ['Ukuran Kamar',  $room->room_size ?: '-',                            'M3 3h18v18H3zM3 9h18M9 21V9'],
        ['Jumlah Unit',   $room->total_units . ' Unit',                       'M3 10.5 12 3l9 7.5V21H3z'],
        ['Tambahan Tamu', ($room->max_extra_guests ?? 0) . ' Orang',          'M12 5v14M5 12h14'],
    ];
@endphp

<main class="bg-white">

    {{-- BREADCRUMB --}}
    <section class="px-5 pt-8 sm:px-8 lg:px-12">

        <div class="mx-auto max-w-7xl">

            <a href="{{ route('room.index') }}"
                class="text-sm text-brand-black/60 transition hover:text-brand-red">
                ← Kembali ke Pilihan Kamar
            </a>

        </div>

    </section>


    {{-- DETAIL KAMAR --}}
    <section class="px-5 py-10 sm:px-8 lg:px-12 lg:py-16">

        <div class="mx-auto max-w-7xl">

            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">

                {{-- GALERI --}}
                <div>

                    @php
                        $fotoUtama = $room->images->firstWhere('is_primary', 1)
                            ?? $room->images->first();
                    @endphp

                    <div class="aspect-[4/3] overflow-hidden rounded-lg bg-gray-100">

                        @if ($fotoUtama)

                            <img
                                src="{{ asset('home/admin/' . $fotoUtama->image) }}"
                                alt="{{ $room->name }}"
                                class="h-full w-full object-cover"
                            >

                        @else

                            <div class="flex h-full items-center justify-center text-gray-400">
                                Foto kamar belum tersedia
                            </div>

                        @endif

                    </div>


                    {{-- FOTO TAMBAHAN --}}
                    @if ($room->images->count() > 1)

                        <div class="mt-4 grid grid-cols-3 gap-3">

                            @foreach ($room->images->where('id', '!=', optional($fotoUtama)->id)->take(3) as $image)

                                <div class="aspect-[4/3] overflow-hidden rounded-md">

                                    <img
                                        src="{{ asset('home/admin/' . $image->image) }}"
                                        alt="{{ $room->name }}"
                                        loading="lazy"
                                        class="h-full w-full object-cover"
                                    >

                                </div>

                            @endforeach

                        </div>

                    @endif

                </div>


                {{-- INFORMASI KAMAR --}}
                <div>

                    <p class="text-xs font-semibold uppercase tracking-widest text-brand-red">
                        Arum Bromo Villas
                    </p>
                    <h1 class="mt-4 font-heading text-4xl font-semibold sm:text-5xl">
                        {{ $room->name }}
                    </h1>
                    <p class="mt-5 text-sm leading-8 text-brand-black/70">
                        {{ $room->description }}
                    </p>
                    {{-- SPESIFIKASI --}}
                    {{-- SPESIFIKASI --}}
<div class="mt-8 grid grid-cols-2 gap-4">

    {{-- Kapasitas --}}
    <div class="rounded-lg bg-gray-50 p-5">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Kapasitas
        </p>
        <p class="mt-2 font-heading text-xl font-semibold">
            {{ $room->capacity }} Tamu
        </p>
    </div>

    {{-- Ukuran Kamar --}}
    <div class="rounded-lg bg-gray-50 p-5">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Ukuran Kamar
        </p>
        <p class="mt-2 font-heading text-xl font-semibold">
            {{ $room->room_size ?: '-' }}
        </p>
    </div>

    {{-- Jumlah Unit --}}
    <div class="rounded-lg bg-gray-50 p-5">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Jumlah Unit
        </p>
        <p class="mt-2 font-heading text-xl font-semibold">
            {{ $room->total_units }} Unit
        </p>
    </div>

    {{-- Tambahan Tamu --}}
    <div class="rounded-lg bg-gray-50 p-5">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Tambahan Tamu
        </p>
        <p class="mt-2 font-heading text-xl font-semibold">
            {{ $room->max_extra_guests }} Orang
        </p>
    </div>

</div>

{{-- FASILITAS KAMAR --}}
 @if ($facilities->isNotEmpty())
                        <div class="mt-12">
                            <h2 class="font-heading text-2xl font-semibold tracking-tight">Fasilitas Kamar</h2>
                            <span class="mt-3 block h-1 w-10 bg-brand-red"></span>

                            <ul class="mt-6 grid grid-cols-1 gap-x-8 gap-y-3 sm:grid-cols-2">
                                @foreach ($facilities as $facility)
                                    <li class="flex items-center gap-3 border-b border-brand-stroke pb-3 text-sm text-brand-black/80">
                                        <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-brand-red text-white">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                        </span>
                                        {{ $facility }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- HARGA --}}
                    <div class="mt-8 rounded-lg border border-brand-stroke p-6">
                        <p class="text-xs uppercase tracking-widest text-gray-500">
                            Harga Normal per Malam
                        </p>
                        <p class="mt-2 font-heading text-3xl font-semibold">
                            Rp {{ number_format($room->price_per_night, 0, ',', '.') }}
                        </p>
                        <p class="mt-2 text-xs text-gray-500">
                            Harga dapat berubah sesuai periode high season.
                        </p>


                        <a href="{{ route('booking.index', ['room_id' => $room->id]) }}"
                            class="mt-6 flex w-full items-center justify-center rounded-md bg-brand-red px-6 py-4 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-red-700">

                            Pesan Kamar Sekarang →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@include('layout.footer')