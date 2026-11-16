@php
  $nav = [
    ['label' => 'Beranda',   'href' => url('/'),         'match' => '/'],
    ['label' => 'Room',     'href' => url('/kamar'),   'match' => 'villas*'],
    ['label' => 'Fasilitas', 'href' => url('/#fasilitas'), 'match' => null],
    ['label' => 'Kontak',    'href' => url('/contact'),  'match' => 'contact*'],
  ];
@endphp

<header id="site-header"
  class="fixed inset-x-0 top-0 z-50 h-[var(--header-h)] border-b border-white/10 backdrop-blur-sm transition-colors duration-300">
  <div class="flex h-full items-stretch justify-between pl-4 sm:pl-6 lg:pl-10">
    {{-- Logo --}}
    <a href="{{ url('/') }}" class="flex items-center gap-2 text-white">
      <span class="grid h-12 w-20">
          <img src="{{ asset('umum/images/logo-arumbromo.png') }}"
            alt="Arum Bromo Villas"
            class="h-full w-full">
      </span>
      <span class="font-heading text-lg font-semibold tracking-tight">Arum Bromo Villas</span>
    </a>

    {{-- Nav desktop --}}
    <nav class="hidden items-stretch xl:flex" aria-label="Utama">
      @foreach ($nav as $item)
        @php $active = $item['match'] && request()->is($item['match']); @endphp
        <a href="{{ $item['href'] }}"
           class="relative flex items-center px-5 text-xs font-medium uppercase tracking-widest transition-colors
                  {{ $active ? 'text-white' : 'text-white/80 hover:text-white' }}">
          {{ $item['label'] }}
          @if ($active)<span class="absolute inset-x-0 bottom-0 h-1 bg-brand-red"></span>@endif
        </a>
      @endforeach
    </nav>

    {{-- Kanan --}}
    <div class="flex items-stretch">
      <a href="{{ url('/#booking') }}"
         class="hidden items-center border-l border-white/10 px-6 text-xs font-semibold uppercase tracking-widest text-white hover:bg-white/10 sm:flex">
        Booking
      </a>
      <button id="menu-btn" type="button" aria-label="Menu" aria-expanded="false" aria-controls="mobile-menu"
        class="grid aspect-square h-full place-items-center bg-brand-red text-white transition hover:bg-brand-red/90 xl:hidden">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
          <path d="M4 5h16M4 12h16M4 19h16"/>
        </svg>
      </button>
    </div>
  </div>

  {{-- Menu mobile --}}
  <div id="mobile-menu" class="hidden max-h-[calc(100vh-var(--header-h))] overflow-y-auto bg-brand-black xl:hidden">
    <nav class="flex flex-col px-4 py-4 sm:px-6" aria-label="Mobile">
      @foreach ($nav as $item)
        <a href="{{ $item['href'] }}"
           class="border-b border-white/10 py-4 text-sm font-medium uppercase tracking-widest text-white/80 hover:text-white">
          {{ $item['label'] }}
        </a>
      @endforeach
      <a href="{{ url('/#booking') }}"
         class="mt-4 rounded-md bg-brand-red py-3 text-center text-sm font-semibold uppercase tracking-widest text-white">
        Booking Sekarang
      </a>
    </nav>
  </div>
</header>