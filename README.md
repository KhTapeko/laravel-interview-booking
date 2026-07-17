# 面試預約系統 Interview Scheduler

一個使用 Laravel 12 與 Vue 3 開發的職缺刊登及面試應徵平台。

系統提供 Admin、Employee、Candidate 三種角色。使用者可以瀏覽及搜尋職缺、查看職缺詳情並提出應徵；員工可以建立及管理職缺；管理員則可以管理使用者與調整角色權限。

> 本專案目前將 Laravel API 與 Vue SPA 整合在同一個 Repository 中，並透過 Vite 建置前端資源。

## Features 主要功能

### 公開功能

- 瀏覽首頁精選職缺
- 搜尋職缺名稱或公司名稱
- 查看所有職缺
- 查看職缺詳細資料
- 使用者註冊及登入

### Candidate 面試者

- 應徵職缺
- 防止重複應徵同一職缺
- 查看及修改個人資料
- 修改登入密碼
- 刪除自己的帳號

### Employee 員工

- 建立新職缺
- 編輯自己建立的職缺
- 刪除自己建立的職缺
- 應徵其他職缺
- 管理個人資料

### Admin 管理員

- 查看及搜尋所有使用者
- 修改使用者基本資料
- 調整使用者角色
- 刪除使用者
- 建立、編輯及刪除職缺

## Role Permissions 角色權限

| 功能 | Admin | Employee | Candidate |
|---|:---:|:---:|:---:|
| 瀏覽及搜尋職缺 | ✅ | ✅ | ✅ |
| 應徵職缺 | ❌ | ✅ | ✅ |
| 建立職缺 | ✅ | ✅ | ❌ |
| 編輯自己建立的職缺 | ✅ | ✅ | ❌ |
| 刪除自己建立的職缺 | ✅ | ✅ | ❌ |
| 管理所有使用者 | ✅ | ❌ | ❌ |
| 調整使用者角色 | ✅ | ❌ | ❌ |
| 修改個人資料 | ✅ | ✅ | ✅ |

## Tech Stack 使用技術

### Backend

- PHP 8.2+
- Laravel 12
- Laravel Sanctum 4
- Eloquent ORM
- SQLite
- PHPUnit 11

### Frontend

- Vue 3
- Vue Router 4
- Pinia 3
- Axios
- Tailwind CSS 3
- Lucide Vue Next
- Vite 6

## Project Structure 專案結構

```text
laravel-interview-booking/
├── app/
│   ├── Http/Controllers/       # API、登入及使用者控制器
│   └── Models/                 # User、Job 資料模型
├── database/
│   ├── migrations/             # 資料庫結構
│   └── seeders/                # 測試資料
├── resources/
│   ├── css/                    # Tailwind CSS 入口
│   ├── js/
│   │   ├── components/         # Vue 共用元件
│   │   ├── pages/              # 頁面元件
│   │   ├── router/             # Vue Router 設定
│   │   └── stores/             # Pinia Store
│   └── views/                  # Laravel Blade 入口
├── routes/
│   └── web.php                 # 網頁及 API 路由
├── composer.json
├── package.json
└── vite.config.js
```

## Database Structure 資料表

### users

儲存使用者基本資料及角色。

主要欄位：

- `name`
- `email`
- `password`
- `gender`
- `birthday`
- `role`

支援角色：

- `admin`
- `employee`
- `candidate`

### jobs

儲存職缺與面試相關資訊。

主要欄位：

- 職缺名稱、公司名稱及工作地點
- 工作內容與條件要求
- 面試類型及面試時間
- 薪資範圍
- 學歷及經驗要求
- 福利與聯絡資訊
- 遠端工作及出差需求
- 職缺建立者

### job_user

記錄使用者與職缺之間的應徵關係。

應徵狀態預設為：

```text
applied
```

資料結構也預留了 `confirmed` 與 `rejected` 等狀態的擴充空間。

## Getting Started 安裝方式

### Requirements 環境需求

請先安裝：

- PHP 8.2 或以上
- Composer
- Node.js 18 或以上
- npm
- PHP SQLite Extension

### 1. Clone Repository

```bash
git clone https://github.com/KhTapeko/laravel-interview-booking.git
cd laravel-interview-booking
```

### 2. Install Dependencies

安裝 Laravel 套件：

```bash
composer install
```

安裝前端套件：

```bash
npm install
```

### 3. Environment Setup

macOS／Linux：

```bash
cp .env.example .env
touch database/database.sqlite
```

Windows PowerShell：

```powershell
Copy-Item .env.example .env
New-Item database/database.sqlite -ItemType File
```

產生 Laravel Application Key：

```bash
php artisan key:generate
```

### 4. Run Migrations

```bash
php artisan migrate
```

目前的 Seeder 尚未提供預設使用者，因此不需要執行 `--seed`。

### 5. Start Development Servers

開啟第一個終端機執行 Laravel：

```bash
php artisan serve
```

開啟第二個終端機執行 Vite：

```bash
npm run dev
```

瀏覽器開啟：

```text
http://127.0.0.1:8000
```

也可以使用 Composer 提供的整合指令，同時啟動 Laravel、Queue、Pail 與 Vite：

```bash
composer run dev
```

## Create an Admin 建立管理員

系統註冊的新帳號預設為 `candidate`。如需在本地測試管理員功能，可以先完成註冊，再執行：

```bash
php artisan tinker
```

進入 Tinker 後輸入：

```php
App\Models\User::where('email', 'your-email@example.com')
    ->update(['role' => 'admin']);
```

將 `your-email@example.com` 替換成已註冊帳號的 Email。

如需建立 Employee，將角色改為：

```php
'employee'
```

## Main Routes 主要頁面

| Route | 說明 | 權限 |
|---|---|---|
| `/` | 首頁及精選職缺 | 公開 |
| `/JobList` | 所有職缺 | 公開 |
| `/jobs/{id}` | 職缺詳情 | 公開 |
| `/login` | 登入 | 訪客 |
| `/register` | 註冊 | 訪客 |
| `/Profile` | 個人資料 | 登入使用者 |
| `/jobs/create` | 建立職缺 | Admin／Employee |
| `/jobs/{id}/edit` | 編輯職缺 | Admin／Employee |
| `/admin/users` | 使用者管理 | Admin |

## API Overview

### Authentication

| Method | Endpoint | 說明 |
|---|---|---|
| `POST` | `/register` | 註冊 |
| `POST` | `/login` | 登入 |
| `POST` | `/logout` | 登出 |
| `GET` | `/me` | 取得登入狀態 |

### Jobs

| Method | Endpoint | 說明 |
|---|---|---|
| `GET` | `/api/jobs` | 取得最新六筆職缺 |
| `GET` | `/api/jobs/all` | 取得所有職缺 |
| `GET` | `/api/jobs/{id}` | 取得職缺詳情 |
| `POST` | `/api/jobs` | 建立職缺 |
| `PUT` | `/api/jobs/{id}` | 更新職缺 |
| `DELETE` | `/api/jobs/{id}` | 刪除職缺 |
| `POST` | `/api/jobs/{id}/apply` | 應徵職缺 |

### Profile and Administration

| Method | Endpoint | 說明 |
|---|---|---|
| `GET` | `/profile` | 取得個人資料 |
| `PUT` | `/profile/update` | 更新個人資料 |
| `DELETE` | `/profile/delete` | 刪除自己的帳號 |
| `GET` | `/api/admin/users` | 取得及搜尋使用者 |
| `PUT` | `/api/admin/users/{id}` | 更新使用者 |
| `DELETE` | `/api/admin/users/{id}` | 刪除使用者 |

## Build for Production 正式環境建置

```bash
npm run build
```

正式部署前請調整 `.env`：

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

接著執行：

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Current Status 目前進度

- [x] Laravel 與 Vue SPA 整合
- [x] 使用者註冊、登入及登出
- [x] Session／Sanctum 認證
- [x] 三種使用者角色
- [x] 首頁精選職缺
- [x] 職缺搜尋及列表
- [x] 職缺詳細資料
- [x] 職缺建立、編輯及刪除
- [x] 職缺應徵功能
- [x] 個人資料管理
- [x] 管理員使用者管理
- [x] RWD 響應式介面
- [ ] 線上 Demo

## Roadmap 未來規劃

- 完成面試時段的新增、查詢、修改及刪除功能
- 加入應徵審核及狀態更新
- 加入 Email 或手機驗證
- 加入面試通知及提醒
- 支援 Google、Facebook 等第三方登入
- 增加履歷上傳功能
- 增加職缺分頁、篩選及排序
- 補充 Feature Test 與 Unit Test
- 建立正式環境部署流程

## Demo 實際展示

目前尚未提供線上 Demo。

## License

本專案使用 Laravel Framework 建立。專案授權方式請依 Repository 內的授權文件為準。
