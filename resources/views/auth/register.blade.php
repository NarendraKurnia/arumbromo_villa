<form action="{{ route('register.submit') }}" method="POST">
    @csrf

    <input type="text" name="name" required>

    <input type="email" name="email" required>

    <input type="text" name="phone" required>

    <input type="password" name="password" required>

    <input type="password" name="password_confirmation" required>

    <button type="submit">
        Daftar Sekarang
    </button>
</form>

@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
        {{ session('error') }}
    </div>
@endif