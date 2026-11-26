<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    @if($isProduction)
        <script
            src="https://app.midtrans.com/snap/snap.js"
            data-client-key="{{ $clientKey }}">
        </script>
    @else
        <script
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ $clientKey }}">
        </script>
    @endif
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-5">

    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-lg w-full">

        <div class="text-center mb-6">

            <h1 class="text-2xl font-bold text-gray-800">
                Pembayaran Booking
            </h1>

            <p class="text-gray-500 mt-2">
                Arum Bromo Villas
            </p>

        </div>

        <div class="border rounded-xl p-5 mb-6">

            <div class="flex justify-between mb-3">
                <span class="text-gray-500">Kode Booking</span>

                <span class="font-semibold">
                    {{ $booking->booking_code }}
                </span>
            </div>

            <div class="flex justify-between mb-3">
                <span class="text-gray-500">Kode Pembayaran</span>

                <span class="font-semibold">
                    {{ $payment->payment_code }}
                </span>
            </div>

            <div class="border-t pt-4 mt-4 flex justify-between">

                <span class="font-semibold">
                    Total Pembayaran
                </span>

                <span class="text-xl font-bold text-green-700">
                    Rp{{ number_format($payment->amount, 0, ',', '.') }}
                </span>

            </div>

        </div>

        <button
            id="pay-button"
            class="w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-4 rounded-xl transition">

            Bayar Sekarang

        </button>

        <a
            href="{{ route('booking.detail', $booking->booking_code) }}"
            class="block text-center text-gray-500 mt-5 hover:text-gray-800">

            Kembali ke Detail Booking

        </a>

    </div>

    <script>
        document.getElementById('pay-button').addEventListener('click', function () {

            window.snap.pay(@json($snapToken), {

                onSuccess: function(result) {
                    window.location.href = "{{ route('booking.detail', $booking->booking_code) }}";
                },

                onPending: function(result) {
                    alert('Pembayaran sedang menunggu penyelesaian.');
                    window.location.href = "{{ route('booking.detail', $booking->booking_code) }}";
                },

                onError: function(result) {
                    alert('Pembayaran gagal. Silakan coba kembali.');
                },

                onClose: function() {
                    console.log('Pelanggan menutup halaman pembayaran.');
                }

            });

        });
    </script>

</body>
</html>