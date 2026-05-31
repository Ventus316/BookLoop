<?php
// 檔名：api/upload_book.php
// 負責處理書籍捐贈表單與實體圖片上傳

session_start();
require_once '../config/database.php';

// 🛡️ 防護 1：確保使用者已登入
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('請先登入才能捐贈書籍！'); window.location.href='../login.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. 接收前端文字資料
    $title = trim($_POST['btitle']);
    $isbn = trim($_POST['bisbn']);
    $author = trim($_POST['bauthor']);
    $category_id = $_POST['bcategory_id'];
    $donor_id = $_SESSION['user_id']; // 從 Session 抓取真實的捐贈者 ID

    // 2. 圖片上傳處理邏輯
    $image_url = 'assets/images/default.png'; // 若未上傳或失敗，給予預設圖片

    // 檢查是否有上傳檔案，且傳輸過程無錯誤
    if (isset($_FILES['bimage']) && $_FILES['bimage']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['bimage']['tmp_name'];
        $file_name = $_FILES['bimage']['name'];

        // 取得副檔名並轉為小寫 (例如: JPG -> jpg)
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // 🛡️ 防護 2：限制只能上傳圖片格式，防範惡意腳本上傳
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_ext, $allowed_exts)) {
            // 產生唯一檔名，避免檔案覆蓋 (例如: book_64a1b2c3.jpg)
            $new_file_name = uniqid('book_') . '.' . $file_ext;

            // 設定伺服器實體儲存路徑 (對應到外層的 assets/images/)
            $upload_path = '../assets/images/' . $new_file_name;

            // 將暫存檔搬移到正式資料夾
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // 儲存成功，將存入資料庫的路徑更新為真實路徑
                $image_url = 'assets/images/' . $new_file_name;
            }
        } else {
            echo "<script>alert('安全警告：只允許上傳 JPG, PNG, GIF 或 WEBP 格式的圖片！'); history.back();</script>";
            exit;
        }
    }

    // 3. 寫入資料庫
    try {
        $stmt = $conn->prepare("INSERT INTO Book (bisbn, btitle, bauthor, bimage_url, bstatus, bdonor_id, bcategory_id) VALUES (?, ?, ?, ?, 'available', ?, ?)");

        if ($stmt->execute([$isbn, $title, $author, $image_url, $donor_id, $category_id])) {
            echo "<script>alert('書籍捐贈成功！感謝您的分享。'); window.location.href='../user_panel.php';</script>";
        } else {
            echo "<script>alert('系統發生錯誤，請稍後再試。'); history.back();</script>";
        }
    } catch (PDOException $e) {
        // 如果發生 SQL 錯誤，印出錯誤訊息方便除錯
        echo "<script>alert('資料庫寫入失敗：" . $e->getMessage() . "'); history.back();</script>";
    }
}
