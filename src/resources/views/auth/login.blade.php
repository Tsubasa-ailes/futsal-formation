@extends('layouts.app')

@section('content')
    <div class="flex min-h-[calc(100vh-73px)] items-center justify-center px-6 py-12">
        <div
            class="w-full max-w-xl rounded-3xl border border-blue-300/30 bg-blue-950/70 p-8 shadow-2xl shadow-blue-950/60 backdrop-blur-xl sm:p-10">

            <div class="mb-8 text-center">
                <div
                    class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-blue-300/40 bg-white/10 shadow-inner">
                    <span class="text-3xl">⚽</span>
                </div>

                <h1 class="text-5xl font-black tracking-widest text-white">
                    TACT
                </h1>

                <p class="mt-3 text-xs font-semibold tracking-[0.35em] text-blue-300">
                    TACTICAL ANALYSIS & CREATION TOOL
                </p>

                <div class="mx-auto mt-6 flex max-w-sm items-center gap-4">
                    <div class="h-px flex-1 bg-blue-400/50"></div>
                    <span class="text-blue-300">●</span>
                    <div class="h-px flex-1 bg-blue-400/50"></div>
                </div>

                <p class="mt-6 text-xl font-semibold text-blue-50">
                    ログインして戦術を管理しましょう
                </p>
            </div>

            <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="login_id" class="mb-2 block text-sm font-bold text-blue-50">
                        ログインID
                    </label>

                    <input type="text" name="login_id" id="login_id" value="{{ old('login_id') }}" required
                        autocomplete="username" placeholder="ログインIDを入力"
                        class="w-full rounded-xl border border-blue-300/30 bg-blue-950/80 px-4 py-3 text-white placeholder-blue-200/50 outline-none transition focus:border-blue-300 focus:ring-2 focus:ring-blue-400/40">

                    @error('login_id')
                        <p class="mt-2 text-sm font-semibold text-red-300">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-bold text-blue-50">
                        パスワード
                    </label>

                    <input type="password" name="password" id="password" required autocomplete="current-password"
                        placeholder="パスワードを入力"
                        class="w-full rounded-xl border border-blue-300/30 bg-blue-950/80 px-4 py-3 text-white placeholder-blue-200/50 outline-none transition focus:border-blue-300 focus:ring-2 focus:ring-blue-400/40">

                    @error('password')
                        <p class="mt-2 text-sm font-semibold text-red-300">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full cursor-pointer rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 py-3 font-bold text-white shadow-lg shadow-blue-950/40 transition hover:from-blue-400 hover:to-blue-600 active:scale-[0.98]">
                    ログイン →
                </button>
            </form>

            <div class="mt-8 border-t border-blue-300/20 pt-6 text-center">
                <p class="text-sm text-blue-100/70">
                    アカウントをお持ちでない方
                </p>

                <a href="{{ route('register') }}"
                    class="mt-3 inline-flex cursor-pointer items-center justify-center rounded-xl border border-blue-300/40 px-6 py-2.5 text-sm font-bold text-blue-200 transition hover:border-blue-200 hover:bg-white/10 hover:text-white active:scale-[0.98]">
                    新規会員登録 →
                </a>

                <p class="mt-6 text-sm text-blue-100/70">
                    TACTで、あなたの戦術を可視化しよう。
                </p>
            </div>
        </div>
    </div>
@endsection
