@include('layout.head')
@include('layout.header')

@php
    $primaryImage = $room->images->firstWhere('is_primary', 1)
        ?? $room->images->first();

    $imageUrl = $primaryImage
        ? asset('home/admin/' . $primaryImage->image)
        : asset('home/admin/icon-bromo2.jpg');

    $checkInDate = \Carbon\Carbon::parse($check_in);
    $checkOutDate = \Carbon\Carbon::parse($check_out);

    $totalNights = $checkInDate->diffInDays($checkOutDate);

    $totalGuests = (int) $dewasa + (int) $anak;

    $maxCapacity = $room->capacity + ($room->max_extra_guests ?? 0);

$nightlyPrices = [];
$totalPrice = 0;

$currentDate = $checkInDate->copy();

while ($currentDate->lt($checkOutDate)) {

    $seasonalPrice = $room->seasonalPrices
        ->first(function ($season) use ($currentDate) {
            return $currentDate->toDateString() >= $season->start_date
                && $currentDate->toDateString() <= $season->end_date;
        });

    $price = $seasonalPrice
        ? $seasonalPrice->price_per_night
        : $room->price_per_night;

    $nightlyPrices[] = [
        'date' => $currentDate->toDateString(),
        'formatted_date' => $currentDate->translatedFormat('d M Y'),
        'price' => (float) $price,
        'season_name' => $seasonalPrice?->season_name,
    ];

    $totalPrice += $price;

    $currentDate->addDay();
}

@endphp

<main class="min-h-screen bg-gray-50">

    {{-- Header halaman --}}
    <section class="bg-brand-black px-5 py-14 text-white sm:py-20">
        <div class="mx-auto max-w-7xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/60">
                Arum Bromo Villas
            </p>

            <h1 class="mt-3 font-heading text-3xl font-semibold sm:text-4xl">
                Konfirmasi Pemesanan
            </h1>

            <p class="mt-3 max-w-xl text-sm leading-7 text-white/70">
                Lengkapi informasi pemesanan kamar sebelum melanjutkan
                ke proses pembayaran.
            </p>
        </div>
    </section>


    <section class="mx-auto max-w-7xl px-5 py-10 sm:py-14">

        {{-- Notifikasi --}}
        @if (session('error'))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-5 py-4">
                <p class="font-semibold text-red-700">
                    Terdapat kesalahan pada pengisian formulir.
                </p>

                <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('booking.store') }}" method="POST">
            @csrf

            <input type="hidden" name="room_id" value="{{ $room->id }}">

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

                {{-- Form pemesanan --}}
                <div class="space-y-8 lg:col-span-2">

                    {{-- Informasi kamar --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-7">

                        <div class="mb-6">
                            <h2 class="font-heading text-xl font-semibold text-brand-black">
                                Kamar yang Dipilih
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Pastikan kamar yang kamu pilih sudah sesuai.
                            </p>
                        </div>

                        <div class="flex flex-col gap-5 sm:flex-row">

                            <img
                                src="{{ $imageUrl }}"
                                alt="{{ $room->name }}"
                                class="h-52 w-full rounded-lg object-cover sm:h-36 sm:w-52"
                            >

                            <div class="flex-1">

                                <h3 class="font-heading text-xl font-semibold text-brand-black">
                                    {{ $room->name }}
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-gray-500">
                                    {{ $room->description ?: 'Kamar nyaman untuk menemani pengalaman menginap Anda.' }}
                                </p>

                                <div class="mt-4 flex flex-wrap gap-4 text-xs text-gray-500">

                                    <span>
                                        {{ $room->capacity }} Tamu
                                    </span>

                                    <span>
                                        {{ $room->room_size ?: '-' }}
                                    </span>

                                    <span>
                                        Maks. {{ $room->total_units }} Unit
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Tanggal menginap --}}
                <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-7">

                    <div class="mb-6">
                        <h2 class="font-heading text-xl font-semibold text-brand-black">
                            Tanggal Menginap
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Tanggal menginap telah ditentukan pada halaman sebelumnya.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div>
                            <label for="check_in"
                                class="mb-2 block text-sm font-medium text-gray-700">
                                Check-in
                            </label>

                            <input
                                type="date"
                                name="check_in"
                                id="check_in"
                                value="{{ old('check_in', $check_in) }}"
                                readonly
                                required
                                class="w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-100 px-4 py-3 text-sm text-gray-500 outline-none"
                            >
                        </div>

                        <div>
                            <label for="check_out"
                                class="mb-2 block text-sm font-medium text-gray-700">
                                Check-out
                            </label>

                            <input
                                type="date"
                                name="check_out"
                                id="check_out"
                                value="{{ old('check_out', $check_out) }}"
                                readonly
                                required
                                class="w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-100 px-4 py-3 text-sm text-gray-500 outline-none"
                            >
                        </div>
                    </div>
                    <div class="mt-4 rounded-lg bg-gray-50 p-4 text-sm text-gray-600">
                        <span id="totalNights">
                            {{ $totalNights }} malam
                        </span>
                    </div>
                </div>


                    {{-- Informasi tamu --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-5 sm:p-7">

                        <div class="mb-6">
                            <h2 class="font-heading text-xl font-semibold text-brand-black">
                                Informasi Tamu
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Informasi tamu telah ditentukan pada halaman sebelumnya.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <label for="dewasa"
                                    class="mb-2 block text-sm font-medium text-gray-700">
                                    Jumlah Dewasa
                                </label>

                                <input
                                    type="number"
                                    name="dewasa"
                                    id="dewasa"
                                    min="1"
                                    value="{{ old('dewasa', $dewasa) }}"
                                    readonly
                                    required
                                    class="w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-100 px-4 py-3 text-sm text-gray-500 outline-none"
                                >
                            </div>

                            <div>
                                <label for="anak"
                                    class="mb-2 block text-sm font-medium text-gray-700">
                                    Jumlah Anak-anak
                                </label>

                                <input
                                    type="number"
                                    name="anak"
                                    id="anak"
                                    min="0"
                                    value="{{ old('anak', $anak) }}"
                                    readonly
                                    class="w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-100 px-4 py-3 text-sm text-gray-500 outline-none"
                                >
                            </div>

                        </div>

                        <div class="mt-5">
                            <label for="quantity"
                                class="mb-2 block text-sm font-medium text-gray-700">
                                Jumlah Unit Kamar
                            </label>

                            <input
                                type="number"
                                name="quantity"
                                id="quantity"
                                min="1"
                                max="{{ $room->total_units }}"
                                value="{{ old('quantity', 1) }}"
                                readonly
                                required
                                class="w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-100 px-4 py-3 text-sm text-gray-500 outline-none sm:max-w-xs"
                            >

                            <p class="mt-2 text-xs text-gray-500">
                                Tersedia maksimal {{ $room->total_units }} unit secara keseluruhan.
                            </p>
                        </div>

                        <div class="mt-5 rounded-lg bg-gray-50 p-4">
                            <p class="text-sm text-gray-600">
                                Kapasitas maksimal per unit:
                                <strong>{{ $maxCapacity }} orang</strong>
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Kapasitas terdiri dari {{ $room->capacity }} tamu utama
                                dan {{ $room->max_extra_guests ?? 0 }} tamu tambahan.
                            </p>
                        </div>
                    </div>
                    </div>


                {{-- Ringkasan pemesanan --}}
                <aside class="lg:col-span-1">

                    <div class="sticky top-24 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">

                        <h2 class="font-heading text-xl font-semibold text-brand-black">
                            Ringkasan Pemesanan
                        </h2>

                        <div class="mt-6 space-y-4 border-b border-gray-200 pb-6">

                            <div class="mt-6 border-b border-gray-200 pb-6">

                            <h3 class="mb-4 text-sm font-semibold text-brand-black">
                                Rincian Harga Menginap
                            </h3>

                            <div class="space-y-3">
                                @foreach ($nightlyPrices as $night)
                                    <div class="flex justify-between gap-4 text-sm">

                                        <div>
                                            <p class="font-medium text-gray-700">
                                                {{ $night['formatted_date'] }}
                                            </p>

                                            @if ($night['season_name'])
                                                <p class="mt-1 text-xs text-orange-600">
                                                    {{ $night['season_name'] }}
                                                </p>
                                            @else
                                                <p class="mt-1 text-xs text-gray-400">
                                                    Harga normal
                                                </p>
                                            @endif
                                        </div>

                                        <span class="font-medium text-brand-black">
                                            Rp{{ number_format($night['price'], 0, ',', '.') }}
                                        </span>

                                    </div>
                                @endforeach
                            </div>

                        </div>

                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-gray-500">Tipe Kamar</span>

                                <span class="text-right font-medium text-brand-black">
                                    {{ $room->name }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-gray-500">Check-in</span>

                                <span id="summaryCheckIn" class="text-right font-medium text-brand-black">
                                    {{ $checkInDate->format('d M Y') }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-gray-500">Check-out</span>

                                <span id="summaryCheckOut" class="text-right font-medium text-brand-black">
                                    {{ $checkOutDate->format('d M Y') }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-gray-500">Jumlah Malam</span>

                                <span id="summaryNights" class="font-medium text-brand-black">
                                    {{ $totalNights }} malam
                                </span>
                            </div>

                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-gray-500">Jumlah Tamu</span>

                                <span id="summaryGuests" class="font-medium text-brand-black">
                                    {{ $totalGuests }} orang
                                </span>
                            </div>

                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-gray-500">Jumlah Kamar</span>

                                <span id="summaryQuantity" class="font-medium text-brand-black">
                                    1 unit
                                </span>
                            </div>

                        </div>

                        <div class="mt-6">

                            <div class="flex items-center justify-between gap-4">
                                <span class="text-sm text-gray-500">
                                    Estimasi harga dasar
                                </span>

                                <span id="estimatedPrice" class="text-sm font-semibold text-brand-black">
                                    Rp{{ number_format($totalPrice, 0, ',', '.') }}
                                </span>
                            </div>

                            <p class="mt-3 text-xs leading-5 text-gray-400">
                                Harga akhir dihitung kembali berdasarkan tanggal menginap,
                                harga high season, dan ketersediaan kamar saat pemesanan.
                            </p>

                        </div>

                        <button
                            type="submit"
                            class="mt-7 flex w-full items-center justify-center rounded-lg bg-brand-red px-6 py-4 text-sm font-semibold uppercase tracking-wider text-white transition hover:bg-red-700">
                            Lanjutkan Pemesanan
                        </button>

                        <a href="{{ route('room.detail', $room->slug) }}"
                            class="mt-4 block text-center text-sm text-gray-500 transition hover:text-brand-red">
                            Kembali ke Detail Kamar
                        </a>

                        <p class="mt-6 text-center text-xs leading-5 text-gray-400">
                            Dengan melanjutkan pemesanan, kamu menyetujui ketentuan
                            pemesanan Arum Bromo Villas.
                        </p>

                    </div>

                </aside>

            </div>

        </form>

    </section>

</main>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');

    const dewasa = document.getElementById('dewasa');
    const anak = document.getElementById('anak');
    const quantity = document.getElementById('quantity');

    const basePrice = {{ (float) $room->price_per_night }};

    // Data harga high season dari database
    const seasonalPrices = {{ \Illuminate\Support\Js::from(
        $room->seasonalPrices->map(function ($season) {
            return [
                'start_date' => \Carbon\Carbon::parse($season->start_date)->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::parse($season->end_date)->format('Y-m-d'),
                'price' => (float) $season->price_per_night,
                'name' => $season->season_name,
            ];
        })->values()->all()
    ) }};

    const formatRupiah = (value) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(value);
    };

    function updateSummary() {

        const start = new Date(checkIn.value + 'T00:00:00');
        const end = new Date(checkOut.value + 'T00:00:00');

        const diff = Math.round(
            (end - start) / (1000 * 60 * 60 * 24)
        );

        const adultCount = parseInt(dewasa.value) || 0;
        const childCount = parseInt(anak.value) || 0;
        const roomCount = parseInt(quantity.value) || 1;

        document.getElementById('summaryGuests').textContent =
            (adultCount + childCount) + ' orang';

        document.getElementById('summaryQuantity').textContent =
            roomCount + ' unit';

        if (diff > 0) {

            const nights = diff + ' malam';

            document.getElementById('totalNights').textContent = nights;
            document.getElementById('summaryNights').textContent = nights;

            let calculatedTotal = 0;

            // Menghitung harga setiap malam
            for (let i = 0; i < diff; i++) {

                const currentDate = new Date(start);
                currentDate.setDate(currentDate.getDate() + i);

                const dateString = [
                    currentDate.getFullYear(),
                    String(currentDate.getMonth() + 1).padStart(2, '0'),
                    String(currentDate.getDate()).padStart(2, '0')
                ].join('-');

                // Cek apakah tanggal termasuk periode high season
                const seasonal = seasonalPrices.find(item => {
                    return dateString >= item.start_date &&
                           dateString <= item.end_date;
                });

                // Gunakan harga high season jika tersedia
                const nightlyPrice = seasonal
                    ? seasonal.price
                    : basePrice;

                calculatedTotal += nightlyPrice;
            }

            // Total harga dikalikan jumlah unit kamar
            document.getElementById('estimatedPrice').textContent =
                formatRupiah(calculatedTotal * roomCount);

        } else {

            document.getElementById('totalNights').textContent =
                'Periksa kembali tanggal menginap';

            document.getElementById('summaryNights').textContent = '-';

            document.getElementById('estimatedPrice').textContent = '-';
        }

        // Mengatur minimal tanggal checkout
        if (checkIn.value) {

            const nextDay = new Date(start);
            nextDay.setDate(nextDay.getDate() + 1);

            const minCheckout = [
                nextDay.getFullYear(),
                String(nextDay.getMonth() + 1).padStart(2, '0'),
                String(nextDay.getDate()).padStart(2, '0')
            ].join('-');

            checkOut.min = minCheckout;

            if (checkOut.value && checkOut.value <= checkIn.value) {
                checkOut.value = minCheckout;
            }
        }

        // Menampilkan tanggal check-in
        document.getElementById('summaryCheckIn').textContent =
            checkIn.value
                ? new Date(checkIn.value + 'T00:00:00').toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                })
                : '-';

        // Menampilkan tanggal check-out
        document.getElementById('summaryCheckOut').textContent =
            checkOut.value
                ? new Date(checkOut.value + 'T00:00:00').toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                })
                : '-';
    }

    // Memperbarui ringkasan ketika input berubah
    [checkIn, checkOut, dewasa, anak, quantity].forEach(input => {
        if (input) {
            input.addEventListener('change', updateSummary);
            input.addEventListener('input', updateSummary);
        }
    });

    updateSummary();

});
</script>

@include('layout.footer')