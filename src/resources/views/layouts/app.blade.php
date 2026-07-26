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

<body class="min-h-screen overflow-x-hidden bg-blue-950 text-white">

    {{-- ヘッダー --}}
    <header
        class="relative z-[100] border-b border-blue-300/30 bg-gradient-to-r from-blue-900 via-blue-700 to-blue-600 shadow-lg shadow-blue-950/40">

        <div class="mx-auto flex max-w-7xl items-center justify-between px-3 py-3 sm:px-6 sm:py-4 lg:px-8">

            {{-- ロゴ --}}
            <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                <div
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-white/30 bg-white/10 shadow-inner sm:h-10 sm:w-10">
                    <span class="text-lg sm:text-xl">⚽</span>
                </div>

                <div class="min-w-0">
                    <a href="{{ route('home') }}"
                        class="block text-xl font-black tracking-widest text-white transition hover:text-blue-100 sm:text-2xl">
                        TACT
                    </a>

                    <p class="hidden text-xs tracking-[0.25em] text-blue-100 sm:block">
                        TACTICAL TOOL
                    </p>
                </div>
            </div>

            @if (Auth::check())
                {{-- タブレット・PC用メニュー --}}
                <div class="hidden items-center gap-2 sm:flex lg:gap-4">
                    @unless (request()->routeIs('lineups.index'))
                        <a href="{{ route('lineups.index') }}"
                            class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-blue-100 transition hover:bg-white/10 hover:text-white">
                            <span aria-hidden="true">▦</span>
                            <span>保存一覧へ</span>
                        </a>
                    @endunless

                    @unless (request()->routeIs('play.index'))
                        <a href="{{ route('play.index') }}"
                            class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-blue-100 transition hover:bg-white/10 hover:text-white">
                            <span aria-hidden="true">＋</span>
                            <span>新規作成</span>
                        </a>
                    @endunless

                    <div class="hidden border-l border-white/20 pl-4 text-right lg:block">
                        <p class="text-xs text-blue-200">
                            ログイン中
                        </p>

                        <p class="max-w-32 truncate font-semibold text-white">
                            {{ Auth::user()->name }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" onclick="return confirm('ログアウトしますか？')"
                            class="cursor-pointer rounded-lg border border-red-400/40 bg-red-600/90 px-4 py-2 text-sm font-bold text-white shadow-md transition hover:bg-red-500 active:scale-[0.98]">
                            ログアウト
                        </button>
                    </form>
                </div>

                {{-- スマホ用ハンバーガーボタン --}}
                <button type="button" id="mobile-menu-button"
                    class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-lg border border-white/30 bg-white/10 text-white transition hover:bg-white/20 sm:hidden"
                    aria-label="メニューを開く" aria-controls="mobile-menu" aria-expanded="false">

                    <span id="mobile-menu-open-icon" class="text-2xl leading-none">
                        ☰
                    </span>

                    <span id="mobile-menu-close-icon" class="hidden text-2xl leading-none">
                        ×
                    </span>
                </button>
            @endif
        </div>
    </header>

    @if (Auth::check())
        {{-- スマホメニュー表示時の背景 --}}
        <button type="button" id="mobile-menu-overlay"
            class="fixed inset-0 z-[90] hidden cursor-default bg-black/40 backdrop-blur-[1px] sm:hidden"
            aria-label="メニューを閉じる" aria-hidden="true">
        </button>

        {{-- スマホ用メニュー本体 --}}
        <div id="mobile-menu"
            class="fixed right-3 top-[65px] z-[110] hidden max-h-[calc(100dvh-77px)] w-64 overflow-y-auto rounded-2xl border border-blue-300/30 bg-blue-950 shadow-2xl shadow-black/60 sm:hidden"
            aria-hidden="true">

            {{-- ユーザー情報 --}}
            <div class="border-b border-blue-300/20 px-4 py-4">
                <p class="text-xs text-blue-300">
                    ログイン中
                </p>

                <p class="mt-1 truncate font-bold text-white">
                    {{ Auth::user()->name }}
                </p>
            </div>

            {{-- ナビゲーション --}}
            <nav class="space-y-1 p-2">
                @unless (request()->routeIs('lineups.index'))
                    <a href="{{ route('lineups.index') }}"
                        class="flex min-h-11 items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-blue-100 transition hover:bg-white/10 hover:text-white">
                        <span aria-hidden="true">▦</span>
                        <span>保存一覧へ</span>
                    </a>
                @endunless

                @unless (request()->routeIs('play.index'))
                    <a href="{{ route('play.index') }}"
                        class="flex min-h-11 items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-blue-100 transition hover:bg-white/10 hover:text-white">
                        <span aria-hidden="true">＋</span>
                        <span>新規作成</span>
                    </a>
                @endunless

                <a href="{{ route('home') }}"
                    class="flex min-h-11 items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-blue-100 transition hover:bg-white/10 hover:text-white">
                    <span aria-hidden="true">⌂</span>
                    <span>ホーム</span>
                </a>
            </nav>

            {{-- ログアウト --}}
            <div class="border-t border-blue-300/20 p-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" onclick="return confirm('ログアウトしますか？')"
                        class="min-h-11 w-full cursor-pointer rounded-xl border border-red-400/40 bg-red-600/90 px-4 py-2 text-sm font-bold text-white transition hover:bg-red-500 active:scale-[0.98]">
                        ログアウト
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- メインコンテンツ --}}
    <main class="relative z-0 min-h-[calc(100dvh-65px)] overflow-x-hidden sm:min-h-[calc(100dvh-73px)]">

        {{-- ブルーベース背景 --}}
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_center,_rgba(96,165,250,0.35),_transparent_42%),linear-gradient(135deg,_#1e3a8a_0%,_#172554_45%,_#1d4ed8_100%)]">
        </div>

        {{-- 左下の青い装飾 --}}
        <div
            class="pointer-events-none absolute -left-40 bottom-0 h-72 w-72 rotate-45 border border-blue-300/30 opacity-30 sm:-left-32 sm:h-96 sm:w-96 sm:border-blue-300/40 sm:opacity-40">
        </div>

        <div
            class="pointer-events-none absolute -left-28 bottom-20 hidden h-80 w-2 rotate-45 bg-blue-300/50 blur-sm sm:block">
        </div>

        {{-- 右側の青い装飾 --}}
        <div
            class="pointer-events-none absolute -right-24 top-20 hidden h-[520px] w-[520px] rotate-45 border border-blue-300/40 opacity-40 sm:block">
        </div>

        <div
            class="pointer-events-none absolute right-16 top-36 hidden h-96 w-2 rotate-45 bg-blue-300/50 blur-sm lg:block">
        </div>

        {{-- ドット装飾 --}}
        <div class="pointer-events-none absolute right-0 top-10 h-48 w-48 opacity-20 sm:h-72 sm:w-72 sm:opacity-30"
            style="
                background-image: radial-gradient(
                    rgba(191, 219, 254, 0.9) 1px,
                    transparent 1px
                );
                background-size: 14px 14px;
            ">
        </div>

        {{-- ページ本体 --}}
        <div class="relative z-10 min-w-0">
            @yield('content')
        </div>
    </main>

    @stack('scripts')

    @if (Auth::check())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const button = document.getElementById('mobile-menu-button');
                const menu = document.getElementById('mobile-menu');
                const overlay = document.getElementById('mobile-menu-overlay');
                const openIcon = document.getElementById('mobile-menu-open-icon');
                const closeIcon = document.getElementById('mobile-menu-close-icon');

                if (!button || !menu || !overlay || !openIcon || !closeIcon) {
                    return;
                }

                const setMenuOpen = (isOpen) => {
                    menu.classList.toggle('hidden', !isOpen);
                    overlay.classList.toggle('hidden', !isOpen);

                    openIcon.classList.toggle('hidden', isOpen);
                    closeIcon.classList.toggle('hidden', !isOpen);

                    button.setAttribute('aria-expanded', String(isOpen));
                    button.setAttribute(
                        'aria-label',
                        isOpen ? 'メニューを閉じる' : 'メニューを開く'
                    );

                    menu.setAttribute('aria-hidden', String(!isOpen));
                    overlay.setAttribute('aria-hidden', String(!isOpen));

                    document.body.classList.toggle('overflow-hidden', isOpen);
                };

                button.addEventListener('click', () => {
                    const isOpen =
                        button.getAttribute('aria-expanded') === 'true';

                    setMenuOpen(!isOpen);
                });

                overlay.addEventListener('click', () => {
                    setMenuOpen(false);
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        setMenuOpen(false);
                    }
                });

                menu.querySelectorAll('a').forEach((link) => {
                    link.addEventListener('click', () => {
                        setMenuOpen(false);
                    });
                });

                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 640) {
                        setMenuOpen(false);
                    }
                });
            });
        </script>
    @endif
</body>

</html>
