<?php
// 模擬第三階段從 Session 驗證的管理員登入狀態
$admin_user = [
    'uname' => '系統管理員',
    'urole' => 'admin'
];

// 模擬全站書籍資料 (用於商品下架管理)
$all_books = [
    ['bbook_id' => 1, 'bisbn' => '9789865021234', 'btitle' => '網頁程式設計：PHP & MySQL 實戰', 'donor_name' => '陳同學', 'bstatus' => 'available'],
    ['bbook_id' => 2, 'bisbn' => '9789573274710', 'btitle' => '設計的心理學', 'donor_name' => '林同學', 'bstatus' => 'available'],
    ['bbook_id' => 3, 'bisbn' => '9789863445343', 'btitle' => '百年孤寂', 'donor_name' => '王同學', 'bstatus' => 'reserved'],
    ['bbook_id' => 5, 'bisbn' => '9789862803144', 'btitle' => '演算法導論 (第四版)', 'donor_name' => '陳同學', 'bstatus' => 'available']
];

// 模擬書籍分類資料 (用於書籍分類管理)
$all_categories = [
    ['ccategory_id' => 1, 'ccategory_name' => '資訊工程'],
    ['ccategory_id' => 2, 'ccategory_name' => '數位設計'],
    ['ccategory_id' => 3, 'ccategory_name' => '語言文學'],
    ['ccategory_id' => 4, 'ccategory_name' => '通識管理']
];

// 模擬全站用戶資料 (用於用戶管理與評分查詢)
$all_users = [
    ['user_id' => 2, 'ustudent_id' => 's1121499', 'uname' => '陳同學', 'uemail' => 'user1@yzu.edu.tw', 'rating' => '4.8', 'status' => 'active'],
    ['user_id' => 3, 'ustudent_id' => 's1121500', 'uname' => '林同學', 'uemail' => 'user2@yzu.edu.tw', 'rating' => '4.5', 'status' => 'active'],
    ['user_id' => 4, 'ustudent_id' => 's1121501', 'uname' => '王同學', 'uemail' => 'user3@yzu.edu.tw', 'rating' => '3.9', 'status' => 'banned']
];
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書活管理員後台 - 內容安全與分類架構</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#059669',
                        admin: '#9f1239' /* 深紅色系 */
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">

    <?php include 'components/header.php'; ?>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <section class="bg-admin p-6 rounded-2xl text-white shadow-md relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-10 z-0"></div>
            <div class="relative z-10 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-black tracking-tight flex items-center gap-2">
                        <span>🛡️</span> 書活管理員後台
                    </h2>
                    <p class="text-rose-100 text-xs mt-1">管理平台內容安全與分類架喚，執行全站數據審查</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 px-4 py-2 rounded-xl text-sm font-bold">
                    身分：<?php echo $admin_user['uname']; ?>
                </div>
            </div>
        </section>

        <section class="space-y-4">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-6">
                    <button id="tab-books" class="admin-tab border-b-2 border-admin text-admin py-3 px-1 text-sm font-bold flex items-center gap-1.5">
                        📖 商品下架管理
                    </button>
                    <button id="tab-categories" class="admin-tab border-b-2 border-transparent text-gray-400 hover:text-gray-600 py-3 px-1 text-sm font-medium flex items-center gap-1.5">
                        🏷️ 書籍分類管理
                    </button>
                    <button id="tab-users" class="admin-tab border-b-2 border-transparent text-gray-400 hover:text-gray-600 py-3 px-1 text-sm font-medium flex items-center gap-1.5">
                        👥 用户帳號管理
                    </button>
                </nav>
            </div>

            <div class="bg-white rounded-2xl border border-gray-150 shadow-sm overflow-hidden">

                <div id="content-books" class="admin-content overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-left">
                        <thead class="bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">書籍資訊</th>
                                <th class="px-6 py-4">ISBN</th>
                                <th class="px-6 py-4">發布者</th>
                                <th class="px-6 py-4">狀態</th>
                                <th class="px-6 py-4 text-right">管理操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
                            <?php foreach ($all_books as $book): ?>
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 font-bold text-gray-900"><?php echo $book['btitle']; ?></td>
                                    <td class="px-6 py-4 font-mono text-gray-400"><?php echo $book['bisbn']; ?></td>
                                    <td class="px-6 py-4 text-gray-500"><?php echo $book['donor_name']; ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold <?php echo $book['bstatus'] === 'available' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                                            <?php echo $book['bstatus'] === 'available' ? '正常展示中' : '已被預約'; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button class="btn-ban-book text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg border border-red-100 transition text-xs font-bold shadow-sm" data-id="<?php echo $book['bbook_id']; ?>">
                                            🚫 強制下架
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div id="content-categories" class="admin-content p-6 space-y-6 hidden">
                    <form id="addCategoryForm" class="flex gap-3 bg-gray-50 p-4 rounded-xl border border-gray-200 max-w-md">
                        <input type="text" id="new-cat-name" required placeholder="輸入新分類名稱 (如: 藝術設計)" class="flex-grow px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:border-admin transition bg-white">
                        <button type="submit" class="bg-admin text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-rose-900 transition shadow-sm whitespace-nowrap">
                            ➕ 增加分類
                        </button>
                    </form>

                    <div class="border border-gray-100 rounded-xl overflow-hidden max-w-md shadow-sm">
                        <table class="min-w-full divide-y divide-gray-100 text-left" id="category-table">
                            <thead class="bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3">分類唯一識別碼 (ID)</th>
                                    <th class="px-6 py-3">分類名稱</th>
                                    <th class="px-6 py-3 text-right">操作</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
                                <?php foreach ($all_categories as $cat): ?>
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-3.5 font-mono text-gray-400"><?php echo $cat['ccategory_id']; ?></td>
                                        <td class="px-6 py-3.5 font-bold text-gray-900 cat-name"><?php echo $cat['ccategory_name']; ?></td>
                                        <td class="px-6 py-3.5 text-right">
                                            <button class="btn-delete-cat text-xs font-bold text-gray-400 hover:text-red-500 transition">刪除</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="content-users" class="admin-content overflow-x-auto hidden">
                    <table class="min-w-full divide-y divide-gray-100 text-left">
                        <thead class="bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">學號</th>
                                <th class="px-6 py-4">用戶姓名</th>
                                <th class="px-6 py-4">電子信箱</th>
                                <th class="px-6 py-4">用戶評分查詢</th>
                                <th class="px-6 py-4">帳號狀態</th>
                                <th class="px-6 py-4 text-right">管理操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
                            <?php foreach ($all_users as $user):
                                $is_banned = $user['status'] === 'banned';
                            ?>
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 font-mono text-gray-500"><?php echo $user['ustudent_id']; ?></td>
                                    <td class="px-6 py-4 font-bold text-gray-900"><?php echo $user['uname']; ?></td>
                                    <td class="px-6 py-4 text-gray-500"><?php echo $user['uemail']; ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1 text-amber-500 font-bold">
                                            <span>⭐</span> <?php echo $user['rating']; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 user-status-cell">
                                        <?php if ($is_banned): ?>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 status-badge">已停權封禁</span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 status-badge">正常運作中</span>
                                        <?php collapse_endif;
                                        endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button class="btn-toggle-user px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition border <?php echo $is_banned ? 'bg-green-50 border-green-200 text-green-600 hover:bg-green-100' : 'bg-rose-50 border-rose-200 text-rose-600 hover:bg-rose-100'; ?>" data-status="<?php echo $user['status']; ?>">
                                            <?php echo $is_banned ? '🔓 解除封禁' : '🔒 帳號封禁'; ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </section>
    </main>

    <?php include 'components/footer.php'; ?>

    <script>
        $(document).ready(function() {

            // 1. 頂部主要管理頁籤 (Tabs) 切換邏輯
            $('.admin-tab').click(function() {
                $('.admin-tab').removeClass('border-admin text-admin font-bold').addClass('border-transparent text-gray-400 font-medium');
                $(this).removeClass('border-transparent text-gray-400 font-medium').addClass('border-admin text-admin font-bold');

                $('.admin-content').addClass('hidden');
                let tabId = $(this).attr('id');
                if (tabId === 'tab-books') $('#content-books').removeClass('hidden');
                if (tabId === 'tab-categories') $('#content-categories').removeClass('hidden');
                if (tabId === 'tab-users') $('#content-users').removeClass('hidden');
            });

            // 2. 書籍強制下架動畫特效
            $('.btn-ban-book').click(function() {
                let row = $(this).closest('tr');
                if (confirm('確定要依據平台內容安全規範，強制下架此二手書籍商品嗎？')) {
                    row.fadeOut(400, function() {
                        $(this).remove();
                    });
                }
            });

            // 3. 書籍分類動態新增與刪除
            let nextCatId = 5; // 模擬遞增主鍵 ID
            $('#addCategoryForm').submit(function(e) {
                e.preventDefault();
                let catName = $('#new-cat-name').val().trim();
                if (catName === '') return;

                let newRow = `
                    <tr class="hover:bg-gray-50/50 transition hidden">
                        <td class="px-6 py-3.5 font-mono text-gray-400">${nextCatId++}</td>
                        <td class="px-6 py-3.5 font-bold text-gray-900 cat-name">${catName}</td>
                        <td class="px-6 py-3.5 text-right">
                            <button class="btn-delete-cat text-xs font-bold text-gray-400 hover:text-red-500 transition">刪除</button>
                        </td>
                    </tr>
                `;

                let $newRow = $(newRow);
                $('#category-table tbody').append($newRow);
                $newRow.fadeIn(300);
                $('#new-cat-name').val('');
            });

            // 監聽動態產生的刪除分類按鈕
            $('#category-table').on('click', '.btn-delete-cat', function() {
                let row = $(this).closest('tr');
                if (confirm(`確定要刪除「${row.find('.cat-name').text()}」分類嗎？`)) {
                    row.fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            });

            // 4. 用戶帳號狀態無重整封禁/解封切換
            $('.btn-toggle-user').click(function() {
                let btn = $(this);
                let currentStatus = btn.attr('data-status');
                let statusCell = btn.closest('tr').find('.user-status-cell');

                if (currentStatus === 'active') {
                    if (confirm('確定要對該用戶實施帳號封禁懲處嗎？')) {
                        btn.attr('data-status', 'banned')
                            .text('🔓 解除封禁')
                            .removeClass('bg-rose-50 border-rose-200 text-rose-600 hover:bg-rose-100')
                            .addClass('bg-green-50 border-green-200 text-green-600 hover:bg-green-100');
                        statusCell.html('<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 status-badge">已停權封禁</span>');
                    }
                } else {
                    btn.attr('data-status', 'active')
                        .text('🔒 帳號封禁')
                        .removeClass('bg-green-50 border-green-200 text-green-600 hover:bg-green-100')
                        .addClass('bg-rose-50 border-rose-200 text-rose-600 hover:bg-rose-100');
                    statusCell.html('<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 status-badge">正常運作中</span>');
                }
            });
        });
    </script>
</body>

</html>