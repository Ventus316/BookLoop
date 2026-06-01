<?php
session_start();
require_once 'config/database.php';

// 🛡️ 終極防護：驗證是否為登入狀態
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 🛡️ 權限防護：再次向資料庫確認是否真的是 admin
$user_id = $_SESSION['user_id'];
try {
    $auth_stmt = $conn->prepare("SELECT urole, uname, uemail FROM User WHERE user_id = ?");
    $auth_stmt->execute([$user_id]);
    $current_user = $auth_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$current_user || $current_user['urole'] !== 'admin') {
        echo "<script>alert('🛑 警告：您沒有管理員權限，系統將自動導回首頁！'); window.location.href='index.php';</script>";
        exit;
    }

    // 📂 1. 撈取「全站所有書籍」
    $all_books_stmt = $conn->query("SELECT b.*, c.ccategory_name, u.uname AS donor_name 
                                    FROM Book b
                                    LEFT JOIN Category c ON b.bcategory_id = c.ccategory_id
                                    LEFT JOIN User u ON b.bdonor_id = u.user_id
                                    ORDER BY b.bcreated_at DESC");
    $all_books = $all_books_stmt->fetchAll(PDO::FETCH_ASSOC);

    // 🏷️ 2. 撈取「全站書籍分類」
    $all_categories_stmt = $conn->query("SELECT * FROM Category ORDER BY ccategory_id ASC");
    $all_categories = $all_categories_stmt->fetchAll(PDO::FETCH_ASSOC);

    // 👥 3. 撈取「全站所有註冊用戶」
    $all_users_stmt = $conn->query("SELECT * FROM User ORDER BY user_id DESC");
    $all_users = $all_users_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("管理員數據加載失敗：" . $e->getMessage());
}

$page_title = '最高權限管理中心 - 書活 BookLoop';
?>
<!DOCTYPE html>
<html lang="zh-TW">
<?php include 'components/head.php'; ?>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">
    <?php include 'components/header.php'; ?>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex justify-between items-end border-b border-gray-200 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 flex items-center gap-2">
                    <span>👑</span> 系統管理中心
                </h1>
                <p class="text-gray-500 mt-1">擁有最高權限的數據監控與社群秩序維護</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-gray-900">歡迎回來，<?php echo htmlspecialchars($current_user['uname']); ?> 總管</p>
                <p class="text-xs text-gray-500 font-mono"><?php echo htmlspecialchars($current_user['uemail']); ?></p>
            </div>
        </div>

        <div class="flex border-b border-gray-200 mb-6 bg-white rounded-t-2xl px-4 pt-2 shadow-sm">
            <button id="tab-books" class="admin-tab border-b-2 border-indigo-600 text-indigo-700 font-bold px-6 py-4 text-sm transition">
                📚 全站書籍管理 (<?php echo count($all_books); ?>)
            </button>
            <button id="tab-categories" class="admin-tab border-b-2 border-transparent text-gray-400 font-medium px-6 py-4 text-sm hover:text-gray-700 transition">
                🏷️ 分類架構管理 (<?php echo count($all_categories); ?>)
            </button>
            <button id="tab-users" class="admin-tab border-b-2 border-transparent text-gray-400 font-medium px-6 py-4 text-sm hover:text-gray-700 transition">
                👥 用戶停權系統 (<?php echo count($all_users); ?>)
            </button>
        </div>

        <div id="content-books" class="admin-content">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-sm">
                            <th class="px-6 py-4 font-bold text-gray-600">ID</th>
                            <th class="px-6 py-4 font-bold text-gray-600">書籍資訊</th>
                            <th class="px-6 py-4 font-bold text-gray-600">發布者</th>
                            <th class="px-6 py-4 font-bold text-gray-600">當前狀態</th>
                            <th class="px-6 py-4 font-bold text-gray-600 text-right">管理操作</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <?php foreach ($all_books as $book): ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 font-mono text-gray-500"><?php echo $book['bbook_id']; ?></td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900"><?php echo htmlspecialchars($book['btitle']); ?></p>
                                    <p class="text-xs text-gray-400 mt-0.5">分類：<?php echo htmlspecialchars($book['ccategory_name']); ?></p>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-700">👤 <?php echo htmlspecialchars($book['donor_name']); ?></td>
                                <td class="px-6 py-4">
                                    <?php
                                    $status_badge = ['available' => 'bg-green-100 text-green-700', 'reserved' => 'bg-yellow-100 text-yellow-700', 'donated' => 'bg-gray-100 text-gray-700'];
                                    $status_text = ['available' => '待領取', 'reserved' => '已預約', 'donated' => '已結案'];
                                    $badge_class = $status_badge[$book['bstatus']] ?? 'bg-gray-100 text-gray-700';
                                    $text = $status_text[$book['bstatus']] ?? '未知';
                                    ?>
                                    <span class="px-2.5 py-1 rounded-md text-xs font-bold <?php echo $badge_class; ?>">● <?php echo $text; ?></span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="api/admin_delete_book.php" method="POST" onsubmit="return confirm('⚠️ 確定要強制下架這本書嗎？此動作將連帶刪除所有留言與交易紀錄，且無法復原！');">
                                        <input type="hidden" name="bbook_id" value="<?php echo $book['bbook_id']; ?>">
                                        <button type="submit" class="text-xs font-bold text-red-500 hover:text-white bg-red-50 hover:bg-red-600 px-3 py-1.5 rounded-lg border border-red-100 hover:border-red-600 transition shadow-sm">
                                            🚨 強制下架
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="content-categories" class="admin-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-1">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-150">
                        <h3 class="font-black text-gray-900 mb-4 text-lg">新增書籍分類</h3>
                        <form id="addCategoryForm" class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">分類名稱 <span class="text-red-500">*</span></label>
                                <input type="text" id="new-cat-name" required placeholder="例如：心理學" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition">
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2.5 rounded-lg hover:bg-indigo-700 transition">
                                ➕ 新增分類
                            </button>
                        </form>
                    </div>
                </div>
                <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
                    <table class="w-full text-left border-collapse" id="category-table">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-sm">
                                <th class="px-6 py-4 font-bold text-gray-600">ID</th>
                                <th class="px-6 py-4 font-bold text-gray-600">分類名稱</th>
                                <th class="px-6 py-4 font-bold text-gray-600 text-right">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <?php foreach ($all_categories as $cat): ?>
                                <tr class="hover:bg-gray-50/50 transition duration-300" id="cat-row-<?php echo $cat['ccategory_id']; ?>">
                                    <td class="px-6 py-3.5 font-mono text-gray-400"><?php echo $cat['ccategory_id']; ?></td>
                                    <td class="px-6 py-3.5 font-bold text-gray-900 cat-name"><?php echo htmlspecialchars($cat['ccategory_name']); ?></td>
                                    <td class="px-6 py-3.5 text-right">
                                        <button data-id="<?php echo $cat['ccategory_id']; ?>" class="btn-delete-cat text-xs font-bold text-gray-400 hover:text-red-500 transition">刪除</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="content-users" class="admin-content hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-sm">
                            <th class="px-6 py-4 font-bold text-gray-600">學號 / ID</th>
                            <th class="px-6 py-4 font-bold text-gray-600">使用者資訊</th>
                            <th class="px-6 py-4 font-bold text-gray-600">權限級別</th>
                            <th class="px-6 py-4 font-bold text-gray-600">帳號狀態</th>
                            <th class="px-6 py-4 font-bold text-gray-600 text-right">懲處操作</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <?php foreach ($all_users as $u): ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900"><?php echo htmlspecialchars($u['ustudent_id']); ?></div>
                                    <div class="text-xs text-gray-400 font-mono mt-0.5">UID: <?php echo $u['user_id']; ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900"><?php echo htmlspecialchars($u['uname']); ?></p>
                                    <p class="text-xs text-gray-500 mt-0.5"><?php echo htmlspecialchars($u['uemail']); ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($u['urole'] === 'admin'): ?>
                                        <span class="text-indigo-600 font-black text-xs bg-indigo-50 px-2 py-1 rounded">👑 Admin</span>
                                    <?php else: ?>
                                        <span class="text-gray-500 font-bold text-xs bg-gray-100 px-2 py-1 rounded">User</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 user-status-cell">
                                    <?php if ($u['status'] === 'active'): ?>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 status-badge">正常運作中</span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 status-badge">已停權封禁</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <?php if ($u['urole'] !== 'admin'): ?>
                                        <button class="btn-toggle-user text-xs font-bold <?php echo $u['status'] === 'active' ? 'bg-rose-50 border-rose-200 text-rose-600 hover:bg-rose-100' : 'bg-green-50 border-green-200 text-green-600 hover:bg-green-100'; ?> px-3 py-1.5 rounded-lg border transition shadow-sm" data-status="<?php echo $u['status']; ?>">
                                            <?php echo $u['status'] === 'active' ? '🔒 帳號封禁' : '🔓 解除封禁'; ?>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-gray-300 font-bold text-xs">不受懲處</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>