@php
  $nav = [
    ['label' => 'Beranda',   'href' => url('/'),           'match' => '/'],
    ['label' => 'Room',      'href' => url('/kamar'),      'match' => 'kamar*'],
    ['label' => 'Fasilitas', 'href' => url('/#fasilitas'), 'match' => null],
    ['label' => 'Kontak',    'href' => url('/contact'),    'match' => 'contact*'],
  ];
@endphp

<header id="site-header"
  class="fixed inset-x-0 top-0 z-50 h-[var(--header-h)] border-b border-white/10 backdrop-blur-sm transition-colors duration-300
         {{ ($solid ?? false) ? 'bg-brand-black' : '' }}"
  data-solid="{{ ($solid ?? false) ? '1' : '0' }}">

  <div class="flex h-full items-stretch justify-between pl-4 sm:pl-6 lg:pl-10">

    {{-- Logo --}}
    <a href="{{ url('/') }}" class="flex min-w-0 items-center gap-2 text-white">
      <img src="{{ asset('umum/images/logo-arumbromo.png') }}" alt="Arum Bromo Villas"
           class="h-10 w-auto shrink-0 object-contain sm:h-12">
      <span class="hidden truncate font-heading text-base font-semibold tracking-tight min-[400px]:inline sm:text-lg">
        Arum Bromo Villas
      </span>
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

      {{-- Desktop: Login / User --}}
<div class="hidden items-stretch xl:flex">

    @if (session()->has('id_user'))

        <div class="relative flex items-stretch">

            <button type="button"
                    onclick="toggleUserMenu(event)"
                    class="flex items-center gap-2 border-l border-white/10 px-6 text-sm font-semibold text-white transition hover:bg-white/10">

                {{-- Icon Profil --}}
                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0zm6 1a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>

                <span class="max-w-[140px] truncate">
                    {{ session('nama') ?? session('username') }}
                </span>

                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Dropdown --}}
            <div id="userDropdown"
                 class="absolute right-0 top-full z-50 hidden w-56 overflow-hidden rounded-xl bg-white text-gray-800 shadow-xl">

                <div class="bg-gray-100 px-4 py-3">
                    <p class="text-xs text-gray-500">Login sebagai</p>
                    <p class="truncate text-sm font-semibold">
                        {{ session('nama') }}
                    </p>
                </div>

                {{-- Update Profil --}}
                <a href="{{ route('profile.index') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-gray-100">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 20h9M16.5 3.5a2.12 2.12 0 013 3L9 17l-4 1 1-4 10.5-10.5z"/>
                    </svg>

                    Update Profil
                </a>

                <div class="border-t"></div>

                {{-- Riwayat Booking --}}
                <a href="{{ route('profile.history') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-gray-100">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 19.5A2.5 2.5 0 016.5 17H20V3H6.5A2.5 2.5 0 004 5.5v14z"/>
                    </svg>

                    Riwayat Booking
                </a>

                <div class="border-t"></div>

                {{-- Logout --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit"
                            class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm font-semibold text-red-600 hover:bg-red-50">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                        </svg>

                        Logout
                    </button>
                </form>

            </div>
        </div>

    @else

        <a href="{{ route('login') }}"
           class="flex items-center border-l border-white/10 px-6 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-white/10">
            Login
        </a>

    @endif

</div>

      {{-- Hamburger --}}
      <button id="menu-btn" type="button" aria-label="Menu" aria-expanded="false"
        onclick="toggleMobileMenu(event)"
        class="grid aspect-square h-full place-items-center bg-brand-red text-white transition hover:bg-brand-red/90 xl:hidden">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
          <path d="M4 5h16M4 12h16M4 19h16"/>
        </svg>
      </button>
    </div>
  </div>

  {{-- Menu mobile --}}
  <div id="mobile-menu" style="display:none;background:#282829"
     class="absolute inset-x-0 top-full z-50 max-h-[80vh] overflow-y-auto shadow-xl xl:hidden">
    <nav class="flex flex-col px-4 py-4 sm:px-6" aria-label="Mobile">
      @foreach ($nav as $item)
        <a href="{{ $item['href'] }}"
           class="border-b border-white/10 py-4 text-sm font-medium uppercase tracking-widest text-white/80 hover:text-white">
          {{ $item['label'] }}
        </a>
      @endforeach

      @if (session()->has('id_user'))

    <button type="button"
            onclick="toggleUserMenuMobile()"
            class="mt-4 flex items-center justify-between rounded-lg border border-white/10 px-4 py-3 text-sm font-semibold text-white">

        <span class="flex items-center gap-2 truncate">
            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                 stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>

            {{ session('nama') ?? session('username') }}
        </span>

        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor"
             stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div id="userDropdownMobile"
         class="mt-2 hidden overflow-hidden rounded-lg bg-white text-gray-800 shadow-lg">

        <div class="bg-gray-100 px-4 py-3">
            <p class="text-xs text-gray-500">Login sebagai</p>
            <p class="text-sm font-semibold">{{ session('nama') }}</p>
        </div>

        <a href="{{ route('profile.index') }}"
           class="block px-4 py-3 text-sm hover:bg-gray-100">
            Update Profil
        </a>

        <div class="border-t"></div>

        <a href="{{ route('profile.history') }}"
           class="block px-4 py-3 text-sm hover:bg-gray-100">
            Riwayat Booking
        </a>

        <div class="border-t"></div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit"
                    class="w-full px-4 py-3 text-left text-sm font-semibold text-red-600 hover:bg-red-50">
                Logout
            </button>
        </form>

    </div>

@else

    <a href="{{ route('login') }}"
       class="mt-4 rounded-md bg-brand-red py-3 text-center text-sm font-semibold uppercase tracking-widest text-white hover:bg-brand-red/90">
        Login
    </a>

@endif
    </nav>
  </div>
</header>