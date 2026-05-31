<?php
// 檔名：config/database.php

$host = '127.0.0.1';
$db   = 'bookloop';
$user = 'root';
$pass = '123456';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    // 1. 遇到 SQL 錯誤時拋出異常 (Exception)，方便開發階段除錯
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

    // 2. 讓資料庫撈出來的資料預設全轉為「鍵值陣列 (Associative Array)」格式，方便前端讀取
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

    // 3. 關閉 PDO 的模擬預處理，強制使用 MySQL 原生的真實預處理，徹底防範 SQL 注入
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // 建立 PDO 實例並儲存在 $conn 變數中 (必須與全站代碼一致！)
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // 萬一連線失敗，停止程式運行並回傳錯誤訊息
    die("資料庫連線失敗，錯誤原因：" . $e->getMessage());
}
