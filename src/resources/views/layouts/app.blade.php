<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TACT ~フットサル戦術~</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="min-h-screen bg-blue-950 text-white">
    <header
        class="relative z-20 border-b border-blue-300/30 bg-gradient-to-r from-blue-900 via-blue-700 to-blue-600 shadow-lg shadow-blue-950/40">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg border border-white/30 bg-white/10 shadow-inner">
                    <span class="text-xl">⚽</span>
                </div>

                <div>
                    <a href="{{ route('home') }}" class="text-2xl font-black tracking-widest text-white">
                        TACT
                        </h1>
                        <p class="text-xs tracking-[0.25em] text-blue-100">
                            TACTICAL TOOL
                        </p>
                </div>
            </div>

            @if (Auth::check())
                <div class="flex items-center gap-6">
                    @unless (request()->routeIs('lineups.index'))
                        <a href="{{ route('lineups.index') }}"
                            class="hidden items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-blue-100 transition hover:bg-white/10 hover:text-white sm:flex">
                            <span>▦</span>
                            <span>保存一覧へ</span>
                        </a>
                    @endunless
                    @unless (request()->routeIs('play.index'))
                        <a href="{{ route('play.index') }}"
                            class="hidden items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-blue-100 transition hover:bg-white/10 hover:text-white sm:flex">
                            <span>＋</span>
                            <span>新規作成</span>
                        </a>
                    @endunless


                    <div class="hidden text-right sm:block">
                        <p class="text-xs text-blue-200">
                            ログイン中
                        </p>
                        <p class="font-semibold text-white">
                            {{ Auth::user()->name }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" onclick="return confirm('ログアウトしますか？')"
                            class="cursor-pointer rounded-lg border border-red-400/40 bg-red-600/90 px-4 py-2 text-sm font-bold text-white shadow-md transition hover:bg-red-500">
                            ログアウト
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </header>

    <main class="relative min-h-[calc(100vh-76px)] overflow-hidden">
        {{-- ブルーベース背景 --}}
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_center,_rgba(96,165,250,0.35),_transparent_42%),linear-gradient(135deg,_#1e3a8a_0%,_#172554_45%,_#1d4ed8_100%)]">
        </div>

        {{-- 左下の青い装飾 --}}
        <div
            class="pointer-events-none absolute -left-32 bottom-0 h-96 w-96 rotate-45 border border-blue-300/40 opacity-40">
        </div>
        <div class="pointer-events-none absolute -left-20 bottom-20 h-80 w-2 rotate-45 bg-blue-300/50 blur-sm">
        </div>

        {{-- 右側の青い装飾 --}}
        <div
            class="pointer-events-none absolute -right-24 top-20 h-[520px] w-[520px] rotate-45 border border-blue-300/40 opacity-40">
        </div>
        <div class="pointer-events-none absolute right-16 top-36 h-96 w-2 rotate-45 bg-blue-300/50 blur-sm">
        </div>

        {{-- ドット装飾 --}}
        <div class="pointer-events-none absolute right-0 top-10 h-72 w-72 opacity-30"
            style="background-image: radial-gradient(rgba(191, 219, 254, 0.9) 1px, transparent 1px); background-size: 14px 14px;">
        </div>

        {{-- ページ本体 --}}
        <div class="relative z-10">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>

</html>
