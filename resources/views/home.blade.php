<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Selamat datang di halaman Home</h1>

    <p>
        <a href="{{ url('/category') }}">Produk</a> |
        <a href="{{ url('/user/1/name/Tiara') }}">Profil User</a> |
        <a href="{{ url('/sales') }}">Penjualan</a>
    </p>
</body>
</html>
