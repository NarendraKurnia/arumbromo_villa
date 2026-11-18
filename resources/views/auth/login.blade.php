<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Icon Web -->
  <link rel="icon" href="{{asset('umum/images/logo-arumbromo.png') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
  <!-- jQuery -->
  <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }} "></script>
  <!-- SweetAlert2 -->
  <script src="{{ asset('admin/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>
</head>
<body class="min-h-screen flex">
<div class="hidden md:flex md:w-11/12 relative">
    <img src="{{ asset('umum/images/icon-bromo2.jpg') }}"
         alt="Background"
         class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-end p-10">
      <h1 class="text-4xl font-bold text-white mb-2">Welcome to Arum Bromo Villa</h1>
      <p class="text-white text-lg">Log in to access your bookings, exclusive deals, and enjoy a faster checkout.</p>
    </div>
  </div>

  <!-- Bagian Kanan (Form Login) -->
  <div class="flex w-full md:w-1/2 justify-center items-center p-8">
  <div class="w-full max-w-sm text-center">
    @if (session('failed'))
    
    
    @endif
    <a href="{{ route('home') }}"><i class="fa fa-arrow-left text-m mr-96 text-gray-700 mb-4" aria-hidden="true"></i></a>
    <h2 class="text-2xl font-bold mb-2">Ready to Plan Your Next Stay?</h2>
    <p class="text-gray-500 mb-6">
      Sign in to access your account and explore our latest offers.
    </p>
      <!-- Form -->
      <form action=/login  method="post">
  @csrf
  <!-- Email -->
  <div class="mb-4">
    <label class="sr-only" for="email">Email</label>
    <div class="flex items-center border border-gray-300 rounded px-3 py-2 bg-white">
      <!-- Icon Amplop -->
      <svg xmlns="http://www.w3.org/2000/svg" 
           class="h-5 w-5 text-gray-400" 
           fill="none" 
           viewBox="0 0 24 24" 
           stroke="currentColor">
        <path stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
      </svg>
      <input 
        type="email" 
        id="email" 
        name="email"      
        placeholder="Insert your email"
        class="ml-2 w-full outline-none border-none text-gray-700 placeholder-gray-400"
        required
      >
    </div>
  </div>

  <!-- Password -->
  <div class="mb-4 relative">
    <label class="sr-only" for="password">Password</label>
    <div class="flex items-center border border-gray-300 rounded px-3 py-2 bg-white relative">
      <!-- Icon Password -->
      <svg xmlns="http://www.w3.org/2000/svg" 
           class="h-5 w-5 text-gray-400" 
           fill="none" 
           viewBox="0 0 24 24" 
           stroke="currentColor">
        <path stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M12 15v2m6-6v-2a4 4 0 00-8 0v2m10 0a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2h12z" />
      </svg>
      <input 
        type="password" 
        id="password" 
        name="password"         {{-- **ini wajib!** --}}
        placeholder="Insert password"
        class="ml-2 w-full outline-none border-none text-gray-700 placeholder-gray-400"
        required
      >
      <!-- Icon Mata -->
      <button type="button" onclick="togglePass('password', 'eye-icon-1')" class="absolute right-3 text-gray-400 hover:text-gray-600 focus:outline-none">
      <i id="eye-icon-1" class="fas fa-eye"></i>
    </button>
    </div>
  </div>

  {{-- Tampilkan flash message --}}
  @if(session('warning'))
    <div class="mb-4 text-yellow-800 bg-yellow-200 p-2 rounded">
      {{ session('warning') }}
    </div>
  @endif
  @if(session('sukses'))
    <div class="mb-4 text-green-800 bg-green-200 p-2 rounded">
      {{ session('sukses') }}
    </div>
  @endif

  <!-- Remember Me & Forgot -->
  <div class="flex items-center justify-between mb-6">
    <label class="flex items-center space-x-2">
      <input type="checkbox" class="form-checkbox">
      <span class="text-sm text-gray-600">Remember me</span>
    </label>
    <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Forgot password?</a>
  </div>

  <!-- Sign In Button -->
  <button type="submit"
          class="w-full bg-yellow-700 hover:bg-yellow-800 text-white py-2 rounded transition">
    Sign In
  </button>

  <!-- Login Gmail -->
  <button type="submit"
      class="w-full bg-blue-700 hover:bg-yellow-800 text-white py-2 rounded transition mt-4 flex items-center justify-center gap-2">
      <!-- SVG icon Google -->
      Login with Google
  </button>
</form>


      <!-- Register Link -->
      <p class="text-center text-sm text-gray-500 mt-6">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
      </p>
    </div>
  </div>

<script>
tinymce.init({
  selector: '.simple',
  menubar: false,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste code help wordcount'
  ],
  toolbar: 'undo redo | formatselect | ' +
  'bold italic backcolor | alignleft aligncenter ' +
  'alignright alignjustify | bullist numlist outdent indent | ' +
  'removeformat | help',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});
</script>

<?php 
$sek  = date('Y');
$awal = $sek-100;
?>
<script>
  // notifikasi jika sukses
<?php if(Session::get('sukses')) { ?>
  Swal.fire({
    title: 'Berhasil!',
    text: "{{ Session::get('sukses') }}",
    icon: 'success',
    confirmButtonText: 'Ok, Terimakasih'
});
<?php } ?> 
// notifikasi jika gagal
<?php if(Session::get('warning')) { ?>
  Swal.fire({
    title: 'Oopsss..!',
    text: "{{ Session::get('warning') }}",
    icon: 'warning',
    confirmButtonText: 'Coba Lagi'
});
<?php } ?> 

  // Popup Delete
  $(document).ready(function() {
    // Event handler untuk link dengan class 'delete-link'
    $('.delete-link').on('click', function(e) {
      e.preventDefault(); //mencegah aksi default link

      var href = $(this).attr('href'); //Mendapatkan URL dari href link

      // Menampilkan konfirmasi dengan SweetAlert2
      Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor:  '#d33',
        confirmButtonText:  'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((reslut) => {
        if    (result.isConfirmed) {
          // Jika pengguna menkonfirmasi, lanjutkan ke URL penghapusan
          window.location.href;
        }    
      })
    })
   })

</script>
<script>
    function togglePass(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById(iconId);

    if (passwordInput.type === 'password') {
        // Ubah jadi text (terlihat)
        passwordInput.type = 'text';
        // Ubah icon jadi mata tertutup
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        // Ubah jadi password (tersembunyi)
        passwordInput.type = 'password';
        // Ubah icon jadi mata terbuka
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
</script>

<!-- Bootstrap 4 -->
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
</body>
</html>