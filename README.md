# 勤怠管理システム (Attendance Management System)

## 概要
企業の勤怠管理をデジタル化し、従業員の打刻から管理者による承認・集計までを一気通貫で行うWebアプリケーションです。
<br>
現場での使いやすさを重視し、直感的なUIと、不正打刻を防止するためのシンプルな操作フローを実装しました。

背景と目的
<br>
・課題: 紙やExcelでの管理による転記ミスや、集計作業の膨大な工数。
<br>
・解決策: クラウド上でリアルタイムに打刻・集計を行うことで、バックオフィス業務の効率化を目指しました。

主な機能
1. 従業員向け機能<br>
　・打刻機能: 出勤・退勤・休憩開始・休憩終了のワンクリック打刻。<br>
　・勤務履歴閲覧: 自身の月ごとの勤務時間や残業時間の確認。<br>
2. 管理者向け機能<br>
　・従業員管理: アカウントの作成、編集、削除。<br>
　・勤怠集計: 全従業員の勤務データの閲覧および月次集計。<br>

使用技術<br><br>
バックエンド<br>
　・PHP 8.x / Laravel 8.x
<br>
　・Eloquent ORM による効率的なデータベース操作。
<br>
　・認証機能（Laravel Fortify/Breeze等）を用いたセキュアなログイン。
<br>

フロントエンド
<br>
　・Bladeテンプレートエンジン / CSS (Tailwind CSS)
<br>
　・レスポンシブデザインに対応。
<br>

開発環境・インフラ
<br>
　・Docker / Docker Compose
<br>
　・php-fpm, nginx, mysql のコンテナ構成。
<br>
　・環境構築の容易化とメンバー間での環境差異の解消。
<br>

システム構成
<br>
.<br>
├── docker/              # Docker設定ファイル<br>
├── src/                 # Laravelプロジェクト本体<br>
│   ├── app/Models/      # ビジネスロジック<br>
│   ├── app/Http/Controllers/ # リクエスト制御<br>
│   └── resources/views/ # UIテンプレート<br>
└── docker-compose.yml   # コンテナオーケストレーション<br>
<br>

こだわったポイント
<br>
1. DB設計:
<br>
　・休憩時間を別テーブル（またはリレーション）で管理することで、1日に複数回の休憩をとるケースにも対応できる拡張性を持たせました。
<br>
2. UI/UX:
<br>
　・打刻状態（現在「出勤中」なのか「休憩中」なのか）を動的に判断し、次に押すべきボタンのみを活性化させることで、誤操作を防ぐ設計にしました。
<br>
3. 環境構築の自動化:
<br>
　・docker-compose up だけで開発環境が立ち上がるよう、設定を最適化しました。
<br>

## ER 図
![image](er_data.png)

## 機能一覧
<br>
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


