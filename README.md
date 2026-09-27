# TACT ― フットサルフォーメーションアプリ

フットサルの陣形（フォーメーション）をブラウザ上で作成・保存・管理するWebアプリケーション。

> **Note**: このREADMEは `infra_design.html`（インフラ設計書）の内容をもとに作成しています。

## 目次

- [アプリ機能](#アプリ機能)
- [使い方](#使い方)
- [技術スタック](#技術スタック)
- [構成概要](#構成概要)
- [ローカル開発環境](#ローカル開発環境)
- [本番環境](#本番環境)
- [デプロイ手順](#デプロイ手順現状手動運用)
- [ネットワーク設計](#ネットワーク設計)
- [TLS / 証明書](#tls--証明書)

## アプリ機能

- フォーメーション作成：コート上で選手の配置・名前を入力
- 保存：タイトル・メモを添えてフォーメーションを保存
- 一覧・詳細：保存済みフォーメーションを一覧から確認・編集
- 削除／復元：フォーメーションの削除はゴミ箱経由で、削除後も復元が可能
- 認証：`login_id` / `password` によるログイン・ユーザー登録（bcryptハッシュ化）
- アクセス制御：フォーメーションの所有者チェック（IDOR対策）
- レート制限：ログイン・登録処理にレート制限あり（`throttle:5,1`、1分間に5回まで）

### 画面一覧

| 画面 | ルート | 概要 |
|---|---|---|
| ログイン | `GET /login` `POST /login` | `login_id` / `password` によるログイン |
| 会員登録 | `GET /register` `POST /register` | ユーザー新規作成、登録後は自動ログイン |
| 戦術新規作成 | `GET /play` `POST /play/save` | 陣形テンプレートを選択して選手配置・名前を入力し保存 |
| 戦術一覧 | `GET /lineups` | ログインユーザーが保存したフォーメーション一覧 |
| 戦術詳細 | `GET /lineups/{lineup}` | 選手一覧・コート表示・画像出力・削除 |
| 戦術編集 | `GET /lineups/{lineup}/edit` `PUT /lineups/{lineup}` | 選手名・タイトルの編集 |
| 戦術ゴミ箱 | `GET /lineups/trash` `PATCH /lineups/{id}/restore` `DELETE /lineups/{id}/force-delete` | 削除済みフォーメーションの復元・完全削除 |

ルーティングの実体は `src/routes/web.php`、コントローラーは `src/app/Http/Controllers` を参照。

### 主なバリデーションルール

- 会員登録（`AuthController::register`）：`login_id` 必須・ユニーク、`name` 必須、`password` 8文字以上・確認一致
- フォーメーション保存（`PlayController::store`）：`formation_template_id` 必須・存在チェック、`title` 20文字以内、`note` 1000文字以内、選手ごとの `slot` / `display_name`（20文字以内）/ `x` / `y` 必須
- フォーメーション更新（`LineupController::update`）：`title` 20文字以内、選手ごとの `display_name` 20文字以内

### アクセス制御

`LineupController` は各操作の前に `authorizeOwner()` でログインユーザーとフォーメーションの所有者が一致するかを確認し、一致しない場合は403を返す（IDOR対策）。

## 使い方

### 1. 会員登録・ログイン

1. 初回は `/register`（会員登録画面）で、ログインID・ユーザー名・パスワード（8文字以上、確認入力あり）を入力して登録する
2. 登録すると自動的にログインされ、戦術一覧画面に移動する
3. 次回以降はログイン画面（`/login`）でログインID・パスワードを入力する
4. ログイン試行にはレート制限（1分間に5回まで）がかかっている

### 2. フォーメーションを新規作成する

1. ヘッダーの「新規作成」（`/play`）を開く
2. 陣形（フォーメーション）テンプレートをプルダウンから選んで「表示」を押すと、選手の配置がコートに表示される
3. 各ポジションの入力欄に選手名を入力する（入力すると同時にコート上の表示にも反映される）
4. 必要であればタイトル・メモを入力する
5. 「保存する」を押す（確認ダイアログが出る）と、戦術一覧に保存される

### 3. 保存済みフォーメーションを見る・編集する

1. ヘッダーの「保存一覧へ」（`/lineups`）で、自分が保存したフォーメーションの一覧を確認する
2. 「詳細」を押すと、選手一覧とコート表示を確認できる
   - 「ボール表示」「相手表示」ボタンでコート上の表示を切り替えられる
   - 「画像出力」ボタンでフォーメーション画像をPNGとしてダウンロードできる
3. 「編集」を押すと、選手名・タイトル・メモ・陣形テンプレートの変更ができる（「更新する」で保存）

### 4. 削除・復元

1. 詳細画面または一覧画面の「削除」を押すと、フォーメーションはゴミ箱に移動する（完全には削除されない）
2. 「ゴミ箱」（`/lineups/trash`）からゴミ箱内のフォーメーションを確認できる
   - 「復元」で一覧に戻せる
   - 「完全削除」で元に戻せない完全削除ができる（確認ダイアログあり）

### 5. ログアウト

ヘッダーの「ログアウト」から行う（確認ダイアログあり）。

## 技術スタック

| レイヤー | 技術 / サービス |
|---|---|
| クラウド / IaaS | AWS EC2（Ubuntu、ap-southeast-2 / シドニーリージョン） |
| CDN / エッジ | Cloudflare（DNSプロキシ、SSL/TLS: Full (Strict)、Origin CA証明書） |
| コンテナ基盤 | Docker / Docker Compose（開発: `docker-compose.yml`、本番: `docker-compose.prod.yml`） |
| Webサーバー | Nginx 1.25（alpine） |
| アプリケーション実行環境 | PHP 8.4（PHP-FPM） |
| アプリケーションフレームワーク | Laravel |
| データベース | PostgreSQL 17（Dockerコンテナ、名前付き永続ボリューム） |
| フロントエンドビルド | Vite + Tailwind CSS v4（本番はビルド済み静的アセットを配信、開発時のみViteサーバー稼働） |
| 認証方式 | 独自実装（login_id / password、bcryptハッシュ化、ログイン・登録にレート制限） |
| ソースコード管理 | GitHub（プライベートリポジトリ） |

## 構成概要

単一のAWS EC2インスタンス上でDocker Composeにより `web`（Nginx）・`app`（PHP-FPM / Laravel）・`db`（PostgreSQL）の3コンテナを稼働させ、Cloudflareをエッジ（DNS・TLS終端の一部・CDN）として前段に配置する構成。

```
利用者(ブラウザ) --HTTPS:443--> Cloudflare(エッジ/CDN) --HTTPS:443(Origin CA)--> EC2
                                                              │
                                          web(Nginx) --fastcgi:9000--> app(PHP-FPM/Laravel) --:5432--> db(PostgreSQL)
```

- コンテナ間通信（web→app→db）はDocker内部ネットワークに閉じており、外部から直接到達できない
- デプロイはGitHub（プライベートリポジトリ）からの手動 `git pull`
- 開発環境（ローカルWindows + Docker Desktop）と本番環境は、Compose定義ファイルを分離して管理
  - 開発用: `docker-compose.yml`
  - 本番用: `docker-compose.prod.yml`

開発環境と本番環境の主な差分：

- 本番はViteでビルド済みの静的アセット（`public/build`）をNginxが配信。node（Viteサーバー）は本番に含めない
- pgAdmin（DB管理画面）は開発専用。本番構成には含めない
- `db` の `:5432` は開発環境ではホストに公開しているが、本番ではコンテナ内部通信のみ
- 証明書パスも開発（Windowsローカルパス）と本番（`/etc/nginx/certs`）で異なる

## ローカル開発環境

- 前提: Windows + Docker Desktop
- 使用する構成ファイル: `docker-compose.yml`
- 開発時のみ公開されるポート: `:5432`（PostgreSQL直接接続）／`:5173`（Vite開発サーバー）／`:5050`（pgAdmin）

### 起動手順

1. リポジトリのルートと `src/` それぞれに `.env` を用意する（`.gitignore` 対象のため各自作成が必要）

   ```bash
   cp src/.env.example src/.env
   ```

   ルート直下の `.env` は `docker-compose.yml` が `${DB_DATABASE}` 等を展開するために使用する。`DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` を `src/.env` と同じ値で用意する。

2. コンテナを起動する

   ```bash
   docker compose up -d --build
   ```

3. アプリケーションキーを生成する

   ```bash
   docker compose exec app php artisan key:generate
   ```

4. マイグレーションとシーディングを実行する（陣形テンプレートは `FormationTemplateSeeder` で投入される）

   ```bash
   docker compose exec app php artisan migrate --seed
   ```

5. `http://localhost:8080` にアクセスする（Vite開発サーバーは `http://localhost:5173`、pgAdminは `http://localhost:5050`）

## 本番環境

- AWS EC2（Ubuntu、ap-southeast-2 / シドニーリージョン）上でDocker Composeにより3コンテナ（`web`/`app`/`db`）を稼働
- 使用する構成ファイル: `docker-compose.prod.yml`
- Cloudflareをエッジに配置（DNSプロキシ、SSL/TLS: Full (Strict)）
- コンテナ障害時は `restart: unless-stopped` により自動再起動
- PostgreSQLデータは名前付き永続ボリューム（`dbdata`）で永続化。アプリ側も `laravel_storage`・`laravel_cache` を永続ボリューム化

### リージョンについて

- 現在のリージョン（`ap-southeast-2` / シドニー）は意図して選定したものではなく、EC2作成時のコンソールの初期表示のまま構築したもの
- 利用者は日本在住を想定しており、シドニー経由だと本来不要な遅延（往復でおおよそ100〜150ms程度、経路や条件により変動）が発生している
- 移行は既存インスタンスのリージョンを直接変更する形ではなく、①東京に新規EC2を構築 → ②DBをバックアップして新環境にリストア → ③CloudflareのDNSを新インスタンスのIPへ切り替え → ④動作確認後に旧インスタンス（シドニー）を停止・終了、という手順で行う（証明書はドメイン単位で発行されているため東京側でも流用可能）。データ移行を伴うため、DB最終同期からDNS切り替えまでの間は短時間のダウンタイムが発生する

## デプロイ手順（現状：手動運用）

現時点ではCI/CDは未整備で、SSH接続＋手動 `git pull` ＋ `docker compose` 実行によるデプロイとなっている。

1. 管理者がSSHでEC2インスタンスに接続する（接続元IP制限の状況は要確認）
2. リポジトリの最新版を取得する

   ```bash
   git pull
   ```

3. `docker-compose.prod.yml` を指定してコンテナを再作成・起動する

   ```bash
   docker compose -f docker-compose.prod.yml up -d --build
   ```

4. ログを確認する

   ```bash
   docker compose -f docker-compose.prod.yml logs -f
   ```

> **注意**：設定ファイルの指定ミス（開発用/本番用Composeファイルの取り違え等）による障害が過去に発生しているため、`-f docker-compose.prod.yml` の指定を必ず確認すること。

5. マイグレーションを実行する（スキーマ変更を伴うリリース時のみ）

   ```bash
   docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
   ```

6. 動作確認する

   ```bash
   curl -kI https://localhost
   ```

   `HTTP/1.1 200` または `302` が返ればアプリまで疎通している。

## ネットワーク設計

本番環境で外部に公開しているポートはCloudflare経由の80／443のみ。コンテナ間通信（Nginx→PHP-FPM、PHP-FPM→PostgreSQL）はDocker Composeのデフォルトブリッジネットワーク内に閉じており、サービス名（`app`、`db`）による名前解決で到達する。

| 区間 | プロトコル / ポート | 用途 | 公開範囲 |
|---|---|---|---|
| 利用者 → Cloudflare | HTTPS :443 | Webアクセス | インターネット全体 |
| Cloudflare → EC2（web） | HTTPS :443（Origin CA証明書） | オリジンプル（Full Strict） | ⚠️ 要確認：セキュリティグループでCloudflare IP等への制限は未実施 |
| web → app | fastcgi :9000 | PHP実行 | Docker内部ネットワークのみ |
| app → db | PostgreSQL :5432 | データアクセス | Docker内部ネットワークのみ（外部非公開） |
| 管理者 → EC2 | SSH :22 | サーバー運用・デプロイ | ⚠️ 要確認：接続元IP制限の状況は未確認 |
| （開発環境のみ） | :5432 / :5173 / :5050 | DB直接接続 / Vite開発サーバー / pgAdmin | 開発機（ローカル）のみ。本番では非公開 |

## TLS / 証明書

Cloudflareの **Full（Strict）** モードを採用しているため、利用者⇔Cloudflare間・Cloudflare⇔オリジン間の両区間がHTTPSで暗号化される。

- オリジン側はCloudflareが発行するOrigin CA証明書（`cert.pem` / `key.pem`）をNginxに配置して検証させている
- 証明書マウント先（本番）: `/etc/nginx/certs`（読み取り専用でマウント）
- 秘密鍵はサーバー上で `chmod 600` により所有者のみ読み取り可能な状態にしている


詳細なインフラ構成・構成図は [`infra_design.html`](./infra_design.html) を参照。
