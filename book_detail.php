<?php
// 檔名：book_detail.php
// 負責顯示單一書籍的詳細資料與互動留言板

session_start();
require_once 'config/database.php';

// 1. 安全接收並檢查網址的 id 參數
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($book_id <= 0) {
    header("Location: search.php");
    exit;
}

try {
    // 2. 撈取書籍詳細資料 (串接分類表與用戶表)
    $query = "SELECT b.*, c.ccategory_name, u.uname AS donor_name, u.ulocation AS donor_location
              FROM Book b
              LEFT JOIN Category c ON b.bcategory_id = c.ccategory_id
              LEFT JOIN User u ON b.bdonor_id = u.user_id
              WHERE b.bbook_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->execute([$book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    // 防呆：若查無此書籍，退回尋書大廳
    if (!$book) {
        echo "<script>alert('抱歉，該書籍不存在或已被下架！'); window.location.href='search.php';</script>";
        exit;
    }

    // 3. 統計點讚與收藏數
    $like_stmt = $conn->prepare("SELECT COUNT(*) FROM Interaction WHERE ibook_id = ? AND iinteraction_type = 'like'");
    $like_stmt->execute([$book_id]);
    $like_count = $like_stmt->fetchColumn();

    $collect_stmt = $conn->prepare("SELECT COUNT(*) FROM Interaction WHERE ibook_id = ? AND iinteraction_type = 'collect'");
    $collect_stmt->execute([$book_id]);
    $collect_count = $collect_stmt->fetchColumn();

    $user_has_liked = false;
    $user_has_collected = false;

    if (isset($_SESSION['user_id'])) {
        $check_stmt = $conn->prepare("SELECT iinteraction_type FROM Interaction WHERE ibook_id = ? AND iuser_id = ?");
        $check_stmt->execute([$book_id, $_SESSION['user_id']]);
        $user_interactions = $check_stmt->fetchAll(PDO::FETCH_COLUMN); // 撈出 ['like', 'collect'] 陣列

        if (in_array('like', $user_interactions)) $user_has_liked = true;
        if (in_array('collect', $user_interactions)) $user_has_collected = true;
    }

    // 4. 撈取該書籍的歷史留言清單
    $comment_query = "SELECT i.*, u.uname 
                      FROM Interaction i
                      LEFT JOIN User u ON i.iuser_id = u.user_id
                      WHERE i.ibook_id = ? AND i.iinteraction_type = 'comment'
                      ORDER BY i.iinteraction_id DESC";
    $comment_stmt = $conn->prepare($comment_query);
    $comment_stmt->execute([$book_id]);
    $comments = $comment_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("數據加載失敗：" . $e->getMessage());
}

$page_title = $book['btitle'] . " - 書活 BookLoop";
?>
<!DOCTYPE html>
<html lang="zh-TW">
<?php include 'components/head.php'; ?>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">
    <?php include 'components/header.php'; ?>

    <main class="flex-grow max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <a href="search.php" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand font-medium transition mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            返回尋書大廳
        </a>

        <div class="bg-white rounded-3xl border border-gray-150 shadow-sm overflow-hidden grid grid-cols-1 md:grid-cols-12 gap-8 p-6 md:p-10 mb-8">

            <div class="md:col-span-4 flex justify-center">
                <div class="w-full max-w-[240px] aspect-[3/4] bg-gray-100 rounded-2xl overflow-hidden shadow-md border border-gray-100">
                    <img src="<?php echo htmlspecialchars($book['bimage_url']); ?>" class="w-full h-full object-cover">
                </div>
            </div>

            <div class="md:col-span-8 flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-green-50 text-brand text-xs font-bold rounded-md border border-green-100">
                            <?php echo htmlspecialchars($book['ccategory_name']); ?>
                        </span>
                        <?php
                        $status_styles = [
                            'available' => 'bg-green-100 text-green-700',
                            'reserved' => 'bg-yellow-100 text-yellow-700',
                            'donated' => 'bg-gray-100 text-gray-700'
                        ];
                        $status_texts = ['available' => '待領取', 'reserved' => '已預約', 'donated' => '已面交完成'];
                        $current_style = $status_styles[$book['bstatus']] ?? 'bg-gray-100 text-gray-700';
                        $current_text = $status_texts[$book['bstatus']] ?? '未知';
                        ?>
                        <span class="px-3 py-1 text-xs font-bold rounded-md <?php echo $current_style; ?>">
                            ● <?php echo $current_text; ?>
                        </span>
                    </div>

                    <h1 class="text-3xl font-black text-gray-900 tracking-tight leading-tight">
                        <?php echo htmlspecialchars($book['btitle']); ?>
                    </h1>

                    <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-sm font-medium border-t border-b border-gray-100 py-4">
                        <div class="text-gray-400">主要作者：<span class="text-gray-800 font-bold"><?php echo htmlspecialchars($book['bauthor'] ?? '未提供'); ?></span></div>
                        <div class="text-gray-400">ISBN 碼：<span class="text-gray-800 font-mono"><?php echo htmlspecialchars($book['bisbn'] ?? '未提供'); ?></span></div>
                        <div class="text-gray-400">書籍提供：<span class="text-gray-800 font-bold">👤 <?php echo htmlspecialchars($book['donor_name']); ?></span></div>
                        <div class="text-gray-400">面交地點：<span class="text-brand font-bold">📍 <?php echo htmlspecialchars($book['donor_location']); ?></span></div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4 pt-4">
                    <?php
                    $like_class = $user_has_liked ? 'text-red-500 bg-red-50 border-red-200' : 'text-gray-650 border-gray-200';
                    $like_fill = $user_has_liked ? 'currentColor' : 'none';


                    $collect_class = $user_has_collected ? 'text-yellow-600 bg-yellow-50 border-yellow-200' : 'text-gray-650 border-gray-200';
                    $collect_fill = $user_has_collected ? 'currentColor' : 'none';
                    ?>

                    <button id="btn-like" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border <?php echo $like_class; ?> hover:text-red-500 font-bold text-sm hover:bg-red-50 hover:border-red-100 transition shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 icon-heart" fill="<?php echo $like_fill; ?>" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        點讚 (<span id="like-count"><?php echo $like_count; ?></span>)
                    </button>

                    <button id="btn-collect" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border <?php echo $collect_class; ?> hover:text-yellow-600 font-bold text-sm hover:bg-yellow-50 hover:border-yellow-100 transition shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 icon-bookmark" fill="<?php echo $collect_fill; ?>" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                        收藏 (<span id="collect-count"><?php echo $collect_count; ?></span>)
                    </button>

                    <?php if ($book['bstatus'] === 'available'): ?>
                        <button class="bg-brand text-white px-8 py-2.5 rounded-xl text-sm font-bold hover:bg-green-700 transition shadow-md ml-auto">
                            🤝 申請預約領取
                        </button>
                    <?php else: ?>
                        <button disabled class="bg-gray-200 text-gray-400 px-8 py-2.5 rounded-xl text-sm font-bold cursor-not-allowed ml-auto">
                            🔒 暫不可預約
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        ---

        <div class="bg-white rounded-3xl border border-gray-150 shadow-sm p-6 md:p-10 space-y-6">
            <h3 class="text-lg font-black text-gray-900 flex items-center gap-2 mb-2">
                <span>💬</span> 書況留言詢問區
            </h3>

            <form id="commentForm" class="flex gap-3">
                <input type="text" id="comment-input" required placeholder="想詢問書況描述或面交時間嗎？請輸入留言..." class="flex-grow px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-green-100 transition">
                <button type="submit" class="bg-brand text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-green-700 transition shadow-sm whitespace-nowrap">
                    發表留言
                </button>
            </form>

            <div id="comment-list" class="space-y-4 pt-2">
                <?php if (count($comments) > 0): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="flex gap-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div class="w-8 h-8 rounded-full bg-emerald-50 text-brand flex-shrink-0 flex items-center justify-center font-black text-xs">
                                <?php echo mb_substr($comment['uname'], 0, 1, 'UTF-8'); ?>
                            </div>
                            <div>
                                <div class="flex items-baseline gap-2 mb-1">
                                    <span class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($comment['uname']); ?></span>
                                    <span class="text-xs text-gray-400 font-mono"><?php echo $comment['icreated_at']; ?></span>
                                </div>
                                <p class="text-sm text-gray-600 leading-relaxed"><?php echo htmlspecialchars($comment['icontent']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div id="empty-state" class="text-center py-10 text-gray-400 italic text-sm">
                        目前這本書還沒有任何提問，有疑問歡迎留言與發布者交流！
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>