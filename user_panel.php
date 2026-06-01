<?php
session_start();
require_once 'config/database.php';

// 🛡️ 防護 1：未登入者踢回登入頁
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // 1. 撈取使用者基本資料
    $user_stmt = $conn->prepare("SELECT * FROM User WHERE user_id = ?");
    $user_stmt->execute([$user_id]);
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    // 2. 📂 區塊 A：撈取「我發布的捐贈書籍」
    $book_query = "SELECT b.*, c.ccategory_name 
                   FROM Book b
                   LEFT JOIN Category c ON b.bcategory_id = c.ccategory_id
                   WHERE b.bdonor_id = ?
                   ORDER BY b.bcreated_at DESC";
    $book_stmt = $conn->prepare($book_query);
    $book_stmt->execute([$user_id]);
    $my_books = $book_stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. 🤝 區塊 B：撈取「我索取的書籍(預約紀錄)」- 串接書籍、分類與捐贈者資訊
    $record_query = "SELECT r.*, b.btitle, b.bimage_url, b.bauthor, c.ccategory_name, u.uname AS donor_name, u.ulocation AS donor_location
                     FROM Record r
                     LEFT JOIN Book b ON r.cbook_id = b.bbook_id
                     LEFT JOIN Category c ON b.bcategory_id = c.ccategory_id
                     LEFT JOIN User u ON b.bdonor_id = u.user_id
                     WHERE r.creceiver_id = ?
                     ORDER BY r.crecord_time DESC";
    $record_stmt = $conn->prepare($record_query);
    $record_stmt->execute([$user_id]);
    $my_records = $record_stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. 計算統計數據
    $total_donated = count($my_books);
    $total_received = count($my_records);
} catch (PDOException $e) {
    die("資料載入失敗：" . $e->getMessage());
}

$page_title = '個人管理後臺 - 書活 BookLoop';
?>
<!DOCTYPE html>
<html lang="zh-TW">
<?php include 'components/head.php'; ?>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">
    <?php include 'components/header.php'; ?>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex justify-between items-end border-b border-gray-200 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900">我的書房</h1>
                <p class="text-gray-500 mt-1">管理您的捐贈紀錄與個人資料</p>
            </div>
            <a href="donate.php" class="bg-brand text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-green-700 transition shadow-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                新增捐贈書籍
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <aside class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-150 text-center">
                    <div class="w-20 h-20 mx-auto rounded-full bg-emerald-50 text-brand flex items-center justify-center text-3xl font-black mb-4">
                        <?php echo mb_substr($user['uname'], 0, 1, 'UTF-8'); ?>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($user['uname']); ?></h2>
                    <p class="text-gray-500 text-sm mt-1"><?php echo htmlspecialchars($user['uemail']); ?></p>

                    <div class="mt-6 flex justify-between items-center text-sm border-t border-gray-100 pt-4">
                        <span class="text-gray-500">常駐地點</span>
                        <span class="font-bold text-gray-800"><?php echo htmlspecialchars($user['ulocation']); ?></span>
                    </div>
                    <div class="mt-3 flex justify-between items-center text-sm">
                        <span class="text-gray-500">累計捐贈</span>
                        <span class="font-bold text-brand"><?php echo $total_donated; ?> 本</span>
                    </div>
                </div>
            </aside>

            <section class="lg:col-span-3 space-y-6">

                <div class="flex border-b border-gray-200">
                    <button id="tab-donated" class="panel-tab border-b-2 border-brand text-brand font-bold px-6 py-3 text-sm transition">
                        📚 我捐贈的書籍 (<?php echo $total_donated; ?>)
                    </button>
                    <button id="tab-received" class="panel-tab border-b-2 border-transparent text-gray-400 font-medium px-6 py-3 text-sm hover:text-gray-700 transition">
                        🤝 我索取的書籍 (<?php echo $total_received; ?>)
                    </button>
                </div>

                <div id="content-donated" class="panel-content grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php if ($total_donated > 0): ?>
                        <?php foreach ($my_books as $book): ?>
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                                <div class="relative h-48 bg-gray-50">
                                    <img src="<?php echo htmlspecialchars($book['bimage_url']); ?>" class="w-full h-full object-cover">
                                    <div class="absolute top-2 right-2 bg-white px-2 py-1 rounded-md text-xs font-bold text-gray-600 shadow-sm opacity-90">
                                        <?php echo $book['bstatus'] === 'available' ? '待領取' : ($book['bstatus'] === 'reserved' ? '已預約' : '已面交'); ?>
                                    </div>
                                </div>
                                <div class="p-4 flex flex-col flex-grow">
                                    <h4 class="font-bold text-gray-900 line-clamp-1 mb-1"><?php echo htmlspecialchars($book['btitle']); ?></h4>
                                    <p class="text-xs text-gray-500 mb-4"><?php echo htmlspecialchars($book['bcategory_name']); ?></p>
                                    <div class="mt-auto pt-3 border-t border-gray-50 flex gap-2">
                                        <a href="edit_book.php?id=<?php echo $book['bbook_id']; ?>" class="w-1/2 text-center py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-bold hover:bg-blue-100 transition">✏️ 編輯</a>
                                        <form action="api/delete_book.php" method="POST" class="w-1/2" onsubmit="return confirm('確定要永久刪除這本書嗎？相關紀錄將一併抹除！');">
                                            <input type="hidden" name="bbook_id" value="<?php echo $book['bbook_id']; ?>">
                                            <button type="submit" class="w-full py-2 bg-red-50 text-red-600 rounded-lg text-sm font-bold hover:bg-red-100 transition">🗑️ 刪除</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-gray-150 border-dashed text-gray-400">
                            您尚未發布任何捐贈書籍。
                        </div>
                    <?php endif; ?>
                </div>

                <div id="content-received" class="panel-content hidden grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php if ($total_received > 0): ?>
                        <?php foreach ($my_records as $record): ?>
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full border-l-4 border-l-yellow-400">
                                <div class="p-5 flex-grow space-y-3">
                                    <div class="flex justify-between items-start">
                                        <span class="text-xs font-mono px-2 py-0.5 bg-gray-100 text-gray-500 rounded font-bold">
                                            <?php echo htmlspecialchars($record['ctrade_code']); ?>
                                        </span>
                                        <span class="text-xs font-bold text-yellow-600">
                                            ● <?php echo $record['crecord_status'] === 'pending' ? '等待面交' : '交易完成'; ?>
                                        </span>
                                    </div>

                                    <div>
                                        <h4 class="font-bold text-gray-900 line-clamp-1 text-base"><?php echo htmlspecialchars($record['btitle']); ?></h4>
                                        <p class="text-xs text-gray-400 mt-0.5">作者：<?php echo htmlspecialchars($record['bauthor'] ?? '未提供'); ?></p>
                                    </div>

                                    <div class="bg-gray-50 p-3 rounded-xl text-xs space-y-1.5 font-medium border border-gray-100">
                                        <div class="text-gray-500">提供同學：<span class="text-gray-800 font-bold">👤 <?php echo htmlspecialchars($record['donor_name']); ?></span></div>
                                        <div class="text-gray-500">面交地點：<span class="text-brand font-bold">📍 <?php echo htmlspecialchars($record['donor_location']); ?></span></div>
                                        <div class="text-gray-500">預約時間：<span class="text-gray-600 font-mono"><?php echo $record['crecord_time']; ?></span></div>
                                    </div>
                                </div>

                                <div class="p-4 bg-gray-50/50 border-t border-gray-50 mt-auto flex gap-2">
                                    <?php if ($record['crecord_status'] === 'pending'): ?>
                                        <form action="api/cancel_reservation.php" method="POST" class="w-full" onsubmit="return confirm('確定要取消這本書的領取預約嗎？這會重新將書籍釋放給全校同學！');">
                                            <input type="hidden" name="ccrecord_id" value="<?php echo $record['ccrecord_id']; ?>">
                                            <button type="submit" class="w-full py-2 bg-white border border-gray-200 text-gray-500 text-xs font-bold rounded-lg hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition shadow-sm">
                                                ❌ 取消預約
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button disabled class="w-full py-2 bg-gray-100 text-gray-400 text-xs font-bold rounded-lg cursor-not-allowed">
                                            🎉 本次流轉已完成
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-gray-150 border-dashed text-gray-400">
                            目前沒有任何索取中的預約紀錄。
                        </div>
                    <?php endif; ?>
                </div>

            </section>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>