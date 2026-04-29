<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lineup->title }} | フォーメーション詳細</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-white min-h-screen">
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold">
                    {{ $lineup->title ?? '無題のフォーメーション' }}
                </h1>
                <p class="text-gray-400 mt-2">
                    フォーメーション: {{ $lineup->formation_code }}
                </p>
            </div>
            <div>
                <a
                    href="{{ route('lineups.edit', $lineup) }}"
                    class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded"
                >
                    編集
                </a>

                <a
                    href="{{ route('lineups.index') }}"
                    class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded"
                >
                    一覧へ戻る
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                <h2 class="text-lg font-bold mb-4">選手一覧</h2>

                <table class="min-w-full text-sm border border-gray-700">
                    <thead class="bg-gray-800 text-gray-300">
                        <tr>
                            <th class="border border-gray-700 px-4 py-2">slot</th>
                            <th class="border border-gray-700 px-4 py-2">選手名</th>
                            <th class="border border-gray-700 px-4 py-2">x</th>
                            <th class="border border-gray-700 px-4 py-2">y</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lineup->players as $player)
                            <tr>
                                <td class="border border-gray-700 px-4 py-2 text-center">
                                    {{ $player->slot }}
                                </td>
                                <td class="border border-gray-700 px-4 py-2 text-center font-semibold">
                                    {{ $player->display_name }}
                                </td>
                                <td class="border border-gray-700 px-4 py-2 text-center">
                                    {{ $player->x }}
                                </td>
                                <td class="border border-gray-700 px-4 py-2 text-center">
                                    {{ $player->y }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                <h2 class="text-lg font-bold mb-4">コート表示</h2>

                <div id="export-area" style="display: flex; justify-content: center;">
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
                                {{ $player->display_name }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <p class="text-sm text-gray-400 mt-4 text-center">
                    保存済みフォーメーションを表示しています
                </p>

                <div class="mt-6 text-center">
                    <button
                        type="button"
                        id="download-image"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded"
                    >
                        画像出力
                    </button>
                </div>            
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>    
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const button = document.getElementById('download-image');
        const exportArea = document.getElementById('export-area');

        if (!button || !exportArea) {
            return;
        }

        button.addEventListener('click', async () => {
            const confirmed = confirm('フォーメーション画像を出力しますか？');

            if(!confirmed) {
                return;
            }
            
            const canvas = await html2canvas(exportArea, {
                backgroundColor: '#111111',
                scale: 2,
            });

            const link = document.createElement('a');
            link.download = 'formation-{{ $lineup->id }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    });
    </script>
</body>
</html>