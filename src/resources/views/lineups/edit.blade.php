<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lineup->title }} | フォーメーション編集</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-white min-h-screen">
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold">フォーメーション編集</h1>
                <p class="text-gray-400 mt-2">
                    フォーメーション: {{ $lineup->formation_code }}
                </p>
            </div>

            <a
                href="{{ route('lineups.show', $lineup) }}"
                class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded"
            >
                詳細へ戻る
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-600 text-white px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('lineups.update', $lineup) }}"
            onsubmit="return confirm('この内容で更新しますか？');"
        >
            @csrf
            @method('PUT')

            <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    タイトル
                </label>

                <input
                    type="text"
                    name="title"
                    value="{{ old('title', $lineup->title) }}"
                    class="w-full rounded px-3 py-2 bg-gray-950 border border-gray-700 text-white"
                    placeholder="例：4月練習試合 1本目"
                    maxlength="20"
                >
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                    <h2 class="text-lg font-bold mb-4">選手名編集</h2>

                    <div class="space-y-4">
                        @foreach ($lineup->players as $player)
                            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="font-bold">
                                        slot {{ $player->slot }}
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        x: {{ $player->x }} / y: {{ $player->y }}
                                    </div>
                                </div>

                                <input
                                    type="hidden"
                                    name="players[{{ $player->slot }}][id]"
                                    value="{{ $player->id }}"
                                >

                                <label class="block text-sm text-gray-300 mb-1">
                                    選手名
                                </label>

                                <input
                                    type="text"
                                    name="players[{{ $player->slot }}][display_name]"
                                    value="{{ old('players.' . $player->slot . '.display_name', $player->display_name) }}"
                                    class="player-name w-full rounded px-3 py-2 bg-gray-950 border border-gray-700 text-white"
                                    data-slot="{{ $player->slot }}"
                                    maxlength="20"
                                    required
                                >
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                    <h2 class="text-lg font-bold mb-4">コート表示</h2>

                    <div style="display: flex; justify-content: center;">
                        <div style="
                            position: relative;
                            width: 300px;
                            height: 500px;
                            background: #16a34a;
                            border: 4px solid white;
                            border-radius: 16px;
                            overflow: hidden;
                        ">
                            <div style="position: absolute; inset: 0; border: 2px solid white; border-radius: 16px;"></div>

                            <div style="
                                position: absolute;
                                left: 50%;
                                top: 0;
                                transform: translateX(-50%);
                                width: 96px;
                                height: 40px;
                                border: 2px solid white;
                                border-top: 0;
                            "></div>

                            <div style="
                                position: absolute;
                                left: 50%;
                                bottom: 0;
                                transform: translateX(-50%);
                                width: 96px;
                                height: 40px;
                                border: 2px solid white;
                                border-bottom: 0;
                            "></div>

                            <div style="position: absolute; left: 0; right: 0; top: 50%; border-top: 2px solid white;"></div>

                            <div style="
                                position: absolute;
                                left: 50%;
                                top: 50%;
                                transform: translate(-50%, -50%);
                                width: 80px;
                                height: 80px;
                                border: 2px solid white;
                                border-radius: 9999px;
                            "></div>

                            @foreach ($lineup->players as $player)
                                <div style="
                                    position: absolute;
                                    left: {{ $player->x }}%;
                                    top: {{ $player->y }}%;
                                    transform: translate(-50%, -50%);
                                    width: 62px;
                                    height: 62px;
                                    background: #2563eb;
                                    color: white;
                                    border-radius: 9999px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    text-align: center;
                                    font-weight: bold;
                                    font-size: 10px;
                                    box-shadow: 0 4px 10px rgba(0,0,0,0.35);
                                    border: 2px solid rgba(255,255,255,0.25);
                                    overflow: hidden;
                                    padding: 4px;
                                ">
                                    <span id="court-name-{{ $player->slot }}">
                                        {{ old('players.' . $player->slot . '.display_name', $player->display_name) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="text-sm text-gray-400 mt-4 text-center">
                        入力内容はリアルタイムでコートに反映されます
                    </p>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3 rounded"
                >
                    更新する
                </button>

                <a
                    href="{{ route('lineups.index', $lineup) }}"
                    class="bg-gray-700 hover:bg-gray-600 text-white font-bold px-6 py-3 rounded"
                >
                    キャンセル
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.player-name').forEach((input) => {
                input.addEventListener('input', (e) => {
                    const slot = e.target.dataset.slot;
                    const target = document.getElementById('court-name-' + slot);

                    if (target) {
                        target.textContent = e.target.value;
                    }
                });
            });
        });
    </script>
</body>
</html>