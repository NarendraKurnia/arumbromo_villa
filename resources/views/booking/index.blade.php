@include('layout.head')
@include('layout.header')

@php
    use Carbon\Carbon;

    $rp = fn ($value) => 'Rp' . number_format((float) $value, 0, ',', '.');

    /* ---------- Gambar kamar ---------- */
    $primaryImage = $room->images->firstWhere('is_primary', 1) ?? $room->images->first();
    $imageUrl = $primaryImage
        ? asset('home/admin/' . $primaryImage->image)
        : asset('home/admin/icon-bromo2.jpg');

    /* ---------- Tanggal & tamu ---------- */
    $checkInDate  = Carbon::parse($check_in)->startOfDay();
    $checkOutDate = Carbon::parse($check_out)->startOfDay();
    $totalNights  = max(0, (int) $checkInDate->diffInDays($checkOutDate));

    $adults      = (int) $dewasa;
    $children    = (int) $anak;
    $totalGuests = $adults + $children;
    $quantity    = max(1, (int) old('quantity', 1));
    $maxCapacity = $room->capacity + ($room->max_extra_guests ?? 0);

    /* ---------- Harga per malam (termasuk high season) ---------- */
    $nightlyPrices = [];
    $perUnitTotal  = 0;
    $cursor        = $checkInDate->copy();

    while ($cursor->lt($checkOutDate)) {
        $date = $cursor->toDateString();

        $season = $room->seasonalPrices->first(function ($item) use ($date) {
            return $date >= Carbon::parse($item->start_date)->toDateString()
                && $date <= Carbon::parse($item->end_date)->toDateString();
        });

        $price = (float) ($season ? $season->price_per_night : $room->price_per_night);

        $nightlyPrices[] = [
            'label'  => $cursor->translatedFormat('D, d M Y'),
            'price'  => $price,
            'season' => $season?->season_name,
        ];

        $perUnitTotal += $price;
        $cursor->addDay();
    }

    $subtotal = $perUnitTotal * $quantity;
@endphp

<main class="min-h-screen bg-gray-50">

    {{-- ===================== HERO ===================== --}}
    <section class="bg-brand-black px-5 py-12 text-white sm:py-16">
        <div class="mx-auto max-w-7xl">

            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/60">
                Arum Bromo Villas
            </p>

            <h1 class="mt-3 font-heading text-3xl font-semibold sm:text-4xl">
                Konfirmasi Pemesanan
            </h1>

            <p class="mt-3 max-w-xl text-sm leading-7 text-white/70">
                Periksa kembali detail pemesananmu sebelum melanjutkan ke pembayaran.
            </p>

            {{-- Indikator langkah --}}
            <ol class="mt-8 flex flex-wrap items-center gap-x-3 gap-y-2 text-xs font-medium">
                <li class="flex items-center gap-2 text-white/50">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full border border-white/30">1</span>
                    Pilih Kamar
                </li>
                <li class="h-px w-6 bg-white/20"></li>
                <li class="flex items-center gap-2 text-white">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-brand-red">2</span>
                    Konfirmasi
                </li>
                <li class="h-px w-6 bg-white/20"></li>
                <li class="flex items-center gap-2 text-white/50">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full border border-white/30">3</span>
                    Pembayaran
                </li>
            </ol>
        </div>
    </section>


    <section class="mx-auto max-w-7xl px-5 py-10 sm:py-14">

        {{-- ===================== NOTIFIKASI ===================== --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-5 py-4">
                <p class="font-semibold text-red-700">Terdapat kesalahan pada pengisian formulir.</p>
                <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
            @csrf

            {{-- Data yang sudah ditentukan di halaman sebelumnya --}}
            <input type="hidden" name="room_id"   value="{{ $room->id }}">
            <input type="hidden" name="check_in"  value="{{ old('check_in', $check_in) }}">
            <input type="hidden" name="check_out" value="{{ old('check_out', $check_out) }}">
            <input type="hidden" name="dewasa"    value="{{ old('dewasa', $dewasa) }}">
            <input type="hidden" name="anak"      value="{{ old('anak', $anak) }}">
            <input type="hidden" name="quantity"  value="{{ $quantity }}">

            <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-3">

                {{-- ===================== KOLOM KIRI ===================== --}}
                <div class="space-y-6 lg:col-span-2">

                    {{-- ---------- Kamar ---------- --}}
                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
                        <div class="flex flex-col sm:flex-row">

                            <img
                                src="{{ $imageUrl }}"
                                alt="{{ $room->name }}"
                                class="h-56 w-full object-cover sm:h-auto sm:w-64"
                            >

                            <div class="flex-1 p-5 sm:p-7">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-red">
                                    Kamar yang dipilih
                                </p>

                                <h2 class="mt-2 font-heading text-2xl font-semibold text-brand-black">
                                    {{ $room->name }}
                                </h2>

                                <p class="mt-3 text-sm leading-6 text-gray-500">
                                    {{ $room->description ?: 'Kamar nyaman untuk menemani pengalaman menginap Anda.' }}
                                </p>

                                <div class="mt-5 flex flex-wrap gap-2 text-xs text-gray-600">
                                    <span class="rounded-full bg-gray-100 px-3 py-1.5">
                                        {{ $room->capacity }} tamu
                                    </span>
                                    <span class="rounded-full bg-gray-100 px-3 py-1.5">
                                        {{ $room->room_size ?: '-' }}
                                    </span>
                                    <span class="rounded-full bg-gray-100 px-3 py-1.5">
                                        {{ $room->total_units }} unit tersedia
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- ---------- Detail menginap ---------- --}}
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-7">

                        <div class="mb-6 flex items-start justify-between gap-4">
                            <div>
                                <h2 class="font-heading text-xl font-semibold text-brand-black">
                                    Detail Menginap
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    Ditentukan pada halaman sebelumnya.
                                </p>
                            </div>

                            <a href="{{ route('room.detail', $room->slug) }}"
                               class="shrink-0 text-xs font-semibold uppercase tracking-wider text-brand-red transition hover:underline">
                                Ubah
                            </a>
                        </div>

                        <dl class="grid grid-cols-2 gap-4 sm:grid-cols-4">

                            <div class="rounded-xl bg-gray-50 p-4">
                                <dt class="text-xs text-gray-500">Check-in</dt>
                                <dd class="mt-1 text-sm font-semibold text-brand-black">
                                    {{ $checkInDate->translatedFormat('d M Y') }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <dt class="text-xs text-gray-500">Check-out</dt>
                                <dd class="mt-1 text-sm font-semibold text-brand-black">
                                    {{ $checkOutDate->translatedFormat('d M Y') }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <dt class="text-xs text-gray-500">Durasi</dt>
                                <dd class="mt-1 text-sm font-semibold text-brand-black">
                                    {{ $totalNights > 0 ? $totalNights . ' malam' : 'Periksa tanggal' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <dt class="text-xs text-gray-500">Jumlah kamar</dt>
                                <dd class="mt-1 text-sm font-semibold text-brand-black">
                                    {{ $quantity }} unit
                                </dd>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <dt class="text-xs text-gray-500">Dewasa</dt>
                                <dd class="mt-1 text-sm font-semibold text-brand-black">
                                    {{ $adults }} orang
                                </dd>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <dt class="text-xs text-gray-500">Anak-anak</dt>
                                <dd class="mt-1 text-sm font-semibold text-brand-black">
                                    {{ $children }} orang
                                </dd>
                            </div>

                            <div class="col-span-2 rounded-xl bg-gray-50 p-4">
                                <dt class="text-xs text-gray-500">Kapasitas maksimal per unit</dt>
                                <dd class="mt-1 text-sm font-semibold text-brand-black">
                                    {{ $maxCapacity }} orang
                                    <span class="font-normal text-gray-500">
                                        ({{ $room->capacity }} utama + {{ $room->max_extra_guests ?? 0 }} tambahan)
                                    </span>
                                </dd>
                            </div>
                        </dl>

                        @if ($totalGuests > $maxCapacity * $quantity)
                            <p class="mt-4 rounded-lg bg-orange-50 px-4 py-3 text-xs text-orange-700">
                                Jumlah tamu melebihi kapasitas maksimal kamar yang dipesan.
                            </p>
                        @endif
                    </div>


                    {{-- ---------- Voucher ---------- --}}
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-7">

                        <div class="mb-6">
                            <h2 class="font-heading text-xl font-semibold text-brand-black">
                                Voucher &amp; Promo
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Pilih satu voucher untuk mendapatkan potongan harga.
                            </p>
                        </div>

                        <div class="space-y-3">

                            {{-- Tanpa voucher --}}
                            <label class="relative block cursor-pointer">
                                <input
                                    type="radio"
                                    name="voucher_id"
                                    value=""
                                    class="peer sr-only"
                                    @checked(! old('voucher_id'))
                                >

                                <div class="rounded-xl border border-gray-200 p-4 pr-12 transition hover:border-gray-300 peer-checked:border-brand-red peer-checked:bg-red-50/40 peer-focus-visible:ring-2 peer-focus-visible:ring-brand-red">
                                    <p class="text-sm font-semibold text-brand-black">Tanpa Voucher</p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Lanjutkan pemesanan tanpa potongan.
                                    </p>
                                </div>

                                <span class="pointer-events-none absolute right-4 top-4 h-5 w-5 rounded-full border-2 border-gray-300 bg-white transition peer-checked:border-[6px] peer-checked:border-brand-red"></span>
                            </label>

                            @forelse ($vouchers as $voucher)
                                @php
                                    $now        = now();
                                    $minAmount  = (float) ($voucher->min_booking_amount ?? 0);
                                    $userUsage  = (int) ($voucher->user_usage_count ?? 0);
                                    $usageLimit = $voucher->usage_per_user;

                                    $alreadyUsed = $usageLimit !== null && $userUsage >= $usageLimit;
                                    $notStarted  = $now->lt($voucher->start_date);
                                    $expired     = $now->gt($voucher->end_date);
                                    $belowMin    = $subtotal < $minAmount;

                                    $disabled = $alreadyUsed || $notStarted || $expired || $belowMin;

                                    $statusText = match (true) {
                                        $alreadyUsed => ['Batas penggunaan voucher sudah tercapai.', 'text-red-600'],
                                        $expired     => ['Voucher sudah kedaluwarsa.', 'text-red-600'],
                                        $notStarted  => ['Voucher belum mulai berlaku.', 'text-orange-600'],
                                        $belowMin    => ['Minimal transaksi ' . $rp($minAmount) . ' belum terpenuhi.', 'text-red-600'],
                                        default      => null,
                                    };
                                @endphp

                                <label class="relative block {{ $disabled ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                                    <input
                                        type="radio"
                                        name="voucher_id"
                                        value="{{ $voucher->id }}"
                                        class="peer sr-only"
                                        data-type="{{ $voucher->discount_type }}"
                                        data-value="{{ $voucher->discount_value }}"
                                        data-max="{{ $voucher->max_discount ?? '' }}"
                                        data-name="{{ $voucher->name }}"
                                        @checked((string) old('voucher_id') === (string) $voucher->id && ! $disabled)
                                        @disabled($disabled)
                                    >

                                    <div class="rounded-xl border border-gray-200 p-4 pr-12 transition hover:border-gray-300 peer-checked:border-brand-red peer-checked:bg-red-50/40 peer-focus-visible:ring-2 peer-focus-visible:ring-brand-red peer-disabled:opacity-60 peer-disabled:hover:border-gray-200">

                                        <div class="flex flex-wrap items-start justify-between gap-2">
                                            <div>
                                                <h3 class="font-semibold text-brand-black">{{ $voucher->name }}</h3>
                                                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-brand-red">
                                                    {{ $voucher->code }}
                                                </p>
                                            </div>

                                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-brand-red">
                                                @if ($voucher->discount_type === 'percentage')
                                                    {{ number_format($voucher->discount_value, 0, ',', '.') }}% OFF
                                                @else
                                                    {{ $rp($voucher->discount_value) }} OFF
                                                @endif
                                            </span>
                                        </div>

                                        @if ($voucher->description)
                                            <p class="mt-3 text-sm leading-6 text-gray-500">
                                                {{ $voucher->description }}
                                            </p>
                                        @endif

                                        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-1 border-t border-dashed border-gray-200 pt-3 text-xs text-gray-500">
                                            <span>
                                                Min. transaksi
                                                <strong class="text-gray-700">{{ $rp($minAmount) }}</strong>
                                            </span>

                                            @if ($voucher->discount_type === 'percentage' && $voucher->max_discount)
                                                <span>
                                                    Maks. potongan
                                                    <strong class="text-gray-700">{{ $rp($voucher->max_discount) }}</strong>
                                                </span>
                                            @endif

                                            <span>
                                                Berlaku s/d
                                                <strong class="text-gray-700">
                                                    {{ Carbon::parse($voucher->end_date)->translatedFormat('d M Y, H:i') }}
                                                </strong>
                                            </span>
                                        </div>

                                        @if ($statusText)
                                            <p class="mt-3 text-xs font-semibold {{ $statusText[1] }}">
                                                {{ $statusText[0] }}
                                            </p>
                                        @endif
                                    </div>

                                    <span class="pointer-events-none absolute right-4 top-4 h-5 w-5 rounded-full border-2 border-gray-300 bg-white transition peer-checked:border-[6px] peer-checked:border-brand-red"></span>
                                </label>

                            @empty
                                <div class="rounded-xl bg-gray-50 p-5 text-center text-sm text-gray-500">
                                    Saat ini belum tersedia voucher.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>


                {{-- ===================== RINGKASAN ===================== --}}
                <aside class="lg:col-span-1">
                    <div class="sticky top-24 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">

                        <h2 class="font-heading text-xl font-semibold text-brand-black">
                            Ringkasan Pemesanan
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">{{ $room->name }}</p>

                        {{-- Tanggal --}}
                        <div class="mt-6 grid grid-cols-2 divide-x divide-gray-200 rounded-xl border border-gray-200 text-sm">
                            <div class="p-4">
                                <p class="text-xs text-gray-500">Check-in</p>
                                <p class="mt-1 font-semibold text-brand-black">
                                    {{ $checkInDate->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-gray-500">Check-out</p>
                                <p class="mt-1 font-semibold text-brand-black">
                                    {{ $checkOutDate->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <dl class="mt-5 space-y-3 text-sm">
                            <div class="flex justify-between gap-4">
                                <dt class="text-gray-500">Durasi</dt>
                                <dd class="font-medium text-brand-black">{{ $totalNights }} malam</dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-gray-500">Tamu</dt>
                                <dd class="font-medium text-brand-black">{{ $totalGuests }} orang</dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-gray-500">Kamar</dt>
                                <dd class="font-medium text-brand-black">{{ $quantity }} unit</dd>
                            </div>
                        </dl>

                        {{-- Rincian harga per malam --}}
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <h3 class="mb-4 text-sm font-semibold text-brand-black">
                                Rincian Harga
                            </h3>

                            <ul class="max-h-56 space-y-3 overflow-y-auto pr-1">
                                @forelse ($nightlyPrices as $night)
                                    <li class="flex items-start justify-between gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-700">{{ $night['label'] }}</p>
                                            <p class="mt-0.5 text-xs {{ $night['season'] ? 'text-orange-600' : 'text-gray-400' }}">
                                                {{ $night['season'] ?: 'Harga normal' }}
                                            </p>
                                        </div>

                                        <span class="font-medium text-brand-black">
                                            {{ $rp($night['price']) }}
                                        </span>
                                    </li>
                                @empty
                                    <li class="text-sm text-gray-500">Periksa kembali tanggal menginap.</li>
                                @endforelse
                            </ul>

                            <div class="mt-5 space-y-3 border-t border-gray-200 pt-5 text-sm">

                                @if ($quantity > 1)
                                    <div class="flex justify-between gap-4">
                                        <span class="text-gray-500">{{ $rp($perUnitTotal) }} × {{ $quantity }} unit</span>
                                    </div>
                                @endif

                                <div class="flex justify-between gap-4">
                                    <span class="text-gray-500">Subtotal</span>
                                    <span class="font-medium text-brand-black">{{ $rp($subtotal) }}</span>
                                </div>

                                <div id="discountRow" class="hidden justify-between gap-4">
                                    <div>
                                        <span class="text-gray-500">Diskon voucher</span>
                                        <p id="voucherName" class="mt-0.5 text-xs text-green-600"></p>
                                    </div>
                                    <span id="voucherDiscount" class="font-medium text-green-600"></span>
                                </div>
                            </div>

                            <div class="mt-5 flex items-center justify-between gap-4 border-t border-gray-200 pt-5">
                                <span class="font-semibold text-brand-black">Total Pembayaran</span>
                                <span id="finalPrice" class="text-xl font-bold text-brand-red">
                                    {{ $rp($subtotal) }}
                                </span>
                            </div>

                            <p class="mt-3 text-xs leading-5 text-gray-400">
                                Harga akhir dihitung ulang saat pemesanan berdasarkan tanggal,
                                harga high season, dan ketersediaan kamar.
                            </p>
                        </div>

                        <button
                            type="submit"
                            id="submitButton"
                            @disabled($totalNights < 1)
                            class="mt-7 flex w-full items-center justify-center rounded-lg bg-brand-red px-6 py-4 text-sm font-semibold uppercase tracking-wider text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60">
                            Lanjutkan Pemesanan
                        </button>

                        <a href="{{ route('room.detail', $room->slug) }}"
                           class="mt-4 block text-center text-sm text-gray-500 transition hover:text-brand-red">
                            Kembali ke Detail Kamar
                        </a>

                        <p class="mt-6 text-center text-xs leading-5 text-gray-400">
                            Dengan melanjutkan, kamu menyetujui ketentuan pemesanan Arum Bromo Villas.
                        </p>
                    </div>
                </aside>

            </div>
        </form>
    </section>
</main>


<script>
(function () {
    // Subtotal dihitung di server (sudah termasuk high season & jumlah unit)
    const subtotal = @js((float) $subtotal);

    const form         = document.getElementById('bookingForm');
    const radios       = document.querySelectorAll('input[name="voucher_id"]');
    const discountRow  = document.getElementById('discountRow');
    const discountText = document.getElementById('voucherDiscount');
    const voucherName  = document.getElementById('voucherName');
    const finalPrice   = document.getElementById('finalPrice');
    const submitButton = document.getElementById('submitButton');

    const rupiah = (value) => 'Rp' + Math.round(value).toLocaleString('id-ID');

    function calculateDiscount(radio) {
        const value = parseFloat(radio.dataset.value) || 0;
        const max   = radio.dataset.max !== '' ? parseFloat(radio.dataset.max) : null;

        let discount = radio.dataset.type === 'percentage'
            ? subtotal * value / 100
            : value;

        if (radio.dataset.type === 'percentage' && max !== null) {
            discount = Math.min(discount, max);
        }

        return Math.min(discount, subtotal);
    }

    function updateSummary() {
        const selected = Array.from(radios).find(r => r.checked && r.value !== '');
        const discount = selected ? calculateDiscount(selected) : 0;

        if (selected && discount > 0) {
            discountRow.classList.remove('hidden');
            discountRow.classList.add('flex');
            discountText.textContent = '-' + rupiah(discount);
            voucherName.textContent  = selected.dataset.name;
        } else {
            discountRow.classList.add('hidden');
            discountRow.classList.remove('flex');
        }

        finalPrice.textContent = rupiah(subtotal - discount);
    }

    radios.forEach(radio => radio.addEventListener('change', updateSummary));

    // Cegah submit ganda
    form.addEventListener('submit', function () {
        submitButton.disabled = true;
        submitButton.textContent = 'Memproses...';
    });

    updateSummary();
})();
</script>

@include('layout.footer')