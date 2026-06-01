<?php
// 檔名：api/admin_delete_category.php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '未授權訪問']);
    exit;
}

$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);
$cat_id = isset($data['category_id']) ? intval($data['category_id']) : 0;

if ($cat_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => '無效的分類 ID']);
    exit;
}

try {
    // 🛡️ 權限驗證
    $auth = $conn->prepare("SELECT urole FROM User WHERE user_id = ?");
    $auth->execute([$_SESSION['user_id']]);
    $user = $auth->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['urole'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => '權限不足']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM Category WHERE ccategory_id = ?");
    $stmt->execute([$cat_id]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => '資料庫錯誤']);
}
