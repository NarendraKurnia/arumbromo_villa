<!DOCTYPE html>
<html lang="id">

<head>
    @include('layout.head')

    <title>{{ $title ?? 'Detail Booking - Arum Bromo Villas' }}</title>
</head>

<body class="bg-gray-50 text-gray-800">

    @include('layout.header')

    @php
        $status = strtolower($booking->status ?? 'pending');

        $statusConfig = [
            'pending' => [
                'label' => 'Menunggu Pembayaran',
                'class' => 'bg-yellow-100 text-yellow-700',
            ],
            'confirmed' => [
                'label' => 'Booking Dikonfirmasi',
                'class' => 'bg-green-100 text-green-700',
            ],
            'paid' => [
                'label' => 'Sudah Dibayar',
                'class' => 'bg-green-100 text-green-700',
            ],
            'checked_in' => [
                'label' => 'Sedang Menginap',
                'class' => 'bg-blue-100 text-blue-700',
            ],
            'completed' => [
                'label' => 'Selesai',
                'class' => 'bg-gray-100 text-gray-700',
            ],
            'cancelled' => [
                'label' => 'Dibatalkan',
                'class' => 'bg-red-100 text-red-700',
            ],
        ];

        $statusInfo = $statusConfig[$status] ?? [
            'label' => ucfirst(str_replace('_', ' ', $status)),
            'class' => 'bg-gray-100 text-gray-700',
        ];

        $rupiah = function ($nominal) {
            return 'Rp ' . number_format((float) $nominal, 0, ',', '.');
        };

        $checkIn = \Carbon\Carbon::parse($booking->check_in);
        $checkOut = \Carbon\Carbon::parse($booking->check_out);

        $isExpired = $status === 'pending'
            && $booking->expires_at
            && \Carbon\Carbon::parse($booking->expires_at)->isPast();
    @endphp

    <main class="min-h-screen pt-28 pb-16 px-4 sm:px-6 lg:px-8">

        <div class="max-w-5xl mx-auto">

            {{-- Breadcrumb --}}
            <div class="mb-6 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-amber-600">
                    Beranda
                </a>

                <span class="mx-2">/</span>

                <a href="{{ route('profile.history') }}" class="hover:text-amber-600">
                    Riwayat Booking
                </a>

                <span class="mx-2">/</span>

                <span class="text-gray-800 font-medium">
                    Detail Booking
                </span>
            </div>

            {{-- Alert --}}
            @if (session('success'))
                <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

                <div>
                    <p class="text-sm text-amber-600 font-semibold mb-2">
                        ARUM BROMO VILLAS
                    </p>

                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        Detail Pemesanan
                    </h1>

                    <p class="text-gray-500 mt-2 text-sm">
                        Periksa kembali informasi pemesanan kamar kamu.
                    </p>
                </div>

                <div>
                    <span class="inline-flex px-4 py-2 rounded-full text-sm font-semibold {{ $statusInfo['class'] }}">
                        {{ $statusInfo['label'] }}
                    </span>
                </div>

            </div>

            {{-- Booking Code --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 mb-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>
                        <p class="text-sm text-gray-500 mb-1">
                            Kode Booking
                        </p>

                        <h2 class="text-xl font-bold text-gray-900 tracking-wide">
                            {{ $booking->booking_code }}
                        </h2>
                    </div>

                    <div class="text-sm text-gray-500">
                        Dibuat pada
                        <p class="font-semibold text-gray-800 mt-1">
                            {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y, H:i') }}
                        </p>
                    </div>

                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT CONTENT --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Informasi Customer --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">

                        <h3 class="text-lg font-bold text-gray-900 mb-5">
                            Informasi Pemesan
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                            <div>
                                <p class="text-sm text-gray-500 mb-1">
                                    Nama Customer
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ $user->name ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 mb-1">
                                    Email
                                </p>

                                <p class="font-semibold text-gray-800 break-all">
                                    {{ $user->email ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 mb-1">
                                    Nomor WhatsApp
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ $user->phone ?? '-' }}
                                </p>
                            </div>

                        </div>

                    </div>

                    {{-- Informasi Menginap --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">

                        <h3 class="text-lg font-bold text-gray-900 mb-5">
                            Informasi Menginap
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-gray-500 mb-2">
                                    Check-in
                                </p>

                                <p class="text-lg font-bold text-gray-900">
                                    {{ $checkIn->format('d M Y') }}
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $checkIn->translatedFormat('l') }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-gray-500 mb-2">
                                    Check-out
                                </p>

                                <p class="text-lg font-bold text-gray-900">
                                    {{ $checkOut->format('d M Y') }}
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $checkOut->translatedFormat('l') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 mb-1">
                                    Durasi Menginap
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ $booking->total_nights }} Malam
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 mb-1">
                                    Jumlah Tamu
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ $booking->total_guests }} Orang
                                </p>
                            </div>

                        </div>

                    </div>

                    {{-- Detail Kamar --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">

                        <h3 class="text-lg font-bold text-gray-900 mb-5">
                            Detail Kamar
                        </h3>

                        <div class="space-y-5">

                            @forelse ($bookingDetails as $detail)

                                <div class="border border-gray-100 rounded-xl p-4">

                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">

                                        <div>
                                            <h4 class="font-bold text-gray-900 text-base">
                                                {{ $detail->room_name }}
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-2">
                                                {{ $detail->quantity }} Kamar
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                {{ $detail->total_nights }} Malam
                                            </p>
                                        </div>

                                        <div class="sm:text-right">
                                            <p class="text-sm text-gray-500">
                                                Harga rata-rata per malam
                                            </p>

                                            <p class="font-semibold text-gray-800">
                                                {{ $rupiah($detail->price_per_night) }}
                                            </p>
                                        </div>

                                    </div>

                                    <div class="border-t border-gray-100 mt-4 pt-4 flex justify-between items-center">

                                        <span class="text-sm text-gray-500">
                                            Subtotal Kamar
                                        </span>

                                        <span class="font-bold text-gray-900">
                                            {{ $rupiah($detail->subtotal) }}
                                        </span>

                                    </div>

                                </div>

                            @empty

                                <p class="text-gray-500 text-sm">
                                    Detail kamar tidak ditemukan.
                                </p>

                            @endforelse

                        </div>

                    </div>

                </div>

                {{-- RIGHT CONTENT --}}
                <div class="space-y-6">

                    {{-- Ringkasan Pembayaran --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">

                        <h3 class="text-lg font-bold text-gray-900 mb-5">
                            Ringkasan Pembayaran
                        </h3>

                        <div class="space-y-4">

                            <div class="flex justify-between gap-3 text-sm">

                                <span class="text-gray-500">
                                    Subtotal
                                </span>

                                <span class="font-semibold text-gray-800">
                                    {{ $rupiah($booking->subtotal_amount) }}
                                </span>

                            </div>

                            {{-- Voucher --}}
                            <div class="flex justify-between gap-3 text-sm">

                                <div>
                                    <p class="text-gray-500">
                                        Voucher

                                        @if (!empty($booking->voucher_code))
                                            <span class="block text-amber-600 font-semibold mt-1">
                                                {{ $booking->voucher_code }}
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <span class="font-semibold text-green-600">
                                    - {{ $rupiah($booking->discount_amount) }}
                                </span>

                            </div>

                            <div class="border-t border-dashed border-gray-200 pt-4">

                                <div class="flex justify-between items-center gap-3">

                                    <span class="font-bold text-gray-900">
                                        Total Pembayaran
                                    </span>

                                    <span class="text-xl font-bold text-amber-600">
                                        {{ $rupiah($booking->total_amount) }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Informasi Pembayaran --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">

                        <h3 class="text-lg font-bold text-gray-900 mb-4">
                            Informasi Pembayaran
                        </h3>

                        @if ($status === 'pending')

                            @if ($isExpired)

                                <div class="rounded-xl bg-red-50 border border-red-100 p-4">
                                    <p class="font-semibold text-red-700 text-sm">
                                        Waktu pembayaran telah berakhir.
                                    </p>

                                    <p class="text-xs text-red-600 mt-2">
                                        Silakan hubungi administrator untuk informasi lebih lanjut.
                                    </p>
                                </div>

                            @else

                                <div class="rounded-xl bg-yellow-50 border border-yellow-100 p-4">

                                    <p class="font-semibold text-yellow-800 text-sm">
                                        Menunggu Pembayaran
                                    </p>

                                    <p class="text-xs text-yellow-700 mt-2">
                                        Silakan lanjutkan pembayaran untuk menyelesaikan pemesanan kamar.
                                    </p>

                                    @if ($booking->expires_at)
                                        <p class="text-xs text-yellow-700 mt-3">
                                            Batas pembayaran:
                                            <strong>
                                                {{ \Carbon\Carbon::parse($booking->expires_at)->format('d M Y, H:i') }}
                                            </strong>
                                        </p>
                                    @endif

                                </div>

                            @endif

                        @elseif (in_array($status, ['paid', 'confirmed', 'checked_in', 'completed']))

                            <div class="rounded-xl bg-green-50 border border-green-100 p-4">

                                <p class="font-semibold text-green-700 text-sm">
                                    Pembayaran Berhasil
                                </p>

                                <p class="text-xs text-green-600 mt-2">
                                    Terima kasih. Pemesanan kamar kamu telah diproses.
                                </p>

                            </div>

                        @else

                            <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">

                                <p class="font-semibold text-gray-700 text-sm">
                                    {{ $statusInfo['label'] }}
                                </p>

                            </div>

                        @endif

                    </div>

                    {{-- Action --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">

                        <div class="space-y-3">

                            @if($booking->status === 'pending')

    <form
        action="{{ route('payment.create', $booking->booking_code) }}"
        method="POST">

        @csrf

        <button
            type="submit"
            class="w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-3 px-6 rounded-xl transition">

            Bayar Sekarang

        </button>

    </form>

@endif

                            <a href="{{ route('room.index') }}"
                                class="block w-full text-center border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl transition">
                                Kembali ke Pilihan Kamar
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

    @include('layout.footer')

</body>
</html>