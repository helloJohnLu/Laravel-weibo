<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', '我的微博') - Laravel 入门教程</title>
    <link rel="stylesheet" href="/css/app.css">
</head>

    @include('layouts._header')

    <div class="container">
        <div class="col-md-offset-1 col-md-10">
            @include('shared._messages')
            @yield('content')
            @include('layouts._footer')
        </div>
    </div>
</body>
</html>