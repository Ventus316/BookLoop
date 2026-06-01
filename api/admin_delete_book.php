<?php
// 檔名：api/admin_delete_book.php
// 負責處理管理員「強制下架(刪除)」全站任何書籍

session_start();
require_once '../config/database.php';

// 🛡️ 防護 1：驗證是否為登入狀態
if (!isset($_SESSION['user_id'])) {
    die("未授權的訪問");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bbook_id'])) {

    $book_id = intval($_POST['bbook_id']);
    $user_id = $_SESSION['user_id'];

    try {
        // 🛡️ 防護 2：權限驗證，確認執行此動作的人「確實是管理員」
        $auth_stmt = $conn->prepare("SELECT urole FROM User WHERE user_id = ?");
        $auth_stmt->execute([$user_id]);
        $user = $auth_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['urole'] !== 'admin') {
            echo "<script>alert('🛑 嚴重警告：越權操作！您的 IP 已被記錄。'); window.location.href='../index.php';</script>";
            exit;
        }

        // 1. 撈出該本書籍的圖片路徑
        $book_stmt = $conn->prepare("SELECT bimage_url FROM Book WHERE bbook_id = ?");
        $book_stmt->execute([$book_id]);
        $book = $book_stmt->fetch(PDO::FETCH_ASSOC);

        if ($book) {
            // 2. 實體檔案清理機制：如果圖片不是系統預設圖，則從伺服器硬碟中刪除
            if ($book['bimage_url'] !== 'assets/images/default.png') {
                $file_path = '../' . $book['bimage_url'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            // 3. 執行終極刪除指令 (💡 關聯的留言、點讚、預約紀錄都會被 CASCADE 自動清除)
            $del_stmt = $conn->prepare("DELETE FROM Book WHERE bbook_id = ?");
            $del_stmt->execute([$book_id]);

            echo "<script>alert('🚨 執行完畢：該違規書籍及其所有關聯紀錄已從系統中強制抹除！'); window.location.href='../admin_panel.php';</script>";
        } else {
            echo "<script>alert('操作失敗：查無此書籍！'); history.back();</script>";
        }
    } catch (PDOException $e) {
        $error_msg = addslashes($e->getMessage());
        echo "<script>alert('系統錯誤：" . $error_msg . "'); history.back();</script>";
    }
} else {
    header("Location: ../admin_panel.php");
}
