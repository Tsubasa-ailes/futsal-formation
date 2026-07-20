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
                    アカウントを作成しましょう
                </p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="space-y-6"
                onsubmit="return confirm('この内容で登録しますか？');">
                @csrf

                <div>
                    <label for="login_id" class="mb-2 block text-sm font-bold text-blue-50">
                        ログインID
                    </label>

                    <input type="text" name="login_id" id="login_id" value="{{ old('login_id') }}" required
                        autocomplete="username" placeholder="ログインIDを入力"
                        class="w-full rounded-xl border bg-blue-950/80 px-4 py-3 text-white placeholder-blue-200/50 outline-none transition
                            {{ $errors->has('login_id')
                                ? 'border-red-400 focus:border-red-300 focus:ring-2 focus:ring-red-400/40'
                                : 'border-blue-300/30 focus:border-blue-300 focus:ring-2 focus:ring-blue-400/40' }}">

                    @error('login_id')
                        <p class="mt-2 text-sm font-semibold text-red-300">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="mb-2 block text-sm font-bold text-blue-50">
                        ユーザー名
                    </label>

                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        autocomplete="name" placeholder="ユーザー名を入力"
                        class="w-full rounded-xl border bg-blue-950/80 px-4 py-3 text-white placeholder-blue-200/50 outline-none transition
                            {{ $errors->has('name')
                                ? 'border-red-400 focus:border-red-300 focus:ring-2 focus:ring-red-400/40'
                                : 'border-blue-300/30 focus:border-blue-300 focus:ring-2 focus:ring-blue-400/40' }}">

                    @error('name')
                        <p class="mt-2 text-sm font-semibold text-red-300">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-bold text-blue-50">
                        パスワード
                    </label>

                    <input type="password" name="password" id="password" required autocomplete="new-password"
                        placeholder="8文字以上で入力"
                        class="w-full rounded-xl border bg-blue-950/80 px-4 py-3 text-white placeholder-blue-200/50 outline-none transition
                            {{ $errors->has('password')
                                ? 'border-red-400 focus:border-red-300 focus:ring-2 focus:ring-red-400/40'
                                : 'border-blue-300/30 focus:border-blue-300 focus:ring-2 focus:ring-blue-400/40' }}">

                    @error('password')
                        <p class="mt-2 text-sm font-semibold text-red-300">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-bold text-blue-50">
                        パスワード（確認）
                    </label>

                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        autocomplete="new-password" placeholder="パスワードをもう一度入力"
                        class="w-full rounded-xl border border-blue-300/30 bg-blue-950/80 px-4 py-3 text-white placeholder-blue-200/50 outline-none transition focus:border-blue-300 focus:ring-2 focus:ring-blue-400/40">
                </div>

                <button type="submit"
                    class="w-full cursor-pointer rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 py-3 font-bold text-white shadow-lg shadow-blue-950/40 transition hover:from-blue-400 hover:to-blue-600 active:scale-[0.98]">
                    登録する →
                </button>
            </form>

            <div class="mt-8 border-t border-blue-300/20 pt-5 text-center">
                <p class="text-sm text-blue-100/70">
                    すでにアカウントをお持ちですか？
                </p>

                <a href="{{ route('login') }}"
                    class="mt-2 inline-block text-sm font-bold text-blue-300 transition hover:text-white hover:underline">
                    ログインはこちら
                </a>
            </div>
        </div>
    </div>
@endsection
