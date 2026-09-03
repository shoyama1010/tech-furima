# tech-furima

<img width="1903" height="913" alt="スクリーンショット (6233)" src="https://github.com/user-attachments/assets/eccbc669-51c8-436c-8cdb-b1967c1481a7" />

# アプリ概要

tech-furima は、Laravel と React（TypeScript）を用いて開発した、メルカリ風のフリマアプリです。

LaravelをAPIサーバーとして利用し、ReactによるSPA（Single Page Application）でユーザー体験を向上させました。

また、本番環境では Railway（バックエンド）へデプロイし、実際の公開・運用までを想定した構成になっています。

# 作成した目的

以前にLaravel Bladeで実装したフリマアプリを、Laravel API + React（TypeScript）によるSPAへ再構築し、より実務に近い構成で開発・公開まで経験することを目的として制作しました。

- API設計を意識したバックエンド実装
- ReactによるSPA化やCookie認証からトークン認証への移行
- Railway・Vercelへの本番デプロイ

フロントエンド（React）のリポジトリは ⇒ https://github.com/shoyama1010/frimaSite-frontend

# アプリケーションURL
ローカル環境

http://localhost

本番環境（Railway）

https://tech-furima-production.up.railway.app

- ログインする時は、デモ用アカウントでログインし、機能を試してください。
- メールアドレス：test@example.com、パスワード：password

# 機能一覧

- 会員登録
- ログイン / ログアウト
- 商品一覧表示
- 商品詳細表示
- 商品出品
- いいね機能
- コメント機能
- マイページ
- 画像アップロード
- 検索機能

# 各種機能（画面構成）について

## メール認証機能
①会員登録後、②登録時に「認証用メール」を送信　

<img width="1752" height="878" alt="スクリーンショット (6255)" src="https://github.com/user-attachments/assets/f89d4ce2-8bd5-4f16-a6df-e3063e1ed0a1" />
<img width="1754" height="904" alt="スクリーンショット (6258)" src="https://github.com/user-attachments/assets/57b20352-1e98-48ea-b072-9cdb55198acb" />

③ ローカル環境では、http://localhost:8025　でアクセスし、（mailhogでの）認証リンクのクリック　

<img width="1872" height="893" alt="スクリーンショット (6259)" src="https://github.com/user-attachments/assets/78dabf6d-923a-4f21-96e1-26eeb82ff03b" />
<img width="1785" height="885" alt="スクリーンショット (6260)" src="https://github.com/user-attachments/assets/dc8f4673-532e-4c78-bda0-e14d95452f0f" />

④メール送信の通知先画面は、今回「マイページ」にて表示させてます。（ユーザー向けに、プロフィール情報も続けて入力して貰えるように）

<img width="1899" height="955" alt="スクリーンショット (6261)" src="https://github.com/user-attachments/assets/d470ad32-716b-4337-beb2-27c7c864d058" />

## コメント送信機能
・ユーザーがログインしている時のみ商品詳細ページの下部に表示されます。

## ユーザー情報変更機能
・プロフィール編集画面にて、ユーザー情報が編集画面上で変更されてから、マイページにて表示されます。
<img width="1895" height="898" alt="スクリーンショット (6237)" src="https://github.com/user-attachments/assets/deff0a9c-52e8-4cd2-a85a-35dd20b6c3f9" />
<img width="1901" height="907" alt="スクリーンショット (6238)" src="https://github.com/user-attachments/assets/15cda466-090a-4dfa-b6bc-739aa1686e63" />

## 商品検索機能
・検索欄では「商品名」を入れると、部分一致検索で、興味ある商品が出てきます。「マイリスト」ページにて見れます。

## 商品購入機能
・商品を１つ選択してオーダーできます。購入方法は「支払い方法選択」にて、次の「決済機能」にておこないます。
<img width="1849" height="905" alt="スクリーンショット (6248)" src="https://github.com/user-attachments/assets/9e762412-ab70-429e-9219-d5e86d89ba54" />

・購入された商品は、「マイリスト」に登録されます。
<img width="1768" height="888" alt="スクリーンショット (6245)" src="https://github.com/user-attachments/assets/16fc5184-2f9e-4ee4-989d-8b29a0c71b4a" />

## 決済機能
・実際に購入ボタンから、PAY.JPに登録して、そこからstripeの決済画面に接続されて、はじめて「購入」となります。
<img width="1904" height="907" alt="スクリーンショット (6250)" src="https://github.com/user-attachments/assets/7c8041a8-bd24-462d-a6da-b2f6fea7209c" />
<img width="1714" height="938" alt="スクリーンショット (6251)" src="https://github.com/user-attachments/assets/5943e5b0-97a8-4904-b661-61d3d8d0408a" />

## いいね機能
・☆印を押して、「いいね」することができ、解除することもできます。「いいね」された商品は、「マイリスト」に登録されます。
<img width="1858" height="898" alt="スクリーンショット (6253)" src="https://github.com/user-attachments/assets/0f5d7d34-efea-4051-9566-7c3d1cf74333" />
<img width="1764" height="881" alt="スクリーンショット (6254)" src="https://github.com/user-attachments/assets/cb6eb4fa-e328-43d1-be1d-667872b5adc7" />

## 出品商品情報登録機能
・.商品出品画面にて必要な情報（商品名、画像、カテゴリ、状態、商品説明）を登録できます。
<img width="1655" height="892" alt="スクリーンショット (6240)" src="https://github.com/user-attachments/assets/2bb7ae1e-7c4a-4562-8170-b1bf29196a9e" />

・出品された商品は、商品一覧画面は勿論、「マイページ/プロフィール」の画面にも登録されます。
<img width="1768" height="888" alt="スクリーンショット (6245)" src="https://github.com/user-attachments/assets/41cbc801-b289-4097-bf2f-60af4ad4330c" />

## 画像アップロード機能
・出品画面から出品されるときに、商品画像（ローカル画像）を、ユーザー自身の画像（縮小済）からアップロードできます。

＊新規画像では、storage/app/public/item_imagesにてシンボリックリンクされております。


# 使用技術
- Laravel 8
- Nginx 1.21.1
- PHP 7.4.9
- html
- css
- mysql 8.0.26
- メール認証：mailhog
- stripe
- storage（シンボリックリンク）
- Sanctum
- Railway

# テーブル設計
<img width="1539" height="660" alt="スクリーンショット (5318)" src="https://github.com/user-attachments/assets/cc224ddf-cefe-4294-afbd-dda08e8fcb4d" />
<img width="1553" height="633" alt="スクリーンショット (5320)" src="https://github.com/user-attachments/assets/435f3a3c-b683-49b1-824e-bb9a3eacd1c2" />

# ER図
<img width="1024" height="763" alt="スクリーンショット (5317)" src="https://github.com/user-attachments/assets/6c6f27e1-a800-46ce-b7ed-5bf4d82fa0a4" />

# 環境構築
## 1 Gitファイルをクローンする

git clone https://github.com/shoyama1010/tech-furima.git

## 2 Dockerコンテナを作成する

docker-compose up -d --build

## 3 Laravelパッケージをインストールする

docker-compose exec php bash
- PHPコンテナにログインし

composer install

## 4 .envファイルを作成する

PHPコンテナにログインした状態で

cp .env.example .env

作成した.envファイルの該当欄を下記のように変更

DB_HOST=mysql

DB_DATABASE=laravel_db

DB_USERNAME=laravel_user

MAIL_MAILER=smtp

MAIL_HOST=mailhog

MAIL_PORT=1025

MAIL_USERNAME=null

MAIL_PASSWORD=null

MAIL_ENCRYPTION=null

MAIL_FROM_ADDRESS=noreply@example.com 

MAIL_FROM_NAME="laravel"

.envファイルの最後に追加

STRIPE_KEY=stripeで取得した公開キー

STRIPE_SECRET=stripeで取得したシークレットキー

DB_PASSWORD=laravel_pass

認証：fortifyパッケージ追加

バリデーション機能：Formrequest使用

.envファイルの最後に追加

## 5 テーブルの作成

docker-compose exec php bash
- PHPコンテナにログインし(ログインしたままであれば上記コマンドは実行しなくて良いです)

php artisan migrate

## 6 ダミーデータ作成

PHPコンテナにログインした状態で

php artisan db:seed

## 7 アプリケーション起動キーの作成

PHPコンテナにログインした状態で

php artisan key:generate

## 8 シンボリックリンクの作成

PHPコンテナにログインした状態で

php artisan storage:link

## 9 API一覧
| Method | URL | 内容 |

|---|---|---|

| POST | /api/login | ログイン |

| POST | /api/logout | ログアウト |

| GET | /api/items | 商品一覧 |

| GET | /api/items/{id} | 商品詳細 |

| POST | /api/items/{id}/toggle-like | いいね切替 |

## 10 認証について
- Laravel Sanctum を用いた Token認証を実装しています。
- ログイン成功時に Token を発行し、React側の localStorage に保持しています。
- API通信時は Authorization Bearer Token により認証を行っています。

# テスト

本アプリでは、PHPUnit を使用して Feature テストおよび Unit テストを実装しています。

## テスト用データベースの作成

docker-compose exec mysql mysql -u root -p

CREATE DATABASE tech_furima_test

## 2 .env.testing の設定例

APP_ENV=testing

DB_CONNECTION=mysql

DB_HOST=mysql

DB_PORT=3306

DB_DATABASE=tech_furima_test

DB_USERNAME=root

DB_PASSWORD=root

CREATE DATABASE tech_furima_test

## 2 .env.testing の設定例

APP_ENV=testing

DB_CONNECTION=mysql

DB_HOST=mysql

DB_PORT=3306

DB_DATABASE=tech_furima_test

DB_USERNAME=root

DB_PASSWORD=root

## 3 テスト用APP_KEY生成

docker-compose exec php bash

php artisan key:generate --env=testing

## 4 テスト用DBのマイグレーション

php artisan migrate --env=testing

## 5 テスト実行

php artisan test

実行結果： Tests: 35 passed

## 工夫した点　

### 1.APIとして利用しやすいレスポンス設計

React側で扱いやすいように、商品一覧・商品詳細APIでは必要なデータだけをJSON形式で返すようにしました。

### 2. Laravel SanctumによるToken認証

React SPAからログインできるように、Laravel Sanctumを用いたToken認証を実装しました。

ログイン成功時にアクセストークンを発行し、認証が必要なAPIではBearer Tokenを使ってログインユーザーを判定しています。

### 3. 認証が必要なAPIの保護

いいね機能など、ログインユーザーに紐づく処理は `auth:sanctum` ミドルウェアで保護しています。

これにより、未ログイン状態ではいいね登録ができないようにし、ログインユーザー本人の操作としてDBに保存されるようにしました。

### 4. いいね機能のトグル処理

いいね機能では、すでにいいね済みの場合は削除し、未いいねの場合は登録するトグル処理を実装しました。

これにより、フロント側では同じAPIを呼び出すだけで、いいね追加・解除の両方に対応できます。

### 5. N+1問題を意識したリレーション取得

商品詳細APIでは、コメント・コメント投稿者・カテゴリー・いいね数を取得するために、Eager Loadingや `withCount` を活用しています。

必要な関連データを事前に取得することで、不要なSQL発行を抑えるようにしています。

### 6. 画像URLの扱い

商品画像はDBに保存されたURLをAPIレスポンスとして返し、React側でそのまま表示できる形式に整えました。

Seeder画像とアップロード画像の表示形式を意識し、フロント側で扱いやすい `image_url` として返却しています。

### 7. APIとBlade画面の共存

既存のLaravel Bladeによる画面を残しつつ、Reactから利用するAPIを追加実装しました。

これにより、従来のLaravelアプリを段階的にSPA化できる構成にしています。

## 今後の課題
- マイページ機能の強化
- Tokenの永続認証改善
- Redux / ContextAPI による状態管理

