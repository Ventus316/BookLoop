<?php
// 檔名：api/add_comment.php
// 負責處理 AJAX 異步留言寫入請求

session_start();
require_once '../config/database.php';

// 宣告回傳格式為 JSON
header('Content-Type: application/json');

// 🛡️ 權限防護：未登入者拒絕執行
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '請先登入帳號後再留言！']);
    exit;
}

// 接收 JSON 資料
$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);

$book_id = isset($data['book_id']) ? intval($data['book_id']) : 0;
$content = isset($data['content']) ? trim($data['content']) : '';
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['uname']; // 從 Session 取得當前登入者姓名

// 防呆：確認有書籍 ID 且留言內容不為空
if ($book_id <= 0 || $content === '') {
    echo json_encode(['status' => 'error', 'message' => '留言內容不可為空！']);
    exit;
}

try {
    // 📝 寫入 Interaction 資料表 (類型指定為 'comment')
    $stmt = $conn->prepare("INSERT INTO Interaction (ibook_id, iuser_id, iinteraction_type, icontent) VALUES (?, ?, 'comment', ?)");

    if ($stmt->execute([$book_id, $user_id, $content])) {
        // 留言成功！回傳成功狀態與渲染畫面所需的資料
        echo json_encode([
            'status' => 'success',
            'uname' => $user_name,
            'content' => htmlspecialchars($content), // 後端過濾 XSS，保護前端安全
            'time' => date('Y-m-d H:i') // 產生當下時間 (例如: 2026-06-01 14:30)
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => '留言寫入失敗。']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => '資料庫連線錯誤。']);
}
