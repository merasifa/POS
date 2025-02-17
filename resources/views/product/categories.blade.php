<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kategori Produk</title>
</head>
<body>
    <h1>Pilih Kategori Produk</h1>
    <ul>
        <li><a href="{{ url('/category/food-beverage') }}">Food & Beverage</a></li>
        <li><a href="{{ url('/category/beauty-health') }}">Beauty & Health</a></li>
        <li><a href="{{ url('/category/home-care') }}">Home Care</a></li>
        <li><a href="{{ url('/category/baby-kid') }}">Baby & Kid</a></li>
    </ul>
</body>
</html>
