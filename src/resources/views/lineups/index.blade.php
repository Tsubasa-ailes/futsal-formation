<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>保存フォーメーション一覧</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-white min-h-screen">
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">保存フォーメーション一覧</h1>

            <a
                href="{{ route('play.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
            >
                新規作成
            </a>
        </div>

        @if ($lineups->isEmpty())
            <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 text-gray-300">
                まだ保存されたフォーメーションはありません。
            </div>
        @else
            <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-800 text-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left">タイトル</th>
                            <th class="px-4 py-3 text-left">フォーメーション</th>
                            <th class="px-4 py-3 text-left">人数</th>
                            <th class="px-4 py-3 text-left">保存日時</th>
                            <th class="px-4 py-3 text-left">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lineups as $lineup)
                            <tr class="border-t border-gray-800 hover:bg-gray-800">
                                <td class="px-4 py-3 font-semibold">
                                    {{ $lineup->title ?? '無題' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $lineup->formation_code }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $lineup->players_count }}人
                                </td>
                                <td class="px-4 py-3 text-gray-400">
                                    {{ $lineup->created_at->format('Y/m/d H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <a
                                        href="{{ route('lineups.show', $lineup) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-xs"
                                    >
                                        詳細
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>
</html>