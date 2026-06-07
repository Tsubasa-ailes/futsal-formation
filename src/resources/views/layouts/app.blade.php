<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TACT</title>

    @stack('styles')
</head>
<body>
    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>