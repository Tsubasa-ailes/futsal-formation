@extends('layouts.app')

@section('content')
<h2>ログイン</h2>

<form method="POST" action="{{ route('login.store') }}">
    @csrf

    <div>
        <label for="login_id">ログインID</label>
        <input type="text" name="login_id" id="login_id" value="{{ old('login_id') }}" required>

        @error('login_id')
            <div>{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="password">パスワード</label>
        <input type="password" name="password" id="password" required>

        @error('password')
            <div>{{ $message }}</div>
        @enderror
    </div>

    <button type="submit">ログイン</button>
</form>
@endsection