<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ゴミ箱</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-white min-h-screen">
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">ゴミ箱</h1>

            <a
                href="{{ route('lineups.index') }}"
                class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded"
            >
                一覧へ戻る
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-600 text-white px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($lineups->isEmpty())
            <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 text-gray-300">
                削除済みのフォーメーションはありません。
            </div>
        @else
            <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-800 text-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left">タイトル</th>
                            <th class="px-4 py-3 text-left">フォーメーション</th>
                            <th class="px-4 py-3 text-left">人数</th>
                            <th class="px-4 py-3 text-left">削除日時</th>
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
                                    {{ $lineup->deleted_at?->format('Y/m/d H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <form
                                            method="POST"
                                            action="{{ route('lineups.restore', $lineup->id) }}"
                                            onsubmit="return confirm('「{{ e($lineup->title ?? '無題') }}」のフォーメーションを復元しますか？');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-xs"
                                            >
                                                復元
                                            </button>
                                        </form>
                                        <form
                                            method="POST"
                                            action="{{ route('lineups.force-delete', $lineup->id) }}"
                                            onsubmit="return confirm('「{{ e($lineup->title ?? '無題') }}」を完全削除しますか？\nこの操作は元に戻せません。');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-xs"
                                            >
                                                完全削除
                                            </button>
                                        </form>   
                                    </div>                             
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