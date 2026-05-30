<?php
// 模擬第三階段從 Session 撈取的登入用戶資訊
$current_user = [
    'uname' => '王同學',
    'ustudent_id' => 's1121501'
];

// 模擬從資料庫撈出的「我捐贈的書籍」列表
$my_donated_books = [
    ['bbook_id' => 1, 'bisbn' => '9789865021234', 'btitle' => '網頁程式設計：PHP & MySQL 實戰', 'bstatus' => 'available', 'bimage_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=150'],
    ['bbook_id' => 2, 'bisbn' => '9789573274710', 'btitle' => '設計的心理學', 'bstatus' => 'reserved', 'bimage_url' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?q=80&w=150'],
    ['bbook_id' => 4, 'bisbn' => '9789862803151', 'btitle' => '微積分 (下)', 'bstatus' => 'donated', 'bimage_url' => 'https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?q=80&w=150']
];

// 模擬「我收藏的書籍」
$my_collected_books = [
    ['bbook_id' => 5, 'bisbn' => '9789862803144', 'btitle' => '演算法導論 (第四版)', 'bstatus' => 'available', 'bimage_url' => 'https://images.unsplash.com/photo-1474932430478-367d16b99031?q=80&w=150']
];

// 模擬「我領取的書籍」
$my_received_books = [
    ['bbook_id' => 6, 'bisbn' => '9789862017050', 'btitle' => 'Clean Code 無瑕的程式碼', 'bstatus' => 'donated', 'bimage_url' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=150']
];
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>個人管理後台 - 書活 BookLoop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#059669',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">

    <?php include 'components/header.php'; ?>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-emerald-50 text-brand flex items-center justify-center text-2xl font-black shadow-inner">
                    <?php echo mb_substr($current_user['uname'], 0, 1, 'UTF-8'); ?>
                </div>
                <div>
                    <h2 class="text-xl font-black text-gray-900">個人管理後台</h2>
                    <p class="text-sm text-gray-500">歡迎回來，<span class="font-bold text-gray-700"><?php echo $current_user['uname']; ?></span>（<?php echo $current_user['ustudent_id']; ?>）</p>
                </div>
            </div>
            <a href="api/auth_process.php?logout=1" class="text-sm font-bold text-red-500 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-xl border border-red-100 transition flex items-center gap-1.5">
                <span>🚪</span> 登出系統
            </a>
        </section>

        <section class="space-y-4">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-6" aria-label="Tabs">
                    <button id="tab-donated" class="panel-tab border-b-2 border-brand text-brand py-3 px-1 text-sm font-bold flex items-center gap-2">
                        📦 我的捐贈書籍 (<span class="text-xs"><?php echo count($my_donated_books); ?></span>)
                    </button>
                    <button id="tab-collected" class="panel-tab border-b-2 border-transparent text-gray-400 hover:text-gray-600 hover:border-gray-300 py-3 px-1 text-sm font-medium flex items-center gap-2">
                        ❤️ 我的收藏紀錄 (<span class="text-xs"><?php echo count($my_collected_books); ?></span>)
                    </button>
                    <button id="tab-received" class="panel-tab border-b-2 border-transparent text-gray-400 hover:text-gray-600 hover:border-gray-300 py-3 px-1 text-sm font-medium flex items-center gap-2">
                        📥 我的領取紀錄 (<span class="text-xs"><?php echo count($my_received_books); ?></span>)
                    </button>
                </nav>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                <div id="content-donated" class="panel-content overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-left">
                        <thead class="bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">封面與書名</th>
                                <th class="px-6 py-4">ISBN</th>
                                <th class="px-6 py-4">目前狀態</th>
                                <th class="px-6 py-4 text-right">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
                            <?php foreach ($my_donated_books as $book):
                                $status_text = $book['bstatus'] === 'available' ? '待領取' : ($book['bstatus'] === 'reserved' ? '已預約' : '已捐出');
                                $status_color = $book['bstatus'] === 'available' ? 'bg-green-100 text-green-700' : ($book['bstatus'] === 'reserved' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700');
                            ?>
                                <tr class="hover:bg-gray-50/50 transition duration-150 group">
                                    <td class="px-6 py-4 flex items-center gap-4">
                                        <img src="<?php echo $book['bimage_url']; ?>" class="w-10 h-14 object-cover rounded bg-gray-100 shadow-sm">
                                        <span class="font-bold text-gray-900 group-hover:text-brand transition"><?php echo $book['btitle']; ?></span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-gray-500"><?php echo $book['bisbn']; ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold <?php echo $status_color; ?>"><?php echo $status_text; ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button class="text-gray-400 hover:text-brand transition p-1 text-xs font-bold border border-gray-200 rounded-md bg-white hover:border-brand shadow-sm">修改內容</button>
                                        <button class="btn-delete-book text-gray-400 hover:text-red-500 transition p-1 text-xs font-bold border border-gray-200 rounded-md bg-white hover:border-red-200 shadow-sm" data-id="<?php echo $book['bbook_id']; ?>">刪除資料</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div id="content-collected" class="panel-content overflow-x-auto hidden">
                    <table class="min-w-full divide-y divide-gray-100 text-left">
                        <thead class="bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">封面與書名</th>
                                <th class="px-6 py-4">ISBN</th>
                                <th class="px-6 py-4">書籍現狀</th>
                                <th class="px-6 py-4 text-right">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
                            <?php foreach ($my_collected_books as $book): ?>
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 flex items-center gap-4">
                                        <img src="<?php echo $book['bimage_url']; ?>" class="w-10 h-14 object-cover rounded bg-gray-100 shadow-sm">
                                        <a href="book_detail.php?id=<?php echo $book['bbook_id']; ?>" class="font-bold text-gray-900 hover:text-brand transition"><?php echo $book['btitle']; ?></a>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-gray-500"><?php echo $book['bisbn']; ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">待領取</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button class="text-xs text-red-500 font-bold hover:underline">取消收藏</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div id="content-received" class="panel-content overflow-x-auto hidden">
                    <table class="min-w-full divide-y divide-gray-100 text-left">
                        <thead class="bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">封面與書名</th>
                                <th class="px-6 py-4">ISBN</th>
                                <th class="px-6 py-4">交易進度</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
                            <?php foreach ($my_received_books as $book): ?>
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 flex items-center gap-4">
                                        <img src="<?php echo $book['bimage_url']; ?>" class="w-10 h-14 object-cover rounded bg-gray-100 shadow-sm">
                                        <span class="font-bold text-gray-900"><?php echo $book['btitle']; ?></span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-gray-500"><?php echo $book['bisbn']; ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-150 text-blue-600 bg-blue-50">面交完成</span>
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

            // 1. 頁籤切換互動邏輯
            $('.panel-tab').click(function() {
                // 移除所有頁籤的選取樣式，並賦予未選取樣式
                $('.panel-tab').removeClass('border-brand text-brand font-bold').addClass('border-transparent text-gray-400 font-medium');
                // 為當前點擊頁籤加上選取樣式
                $(this).removeClass('border-transparent text-gray-400 font-medium').addClass('border-brand text-brand font-bold');

                // 隱藏所有表格內容
                $('.panel-content').addClass('hidden');

                // 依據點擊的頁籤 ID 顯示對應的表格內容
                let tabId = $(this).attr('id');
                if (tabId === 'tab-donated') $('#content-donated').removeClass('hidden');
                if (tabId === 'tab-collected') $('#content-collected').removeClass('hidden');
                if (tabId === 'tab-received') $('#content-received').removeClass('hidden');
            });

            // 2. 動態刪除書籍互動 (DOM 移除與特效)
            $('.btn-delete-book').click(function() {
                let bookId = $(this).data('id');
                let trRow = $(this).closest('tr'); // 抓到對應的表格行 (Row)

                if (confirm('確定要永久刪除這本已捐贈的書籍資料嗎？')) {
                    // 模擬前端直接刪除動畫特效 (第三階段將補上真實的 AJAX 後端剔除請求)
                    trRow.fadeOut(400, function() {
                        $(this).remove();
                    });
                }
            });
        });
    </script>
</body>

</html>