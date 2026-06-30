@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold">フォーメーション編集</h1>
                <p class="mt-2 text-sm text-gray-300">
                    現在のフォーメーション:
                    <span class="font-bold text-white">
                        {{ $selectedTemplate->formation_code }}
                    </span>
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('lineups.show', $lineup) }}"
                    class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    詳細
                </a>

                <a href="{{ route('lineups.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    一覧へ戻る
                </a>
            </div>
        </div>

        {{-- フォーメーション選択 --}}
        <div class="bg-gray-900 p-6 rounded mb-6">
            <form method="GET" action="{{ route('lineups.edit', $lineup) }}">
                <select name="formation_code" class="bg-gray-800 text-white p-2 rounded">
                    @foreach ($formationTemplates as $template)
                        <option value="{{ $template->formation_code }}"
                            {{ $selectedTemplate && $selectedTemplate->formation_code === $template->formation_code ? 'selected' : '' }}>
                            {{ $template->name ?? $template->formation_code }}
                        </option>
                    @endforeach
                </select>

                <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded ml-2">
                    表示
                </button>
            </form>
        </div>

        @if ($errors->any())
            <div class="bg-red-600 p-3 mb-4 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-600 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @php
            $roleLabels = [
                'G' => 'ゴレイロ',
                'F' => 'フィクソ',
                'A' => 'アラ',
                'P' => 'ピヴォ',
            ];

            $playersBySlot = $lineup->players->keyBy('slot');
        @endphp

        <form method="POST" action="{{ route('lineups.update', $lineup) }}" onsubmit="return confirm('この内容で更新しますか？');">
            @csrf
            @method('PUT')

            <input type="hidden" name="formation_code" value="{{ $selectedTemplate->formation_code }}">

            {{-- タイトル --}}
            <div class="mb-6">
                <input type="text" name="title" value="{{ old('title', $lineup->title) }}" placeholder="タイトル"
                    class="w-full bg-gray-800 p-2 rounded" maxlength="20">
            </div>

            {{-- メモ --}}
            <div class="mb-6">
                <textarea name="note" rows="4" placeholder="メモ・戦術意図・注意点など" class="w-full bg-gray-800 p-2 rounded">{{ old('note', $lineup->note) }}</textarea>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- 入力欄 --}}
                <div>
                    @foreach ($selectedTemplate->slots as $slot)
                        @php
                            $player = $playersBySlot->get($slot->slot);
                            $roleName = $roleLabels[$slot->role_label] ?? $slot->role_label;
                        @endphp

                        <div class="bg-gray-800 p-4 mb-4 rounded">
                            <div class="mb-2 font-bold">
                                slot {{ $slot->slot }}（{{ $roleName }}）
                            </div>

                            <input type="hidden" name="players[{{ $slot->slot }}][id]" value="{{ $player?->id }}">

                            <input type="hidden" name="players[{{ $slot->slot }}][slot]" value="{{ $slot->slot }}">

                            <input type="hidden" name="players[{{ $slot->slot }}][x]" value="{{ $slot->default_x }}">

                            <input type="hidden" name="players[{{ $slot->slot }}][y]" value="{{ $slot->default_y }}">

                            <input type="text" name="players[{{ $slot->slot }}][display_name]"
                                value="{{ old('players.' . $slot->slot . '.display_name', $player?->display_name) }}"
                                class="player-name w-full bg-gray-900 p-2 rounded" data-slot="{{ $slot->slot }}"
                                placeholder="選手名" required>
                        </div>
                    @endforeach
                </div>

                {{-- コート --}}
                <div style="display:flex; justify-content:center;">
                    <div
                        style="
                            position: relative;
                            width: 300px;
                            height: 500px;
                            background: green;
                            border: 4px solid white;
                            border-radius: 16px;
                        ">
                        <div style="position: absolute; inset: 0; border: 2px solid white; border-radius: 16px;">
                        </div>

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
                            ">
                        </div>

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
                            ">
                        </div>

                        <div style="position: absolute; left: 0; right: 0; top: 50%; border-top: 2px solid white;">
                        </div>

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
                            ">
                        </div>

                        @foreach ($selectedTemplate->slots as $slot)
                            @php
                                $player = $playersBySlot->get($slot->slot);
                            @endphp

                            <div
                                style="
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
                                    color:white;
                                    text-align:center;
                                    font-weight:bold;
                                    padding:4px;
                                    overflow:hidden;
                                ">
                                <span id="court-name-{{ $slot->slot }}"
                                    style="
                                        display:block;
                                        max-width:52px;
                                        line-height:1.2;
                                        overflow-wrap:anywhere;
                                        word-break:break-word;
                                    ">
                                    {{ old('players.' . $slot->slot . '.display_name', $player?->display_name) ?: $slot->slot }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="bg-green-600 hover:bg-green-700 px-6 py-3 rounded">
                    更新する
                </button>

                <a href="{{ route('lineups.index') }}" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded">
                    キャンセル
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.player-name').forEach(input => {
            input.addEventListener('input', e => {
                let slot = e.target.dataset.slot;
                document.getElementById('court-name-' + slot).textContent = e.target.value;
            });
        });
    </script>
@endpush
