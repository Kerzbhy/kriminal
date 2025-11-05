<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('masuk/css/style.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Login</title>
</head>

<body>
    <div class="wrapper">
        <form action="{{ route('loginProses') }}" method="POST">@csrf
            <h1>Login</h1>
            <div class="input-box">
                <input type="text" name="email" placeholder="Email" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>@error('password')
                    <small class="text-light">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn">Masuk</button>
        </form>
        <hr>
        <div class="text-center">
            <small>
                Kembali Ke Beranda?
                <a href="{{ route('welcome') }}">Klik disini</a>
            </small>
        </div>
    </div>

    <script src="{{ asset('sweet/dist/sweetalert2.all.min.js') }}"></script>

    @session('success')
        <script>Swal.fire({
                title: "Sukses!",
                text: "{{ session('success') }}",
                icon: "success"
            });</script>
    @endsession

    @session('error')
        <script>Swal.fire({
                title: "Gagal!",
                text: "{{ session('error') }}",
                icon: "error"
            });</script>
    @endsession

</body>

</html>