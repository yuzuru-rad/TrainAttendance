<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## はじめに

nfcカードリーダーを用いて、研修参加確認をするためのアプリケーションです。
php8.1/laravel10.12で作成されています。
カードリーダーですが、下記説明しますが対応/未対応が存在します。
以下のものでは動作確認を完了していますが、それ以外のものを使う場合は自己責任で。

- Sony RC-S330
- Sony RC-S380/p

## インストール方法

ざっくりざくざく必要なもの落としましょう
事前にphp,composer,npm,pythonは準備完了しているものとします。
それぞれのバージョンは以下とします。

- php -> 8.1(xampp経由が楽)
- laravel -> 10.12
- npm -> 9.5
- python -> 3.11.3

これらがある場合、下記手順を行ってください。

1. composer install
2. npm install
3. nfcpyの設定、こちら参考に。　https://qiita.com/75u2u/items/010b602605d087edd1ee

その後、envファイルをいじりましょう。  
.env.exampleから.envを作って、db_connectionと最下部にあるDB_HOST_SQLSRV周りを適切に設定します。

ここまでできたら、php artisan serve をして動作確認をお願いします。

## 操作説明

おおむね各ページに書いてありますが、まとめとして作成。

### 出席確認

研修一覧から任意の研修を選択して、その後に読み取り開始ボタンを押下してもらう。  
その後、カードをタッチすると読み込まれる。音を鳴らしてからタッチしてもらうようにすることで、二重タッチや、読み込み失敗などの事故を防ぐ。  
終了する際は、読み取り停止ボタンを押下して、出てきたメッセージの指示に従ってもらう。  

### 職員更新

そのまんま。sqlsrvの設定がうまくいっていないと、通らないプログラムなので注意。

### データ出力  

そのまんまその2,出力したい研修のあった日を選択すること。  
