<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Futsal Formation</title>
  @vite(['resources/css/app.css', 'resources/js/play/index.js'])
</head>
<body class="p-4">
  <h1 class="text-xl font-bold">/play 動作確認</h1>

  <div class="mt-4">
    <label class="block font-semibold">フォーメーション</label>
    <select id="formation" class="border p-2">
      <option value="22">2-2</option>
      <option value="121">1-2-1</option>
      <option value="31">3-1</option>
    </select>
  </div>

  <div class="mt-4">
    <label class="block font-semibold">選手1</label>
    <input id="p1" class="border p-2" placeholder="名前">
  </div>

  <div class="mt-4 border p-4" id="capture-area">
    <div class="font-semibold">コート（仮）</div>
    <div class="mt-2">formation: <span id="formation-label">22</span></div>
    <div class="mt-2">p1: <span id="p1-label">未入力</span></div>
  </div>
</body>
</html>