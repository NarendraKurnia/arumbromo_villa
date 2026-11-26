<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - Arum Bromo Villas</title>

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('umum/images/logo-arumbromo.png') }}">
</head>

<body class="min-h-screen flex bg-white">

    <!-- Bagian Kiri: Background -->
    <div class="hidden md:flex md:w-1/2 relative min-h-screen">

        <img src="{{ asset('umum/images/icon-bromo2.jpg') }}"
            alt="Arum Bromo Villas"
            class="absolute inset-0 w-full h-full object-cover">

        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-end p-10">

            <h1 class="text-4xl font-bold text-white mb-3">
                Welcome to Arum Bromo Villa
            </h1>

            <p class="text-white text-lg leading-relaxed">
                Create your account and start planning your unforgettable
                stay at Arum Bromo Villas. Enjoy a comfortable experience
                surrounded by the beauty of Mount Bromo.
            </p>

        </div>
    </div>


    <!-- Bagian Kanan: Form Register -->
    <div class="flex w-full md:w-1/2 justify-center items-center px-6 py-10 md:px-12">

        <div class="w-full max-w-md">

            <!-- Tombol Kembali -->
            <a href="{{ route('home') }}"
                class="inline-flex items-center text-gray-600 hover:text-yellow-700 mb-6 transition">

                <i class="fas fa-arrow-left mr-2"></i>
                <span class="text-sm">Kembali ke Beranda</span>

            </a>


            <!-- Judul -->
            <div class="text-center mb-7">

                <img src="{{ asset('umum/images/logo-arumbromo.png') }}"
                    alt="Logo Arum Bromo"
                    class="h-16 mx-auto mb-4 object-contain">

                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    Create Your Account
                </h2>

                <p class="text-gray-500 text-sm">
                    Register now and enjoy your stay at Arum Bromo Villas.
                </p>

            </div>


            <!-- Notifikasi Error -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-700 p-3 rounded-lg mb-5 text-sm">

                    <div class="flex items-center font-semibold mb-1">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Terjadi Kesalahan
                    </div>

                    @foreach ($errors->all() as $error)
                        <p class="ml-6">{{ $error }}</p>
                    @endforeach

                </div>
            @endif


            @if (session('error'))
                <div class="bg-red-100 border border-red-200 text-red-700 p-3 rounded-lg mb-5 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif


            <!-- Form Register -->
            <form action="{{ route('register.submit') }}" method="POST">
                @csrf

                <!-- Nama Lengkap -->
                <div class="mb-4">

                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap
                    </label>

                    <div class="flex items-center border border-gray-300 rounded-lg px-3 py-3 bg-white focus-within:border-yellow-700 transition">

                        <i class="fas fa-user text-gray-400"></i>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap"
                            autocomplete="name"
                            class="ml-3 w-full outline-none border-none text-gray-700 placeholder-gray-400 text-sm"
                            required>

                    </div>

                </div>


                <!-- Email -->
                <div class="mb-4">

                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>

                    <div class="flex items-center border border-gray-300 rounded-lg px-3 py-3 bg-white focus-within:border-yellow-700 transition">

                        <i class="fas fa-envelope text-gray-400"></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan alamat email"
                            autocomplete="email"
                            class="ml-3 w-full outline-none border-none text-gray-700 placeholder-gray-400 text-sm"
                            required>

                    </div>

                </div>


                <!-- Nomor WhatsApp -->
                <div class="mb-4">

                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor WhatsApp
                    </label>

                    <div class="flex items-center border border-gray-300 rounded-lg px-3 py-3 bg-white focus-within:border-yellow-700 transition">

                        <i class="fab fa-whatsapp text-gray-400"></i>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="Contoh: 081234567890"
                            autocomplete="tel"
                            class="ml-3 w-full outline-none border-none text-gray-700 placeholder-gray-400 text-sm"
                            required>

                    </div>

                </div>


                <!-- Password -->
                <div class="mb-4">

                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>

                    <div class="flex items-center border border-gray-300 rounded-lg px-3 py-3 bg-white focus-within:border-yellow-700 transition">

                        <i class="fas fa-lock text-gray-400"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="new-password"
                            class="ml-3 w-full outline-none border-none text-gray-700 placeholder-gray-400 text-sm"
                            required>

                        <button
                            type="button"
                            onclick="togglePass('password', 'eye-icon-1')"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none">

                            <i id="eye-icon-1" class="fas fa-eye"></i>

                        </button>

                    </div>

                </div>


                <!-- Konfirmasi Password -->
                <div class="mb-6">

                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password
                    </label>

                    <div class="flex items-center border border-gray-300 rounded-lg px-3 py-3 bg-white focus-within:border-yellow-700 transition">

                        <i class="fas fa-lock text-gray-400"></i>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Masukkan ulang password"
                            autocomplete="new-password"
                            class="ml-3 w-full outline-none border-none text-gray-700 placeholder-gray-400 text-sm"
                            required>

                        <button
                            type="button"
                            onclick="togglePass('password_confirmation', 'eye-icon-2')"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none">

                            <i id="eye-icon-2" class="fas fa-eye"></i>

                        </button>

                    </div>

                </div>


                <!-- Tombol Register -->
                <button
                    type="submit"
                    class="w-full bg-yellow-700 hover:bg-yellow-800 text-white py-3 rounded-lg transition duration-200 font-semibold shadow-sm">

                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang

                </button>

            </form>


            <!-- Login Link -->
            <p class="text-center text-sm text-gray-500 mt-6">

                Already have an account?

                <a href="{{ route('login') }}"
                    class="text-yellow-700 hover:text-yellow-800 font-semibold hover:underline">

                    Sign In

                </a>

            </p>


            <!-- Footer -->
            <p class="text-center text-xs text-gray-400 mt-8">
                &copy; {{ date('Y') }} Arum Bromo Villas. All Rights Reserved.
            </p>

        </div>

    </div>


    <!-- Toggle Password -->
    <script>
        function togglePass(inputId, iconId) {

            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {

                passwordInput.type = 'text';

                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');

            } else {

                passwordInput.type = 'password';

                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');

            }

        }
    </script>

</body>
</html>