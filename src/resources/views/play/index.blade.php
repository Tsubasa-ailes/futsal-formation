<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フォーメーション編集</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-white min-h-screen">
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold mb-6">フォーメーション編集</h1>
        <a
            href="{{ route('lineups.index') }}"
            class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded"
        >
            保存一覧
        </a>
    </div>
    {{-- フォーメーション選択 --}}
    <div class="bg-gray-900 p-6 rounded mb-6">
        <form method="GET" action="{{ route('play.index') }}">
            <select name="formation_template_id" class="bg-gray-800 text-white p-2 rounded">
                @foreach ($templates as $template)
                    <option value="{{ $template->id }}"
                        {{ $selectedTemplate && $selectedTemplate->id === $template->id ? 'selected' : '' }}>
                        {{ $template->name }}
                    </option>
                @endforeach
            </select>

            <button class="bg-blue-600 px-4 py-2 rounded ml-2">表示</button>
        </form>
    </div>

    @if ($selectedTemplate)

        {{-- 保存成功 --}}
        @if (session('success'))
            <div class="bg-green-600 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('play.store') }}" onsubmit="return confirm('このフォーメーションを保存しますか？');">
            @csrf

            <input type="hidden" name="formation_template_id" value="{{ $selectedTemplate->id }}">

            {{-- タイトル --}}
            <div class="mb-6">
                <input type="text" name="title"
                    placeholder="タイトル"
                    class="w-full bg-gray-800 p-2 rounded">
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- 入力欄 --}}
                <div>
                    @foreach ($selectedTemplate->slots as $slot)
                        <div class="bg-gray-800 p-4 mb-4 rounded">

                            <div class="mb-2 font-bold">
                                slot {{ $slot->slot }}（{{ $slot->role_label }}）
                            </div>

                            <input type="hidden" name="players[{{ $slot->slot }}][slot]" value="{{ $slot->slot }}">
                            <input type="hidden" name="players[{{ $slot->slot }}][x]" value="{{ $slot->default_x }}">
                            <input type="hidden" name="players[{{ $slot->slot }}][y]" value="{{ $slot->default_y }}">

                            <input
                                type="text"
                                name="players[{{ $slot->slot }}][display_name]"
                                class="player-name w-full bg-gray-900 p-2 rounded"
                                data-slot="{{ $slot->slot }}"
                                placeholder="選手名"
                                required
                            >
                        </div>
                    @endforeach
                </div>

                {{-- コート --}}
                <div style="display:flex; justify-content:center;">
                    <div style="
                        position: relative;
                        width: 300px;
                        height: 500px;
                        background: green;
                        border: 4px solid white;
                        border-radius: 16px;
                    ">

                        @foreach ($selectedTemplate->slots as $slot)
                            <div style="
                                position:absolute;
                                left: {{ $slot->default_x }}%;
                                top: {{ $slot->default_y }}%;
                                transform: translate(-50%, -50%);
                                width:60px;
                                height:60px;
                                background:#2563eb;
                                border-radius:50%;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                font-size:12px;
                            ">
                                <span id="court-name-{{ $slot->slot }}">
                                    {{ $slot->role_label }}
                                </span>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>

            <div class="mt-6">
                <button
                    type="submit" 
                    class="bg-green-600 px-6 py-3 rounded">
                    保存する
                </button>
            </div>

        </form>
    @endif
</div>

<script>
document.querySelectorAll('.player-name').forEach(input => {
    input.addEventListener('input', e => {
        let slot = e.target.dataset.slot;
        document.getElementById('court-name-' + slot).textContent = e.target.value;
    });
});
</script>

</body>
</html>