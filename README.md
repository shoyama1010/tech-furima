# tech-furima

![スクリーンショット (315)](https://github.com/user-attachments/assets/efe1a434-1ee6-4cb5-ae76-2c849c63ab57)

アイテムの出品と購入を行うためのフリマアプリです。
出品されている商品を検索（カテゴリ選択ではなく、部分一致検索）できます。また、商品の詳細を閲覧することができます。

# 作成した目的
クライアントより自社でフリマサービスを持ちたいとの要望があった為、要望に添った機能を持つフリマサービスを構築するため作成しました。

# アプリケーションURL
ローカル環境
http://localhost

# 機能一覧

・メール認証機能
・コメント送信機能
・ユーザー情報変更機能
・商品検索機能
・商品購入機能
・決済機能
・いいね機能
・画像アップロード機能
・出品商品情報登録機能

# 使用技術
・Laravel 8
・nginx 1.21.1
・php 7.4.9
・html
・css
・mysql 8.0.26
・メール認証：mailhog
・stripe
・storage

# テーブル設計


# ER図

https://app.diagrams.net/?src=about#W5b3a97a76e9aeee3%2F5B3A97A76E9AEEE3!163095#%7B%22pageId%22%3A%22R2lEEEUBdFMjLlhIrx00%22%7D

# 環境構築
1 Gitファイルをクローンする
git clone https://github.com/shoyama1010/tech-furima.git

2 Dockerコンテナを作成する
docker-compose up -d --build

3 Laravelパッケージをインストールする
docker-compose exec php bash
でPHPコンテナにログインし
composer install

4 .envファイルを作成する
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

5 テーブルの作成
docker-compose exec php bash

でPHPコンテナにログインし(ログインしたままであれば上記コマンドは実行しなくて良いです。)

php artisan migrate

6 ダミーデータ作成
PHPコンテナにログインした状態で

php artisan db:seed

7 アプリケーション起動キーの作成
PHPコンテナにログインした状態で

php artisan key:generate

8 シンボリックリンクの作成
PHPコンテナにログインした状態で

php artisan storage:link

# 各種機能について

メール認証機能
・基本、①会員登録後、②登録時に「認証用メール」を送信　③（mailhogでの）認証リンクのクリック　④ユーザーが受信したメール内のリンクをクリックとなってますが、
メールが送信された「お知らせ」は、今回「マイページ」にて表示させてます。（ユーザー向けに、プロフィールも続けてやって貰えるように）

コメント送信機能
・ユーザーがログインしている時のみ商品詳細ページの下部に表示されます。

ユーザー情報変更機能
・プロフィール編集画面にて、ユーザー情報が編集画面上で変更されてから、マイページにて表示されます。

商品検索機能
・検索欄では「商品名」を入れると、部分一致検索で商品が出てきます。

商品購入機能
・商品を１つ選択してオーダーできます。購入方法は「支払い方法選択」にて、決済にておこないます。

決済機能
・実際に購入ボタンから、PAY.JPに登録して、そこからsripeの決済画面に接続されて、はじめて「購入」となります。

いいね機能
・☆印を押して、「いいね」することができますし、解除することもできます。

出品商品情報登録機能
・.商品出品画面にて必要な情報（商品名、画像、カテゴリ、状態、商品説明）を登録できます。

画像アップロード機能
・出品画面から出品されるときに、商品画像（ローカル画像）を、自分のPCの画像（縮小済）からアップロードできます。





