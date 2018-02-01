<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', '我的微博') - Laravel 入门教程</title>
    <link rel="stylesheet" href="/css/app.css">
</head>

    @include('layouts._header')

    <div class="container">
        @yield('content')
        @include('layouts._footer')
    </div>
</body>
</html>