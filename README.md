# 勤怠管理アプリ

## ⚪︎ 機能一覧
管理者ユーザー
管理者ログイン画面
<img width="2558" height="1273" alt="管理者ログイン画面" src="https://github.com/user-attachments/assets/97ebed48-b739-41f7-8f34-05f9c4835771" />
勤怠一覧画面
<img width="2545" height="1228" alt="勤怠一覧画面" src="https://github.com/user-attachments/assets/19cde08f-fbe5-437f-95e0-57eeca8ab080" />
スタッフ一覧画面
<img width="2545" height="1259" alt="スタッフ一覧画面" src="https://github.com/user-attachments/assets/cb290cf9-5c74-4599-853e-c2ea8358bc7b" />
申請一覧承認済み画面
<img width="2538" height="1255" alt="申請一覧承認済み画面" src="https://github.com/user-attachments/assets/558d4fd2-27e0-4fde-a0ed-2ae215db893a" />
申請一覧承認待ち画面
<img width="2538" height="1252" alt="申請一覧承認待ち画面" src="https://github.com/user-attachments/assets/8761b3fb-fcb2-4d18-afdd-d538a6938af2" />
勤怠詳細承認画面
<img width="2558" height="1269" alt="勤怠詳細承認画面" src="https://github.com/user-attachments/assets/916c1416-45de-42b8-b99e-8e170e7f0e88" />
勤怠詳細承認済み画面
<img width="2552" height="1266" alt="勤怠詳細承認済み画面" src="https://github.com/user-attachments/assets/9c258326-4826-4ad7-83a1-c4fed54ada93" />

一般ユーザー
一般ログイン画面
<img width="2552" height="1261" alt="一般ログイン画面" src="https://github.com/user-attachments/assets/60f33bfe-f5f2-46d7-94a9-9dd26abe4001" />
会員登録画面
<img width="2539" height="1250" alt="会員登録画面" src="https://github.com/user-attachments/assets/ef90335f-0fc3-4a6e-a37d-fa23aea06a0c" />
打刻画面1
<img width="2551" height="1266" alt="打刻画面1" src="https://github.com/user-attachments/assets/e5347b80-d1f4-457f-bf49-f0cc127fe1e2" />
打刻画面2
<img width="2553" height="1257" alt="打刻画面2" src="https://github.com/user-attachments/assets/1e87b429-7bf2-4d8b-8be7-1366f07fd58d" />
打刻画面3
<img width="2550" height="1260" alt="打刻画面3" src="https://github.com/user-attachments/assets/7c942853-9044-45f7-a644-5d3ff366a809" />
打刻画面4
<img width="2544" height="1264" alt="打刻画面4" src="https://github.com/user-attachments/assets/4fd8b3e6-f5b8-4a47-90c5-b3597d7e0d83" />
勤怠一覧画面
<img width="2547" height="1267" alt="勤怠一覧画面" src="https://github.com/user-attachments/assets/e0e8cb82-ec16-4e9e-a888-e17f1d02c8d3" />
勤怠詳細画面
<img width="2552" height="1266" alt="勤怠詳細画面" src="https://github.com/user-attachments/assets/305acdfa-7ac0-49b1-8ff4-be60727f67bd" />
勤怠詳細修正前画面
<img width="2550" height="1262" alt="勤怠詳細修正前画面" src="https://github.com/user-attachments/assets/6f9c6bf7-b6c4-462a-912c-19c1479a6545" />
勤怠詳細修正後画面
<img width="2556" height="1253" alt="勤怠詳細修正後画面" src="https://github.com/user-attachments/assets/c2212a52-2ca0-465b-92f9-1de639e80515" />
申請一覧承認待ち画面
<img width="2541" height="1248" alt="申請一覧承認待ち画面" src="https://github.com/user-attachments/assets/b4cc0e68-b369-44a8-9d23-5c406f82859c" />
申請一覧承認済み画面
<img width="2537" height="1255" alt="申請一覧承認済み画面" src="https://github.com/user-attachments/assets/f08414a6-a248-4834-bbf9-fbeba5454be2" />



## 環境構築

## メール認証

mailtrapというツールを使用しています。
以下のリンクから会員登録をしてください。　
https://mailtrap.io/

メールボックスのIntegrationsから 「SMTP」を選択し、
.envファイルのMAIL_MAILERからMAIL_ENCRYPTIONまでの項目をコピー＆ペーストしてください。
MAIL_FROM_ADDRESSは任意のメールアドレスを入力してください。

## Docker ビルド

1. git clone git@github.com:lillian-angelina/attendance-management.git
2. cd ~/coachtech/laravel/attendance-management
3. docker-compose up -d --build

## Laravel のセットアップ

1. docker-compose exec php bash
2. composer install
3. .env ファイルの一部を以下のように編集

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=laravel_db_new
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

4. php artisan key:generate
5. php artisan migrate --seed

## キャッシュクリア（エラー回避のため）

1. php artisan config:clear
2. php artisan cache:clear
3. php artisan view:clear
4. php artisan route:clear

## user のログイン用初期データ
### テストユーザー(一般)
- メールアドレス: test@example.com
- パスワード: password123

### テストユーザー(管理者)
- メールアドレス: admin@example.com
- パスワード: password123

## 使用技術

- MySQL 9.2.0
- PHP 8.2
- Laravel 12.0

## URL

- 環境開発: http://localhost:8082/
- phpMyAdmin: http://localhost:8081/

## ER 図

![image](er_data.png)
