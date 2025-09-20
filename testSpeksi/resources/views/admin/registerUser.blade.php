<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna Baru</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/registerUser.css') }}?v={{ time() }}">
</head>

<body>

    <div class="header">
        <img src="https://raw.githubusercontent.com/ZulhilmiAmy/Final-Year-Project/main/Image%20banner/Banner-SPeKSi.png"
            alt="Banner">

        <div class="top-bar">
            <div class="breadcrumbs">
                <a>Log Masuk</a>
                <span class="separator">&gt;</span>
                <a href="{{ route('home') }}">Halaman Utama</a>
                <span class="separator">&gt;</span>
                <a>Daftar Pengguna Baru</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h2><i class="fas fa-user-plus"></i> Daftar Pengguna Baru</h2>

        <form method="POST" action="{{ route('admin.registerUser.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">Nama Penuh</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="username">Email / ID Pengguna</label>
                <input type="email" id="username" name="username" value="{{ old('username') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Kata Laluan</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Sahkan Kata Laluan</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <label for="role">Peranan</label>
                <select name="role" id="role" required>
                    <option value="user">Pengguna Biasa</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" class="btn">Daftar</button>
        </form>

        <a href="{{ route('admin.dashboard') }}" class="back-btn">← Kembali ke Dashboard</a>
    </div>

</body>

</html>