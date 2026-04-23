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
        <h1 class="text-3xl font-bold mb-6 text-white">フォーメーション編集</h1>

        <div class="bg-gray-900 shadow-lg rounded-lg p-6 mb-6 border border-gray-800">
            <form method="GET" action="{{ route('play.index') }}">
                <label for="formation_template_id" class="block text-sm font-medium text-gray-300 mb-2">
                    フォーメーション選択
                </label>

                <div class="flex flex-wrap gap-3 items-center">
                    <select
                        name="formation_template_id"
                        id="formation_template_id"
                        class="border border-gray-700 rounded px-3 py-2 bg-gray-800 text-white"
                    >
                        @foreach ($templates as $template)
                            <option
                                value="{{ $template->id }}"
                                {{ $selectedTemplate && $selectedTemplate->id === $template->id ? 'selected' : '' }}
                            >
                                {{ $template->name }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded"
                    >
                        表示
                    </button>
                </div>
            </form>
        </div>

        @if ($selectedTemplate)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-gray-900 shadow-lg rounded-lg p-6 border border-gray-800">
                    <h2 class="text-lg font-bold mb-2 text-white">選択中のテンプレート</h2>
                    <p class="text-gray-300 mb-1">
                        <span class="font-semibold text-gray-100">名前:</span> {{ $selectedTemplate->name }}
                    </p>
                    <p class="text-gray-300 mb-4">
                        <span class="font-semibold text-gray-100">コード:</span> {{ $selectedTemplate->formation_code }}
                    </p>

                    <h3 class="text-md font-bold mb-3 text-white">スロット一覧</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-700 text-sm text-white">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="border border-gray-700 px-4 py-2">slot</th>
                                    <th class="border border-gray-700 px-4 py-2">role</th>
                                    <th class="border border-gray-700 px-4 py-2">x</th>
                                    <th class="border border-gray-700 px-4 py-2">y</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selectedTemplate->slots as $slot)
                                    <tr class="bg-gray-900">
                                        <td class="border border-gray-700 px-4 py-2 text-center">{{ $slot->slot }}</td>
                                        <td class="border border-gray-700 px-4 py-2 text-center">{{ $slot->role_label }}</td>
                                        <td class="border border-gray-700 px-4 py-2 text-center">{{ $slot->default_x }}</td>
                                        <td class="border border-gray-700 px-4 py-2 text-center">{{ $slot->default_y }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-gray-900 shadow-lg rounded-lg p-6 border border-gray-800">
                    <h2 class="text-lg font-bold mb-4 text-white">コート表示</h2>

                    <div style="display: flex; justify-content: center;">
                        <div
                            style="
                                position: relative;
                                width: 300px;
                                height: 500px;
                                background: #16a34a;
                                border: 4px solid white;
                                border-radius: 16px;
                                overflow: hidden;
                            "
                        >
                            <div
                                style="
                                    position: absolute;
                                    inset: 0;
                                    border: 2px solid white;
                                    border-radius: 16px;
                                "
                            ></div>

                            <div
                                style="
                                    position: absolute;
                                    left: 50%;
                                    top: 0;
                                    transform: translateX(-50%);
                                    width: 96px;
                                    height: 40px;
                                    border: 2px solid white;
                                    border-top: 0;
                                "
                            ></div>

                            <div
                                style="
                                    position: absolute;
                                    left: 50%;
                                    bottom: 0;
                                    transform: translateX(-50%);
                                    width: 96px;
                                    height: 40px;
                                    border: 2px solid white;
                                    border-bottom: 0;
                                "
                            ></div>

                            <div
                                style="
                                    position: absolute;
                                    left: 0;
                                    right: 0;
                                    top: 50%;
                                    border-top: 2px solid white;
                                "
                            ></div>

                            <div
                                style="
                                    position: absolute;
                                    left: 50%;
                                    top: 50%;
                                    transform: translate(-50%, -50%);
                                    width: 80px;
                                    height: 80px;
                                    border: 2px solid white;
                                    border-radius: 9999px;
                                "
                            ></div>

                            @foreach ($selectedTemplate->slots as $slot)
                                <div
                                    style="
                                        position: absolute;
                                        left: {{ $slot->default_x }}%;
                                        top: {{ $slot->default_y }}%;
                                        transform: translate(-50%, -50%);
                                        width: 56px;
                                        height: 56px;
                                        background: #2563eb;
                                        color: white;
                                        border-radius: 9999px;
                                        display: flex;
                                        flex-direction: column;
                                        align-items: center;
                                        justify-content: center;
                                        font-weight: bold;
                                        box-shadow: 0 4px 10px rgba(0,0,0,0.35);
                                        font-size: 12px;
                                        border: 2px solid rgba(255,255,255,0.25);
                                    "
                                >
                                    <div style="font-size: 10px; line-height: 1;">{{ $slot->role_label }}</div>
                                    <div style="font-size: 12px; line-height: 1; margin-top: 4px;">{{ $slot->slot }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="text-sm text-gray-400 mt-4 text-center">
                        いまはテンプレ座標を表示しています
                    </p>
                </div>
            </div>
        @endif
    </div>
</body>
</html>