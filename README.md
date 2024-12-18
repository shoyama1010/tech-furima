# tech-furima


アイテムの出品と購入を行うためのフリマアプリです。
出品されている商品を検索できます。また、商品の詳細を閲覧することができます。

# 作成した目的
クライアントより自社でフリマサービスを持ちたいとの要望があった為、要望に添った機能を持つフリマサービスを構築するため作成しました。

# アプリケーションURL
ローカル環境
http://localhost

# 機能一覧

・認証機能
・コメント送信機能
・ユーザー情報変更機能
・商品検索機能
・購入機能
・決済機能

# 使用技術
・Laravel 8
・nginx 1.21.1
・php 7.4.9
・html
・css
・mysql 8.0.26
・メール認証：mailhog
・stripe

# テーブル設計


# ER図

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

DB_PASSWORD=laravel_pass

認証：fortifyパッケージ追加

バリデーション機能：Formrequest使用

.envファイルの最後に追加

