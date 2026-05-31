<?php
// 檔名：api/auth_process.php
// 負責處理註冊、登入、登出的後端邏輯

// 1. 啟動 Session (非常重要！必須放在檔案最頂部)
session_start();

// 2. 引入資料庫連線
require_once '../config/database.php';

// ==========================================
// 💡 登出邏輯 (接收 GET 請求)
// ==========================================
if (isset($_GET['logout'])) {
    session_unset();    // 清空所有 Session 變數
    session_destroy();  // 銷毀 Session
    header("Location: ../index.php"); // 導回首頁
    exit;
}

// ==========================================
// 💡 登入與註冊邏輯 (接收 POST 請求)
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 取得表單隱藏欄位 (判斷是 login 還是 register)
    $action = $_POST['action'] ?? '';

    // ------------------------------------------
    // 📝 處理註冊 (Register)
    // ------------------------------------------
    if ($action === 'register') {
        $student_id = trim($_POST['ustudent_id']);
        $name = trim($_POST['uname']);
        $email = trim($_POST['uemail']);
        $password = $_POST['upassword'];
        $location = trim($_POST['ulocation']);

        // 防護 1：檢查信箱或學號是否已存在 (利用 PDO 避免 SQL Injection)
        $check_stmt = $conn->prepare("SELECT user_id FROM User WHERE uemail = ? OR ustudent_id = ?");
        $check_stmt->execute([$email, $student_id]);
        if ($check_stmt->rowCount() > 0) {
            echo "<script>alert('此 Email 或學號已被註冊過了！'); history.back();</script>";
            exit;
        }

        // 防護 2：對明文密碼進行不可逆的 BCRYPT 加密
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 寫入資料庫 (urole 預設為 user，status 預設為 active)
        $insert_stmt = $conn->prepare("INSERT INTO User (ustudent_id, uname, uemail, upassword, ulocation, urole, status) VALUES (?, ?, ?, ?, ?, 'user', 'active')");

        if ($insert_stmt->execute([$student_id, $name, $email, $hashed_password, $location])) {
            // 註冊成功後，自動幫使用者登入 (寫入 Session)
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['uname'] = $name;
            $_SESSION['urole'] = 'user';

            echo "<script>alert('註冊成功！歡迎加入書活 BookLoop。'); window.location.href='../index.php';</script>";
        } else {
            echo "<script>alert('系統發生錯誤，請稍後再試。'); history.back();</script>";
        }
        exit;
    }

    // ------------------------------------------
    // 🔑 處理登入 (Login)
    // ------------------------------------------
    if ($action === 'login') {
        $email = trim($_POST['uemail']);
        $password = $_POST['upassword'];

        // 從資料庫撈取該 Email 的使用者資料
        $stmt = $conn->prepare("SELECT * FROM User WHERE uemail = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 防護 1：驗證帳號存在，且密碼比對正確
        if ($user && password_verify($password, $user['upassword'])) {

            // 防護 2：檢查帳號是否被管理員封禁
            if ($user['status'] === 'banned') {
                echo "<script>alert('您的帳號已被系統管理員停權，請聯繫客服。'); history.back();</script>";
                exit;
            }

            // 登入成功，將核心資訊寫入 Session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['uname'] = $user['uname'];
            $_SESSION['urole'] = $user['urole'];

            // 根據權限 (urole) 導向不同頁面
            if ($user['urole'] === 'admin') {
                echo "<script>alert('管理員身分驗證成功！'); window.location.href='../admin_panel.php';</script>";
            } else {
                echo "<script>alert('登入成功！歡迎回來。'); window.location.href='../index.php';</script>";
            }
        } else {
            echo "<script>alert('帳號或密碼輸入錯誤！'); history.back();</script>";
        }
        exit;
    }
}
