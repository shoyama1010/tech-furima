# tech-furima

![スクリーンショット (315)](https://github.com/user-attachments/assets/efe1a434-1ee6-4cb5-ae76-2c849c63ab57)

アイテムの出品と購入を行うためのフリマアプリです。
出品されている商品を検索（部分一致検索）できます。またユーザーがログインしなくても、商品の詳細を閲覧することができます。

# 作成した目的
クライアントより自社でフリマサービスを持ちたいとの要望があった為、要望に添った機能を持つ、フリマサービスを構築するため作成しました。フロントエンドによりSPA化するため、Laravel API と React(TypeScript) を用いたフリマアプリにしました。フロントエンド（React）のリポジトリは下記です。

https://github.com/shoyama1010/frimaSite-frontend

# アプリケーションURL
ローカル環境
http://localhost

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

# 使用技術
- Laravel 8
- nginx 1.21.1
- php 7.4.9
- html
- css
- mysql 8.0.26
- メール認証：mailhog
- stripe
- storage（シンボリックリンク）
- Sanctum

# テーブル設計
![Image](https://github.com/user-attachments/assets/87df9928-f44d-4ce9-8684-85df3658eef8)

![Image](https://github.com/user-attachments/assets/8cc6c5e8-4cb3-40f2-906e-10153395ad60)

![Image](https://github.com/user-attachments/assets/40918133-0891-4935-9706-226072d6ba64)

# ER図

![Image](https://github.com/user-attachments/assets/f4168643-0bee-4211-ba6a-98725a6b484f)


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

# 各種機能について

## メール認証機能
・①会員登録後、②登録時に「認証用メール」を送信　③（mailhogでの）認証リンクのクリック　④ユーザーが受信したメール内のリンクをクリックとなってますが、
メールが送信された「お知らせ」は、今回「マイページ」にて表示させてます。（ユーザー向けに、プロフィール情報も続けて入力して貰えるように）

ローカル環境：http://localhost:8025

<img width="1764" height="923" alt="Image" src="https://github.com/user-attachments/assets/141e300c-644d-48bd-af26-542d79e50211" />

![Image](https://github.com/user-attachments/assets/1414af7e-2d49-422d-a64c-990cb4a0837f)

![Image](https://github.com/user-attachments/assets/a74ed594-11a9-4214-82cc-910035582ac3)

## コメント送信機能
・ユーザーがログインしている時のみ商品詳細ページの下部に表示されます。

## ユーザー情報変更機能
・プロフィール編集画面にて、ユーザー情報が編集画面上で変更されてから、マイページにて表示されます。
<img width="1776" height="918" alt="Image" src="https://github.com/user-attachments/assets/7b770cf2-160e-4f6c-9f1b-c4f0577e8fc2" />
<img width="1601" height="952" alt="スクリーンショット (5307)" src="https://github.com/user-attachments/assets/f30e299b-54e2-4508-933d-63512923973a" />


## 商品検索機能
・検索欄では「商品名」を入れると、部分一致検索で、興味ある商品が出てきます。「マイリスト」ページにて見れます。

## 商品購入機能
・商品を１つ選択してオーダーできます。購入方法は「支払い方法選択」にて、決済にておこないます。

・購入した住所も画面から変更できます。

・購入された商品は、「マイリスト」に登録されます。
![Image](https://github.com/user-attachments/assets/e2711372-f370-406c-96f3-28743e6fc4cd)
![Image](https://github.com/user-attachments/assets/14a19ba9-2e89-4e16-8e72-e1f769888145)
![Image](https://github.com/user-attachments/assets/7cc37d61-2a95-41e1-9ac0-ff4c9b6bce61)

## 決済機能
・実際に購入ボタンから、PAY.JPに登録して、そこからsripeの決済画面に接続されて、はじめて「購入」となります。
<img width="1775" height="961" alt="スクリーンショット (5301)" src="https://github.com/user-attachments/assets/4af2ac91-da38-4763-b636-2241f49b2313" />
<img width="1758" height="988" alt="スクリーンショット (5302)" src="https://github.com/user-attachments/assets/ead0a027-dde9-4460-be8c-630b54bed610" />

## いいね機能
・☆印を押して、「いいね」することができ、解除することもできます。「いいね」された商品は、「マイリスト」に登録されます。
<img width="1906" height="968" alt="スクリーンショット (5311)" src="https://github.com/user-attachments/assets/41d524bd-f21a-4353-bc91-5dd8d311ffc3" />
<img width="1920" height="951" alt="スクリーンショット (5312)" src="https://github.com/user-attachments/assets/5d7ec90f-3ddb-4b06-b1f9-d5bb78b93af7" />

## 出品商品情報登録機能
・.商品出品画面にて必要な情報（商品名、画像、カテゴリ、状態、商品説明）を登録できます。
<img width="1602" height="956" alt="スクリーンショット (5303)" src="https://github.com/user-attachments/assets/14e27e82-2744-4d68-adf9-430834286e2c" />
<img width="1607" height="957" alt="スクリーンショット (5304)" src="https://github.com/user-attachments/assets/b43ef1f0-a7ef-4124-b91c-a5ab00c9d6cf" />

・出品された商品は、商品一覧画面は勿論、「マイページ/プロフィール」の画面にも登録されます。
<img width="1768" height="908" alt="スクリーンショット (5305)" src="https://github.com/user-attachments/assets/9d177c6f-ed6e-4d19-8e16-2b130f94102f" />

## 画像アップロード機能
・出品画面から出品されるときに、商品画像（ローカル画像）を、ユーザー自身の画像（縮小済）からアップロードできます。
＊新規画像では、storage/app/public/item_imagesにてシンボリックリンクされております。





