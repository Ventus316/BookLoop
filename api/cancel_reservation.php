<?php
// 檔名：api/cancel_reservation.php
// 負責處理使用者取消預約，並釋放書籍狀態

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
        // 1. 撈取預約紀錄 (💡 修正：將 cccrecord_id 改回正確的 ccrecord_id)
        $stmt = $conn->prepare("SELECT cbook_id, creceiver_id, crecord_status FROM Record WHERE ccrecord_id = ?");
        $stmt->execute([$record_id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        // 🛡️ 防護 2：權限與狀態雙重防禦
        if ($record && $record['creceiver_id'] == $user_id && $record['crecord_status'] === 'pending') {

            $book_id = $record['cbook_id'];

            // ==========================================
            // 🔒 開啟資料庫交易防護機制 (Transaction)
            // ==========================================
            $conn->beginTransaction();

            // 動作 A：將書籍狀態由 reserved 還原回 available
            $update_stmt = $conn->prepare("UPDATE Book SET bstatus = 'available' WHERE bbook_id = ? AND bstatus = 'reserved'");
            $update_stmt->execute([$book_id]);

            // 動作 B：刪除這筆 pending 的 Record 紀錄 (💡 修正：改回正確的 ccrecord_id)
            $delete_stmt = $conn->prepare("DELETE FROM Record WHERE ccrecord_id = ?");
            $delete_stmt->execute([$record_id]);

            // 兩者皆成功，提交生效
            $conn->commit();
            // ==========================================

            echo "<script>alert('預約已成功取消，書籍已重新釋放！'); window.location.href='../user_panel.php';</script>";
        } else {
            echo "<script>alert('操作失敗：權限不足或該交易已結案！'); history.back();</script>";
        }
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        // 💡 終極防護：使用 addslashes 幫單引號加上反斜線，避免破壞 JS 結構
        $error_msg = addslashes($e->getMessage());
        echo "<script>alert('系統處理錯誤：" . $error_msg . "'); history.back();</script>";
    }
} else {
    header("Location: ../user_panel.php");
}
