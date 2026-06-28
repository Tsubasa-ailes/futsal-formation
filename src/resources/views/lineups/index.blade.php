@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">保存フォーメーション一覧</h1>
            <div class="flex gap-2">
                <a href="{{ route('lineups.trash') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    ゴミ箱
                </a>
            </div>
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
                                    <div class="flex gap-2">
                                        <a href="{{ route('lineups.show', $lineup) }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-xs">
                                            詳細
                                        </a>
                                        <a href="{{ route('lineups.edit', $lineup) }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-xs">
                                            編集
                                        </a>
                                        <form method="POST" action="{{ route('lineups.destroy', $lineup) }}"
                                            onsubmit="return confirm('「{{ e($lineup->title ?? '無題') }}」を削除しますか？');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-xs">
                                                削除
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
@endsection
