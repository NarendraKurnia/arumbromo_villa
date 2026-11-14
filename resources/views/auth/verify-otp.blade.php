<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verifikasi OTP - Arum Bromo Villas</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <div class="text-center mb-6">

            <h1 class="text-2xl font-bold text-amber-800">
                Arum Bromo Villas
            </h1>

            <h2 class="text-xl font-semibold mt-5">
                Verifikasi Akun
            </h2>

            <p class="text-gray-500 text-sm mt-2">
                Kami telah mengirimkan kode OTP ke email:
            </p>

            <p class="font-semibold text-gray-800">
                {{ $otp->email }}
            </p>

        </div>


        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif


        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif


        {{-- Form verifikasi OTP --}}
        <form action="{{ route('otp.verify.submit') }}" method="POST">

            @csrf

            <label class="block text-sm font-medium mb-2">
                Masukkan Kode OTP
            </label>

            <input
                type="text"
                name="otp"
                maxlength="6"
                inputmode="numeric"
                pattern="[0-9]{6}"
                required
                placeholder="Masukkan 6 digit OTP"
                class="w-full border rounded-lg px-4 py-3 text-center tracking-[10px] text-xl focus:ring-2 focus:ring-amber-600 outline-none"
            >

            <button
                type="submit"
                class="w-full bg-amber-800 hover:bg-amber-900 text-white py-3 rounded-lg mt-5"
            >
                Verifikasi OTP
            </button>

        </form>


        <div class="text-center mt-6">

            <div class="text-center mt-6">

    <p class="text-sm text-gray-500 mb-2">
        Belum menerima kode OTP?
    </p>

    <p class="text-xs text-gray-400 mb-4">
        Periksa folder Inbox, Spam, atau Promotions pada Gmail kamu.
    </p>

    {{-- Kirim ulang melalui Gmail --}}
    <form action="{{ route('otp.resend.email') }}" method="POST">
        @csrf

        <button
            type="submit"
            class="w-full border border-amber-800 text-amber-800 hover:bg-amber-50 font-semibold py-3 rounded-lg transition"
        >
            Kirim Ulang OTP melalui Gmail
        </button>
    </form>

    <div class="flex items-center gap-3 my-5">
        <div class="flex-1 border-t"></div>
        <span class="text-xs text-gray-400">ATAU</span>
        <div class="flex-1 border-t"></div>
    </div>

    {{-- Kirim melalui WhatsApp --}}
    <form action="{{ route('otp.send.whatsapp') }}" method="POST">
        @csrf

        <button
            type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition"
        >
            Kirim OTP melalui WhatsApp
        </button>
    </form>

</div>

<p class="text-xs text-gray-400 text-center mt-6">
    Kode OTP berlaku selama 5 menit.
    Kamu dapat meminta kode baru jika belum menerima email.
</p>

    </div>

</body>
</html>