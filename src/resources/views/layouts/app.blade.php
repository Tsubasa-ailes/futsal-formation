<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TACT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-black text-white min-h-screen">
    @if(Auth::check())
        <div class="bg-blue-800 flex justify-between items-center px-6 py-4">
            <h1 class="text-white text-xl font-bold">
                TACT
            </h1>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded"
                >
                    ログアウト
                </button>
            </form>
        </div>    
    @endif
    <main>
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>