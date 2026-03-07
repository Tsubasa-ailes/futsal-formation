<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Futsal Formation</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    .court { width: 320px; height: 500px; border: 2px solid #111; border-radius: 12px; position: relative; background: #0b7; }
    .line { position:absolute; left:0; right:0; top:50%; height:2px; background:#fff; opacity:.8; }
    .circle { position:absolute; left:50%; top:50%; width:80px; height:80px; border:2px solid #fff; border-radius:999px; transform:translate(-50%,-50%); opacity:.8;}
    .p { position:absolute; width:80px; padding:6px 8px; background:#fff; border-radius:999px; text-align:center; font-size:12px; }
    .pos-gk { left:50%; top:86%; transform:translate(-50%,-50%); }
    .pos-2 { left:30%; top:65%; transform:translate(-50%,-50%); }
    .pos-3 { left:70%; top:65%; transform:translate(-50%,-50%); }
    .pos-4 { left:30%; top:30%; transform:translate(-50%,-50%); }
    .pos-5 { left:70%; top:30%; transform:translate(-50%,-50%); }
  </style>
</head>
<body style="padding:16px; font-family: sans-serif;">
  <h1 style="font-size:20px; font-weight:700;">フットサル フォーメーションメーカー</h1>

  <div style="display:flex; gap:24px; flex-wrap:wrap; margin-top:16px;">
    <div>
      <div style="margin-bottom:8px; font-weight:600;">フォーメーション</div>
      <label><input type="radio" name="formation" value="22" checked> 2-2</label><br>
      <label><input type="radio" name="formation" value="121"> 1-2-1</label><br>
      <label><input type="radio" name="formation" value="31"> 3-1</label>

      <div style="margin-top:16px; font-weight:600;">選手入力（5人）</div>
      <div style="margin-top:6px;">
        <div>1 GK <input data-player-input="1" placeholder="名前" style="border:1px solid #ccc; padding:6px; width:220px;"></div>
        <div style="margin-top:6px;">2 <input data-player-input="2" placeholder="名前" style="border:1px solid #ccc; padding:6px; width:220px;"></div>
        <div style="margin-top:6px;">3 <input data-player-input="3" placeholder="名前" style="border:1px solid #ccc; padding:6px; width:220px;"></div>
        <div style="margin-top:6px;">4 <input data-player-input="4" placeholder="名前" style="border:1px solid #ccc; padding:6px; width:220px;"></div>
        <div style="margin-top:6px;">5 <input data-player-input="5" placeholder="名前" style="border:1px solid #ccc; padding:6px; width:220px;"></div>
      </div>
    </div>

    <div>
      <div style="margin-bottom:8px; font-weight:600;">コート（ここを画像出力予定）</div>
      <div id="capture-area" class="court" data-formation-board>
        <div class="line"></div>
        <div class="circle"></div>

        <div class="p pos-gk" data-player-label="1">GK</div>
        <div class="p pos-2" data-player-label="2">2</div>
        <div class="p pos-3" data-player-label="3">3</div>
        <div class="p pos-4" data-player-label="4">4</div>
        <div class="p pos-5" data-player-label="5">5</div>
      </div>
    </div>
  </div>
</body>
</html>