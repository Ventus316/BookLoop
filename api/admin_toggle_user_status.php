<?php
// 檔名：api/admin_toggle_user_status.php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '未授權訪問']);
    exit;
}

$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);

$target_user_id = isset($data['user_id']) ? intval($data['user_id']) : 0;
$new_status = isset($data['status']) ? $data['status'] : '';

if ($target_user_id <= 0 || !in_array($new_status, ['active', 'banned'])) {
    echo json_encode(['status' => 'error', 'message' => '參數錯誤']);
    exit;
}

try {
    // 🛡️ 權限驗證：確認操作者是管理員
    $auth = $conn->prepare("SELECT urole FROM User WHERE user_id = ?");
    $auth->execute([$_SESSION['user_id']]);
    $admin = $auth->fetch(PDO::FETCH_ASSOC);

    if (!$admin || $admin['urole'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => '權限不足']);
        exit;
    }

    // 防呆：不能封禁自己
    if ($target_user_id === $_SESSION['user_id']) {
        echo json_encode(['status' => 'error', 'message' => '您無法更改自己的帳號狀態！']);
        exit;
    }

    // 執行狀態更新
    $stmt = $conn->prepare("UPDATE User SET status = ? WHERE user_id = ?");
    $stmt->execute([$new_status, $target_user_id]);

    echo json_encode(['status' => 'success', 'new_status' => $new_status]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => '資料庫錯誤']);
}
