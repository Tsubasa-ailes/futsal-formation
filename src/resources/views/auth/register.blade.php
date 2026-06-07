@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ユーザー登録</h1>

    <form action="{{ route('register.store') }}" method="POST" onsubmit="return confirm('この内容で登録しますか？');">
        @csrf

        <div class="mb-3">
            <label for="login_id" class="form-label">ログインID</label>
            <input
                type="text"
                id="login_id"
                name="login_id"
                class="form-control"
                value="{{ old('login_id') }}"
                required
            >

            @error('login_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">ユーザー名</label>
            <input
                type="text"
                id="name"
                name="name"
                class="form-control"
                value="{{ old('name') }}"
                required
            >

            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">パスワード</label>
            <input
                type="password"
                id="password"
                name="password"
                class="form-control"
                required
            >

            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">
                パスワード（確認）
            </label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">
            登録
        </button>
    </form>
</div>
@endsection