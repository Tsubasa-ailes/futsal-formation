<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TACT</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
      padding: 16px;
      font-family: sans-serif;
    }

    .title {
      font-size: 20px;
      font-weight: 700;
      margin: 0;
    }

    .sub-title {
      font-size: 20px;
      font-weight: 700;
      margin: 4px 0 0;
    }

    .layout {
      display: flex;
      gap: 24px;
      flex-wrap: wrap;
      margin-top: 16px;
    }

    .section-title {
      margin-bottom: 8px;
      font-weight: 600;
    }

    .players-title {
      margin-top: 16px;
    }

    .players div {
      margin-top: 6px;
    }

    .players input {
      border: 1px solid #ccc;
      padding: 6px;
      width: 220px;
    }

    .court {
      width: 320px;
      height: 500px;
      border: 2px solid #111;
      border-radius: 12px;
      position: relative;
      background: #0b7;
    }

    .line {
      position: absolute;
      left: 0;
      right: 0;
      top: 50%;
      height: 2px;
      background: #fff;
      opacity: 0.8;
    }

    .circle {
      position: absolute;
      left: 50%;
      top: 50%;
      width: 80px;
      height: 80px;
      border: 2px solid #fff;
      border-radius: 999px;
      transform: translate(-50%, -50%);
      opacity: 0.8;
    }

    .p {
      position: absolute;
      width: 80px;
      padding: 6px 8px;
      background: #fff;
      border-radius: 999px;
      text-align: center;
      font-size: 12px;
      transform: translate(-50%, -50%);
      cursor: grab;
      user-select: none;
      touch-action: none;
    }

    .p.dragging {
      cursor: grabbing;
      z-index: 1000;
    }

    .pos-gk { left: 50%; top: 86%; }

    .pos-2 { left: 30%; top: 65%; }
    .pos-3 { left: 70%; top: 65%; }
    .pos-4 { left: 30%; top: 30%; }
    .pos-5 { left: 70%; top: 30%; }

    .pos-121-df  { left: 50%; top: 68%; }
    .pos-121-mf1 { left: 32%; top: 45%; }
    .pos-121-mf2 { left: 68%; top: 45%; }
    .pos-121-fw  { left: 50%; top: 23%; }

    .pos-31-df1 { left: 20%; top: 55%; }
    .pos-31-df2 { left: 50%; top: 55%; }
    .pos-31-df3 { left: 80%; top: 55%; }
    .pos-31-fw  { left: 50%; top: 23%; }

    .btn {
      margin-top: 12px;
      padding: 8px 14px;
      border: 1px solid #333;
      width: 100%;
      font-size: 16px;
      border-radius: 6px;
      background: #fff;
      cursor: pointer;
    }

    .btn:hover {
      background: #333;
      color: white;
    }
  </style>
</head>
<body>
  <h1 class="title">フットサル フォーメーションメーカー</h1>
  <h2 class="sub-title">TACT</h2>

  <div class="layout">
    <div class="left-panel">
      <div class="section-title">フォーメーション</div>
      <label><input type="radio" name="formation" value="22" checked> 2-2</label><br>
      <label><input type="radio" name="formation" value="121"> 1-2-1</label><br>
      <label><input type="radio" name="formation" value="31"> 3-1</label>

      <div class="section-title players-title">選手入力（5人）</div>
      <div class="players">
        <div>1 GK <input data-player-input="1" placeholder="名前"></div>
        <div>2 FP <input data-player-input="2" placeholder="名前"></div>
        <div>3 FP <input data-player-input="3" placeholder="名前"></div>
        <div>4 FP <input data-player-input="4" placeholder="名前"></div>
        <div>5 FP <input data-player-input="5" placeholder="名前"></div>
      </div>

      <button id="reset-btn" class="btn reset-btn">メンバーリセット</button>
      <button id="formation-reset-btn" class="btn reset-btn">陣形リセット</button>
    </div>

    <div class="right-panel">
      <div class="section-title">コート</div>

      <div id="capture-area" class="court">
        <div class="line"></div>
        <div class="circle"></div>

        <div class="p pos-gk" data-player-label="1">1</div>
        <div class="p pos-2" data-player-label="2">2</div>
        <div class="p pos-3" data-player-label="3">3</div>
        <div class="p pos-4" data-player-label="4">4</div>
        <div class="p pos-5" data-player-label="5">5</div>
      </div>

      <button id="export-btn" class="btn export-btn">画像保存</button>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
</body>
</html>