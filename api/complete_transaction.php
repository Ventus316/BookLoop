<?php
// 檔名：api/complete_transaction.php
// 負責處理使用者確認收到書籍，並將交易與書籍狀態結案

session_start();
require_once '../config/database.php';

// 🛡️ 防護 1：驗證登入狀態
if (!isset($_SESSION['user_id'])) {
    die("未授權的訪問");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ccrecord_id'])) {

    $record_id = intval($_POST['ccrecord_id']);
    $user_id = $_SESSION['user_id'];

    try {
        // 1. 撈取預約紀錄，嚴格驗證這筆預約「確實是由當前登入者發起的」
        $stmt = $conn->prepare("SELECT cbook_id, creceiver_id, crecord_status FROM Record WHERE ccrecord_id = ?");
        $stmt->execute([$record_id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        // 🛡️ 防護 2：確認紀錄存在、屬於該用戶，且狀態仍在 pending
        if ($record && $record['creceiver_id'] == $user_id && $record['crecord_status'] === 'pending') {

            $book_id = $record['cbook_id'];

            // ==========================================
            // 🔒 開啟資料庫交易防護機制 (Transaction)
            // ==========================================
            $conn->beginTransaction();

            // 動作 A：將書籍狀態由 reserved 更新為 donated (正式結案隱藏)
            $update_stmt = $conn->prepare("UPDATE Book SET bstatus = 'donated' WHERE bbook_id = ?");
            $update_stmt->execute([$book_id]);

            // 動作 B：將 Record 紀錄的狀態更新為 completed
            $complete_stmt = $conn->prepare("UPDATE Record SET crecord_status = 'completed' WHERE ccrecord_id = ?");
            $complete_stmt->execute([$record_id]);

            // 兩者皆成功，提交生效
            $conn->commit();
            // ==========================================

            echo "<script>alert('🎉 恭喜完成面交！交易已正式結案。'); window.location.href='../user_panel.php';</script>";
        } else {
            echo "<script>alert('操作失敗：權限不足或該交易已結案！'); history.back();</script>";
        }
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        $error_msg = addslashes($e->getMessage());
        echo "<script>alert('系統處理錯誤：" . $error_msg . "'); history.back();</script>";
    }
} else {
    header("Location: ../user_panel.php");
}
