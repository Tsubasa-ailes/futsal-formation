# TACT ― フットサルフォーメーションアプリ

フットサルの陣形（フォーメーション）をブラウザ上で作成・保存・管理するWebアプリケーション。

> **Note**: このREADMEは `infra_design.html`（インフラ設計書）の内容をもとに作成した下書きです。アプリのソースコード（`src/`配下）が本環境からまだ参照できていないため、アプリ機能の詳細やローカル起動コマンドなど一部項目は `TODO` として仮置きしています。ソース参照が可能になり次第、実装内容に合わせて更新してください。

## 目次

- [アプリ機能](#アプリ機能)
- [技術スタック](#技術スタック)
- [構成概要](#構成概要)
- [ローカル開発環境](#ローカル開発環境)
- [本番環境](#本番環境)
- [デプロイ手順](#デプロイ手順現状手動運用)
- [ネットワーク設計](#ネットワーク設計)
- [TLS / 証明書](#tls--証明書)
- [運用メモ](#運用メモ)

## アプリ機能

- フォーメーション作成：コート上で選手の配置・名前を入力
- 保存：タイトル・メモを添えてフォーメーションを保存
- 一覧・詳細：保存済みフォーメーションを一覧から確認・編集
- 削除／復元：フォーメーションの削除はゴミ箱経由で、削除後も復元が可能
- 認証：`login_id` / `password` によるログイン・ユーザー登録（bcryptハッシュ化）
- アクセス制御：フォーメーションの所有者チェック（IDOR対策）
- レート制限：ログイン・登録処理にレート制限あり

> **TODO**：画面構成・API仕様・バリデーションルールなど、実装の詳細は `src/routes`・`src/app` 等のソースを参照して追記する。

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

> **TODO**：起動コマンド（`docker compose up` 等）、環境変数（`.env`）のセットアップ手順、初回マイグレーション・シーディング手順など、具体的な手順は実際の運用手順に合わせて追記する。

## 本番環境

- AWS EC2（Ubuntu、ap-southeast-2 / シドニーリージョン）上でDocker Composeにより3コンテナ（`web`/`app`/`db`）を稼働
- 使用する構成ファイル: `docker-compose.prod.yml`
- Cloudflareをエッジに配置（DNSプロキシ、SSL/TLS: Full (Strict)）
- コンテナ障害時は `restart: unless-stopped` により自動再起動
- PostgreSQLデータは名前付き永続ボリューム（`dbdata`）で永続化。アプリ側も `laravel_storage`・`laravel_cache` を永続ボリューム化

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
>
> **TODO**：マイグレーション実行コマンド、ロールバック手順、デプロイ前後のヘルスチェック方法など、実際に使用しているスクリプト・運用手順があれば反映する。

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


詳細なインフラ構成・構成図は [`infra_design.html`](../infra_design.html) を参照。
