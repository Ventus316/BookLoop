<?php
// 檔名：api/admin_add_category.php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '未授權訪問']);
    exit;
}

$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);
$cat_name = isset($data['category_name']) ? trim($data['category_name']) : '';

if ($cat_name === '') {
    echo json_encode(['status' => 'error', 'message' => '分類名稱不可為空']);
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

    $stmt = $conn->prepare("INSERT INTO Category (ccategory_name) VALUES (?)");
    $stmt->execute([$cat_name]);
    $new_id = $conn->lastInsertId(); // 取得剛新增的分類 ID

    echo json_encode([
        'status' => 'success',
        'category_id' => $new_id,
        'category_name' => htmlspecialchars($cat_name)
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => '資料庫錯誤']);
}
