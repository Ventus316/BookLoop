# 📚 書活 BookLoop - 校園二手書循環平台

「打破空間限制，讓每本書找到下一個歸屬。」

**書活 BookLoop** 是一個專為大學校園打造的全端二手書媒合與交易平台。結合社群互動、電子商務級的預約狀態機與嚴密的後臺管理系統，旨在推動校園內的知識流動，實踐 SDGs 責任消費與平權教育理念。本專案採前後端分離架構思維，透過非同步技術提供無縫流暢的使用者體驗。

## 🛠️ 技術棧 (Tech Stack)

- **前端介面 (UI/UX)**: HTML5, Tailwind CSS
- **前端邏輯與非同步通訊**: Vanilla JavaScript (ES6+), Fetch API
- **後端核心 (Backend)**: PHP 8.x
- **資料庫 (Database)**: MySQL (PDO 連線驅動)
- **資安加密機制**: BCRYPT Hash 演算法

## 💡 核心技術架構 (Technical Highlights)

本專案跳脫傳統學生專案的靜態刻板印象，導入多項業界標準的架構與防護機制：

### 1. 雙表連動狀態機 (State Machine)

捨棄單純的 CRUD，我們為交易系統開發了嚴謹的狀態機邏輯。書籍狀態（`available` ↔ `reserved` ↔ `donated`）與預約紀錄表 (`Record`) 深度連動。透過 SQL 條件鎖定，確保同一本書在被預約的瞬間即產生排他性，完美解決併發領取的問題。

### 2. RBAC 雙重權限控管 (Role-Based Access Control)

透過資料庫的 `urole` 欄位建構了管理員 (`admin`) 與一般用戶 (`user`) 的權限隔離。除了前端介面實作「上帝視角」的專屬路由外，後端 API 更具備獨立的 Session 防護與越權阻擋（IDOR 防禦），確保核心管理功能不被惡意呼叫。

### 3. 動態內容管理系統與級聯刪除 (CMS & Cascade Deletion)

全站分類系統全面實現「資料驅動 (Data-Driven)」。管理員新增分類後，將即時同步至尋書大廳與捐書表單。底層架構採用 `FOREIGN KEY ... ON DELETE CASCADE`，當管理員執行「強制下架」或「刪除分類」時，關聯的互動紀錄與實體圖檔將被一併抹除，維持資料庫與伺服器硬碟的絕對整潔。

### 4. 企業級資安防護矩陣 (Security Matrix)

- **SQL Injection 防禦**：全面捨棄字串拼接，後端 API 100% 採用 PDO Prepared Statements 綁定參數。
- **XSS 防護與跳脫**：前端輸出嚴格執行 `htmlspecialchars`，後端報錯訊息導入 `addslashes`，確保惡意腳本無法破壞 DOM 結構。
- **密碼安全與登入閘道**：採用不可逆的 `password_hash` 處理機敏數據，並在登入驗證層實作「帳號停權 (Banned)」攔截器，將違規用戶物理隔離於系統之外。

### 5. 全站非同步互動體驗 (Asynchronous AJAX Interactivity)

捨棄傳統表單的刷新機制，點讚、收藏、留言版與後臺管理操作皆採用原生 `Fetch API` 實作。配合 DOM 節點的精準選取與 CSS 淡入淡出，達成單頁應用程式 (SPA) 般的絲滑體驗。

## 🎨 平台核心理念 (Core Values)

平台設計圍繞著永續發展目標 (SDGs) 展開：

- **SDGs 12 責任消費**：提倡二手書籍循環，減少過度印刷。
- **SDGs 4 優質教育**：降低獲取教材的門檻，確保知識平權。
- **透明的知識足跡**：完整的留言與捐贈紀錄，讓每一本書的交接都充滿社群溫度。

## 📂 系統架構目錄 (Directory Structure)

```text
BookLoop/
├── 📂 api/                # 後端 API 接口 (處理 AJAX 請求與表單邏輯)
├── 📂 assets/             # 靜態資源
│   ├── 📂 images/         # 網站圖片與書籍實體照片存放區
│   └── 📄 script.js       # 全域前端互動邏輯 (Fetch API, DOM 操作)
├── 📂 components/         # 共用 UI 模組 (Header, Footer, Book Card)
├── 📂 config/             # 全域設定
│   └── 📄 database.php    # PDO 資料庫連線配置
├── 📄 table.sql           # 資料庫 Schema 與預設數據建置檔
├── 📄 index.php           # 系統首頁 (落地頁)
├── 📄 search.php          # 尋書大廳 (動態過濾與搜尋)
├── 📄 user_panel.php      # 使用者個人後台 (CRUD 與預約管理)
└── 📄 admin_panel.php     # 系統管理員後台 (最高權限 CMS)
```

## 🚀 部署與運行 (How to Run)

### 環境需求

- 支援 PHP 8.0+ 的本地伺服器環境 (推薦使用 XAMPP, MAMP 或 Laragon)
- MySQL 資料庫

### 安裝步驟

1.  **Clone 專案**:

```bash
git clone https://github.com/Ventus316/BookLoop.git
```

2.  **佈署至本機伺服器**:

- 將整個 BookLoop 資料夾移動至伺服器的根目錄中（例如 XAMPP 的 htdocs 或 MAMP 的 htdocs）

3. **建置資料庫**:

- 開啟 phpMyAdmin 或 Navicat。
- 建立一個名為 bookloop 的空白資料庫，編碼設定為 utf8mb4_unicode_ci。
- 匯入專案根目錄下的 table.sql、data.sql 檔案，以生成完整的資料表結構與測試數據。

4. **設定資料庫連線**:

開啟 config/database.php，確認以下設定與您的本地環境相符：

```bash
$host = "localhost";
$dbname = "bookloop";
$username = "root";      // 替換為您的資料庫帳號
$password = "";          // 替換為您的資料庫密碼
```

5. **啟動專案**:

開啟瀏覽器，輸入網址 http://localhost/BookLoop/ 即可進入系統。

## 👥 開發團隊 (Credits)

本專案由 [元智大學 - 資訊傳播學系] 團隊共同開發：

- **李柏融 (後端)**: 系統核心架構設計、資料庫建置與 API 核心邏輯撰寫。
- **王晴右 (後端)**: 資安防護實作、RBAC 權限控管與伺服器端狀態機開發。
- **王宥慈 (前端)**: UI/UX 介面設計、Tailwind 響應式切版與視覺動線優化。
- **李忻育 (前端)**: AJAX 非同步串接、DOM 操作與前端互動邏輯實作。
