# 面試預約系統 Interview Scheduler

## Description 描述

本系統是一個支援三種使用者角色（Admin／Employee／Candidate）的面試預約平台。員工可以開放面試時段，面試者依照職缺申請預約，管理員可以調整權限與檢視整體狀況。  
採用前後端分離架構，Laravel 作為後端 API，Vue 3 搭配 Tailwind CSS 負責前端呈現。

## Supported this Types 程式支援類型

- Laravel 12（API 開發）
- Vue 3（SPA 前端）
- Tailwind CSS v4（前端 UI 樣式）
- Pinia（狀態管理）
- Sanctum（登入認證）
- Spatie Laravel Permission（權限控管）
- SQLite（資料庫，可切換至其他如 MySQL）

## Feature 主要功能

- [x] 精選職缺首頁卡片區塊展示
- [x] 精選職缺搜尋功能
- [x] 使用者註冊／登入功能（採用 Sanctum）
- [/] 顯示職缺詳細資料
- [ ] 面試者可依職缺預約面試
- [x] 管理員可設定用戶權限、管理職缺
- [x] 前後端完全分離設計

## 進度區

- [x] 首頁畫面顯示（Header / Footer / 精選職缺卡片）
- [x] API 串接職缺資料
- [x] 註冊 / 登入功能（Sanctum）
- [/] Admin 後台管理功能
- [ ] 面試者預約介面
- [x] RWD 響應式設計

## Demo 實際展示

尚未提供

## Getting Started 安裝教學

1. 複製專案：
   ```bash
   git clone https://github.com/yourname/interview-scheduler.git

2. Laravel 後端安裝：
    ```bash
    cd interview-scheduler
    composer install
    cp .env.example .env
    php artisan key:generate
    touch database/database.sqlite
    php artisan migrate --seed
    php artisan serve

3. 前端 Vue 安裝：
    ```bash
    cd frontend
    npm install
    npm run dev
    
## How to Use 如何使用

 - 

## Futrue 未來展望

## Download 下載區

尚未提供

## Questions and Answers 問題&答案
