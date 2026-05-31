<?php
// 檔名：edit_book.php
// 負責顯示並讓使用者修改自己的書籍資料

session_start();
require_once 'config/database.php';

// 🛡️ 防護 1：未登入者踢回登入頁
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

try {
    // 🛡️ 防護 2：撈取書籍資料，並嚴格限制只能撈出「自己捐的書」
    $stmt = $conn->prepare("SELECT * FROM Book WHERE bbook_id = ? AND bdonor_id = ?");
    $stmt->execute([$book_id, $user_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    // 防呆：如果查無書籍或非擁有者，拒絕進入
    if (!$book) {
        echo "<script>alert('錯誤：查無此書籍或您無權限編輯！'); window.location.href='user_panel.php';</script>";
        exit;
    }
} catch (PDOException $e) {
    die("資料載入失敗：" . $e->getMessage());
}

$page_title = '編輯書籍 - 書活 BookLoop';
?>
<!DOCTYPE html>
<html lang="zh-TW">
<?php include 'components/head.php'; ?>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">
    <?php include 'components/header.php'; ?>

    <main class="flex-grow py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <a href="user_panel.php" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-800 transition mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                返回我的書房
            </a>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-blue-600 px-8 py-10 text-white">
                    <h2 class="text-3xl font-black tracking-tight mb-2">編輯書籍資料</h2>
                    <p class="text-blue-100 text-sm">隨時更新您的書況描述或書籍狀態。</p>
                </div>

                <form action="api/update_book.php" method="POST" enctype="multipart/form-data" class="space-y-6 p-8">
                    <input type="hidden" name="bbook_id" value="<?php echo $book['bbook_id']; ?>">

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">書名 <span class="text-red-500">*</span></label>
                            <input type="text" name="btitle" required value="<?php echo htmlspecialchars($book['btitle']); ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">ISBN 碼</label>
                                <input type="text" name="bisbn" value="<?php echo htmlspecialchars($book['bisbn']); ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">作者</label>
                                <input type="text" name="bauthor" value="<?php echo htmlspecialchars($book['bauthor']); ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">書籍類別 <span class="text-red-500">*</span></label>
                                <select name="bcategory_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 transition bg-white">
                                    <option value="1" <?php echo $book['bcategory_id'] == 1 ? 'selected' : ''; ?>>資訊工程</option>
                                    <option value="2" <?php echo $book['bcategory_id'] == 2 ? 'selected' : ''; ?>>數位設計</option>
                                    <option value="3" <?php echo $book['bcategory_id'] == 3 ? 'selected' : ''; ?>>語言文學</option>
                                    <option value="4" <?php echo $book['bcategory_id'] == 4 ? 'selected' : ''; ?>>通識管理</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">書籍當前狀態 <span class="text-red-500">*</span></label>
                                <select name="bstatus" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 transition bg-white font-bold">
                                    <option value="available" <?php echo $book['bstatus'] === 'available' ? 'selected' : ''; ?>>🟢 待領取 (展示中)</option>
                                    <option value="reserved" <?php echo $book['bstatus'] === 'reserved' ? 'selected' : ''; ?>>🟡 已被預約 (保留中)</option>
                                    <option value="donated" <?php echo $book['bstatus'] === 'donated' ? 'selected' : ''; ?>>⚪ 已面交完成 (隱藏結案)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">重新上傳圖片 <span class="text-xs text-gray-400 font-normal">(若不修改請留空)</span></label>
                            <div class="relative w-full h-32 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-gray-100 hover:border-blue-400 transition flex items-center justify-center cursor-pointer overflow-hidden group" onclick="document.getElementById('bimage').click()">
                                <img src="<?php echo htmlspecialchars($book['bimage_url']); ?>" class="absolute inset-0 w-full h-full object-cover opacity-30 group-hover:opacity-10 transition">
                                <div class="text-center z-10 relative">
                                    <span class="text-sm text-gray-700 font-bold bg-white/80 px-3 py-1 rounded-full shadow-sm">更換封面圖片</span>
                                </div>
                                <input type="file" id="bimage" name="bimage" accept="image/png, image/jpeg" class="hidden">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                        <button type="button" onclick="history.back()" class="px-6 py-2.5 rounded-lg font-bold text-gray-600 hover:bg-gray-100 transition">
                            取消
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-8 py-2.5 rounded-lg font-bold hover:bg-blue-700 transition shadow-md">
                            儲存修改
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>