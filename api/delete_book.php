<?php
// 檔名：api/delete_book.php
// 負責處理書籍的永久刪除與實體圖片釋放

session_start();
require_once '../config/database.php';

// 🛡️ 防護 1：未登入者拒絕執行
if (!isset($_SESSION['user_id'])) {
    die("未授權的訪問");
}

// 必須透過 POST 表單送出，防範 CSRF 網址攻擊
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bbook_id'])) {

    $book_id = intval($_POST['bbook_id']);
    $user_id = $_SESSION['user_id'];

    try {
        // 1. 驗證權限：確認這本書真的是這個使用者捐的，並同時撈出圖片路徑
        $stmt = $conn->prepare("SELECT bdonor_id, bimage_url FROM Book WHERE bbook_id = ?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        // 如果書籍存在，且登入者 ID 等於捐贈者 ID
        if ($book && $book['bdonor_id'] == $user_id) {

            // 2. 實體檔案清理機制：如果圖片不是系統預設的那張圖，就從硬碟中徹底刪除 (釋放空間)
            if ($book['bimage_url'] !== 'assets/images/default.png') {
                $file_path = '../' . $book['bimage_url'];
                if (file_exists($file_path)) {
                    unlink($file_path); // 執行刪除實體檔案
                }
            }

            // 3. 刪除資料庫紀錄 
            // (💡 由於我們在 table.sql 有設定 ON DELETE CASCADE，這本書的相關留言與交易紀錄會自動完美消失！)
            $del_stmt = $conn->prepare("DELETE FROM Book WHERE bbook_id = ?");
            $del_stmt->execute([$book_id]);

            echo "<script>alert('書籍已成功刪除！'); window.location.href='../user_panel.php';</script>";
        } else {
            // 防護觸發：有人試圖刪除別人的書
            echo "<script>alert('操作失敗：權限不足或查無此書籍！'); history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('系統錯誤：" . $e->getMessage() . "'); history.back();</script>";
    }
} else {
    // 若不當存取此頁面，直接踢回後臺
    header("Location: ../user_panel.php");
}
