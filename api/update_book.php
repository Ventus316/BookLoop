<?php
// 檔名：api/update_book.php
// 負責處理書籍資料的更新與圖片覆蓋機制

session_start();
require_once '../config/database.php';

// 🛡️ 防護 1：未登入者拒絕執行
if (!isset($_SESSION['user_id'])) {
    die("未授權的訪問");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 接收資料
    $book_id = intval($_POST['bbook_id']);
    $user_id = $_SESSION['user_id'];

    $title = trim($_POST['btitle']);
    $isbn = trim($_POST['bisbn']);
    $author = trim($_POST['bauthor']);
    $category_id = intval($_POST['bcategory_id']);
    $status = $_POST['bstatus'];

    try {
        // 🛡️ 防護 2：驗證身分，同時取出舊圖片的路徑以備後續比對
        $stmt = $conn->prepare("SELECT bimage_url FROM Book WHERE bbook_id = ? AND bdonor_id = ?");
        $stmt->execute([$book_id, $user_id]);
        $old_book = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$old_book) {
            echo "<script>alert('錯誤：權限不足或查無此書籍！'); history.back();</script>";
            exit;
        }

        // 預設將現有路徑當作新的路徑
        $final_image_url = $old_book['bimage_url'];

        // 🖼️ 處理圖片上傳更新邏輯
        if (isset($_FILES['bimage']) && $_FILES['bimage']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['bimage']['tmp_name'];
            $file_name = $_FILES['bimage']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($file_ext, $allowed_exts)) {
                $new_file_name = uniqid('book_') . '.' . $file_ext;
                $upload_path = '../assets/images/' . $new_file_name;

                // 若新圖片搬移成功
                if (move_uploaded_file($file_tmp, $upload_path)) {

                    // ⚠️ 清除舊檔案：若舊圖片不是預設圖片，就將其從伺服器實體刪除
                    if ($final_image_url !== 'assets/images/default.png') {
                        $old_file_path = '../' . $final_image_url;
                        if (file_exists($old_file_path)) {
                            unlink($old_file_path);
                        }
                    }

                    // 更新為新的路徑
                    $final_image_url = 'assets/images/' . $new_file_name;
                }
            } else {
                echo "<script>alert('安全警告：只允許上傳圖片格式！'); history.back();</script>";
                exit;
            }
        }

        // 📝 執行 SQL UPDATE 更新資料
        $update_stmt = $conn->prepare("UPDATE Book SET btitle = ?, bisbn = ?, bauthor = ?, bcategory_id = ?, bstatus = ?, bimage_url = ? WHERE bbook_id = ? AND bdonor_id = ?");

        if ($update_stmt->execute([$title, $isbn, $author, $category_id, $status, $final_image_url, $book_id, $user_id])) {
            echo "<script>alert('書籍資料更新成功！'); window.location.href='../user_panel.php';</script>";
        } else {
            echo "<script>alert('更新失敗，請稍後再試。'); history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('系統錯誤：" . $e->getMessage() . "'); history.back();</script>";
    }
}
