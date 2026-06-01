<?php
// 檔名：api/reserve_book.php
// 負責處理書籍預約領取與生成交易紀錄

session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

// 🛡️ 防護 1：未登入者踢回
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '請先登入帳號後再進行預約！']);
    exit;
}

$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);

$book_id = isset($data['book_id']) ? intval($data['book_id']) : 0;
$user_id = $_SESSION['user_id'];

if ($book_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => '無效的書籍請求！']);
    exit;
}

try {
    // 🛡️ 防護 2：檢查書籍是否真的存在、是否是 available，且「不能預約自己捐的書」
    $check_stmt = $conn->prepare("SELECT bdonor_id, bstatus FROM Book WHERE bbook_id = ?");
    $check_stmt->execute([$book_id]);
    $book = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        echo json_encode(['status' => 'error', 'message' => '找不到該書籍資料！']);
        exit;
    }
    if ($book['bdonor_id'] == $user_id) {
        echo json_encode(['status' => 'error', 'message' => '您無法預約自己捐贈的書籍！']);
        exit;
    }
    if ($book['bstatus'] !== 'available') {
        echo json_encode(['status' => 'error', 'message' => '手腳太慢啦！這本書剛被別人預約或已結案。']);
        exit;
    }

    // 🎲 產生一組 8 碼的隨機交易編號 (例如: TR-A1B2C3D4)
    $trade_code = 'TR-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

    // ==========================================
    // 🔒 開啟資料庫交易 (Transaction)
    // ==========================================
    $conn->beginTransaction();

    // 動作 A：鎖定書籍狀態
    $update_stmt = $conn->prepare("UPDATE Book SET bstatus = 'reserved' WHERE bbook_id = ? AND bstatus = 'available'");
    $update_stmt->execute([$book_id]);

    // 如果沒有成功更新 (可能在那 0.001 秒內被別人搶走)，觸發例外
    if ($update_stmt->rowCount() === 0) {
        throw new Exception('書籍已被搶先預約！');
    }

    // 動作 B：寫入 Record 交易紀錄表
    $insert_stmt = $conn->prepare("INSERT INTO Record (ctrade_code, cbook_id, creceiver_id, crecord_status) VALUES (?, ?, ?, 'pending')");
    $insert_stmt->execute([$trade_code, $book_id, $user_id]);

    // 兩個動作都成功，正式提交寫入資料庫
    $conn->commit();
    // ==========================================

    echo json_encode([
        'status' => 'success',
        'message' => '預約成功！請至個人後臺查看面交資訊。',
        'trade_code' => $trade_code
    ]);
} catch (Exception $e) {
    // 發生任何錯誤，倒轉回復上述所有的 SQL 變更
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
