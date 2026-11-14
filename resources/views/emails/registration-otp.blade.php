<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Akun</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f5f5f5; padding: 30px;">

    <div style="max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 10px;">

        <h2 style="text-align: center; color: #987447;">
            Arum Bromo Villas
        </h2>

        <h3>Verifikasi Akun Anda</h3>

        <p>Halo, {{ $name }}.</p>

        <p>
            Terima kasih telah mendaftar di Arum Bromo Villas.
            Gunakan kode OTP berikut untuk menyelesaikan proses registrasi.
        </p>

        <div style="
            background: #f5f0e8;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin: 25px 0;
        ">
            <h1 style="letter-spacing: 10px; color: #987447;">
                {{ $otp }}
            </h1>
        </div>

        <p>
            Kode OTP berlaku selama <b>5 menit</b>.
            Jangan berikan kode ini kepada siapa pun.
        </p>

        <p>
            Jika Anda tidak merasa melakukan registrasi,
            abaikan email ini.
        </p>

        <hr>

        <p style="font-size: 12px; color: #999; text-align: center;">
            © {{ date('Y') }} Arum Bromo Villas
        </p>

    </div>

</body>
</html>