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
        <h1 class="text-3xl font-bold mb-6">フォーメーション編集</h1>

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
                    <h2 class="text-lg font-bold mb-2">選手入力</h2>

                    <p class="text-gray-400 text-sm mb-4">
                        選手名と背番号を入力すると、右のコートに反映されます。
                    </p>

                    <div class="space-y-4">
                        @foreach ($selectedTemplate->slots as $slot)
                            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="font-bold">
                                        slot {{ $slot->slot }}
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        {{ $slot->role_label }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-300 mb-1">
                                        選手名
                                    </label>
                                    <input
                                        type="text"
                                        class="player-name w-full rounded px-3 py-2 bg-gray-950 border border-gray-700 text-white"
                                        data-slot="{{ $slot->slot }}"
                                        placeholder="例：遠藤"
                                    >
                                </div>
                            </div>
                        @endforeach                    
                    </div>
                </div>

                <div class="bg-gray-900 shadow-lg rounded-lg p-6 border border-gray-800">
                    <h2 class="text-lg font-bold mb-4">コート表示</h2>

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
                            <div style="position: absolute; inset: 0; border: 2px solid white; border-radius: 16px;"></div>

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

                            <div style="position: absolute; left: 0; right: 0; top: 50%; border-top: 2px solid white;"></div>

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
                                        width: 62px;
                                        height: 62px;
                                        background: #2563eb;
                                        color: white;
                                        border-radius: 9999px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        font-weight: bold;
                                        box-shadow: 0 4px 10px rgba(0,0,0,0.35);
                                        border: 2px solid rgba(255,255,255,0.25);
                                        font-size: 10px;
                                    "
                                >
                                    <div
                                        id="court-name-{{ $slot->slot }}"
                                        style="
                                            text-align: center;
                                            max-width: 56px;
                                            overflow: hidden;
                                            white-space: nowrap;
                                            text-overflow: ellipsis;
                                        "
                                    >
                                        {{ $slot->role_label }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="text-sm text-gray-400 mt-4 text-center">
                        入力内容はまだ保存されません
                    </p>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const nameInputs = document.querySelectorAll('.player-name');
            const numberInputs = document.querySelectorAll('.player-number');

            nameInputs.forEach((input) => {
                input.addEventListener('input', () => {
                    const slot = input.dataset.slot;
                    const target = document.getElementById(`court-name-${slot}`);

                    if (target) {
                        target.textContent = input.value || input.placeholder.replace('例：', '');
                    }
                });
            });

            numberInputs.forEach((input) => {
                input.addEventListener('input', () => {
                    const slot = input.dataset.slot;
                    const target = document.getElementById(`court-number-${slot}`);

                    if (target) {
                        target.textContent = input.value || slot;
                    }
                });
            });
        });
    </script>
</body>
</html>