<?php
// 檔名：api/toggle_interaction.php
// 負責處理 AJAX 異步點讚與收藏請求

session_start();
require_once '../config/database.php';

// 宣告回傳格式為 JSON
header('Content-Type: application/json');

// 1. 權限防護
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '請先登入帳號！']);
    exit;
}

// 2. 接收前端 JS 透過 fetch 傳來的 JSON 資料
$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);

$book_id = isset($data['book_id']) ? intval($data['book_id']) : 0;
$type = isset($data['type']) ? $data['type'] : '';
$user_id = $_SESSION['user_id'];

// 驗證互動類型
if ($book_id <= 0 || !in_array($type, ['like', 'collect'])) {
    echo json_encode(['status' => 'error', 'message' => '參數錯誤']);
    exit;
}

try {
    // 3. 檢查是否已經點過讚/收藏 (Toggle 邏輯)
    $check_stmt = $conn->prepare("SELECT iinteraction_id FROM Interaction WHERE ibook_id = ? AND iuser_id = ? AND iinteraction_type = ?");
    $check_stmt->execute([$book_id, $user_id, $type]);
    $exists = $check_stmt->fetch(PDO::FETCH_ASSOC);

    $action = '';
    if ($exists) {
        // 如果存在，代表使用者要「收回」讚/收藏 (DELETE)
        $del_stmt = $conn->prepare("DELETE FROM Interaction WHERE iinteraction_id = ?");
        $del_stmt->execute([$exists['iinteraction_id']]);
        $action = 'removed';
    } else {
        // 如果不存在，代表使用者要「新增」讚/收藏 (INSERT)
        $ins_stmt = $conn->prepare("INSERT INTO Interaction (ibook_id, iuser_id, iinteraction_type) VALUES (?, ?, ?)");
        $ins_stmt->execute([$book_id, $user_id, $type]);
        $action = 'added';
    }

    // 4. 重新計算該書籍的總讚數/收藏數
    $count_stmt = $conn->prepare("SELECT COUNT(*) FROM Interaction WHERE ibook_id = ? AND iinteraction_type = ?");
    $count_stmt->execute([$book_id, $type]);
    $new_count = $count_stmt->fetchColumn();

    // 5. 將結果打包成 JSON 回傳給前端
    echo json_encode([
        'status' => 'success',
        'action' => $action,      // 告訴前端是 added 還是 removed
        'new_count' => $new_count // 最新的數字
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => '資料庫錯誤']);
}
