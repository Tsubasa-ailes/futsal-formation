@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold">
                    {{ $lineup->title ?? '無題のフォーメーション' }}
                </h1>

                <p class="text-gray-300 mt-2">
                    フォーメーション:
                    <span class="font-bold text-white">
                        {{ $lineup->formation_code }}
                    </span>
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('lineups.edit', $lineup) }}"
                    class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    編集
                </a>

                <a href="{{ route('lineups.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    一覧へ戻る
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- 左：選手一覧 --}}
            <div class="bg-gray-900 rounded p-6">
                <h2 class="text-lg font-bold mb-4">
                    選手一覧
                </h2>

                @php
                    $roleLabels = [
                        'G' => 'ゴレイロ',
                        'F' => 'フィクソ',
                        'A' => 'アラ',
                        'P' => 'ピヴォ',
                    ];

                    $roleBySlot = $selectedTemplate
                        ? $selectedTemplate->slots->keyBy('slot')->map(function ($slot) use ($roleLabels) {
                            return $roleLabels[$slot->role_label] ?? $slot->role_label;
                        })
                        : collect();
                @endphp

                <div class="overflow-hidden rounded">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-800 text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-center">ポジション</th>
                                <th class="px-4 py-3 text-center">選手名</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($lineup->players as $player)
                                <tr class="border-t border-gray-800 hover:bg-gray-800">
                                    <td class="px-4 py-3 text-center text-gray-300">
                                        {{ $roleBySlot->get($player->slot, 'slot ' . $player->slot) }}
                                    </td>

                                    <td class="px-4 py-3 text-center font-semibold text-white">
                                        {{ $player->display_name }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($lineup->note)
                    <div class="mt-6 text-left">
                        <h3 class="mb-2 text-left text-lg font-bold">
                            メモ
                        </h3>

                        <div class="rounded bg-gray-800 p-4 text-left text-gray-100"
                            style="text-align: left; vertical-align: top;">
                            {!! nl2br(e(trim($lineup->note))) !!}
                        </div>
                    </div>
                @endif
            </div>

            {{-- 右：コート表示 --}}
            <div class="bg-gray-900 rounded p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold">
                        コート表示
                    </h2>

                    <div class="flex gap-2">
                        <button type="button" id="toggle-ball"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold px-4 py-2 rounded">
                            <span id="toggle-ball-text">ボール表示</span>
                        </button>

                        <button type="button" id="toggle-opponents"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold px-4 py-2 rounded">
                            <span id="toggle-opponents-text">相手表示</span>
                        </button>
                    </div>
                </div>

                <div id="export-area" style="display: flex; justify-content: center;">
                    <div id="court"
                        style="
                            position: relative;
                            width: 300px;
                            height: 500px;
                            background: #16a34a;
                            border: 4px solid white;
                            border-radius: 16px;
                            overflow: hidden;
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

                        <div
                            style="
                                position: absolute;
                                left: 0;
                                right: 0;
                                top: 50%;
                                border-top: 2px solid white;
                            ">
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

                        @php
                            /*
                                自チームは「陣形の形を崩さず」に自陣側へ寄せる。

                                - x座標はそのまま使う
                                - y座標の上下関係・距離感は維持する
                                - y座標だけ 54%〜88% の範囲に収める
                            */
                            $ownTop = 54;
                            $ownBottom = 88;

                            $playerYs = $lineup->players->pluck('y')->map(fn($y) => (float) $y);

                            $minY = $playerYs->min();
                            $maxY = $playerYs->max();
                        @endphp

                        @foreach ($lineup->players as $player)
                            @php
                                $originalX = (float) $player->x;
                                $originalY = (float) $player->y;

                                if ($maxY === $minY) {
                                    $displayY = 70;
                                } else {
                                    $displayY =
                                        $ownTop + (($originalY - $minY) / ($maxY - $minY)) * ($ownBottom - $ownTop);
                                }
                            @endphp

                            <div class="draggable-player" data-slot="{{ $player->slot }}"
                                style="
                                    position: absolute;
                                    left: {{ $originalX }}%;
                                    top: {{ $displayY }}%;
                                    transform: translate(-50%, -50%);
                                    width: 46.5px;
                                    height: 46.5px;
                                    background: #2563eb;
                                    color: white;
                                    border-radius: 9999px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    text-align: center;
                                    font-weight: bold;
                                    font-size: 8px;
                                    line-height: 1.2;
                                    box-shadow: 0 4px 10px rgba(0,0,0,0.35);
                                    border: 2px solid rgba(255,255,255,0.25);
                                    overflow: hidden;
                                    padding: 3px;
                                    cursor: grab;
                                    user-select: none;
                                    touch-action: none;
                                    z-index: 10;
                                ">
                                {{ $player->display_name }}
                            </div>
                        @endforeach @php
                            $opponentPlayers = [
                                ['no' => 1, 'x' => 50, 'y' => 10],
                                ['no' => 2, 'x' => 30, 'y' => 28],
                                ['no' => 3, 'x' => 70, 'y' => 28],
                                ['no' => 4, 'x' => 35, 'y' => 42],
                                ['no' => 5, 'x' => 65, 'y' => 42],
                            ];
                        @endphp

                        @foreach ($opponentPlayers as $opponent)
                            <div class="draggable-opponent" data-opponent-no="{{ $opponent['no'] }}"
                                style="
                                    position: absolute;
                                    left: {{ $opponent['x'] }}%;
                                    top: {{ $opponent['y'] }}%;
                                    transform: translate(-50%, -50%);
                                    width: 46.5px;
                                    height: 46.5px;
                                    background: #dc2626;
                                    color: white;
                                    border-radius: 9999px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    text-align: center;
                                    font-weight: bold;
                                    font-size: 8px;
                                    line-height: 1.2;
                                    box-shadow: 0 4px 10px rgba(0,0,0,0.35);
                                    border: 2px solid rgba(255,255,255,0.25);
                                    overflow: hidden;
                                    padding: 3px;
                                    cursor: grab;
                                    user-select: none;
                                    touch-action: none;
                                    z-index: 12;
                                    display: none;
                                ">
                                {{ $opponent['no'] }}
                            </div>
                        @endforeach
                        <img id="draggable-ball" src="{{ asset('images/ball.png') }}" alt="ball"
                            style="
                                position: absolute;
                                left: 50%;
                                top: 50%;
                                transform: translate(-50%, -50%);
                                width: 25px;
                                height: 25px;
                                border-radius: 50%;
                                cursor: grab;
                                user-select: none;
                                touch-action: none;
                                z-index: 25;
                                display: none;
                            ">
                    </div>
                </div>

                <p class="text-sm text-gray-400 mt-4 text-center">
                    保存済みフォーメーションを表示しています
                </p>

                <div class="mt-6 text-center">
                    <div class="flex gap-2 justify-center">
                        <button type="button" id="download-image"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded">
                            画像出力
                        </button>

                        <form method="POST" action="{{ route('lineups.destroy', $lineup) }}"
                            onsubmit="return confirm('「{{ e($lineup->title ?? '無題') }}」を削除しますか？');">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3 rounded">
                                戦術データ削除
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const downloadButton = document.getElementById('download-image');
            const court = document.getElementById('court');
            const ball = document.getElementById('draggable-ball');

            if (!downloadButton || !court) {
                return;
            }

            downloadButton.addEventListener('click', () => {
                const confirmed = confirm('フォーメーション画像を出力しますか？');

                if (!confirmed) {
                    return;
                }

                const canvas = document.createElement('canvas');
                const scale = 2;

                canvas.width = 300 * scale;
                canvas.height = 500 * scale;

                const ctx = canvas.getContext('2d');

                ctx.scale(scale, scale);

                // コート背景
                ctx.fillStyle = '#16a34a';
                ctx.fillRect(0, 0, 300, 500);

                // コート外枠
                ctx.strokeStyle = '#ffffff';
                ctx.lineWidth = 4;
                ctx.strokeRect(2, 2, 296, 496);

                // 内側ライン
                ctx.lineWidth = 2;
                ctx.strokeRect(8, 8, 284, 484);

                // 上ゴールエリア
                ctx.beginPath();
                ctx.moveTo(102, 0);
                ctx.lineTo(102, 40);
                ctx.lineTo(198, 40);
                ctx.lineTo(198, 0);
                ctx.stroke();

                // 下ゴールエリア
                ctx.beginPath();
                ctx.moveTo(102, 500);
                ctx.lineTo(102, 460);
                ctx.lineTo(198, 460);
                ctx.lineTo(198, 500);
                ctx.stroke();

                // センターライン
                ctx.beginPath();
                ctx.moveTo(0, 250);
                ctx.lineTo(300, 250);
                ctx.stroke();

                // センターサークル
                ctx.beginPath();
                ctx.arc(150, 250, 40, 0, Math.PI * 2);
                ctx.stroke();

                // 自チーム選手を描画
                document.querySelectorAll('.draggable-player').forEach((player) => {
                    const left = parseFloat(player.style.left);
                    const top = parseFloat(player.style.top);

                    const x = 300 * left / 100;
                    const y = 500 * top / 100;

                    const name = player.textContent.trim();

                    // 選手丸
                    ctx.beginPath();
                    ctx.fillStyle = '#2563eb';
                    ctx.arc(x, y, 31, 0, Math.PI * 2);
                    ctx.fill();

                    ctx.lineWidth = 2;
                    ctx.strokeStyle = 'rgba(255,255,255,0.35)';
                    ctx.stroke();

                    // 選手名
                    ctx.fillStyle = '#ffffff';
                    ctx.font = 'bold 10px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    drawWrappedText(ctx, name, x, y, 50, 12);
                });

                // 相手チーム選手を描画
                document.querySelectorAll('.draggable-opponent').forEach((opponent) => {
                    if (opponent.style.display === 'none') {
                        return;
                    }

                    const left = parseFloat(opponent.style.left);
                    const top = parseFloat(opponent.style.top);

                    const x = 300 * left / 100;
                    const y = 500 * top / 100;

                    const name = opponent.textContent.trim();

                    ctx.beginPath();
                    ctx.fillStyle = '#dc2626';
                    ctx.arc(x, y, 31, 0, Math.PI * 2);
                    ctx.fill();

                    ctx.lineWidth = 2;
                    ctx.strokeStyle = 'rgba(255,255,255,0.35)';
                    ctx.stroke();

                    ctx.fillStyle = '#ffffff';
                    ctx.font = 'bold 10px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    drawWrappedText(ctx, name, x, y, 50, 12);
                });

                // ボールを描画
                if (ball && ball.style.display !== 'none') {
                    const left = parseFloat(ball.style.left);
                    const top = parseFloat(ball.style.top);

                    const x = 300 * left / 100;
                    const y = 500 * top / 100;

                    ctx.beginPath();
                    ctx.fillStyle = '#ffffff';
                    ctx.arc(x, y, 12, 0, Math.PI * 2);
                    ctx.fill();

                    ctx.lineWidth = 2;
                    ctx.strokeStyle = '#111111';
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.fillStyle = '#111111';
                    ctx.arc(x, y, 4, 0, Math.PI * 2);
                    ctx.fill();
                }

                const link = document.createElement('a');
                link.download = 'formation-{{ $lineup->id }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });

            function drawWrappedText(ctx, text, x, y, maxWidth, lineHeight) {
                const chars = text.split('');
                const lines = [];
                let line = '';

                chars.forEach((char) => {
                    const testLine = line + char;
                    const width = ctx.measureText(testLine).width;

                    if (width > maxWidth && line !== '') {
                        lines.push(line);
                        line = char;
                    } else {
                        line = testLine;
                    }
                });

                lines.push(line);

                const startY = y - ((lines.length - 1) * lineHeight) / 2;

                lines.forEach((lineText, index) => {
                    ctx.fillText(lineText, x, startY + index * lineHeight);
                });
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const toggleBallButton = document.getElementById('toggle-ball');
            const toggleBallText = document.getElementById('toggle-ball-text');
            const ball = document.getElementById('draggable-ball');

            if (toggleBallButton && toggleBallText && ball) {
                let isBallVisible = false;

                toggleBallButton.addEventListener('click', () => {
                    if (isBallVisible) {
                        ball.style.display = 'none';
                        toggleBallText.textContent = 'ボール表示';

                        toggleBallButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                        toggleBallButton.classList.add('bg-gray-600', 'hover:bg-gray-700');

                        isBallVisible = false;
                    } else {
                        ball.style.display = 'block';
                        toggleBallText.textContent = 'ボール非表示';

                        toggleBallButton.classList.remove('bg-gray-600', 'hover:bg-gray-700');
                        toggleBallButton.classList.add('bg-blue-600', 'hover:bg-blue-700');

                        isBallVisible = true;
                    }
                });
            }

            const toggleOpponentsButton = document.getElementById('toggle-opponents');
            const toggleOpponentsText = document.getElementById('toggle-opponents-text');
            const opponents = document.querySelectorAll('.draggable-opponent');

            if (toggleOpponentsButton && toggleOpponentsText && opponents.length > 0) {
                let areOpponentsVisible = false;

                toggleOpponentsButton.addEventListener('click', () => {
                    if (areOpponentsVisible) {
                        opponents.forEach((opponent) => {
                            opponent.style.display = 'none';
                        });

                        toggleOpponentsText.textContent = '相手表示';

                        toggleOpponentsButton.classList.remove('bg-red-600', 'hover:bg-red-700');
                        toggleOpponentsButton.classList.add('bg-gray-600', 'hover:bg-gray-700');

                        areOpponentsVisible = false;
                    } else {
                        opponents.forEach((opponent) => {
                            opponent.style.display = 'flex';
                        });

                        toggleOpponentsText.textContent = '相手非表示';

                        toggleOpponentsButton.classList.remove('bg-gray-600', 'hover:bg-gray-700');
                        toggleOpponentsButton.classList.add('bg-red-600', 'hover:bg-red-700');

                        areOpponentsVisible = true;
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const court = document.getElementById('court');
            let activeItem = null;

            if (!court) {
                return;
            }

            document.querySelectorAll('.draggable-player, .draggable-opponent, #draggable-ball').forEach((item) => {
                item.addEventListener('pointerdown', (e) => {
                    activeItem = item;
                    activeItem.style.cursor = 'grabbing';
                    activeItem.style.zIndex = 50;
                    e.preventDefault();
                });
            });

            document.addEventListener('pointermove', (e) => {
                if (!activeItem) {
                    return;
                }

                const rect = court.getBoundingClientRect();

                let x = e.clientX - rect.left;
                let y = e.clientY - rect.top;

                x = Math.max(0, Math.min(x, rect.width));
                y = Math.max(0, Math.min(y, rect.height));

                const xPercent = (x / rect.width) * 100;
                const yPercent = (y / rect.height) * 100;

                activeItem.style.left = xPercent.toFixed(2) + '%';
                activeItem.style.top = yPercent.toFixed(2) + '%';
            });

            document.addEventListener('pointerup', () => {
                if (!activeItem) {
                    return;
                }

                activeItem.style.cursor = 'grab';
                activeItem.style.zIndex = activeItem.id === 'draggable-ball' ? 25 : 10;
                activeItem = null;
            });
        });
    </script>
@endpush
