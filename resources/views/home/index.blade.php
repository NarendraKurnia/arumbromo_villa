@extends('layout.wrapper')

@section('title', 'Arum Bromo Villas')

@php
  $facilities = [
    ['Kolam Renang Privat', 'Setiap villa punya kolam sendiri untuk berenang sepuasnya.'],
    ['Sarapan Harian', 'Menu lokal dan internasional disiapkan segar tiap pagi.'],
    ['WiFi Cepat', 'Cocok untuk liburan sambil bekerja jarak jauh.'],
    ['Antar-Jemput Bandara', 'Layanan transfer nyaman dari dan ke bandara.'],
    ['Keamanan 24 Jam', 'Petugas jaga dan CCTV di area villa setiap saat.'],
    ['Layanan Concierge', 'Bantu atur tur, spa, hingga makan malam privat.'],
  ];
@endphp

@section('content')

{{-- HERO + FORM BOOKING --}}
<section id="booking" class="relative flex min-h-[85vh] flex-col overflow-hidden bg-brand-black pt-[var(--header-h)] text-white">
  <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1920"
       alt="" class="absolute inset-0 h-full w-full object-cover" fetchpriority="high">
  <div class="absolute inset-0 bg-black/40"></div>
  <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-brand-black to-transparent"></div>

  <div class="relative z-10 mt-auto px-4 pb-10 pt-20 sm:px-6 lg:px-10 lg:pb-16">
    <h1 class="mt-8 max-w-3xl font-heading text-4xl font-semibold leading-[1.05] tracking-tight sm:text-6xl lg:text-7xl">
      Menginap Tenang di Villa Impian Anda
    </h1>

    <form action="{{ url('availability.index') }}" method="GET"
      class="mt-16 overflow-hidden rounded-2xl border border-white/30 bg-white/10 text-white shadow-2xl shadow-black/20 backdrop-blur-md">

  {{-- Judul form --}}
  <div class="flex items-center justify-between gap-4 border-b border-white/20 bg-black/20 px-5 py-4 sm:px-6">
    <div class="flex items-center gap-3">
      <span class="h-2.5 w-2.5 rounded-full bg-brand-red"></span>
      <h2 class="font-heading text-sm font-semibold uppercase tracking-widest text-white sm:text-base">
        Cek Ketersediaan Villa
      </h2>
    </div>
    <p class="hidden text-xs text-white/60 sm:block">Pilih tanggal &amp; jumlah tamu</p>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1.3fr_1.3fr_1fr_1fr_auto]">

    {{-- CHECK-IN --}}
    <label for="checkin"
           class="group flex cursor-pointer items-center gap-4 border-b border-white/20 px-5 py-4 transition hover:bg-white/10 focus-within:bg-white/15 sm:border-r lg:border-b-0">
      <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-white/15 text-white transition group-focus-within:bg-brand-red">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
        </svg>
      </span>
      <span class="min-w-0 flex-1">
        <span class="block text-[11px] font-semibold uppercase tracking-widest text-white/70">Check-in</span>
        <input type="date" name="checkin" id="checkin" min="{{ date('Y-m-d') }}"
               value="{{ request('checkin') }}" required
               class="mt-1 w-full bg-transparent text-sm font-semibold text-white outline-none [color-scheme:dark]">
      </span>
    </label>

    {{-- CHECK-OUT --}}
    <label for="checkout"
           class="group flex cursor-pointer items-center gap-4 border-b border-white/20 px-5 py-4 transition hover:bg-white/10 focus-within:bg-white/15 lg:border-b-0 lg:border-r">
      <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-white/15 text-white transition group-focus-within:bg-brand-red">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M9 16l2 2 4-4"/>
        </svg>
      </span>
      <span class="min-w-0 flex-1">
        <span class="block text-[11px] font-semibold uppercase tracking-widest text-white/70">Check-out</span>
        <input type="date" name="checkout" id="checkout" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
               value="{{ request('checkout') }}" required
               class="mt-1 w-full bg-transparent text-sm font-semibold text-white outline-none [color-scheme:dark]">
      </span>
    </label>

    {{-- DEWASA --}}
    <label class="group flex cursor-pointer items-center gap-4 border-b border-white/20 px-5 py-4 transition hover:bg-white/10 focus-within:bg-white/15 sm:border-r lg:border-b-0">
      <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-white/15 text-white transition group-focus-within:bg-brand-red">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
          <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
      </span>
      <span class="min-w-0 flex-1">
        <span class="block text-[11px] font-semibold uppercase tracking-widest text-white/70">Dewasa</span>
        <input type="number" name="dewasa" min="1" value="{{ request('dewasa', 2) }}" required
               class="mt-1 w-full bg-transparent text-sm font-semibold text-white outline-none">
      </span>
    </label>

    {{-- ANAK --}}
    <label class="group flex cursor-pointer items-center gap-4 border-b border-white/20 px-5 py-4 transition hover:bg-white/10 focus-within:bg-white/15 lg:border-b-0 lg:border-r">
      <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-white/15 text-white transition group-focus-within:bg-brand-red">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="7" r="3.5"/><path d="M5 21v-1a7 7 0 0 1 14 0v1"/>
        </svg>
      </span>
      <span class="min-w-0 flex-1">
        <span class="block text-[11px] font-semibold uppercase tracking-widest text-white/70">Anak-anak</span>
        <input type="number" name="anak" min="0" value="{{ request('anak', 0) }}"
               class="mt-1 w-full bg-transparent text-sm font-semibold text-white outline-none">
      </span>
    </label>

    {{-- BUTTON --}}
    <div class="p-3 sm:col-span-2 lg:col-span-1 lg:flex">
      <button type="submit"
              class="flex min-h-[60px] w-full items-center justify-center gap-2 rounded-xl bg-brand-red/90 px-8 py-4 text-sm font-semibold uppercase tracking-widest text-white shadow-lg shadow-brand-red/30 transition hover:bg-brand-red active:scale-[0.98] lg:w-auto">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
          <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
        </svg>
        Cari Villa
      </button>
    </div>

  </div>
</form>
  </div>

  {{-- Statistik --}}
  <div class="relative z-10 grid grid-cols-2 border-t border-white/15 lg:grid-cols-3">
    @foreach ([['7+','Kamar Pilihan'],['500+','Tamu Puas'],['4.9','Rating Rata-rata']] as $i => [$n,$l])
      <div class="px-4 py-6 sm:px-6 lg:px-10 {{ $i > 0 ? 'border-l border-white/15' : '' }} {{ $i === 2 ? 'col-span-2 border-t border-l-0 lg:col-span-1 lg:border-l lg:border-t-0' : '' }}">
        <div class="font-heading text-4xl font-semibold leading-none lg:text-5xl">{{ $n }}</div>
        <div class="mt-2 text-[11px] uppercase tracking-widest text-white/50">{{ $l }}</div>
      </div>
    @endforeach
  </div>
</section>

{{-- SUB NAV STICKY --}}
<nav class="sticky top-[var(--header-h)] z-40 h-[var(--subnav-h)] border-b border-brand-stroke bg-white" aria-label="Bagian halaman">
  <ul class="flex h-full overflow-x-auto lg:grid lg:grid-cols-4 [scrollbar-width:none]">
    @foreach ([['#tentang','Tentang Kami'],['#villa','Pilihan Villa'],['#fasilitas','Fasilitas'],['#testimoni','Testimoni']] as $i => [$h,$t])
      <li class="shrink-0 {{ $i ? 'border-l border-brand-stroke' : '' }} lg:shrink">
        <a href="{{ $h }}" class="flex h-full items-center justify-center px-6 text-xs font-semibold uppercase tracking-widest whitespace-nowrap hover:bg-brand-red hover:text-white transition-colors">{{ $t }}</a>
      </li>
    @endforeach
  </ul>
</nav>

{{-- TENTANG --}}
<section id="tentang" class="scroll-mt-nav bg-white">
  <div class="grid grid-cols-1 lg:grid-cols-[2fr_1px_3fr]">
    <div class="px-4 pt-16 sm:px-6 lg:px-10 lg:pt-24">
      <h2 class="font-heading text-4xl font-semibold leading-tight tracking-tight sm:text-5xl">Tentang Villa Asri</h2>
    </div>
    <div class="hidden bg-brand-stroke lg:block"></div>
    <div class="px-4 pb-16 pt-6 sm:px-6 lg:px-12 lg:pb-24 lg:pt-24">
      <p class="font-heading text-xl font-medium leading-snug sm:text-2xl">
        Berawal dari satu villa keluarga di Ubud pada 2015, kini Villa Asri mengelola 12 villa privat di berbagai sudut Bali.
      </p>
      <p class="mt-6 font-light leading-relaxed text-brand-black/70">
        Kami percaya liburan terbaik adalah yang membuat Anda merasa seperti di rumah sendiri. Setiap villa dirawat langsung oleh tim lokal kami dengan standar kebersihan dan keramahan yang konsisten.
      </p>
      <p class="mt-4 font-light leading-relaxed text-brand-black/70">
        Booking dilakukan langsung tanpa perantara, sehingga harga lebih transparan dan layanan lebih personal.
      </p>
    </div>
  </div>
</section>

{{-- BANNER PENUH --}}
<section class="relative h-[60vh] overflow-hidden lg:h-[80vh]">
  <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1920" alt="" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
  <div class="absolute inset-0 bg-black/30"></div>
  <div class="relative z-10 grid h-full place-items-center px-4 text-center">
    <h2 class="max-w-4xl font-heading text-4xl font-semibold leading-[0.95] tracking-tight text-white sm:text-6xl lg:text-7xl">
      Ketenangan Dimulai dari Pintu Villa
    </h2>
  </div>
</section>

{{-- PILIHAN VILLA --}}
{{-- PILIHAN KAMAR --}}
<section id="villa" class="scroll-mt-nav bg-white px-4 py-16 sm:px-6 lg:px-10 lg:py-24">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <p class="text-xs font-medium uppercase tracking-widest">
                <span class="mr-2 inline-block h-2 w-2 rounded-full bg-brand-red"></span>
                Pilihan Kamar
            </p>

            <h2 class="mt-3 font-heading text-4xl font-semibold tracking-tight sm:text-5xl">
                Temukan Pilihan Kamar Terbaik
            </h2>
        </div>

        <a href="{{ url('/rooms') }}"
            class="text-sm font-semibold uppercase tracking-widest text-brand-red hover:underline">
            Lihat semua →
        </a>

    </div>


    <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

        @forelse ($rooms as $room)

            @php
                $roomImage = $room->images->firstWhere('is_primary', 1)
                    ?? $room->images->first();
            @endphp

            <article class="group overflow-hidden rounded-lg bg-[#eeeeee]">

                {{-- FOTO KAMAR --}}
                <div class="aspect-[4/3] overflow-hidden bg-gray-200">

                    @if ($roomImage)

                        <img
                            src="{{ asset('home/admin/' . $roomImage->image) }}"
                            alt="{{ $room->name }}"
                            loading="lazy"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        >

                    @else

                        <div class="flex h-full items-center justify-center text-gray-400">
                            <span class="text-sm">Foto kamar belum tersedia</span>
                        </div>

                    @endif

                </div>


                {{-- INFORMASI KAMAR --}}
                <div class="p-5">

                    <p class="text-xs font-semibold uppercase tracking-widest text-brand-red">
                        {{ $room->villa->name ?? 'Arum Bromo Villas' }}
                    </p>

                    <h3 class="mt-1 font-heading text-2xl font-semibold">
                        {{ $room->name }}
                    </h3>

                    <p class="mt-2 text-sm text-brand-black/60 line-clamp-2">
                        {{ $room->description ?? 'Nikmati kenyamanan menginap bersama Arum Bromo Villas.' }}
                    </p>


                    {{-- DETAIL KAMAR --}}
                    <div class="mt-4 flex flex-wrap gap-x-4 gap-y-2 text-sm text-brand-black/60">

                        <span>
                            ♙ {{ $room->capacity }} Tamu
                        </span>

                        @if ($room->room_size)
                            <span>
                                ◈ {{ $room->room_size }}
                            </span>
                        @endif

                    </div>


                    {{-- HARGA --}}
                    <div class="mt-5 flex items-center justify-between border-t border-brand-stroke pt-4">

                        <p>
                            <span class="font-heading text-xl font-semibold">
                                Rp {{ number_format($room->price_per_night, 0, ',', '.') }}
                            </span>

                            <span class="text-xs text-brand-black/50">
                                / malam
                            </span>
                        </p>

                        <a href="{{ url('/rooms/' . $room->slug) }}"
                            class="rounded-md bg-brand-black px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-brand-red">

                            Pesan

                        </a>

                    </div>

                </div>

            </article>

        @empty

            <div class="col-span-full py-16 text-center">

                <h3 class="font-heading text-xl font-semibold text-brand-black">
                    Belum Ada Kamar Tersedia
                </h3>

                <p class="mt-2 text-sm text-gray-500">
                    Silakan kembali lagi untuk melihat pilihan kamar kami.
                </p>

            </div>

        @endforelse

    </div>

</section>

{{-- FASILITAS --}}
<section id="fasilitas" class="scroll-mt-nav border-y border-brand-stroke-dark bg-brand-black px-4 py-16 text-white sm:px-6 lg:px-10 lg:py-24">
  <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <h2 class="max-w-xl font-heading text-4xl font-semibold tracking-tight sm:text-5xl">Fasilitas di Setiap Menginap</h2>
    <p class="max-w-md font-light text-white/60 lg:text-right">Semua yang Anda butuhkan untuk liburan nyaman, sudah kami siapkan.</p>
  </div>

  <div class="mt-12 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6">
    @foreach ($facilities as $i => [$title, $desc])
      <div class="group rounded-lg bg-white/[0.03] p-6 transition-colors hover:bg-white/[0.06] lg:p-8">
        <div class="grid h-14 w-14 place-items-center rounded-xl bg-brand-red font-heading text-xl font-semibold">{{ $i + 1 }}</div>
        <h3 class="mt-6 font-heading text-xl font-semibold lg:text-2xl">{{ $title }}</h3>
        <span class="mt-3 block h-1 w-8 bg-brand-red transition-all duration-300 group-hover:w-14"></span>
        <p class="mt-4 text-sm font-light leading-relaxed text-white/55 group-hover:text-white/80">{{ $desc }}</p>
      </div>
    @endforeach
  </div>
</section>

{{-- TESTIMONI --}}
<section id="testimoni" class="scroll-mt-nav bg-brand-black px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
    <div class="rounded-xl bg-[#f5f5f5] px-4 py-14 sm:px-8 lg:px-12 lg:py-20">
        <p class="text-xs font-medium uppercase tracking-widest">
            <span class="mr-2 inline-block h-2 w-2 rounded-full bg-brand-red"></span>
            Kata Mereka
        </p>
        <h2 class="mt-4 font-heading text-4xl font-semibold tracking-tight sm:text-5xl">
            Cerita dari <span class="text-brand-red">Tamu</span> Kami
        </h2>
        <div class="mt-12 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($reviews as $review)
                @php
                    $nama = data_get($review, 'name')
                        ?? data_get($review, 'nama')
                        ?? data_get($review, 'user.name')
                        ?? 'Pengunjung';
                    $komentar = data_get($review, 'comment')
                        ?? data_get($review, 'komentar')
                        ?? data_get($review, 'review')
                        ?? data_get($review, 'content')
                        ?? '';
                    $kota = data_get($review, 'city')
                        ?? data_get($review, 'kota')
                        ?? '';
                    $rating = (int) (data_get($review, 'rating') ?? 5);
                    $rating = max(0, min(5, $rating));
                @endphp
                <figure class="flex flex-col rounded-lg bg-[#eeeeee] p-6">
                    {{-- RATING --}}
                    <div class="text-brand-red" aria-label="{{ $rating }} bintang">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $rating ? '' : 'text-gray-300' }}">
                                ★
                            </span>
                        @endfor
                    </div>
                    {{-- KOMENTAR --}}
                    <blockquote class="mt-4 flex-1 font-light leading-relaxed text-brand-black/80">
                        “{{ $komentar }}”
                    </blockquote>
                    {{-- IDENTITAS PENGUNJUNG --}}
                    <figcaption class="mt-6 border-t border-brand-stroke pt-4">
                        <p class="font-heading font-semibold uppercase text-[#C4141B]">
                            {{ $nama }}
                        </p>
                        @if ($kota)
                            <p class="text-sm text-brand-black/60">
                                {{ $kota }}
                            </p>
                        @endif
                    </figcaption>
                </figure>
            @empty
                <div class="col-span-full py-12 text-center">
                    <h3 class="font-heading text-xl font-semibold">
                        Belum Ada Review
                    </h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Jadilah pengunjung pertama yang memberikan ulasan.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="border-t border-brand-stroke-dark bg-brand-black">
  <a href="#booking" class="group mx-auto flex flex-col items-start gap-3 px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
    <span class="text-xs font-semibold uppercase tracking-widest text-white/60 group-hover:text-white">Siap berlibur?</span>
    <span class="font-heading text-4xl font-semibold text-white sm:text-6xl">Booking Sekarang →</span>
  </a>
</section>

@endsection