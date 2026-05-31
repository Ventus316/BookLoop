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

    // 2. 撈取該使用者的所有捐贈書籍 (依照時間倒序)
    $book_query = "SELECT b.*, c.ccategory_name 
                   FROM Book b
                   LEFT JOIN Category c ON b.bcategory_id = c.ccategory_id
                   WHERE b.bdonor_id = ?
                   ORDER BY b.bcreated_at DESC";
    $book_stmt = $conn->prepare($book_query);
    $book_stmt->execute([$user_id]);
    $my_books = $book_stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. 計算統計數據
    $total_books = count($my_books);
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
                        <span class="font-bold text-brand"><?php echo $total_books; ?> 本</span>
                    </div>
                </div>
            </aside>

            <section class="lg:col-span-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span>📚</span> 我發布的書籍
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php if ($total_books > 0): ?>
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
                                        <a href="edit_book.php?id=<?php echo $book['bbook_id']; ?>" class="w-1/2 text-center py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-bold hover:bg-blue-100 transition">
                                            ✏️ 編輯
                                        </a>

                                        <form action="api/delete_book.php" method="POST" class="w-1/2" onsubmit="return confirm('確定要永久刪除這本書嗎？相關的留言與互動紀錄也會一併刪除！');">
                                            <input type="hidden" name="bbook_id" value="<?php echo $book['bbook_id']; ?>">
                                            <button type="submit" class="w-full py-2 bg-red-50 text-red-600 rounded-lg text-sm font-bold hover:bg-red-100 transition">
                                                🗑️ 刪除
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-gray-150 border-dashed text-gray-400">
                            您尚未發布任何捐贈書籍。<br>
                            <a href="donate.php" class="text-brand font-bold hover:underline mt-2 inline-block">立刻捐贈第一本書！</a>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>