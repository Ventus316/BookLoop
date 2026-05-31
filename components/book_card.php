<?php
// 檔名：components/book_card.php
// 測試用假資料防呆 (若外部沒有傳入 $book，則給予預設值)
if (!isset($book)) {
    $book = [
        'bbook_id' => 1, // 💡 新增防呆 ID
        'bstatus' => 'available',
        'bimage_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=400&auto=format&fit=crop',
        'ccategory_name' => '資訊工程',
        'btitle' => '演算法導論 (第四版)',
        'bauthor' => 'Thomas H. Cormen',
        'donor_name' => '陳同學'
    ];
}

// 根據書籍狀態切換 Tailwind 標籤顏色
$status_bg = 'bg-green-100 text-green-700';
$status_dot = 'bg-green-500';
$status_text = '待領取';

if ($book['bstatus'] === 'reserved') {
    $status_bg = 'bg-yellow-100 text-yellow-700';
    $status_dot = 'bg-yellow-500';
    $status_text = '已預約';
} elseif ($book['bstatus'] === 'donated') {
    $status_bg = 'bg-gray-100 text-gray-700';
    $status_dot = 'bg-gray-500';
    $status_text = '已捐出';
}
?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col h-full">

    <a href="book_detail.php?id=<?php echo $book['bbook_id']; ?>" class="relative h-64 overflow-hidden bg-gray-50 block group">
        <div class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 <?php echo $status_bg; ?> z-10 backdrop-blur-sm bg-opacity-90">
            <span class="w-2 h-2 rounded-full <?php echo $status_dot; ?>"></span>
            <?php echo $status_text; ?>
        </div>
        <img src="<?php echo htmlspecialchars($book['bimage_url']); ?>" alt="<?php echo htmlspecialchars($book['btitle']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
    </a>

    <div class="p-5 flex flex-col flex-grow">
        <div class="text-xs font-bold text-brand bg-green-50 inline-block px-2 py-1 rounded mb-2 w-max">
            <?php echo htmlspecialchars($book['ccategory_name']); ?>
        </div>

        <h3 class="text-lg font-bold text-gray-800 mb-1 line-clamp-1" title="<?php echo htmlspecialchars($book['btitle']); ?>">
            <a href="book_detail.php?id=<?php echo $book['bbook_id']; ?>" class="hover:text-brand transition">
                <?php echo htmlspecialchars($book['btitle']); ?>
            </a>
        </h3>

        <p class="text-sm text-gray-500 mb-4 line-clamp-1"><?php echo htmlspecialchars($book['bauthor']); ?></p>

        <div class="mt-auto pt-4 border-t border-gray-50 flex justify-between items-center">
            <span class="text-xs text-gray-400">捐贈者</span>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-600 font-bold">
                    <?php echo mb_substr($book['donor_name'], 0, 1, 'UTF-8'); ?>
                </div>
                <span class="text-sm text-gray-600 font-medium"><?php echo htmlspecialchars($book['donor_name']); ?></span>
            </div>
        </div>
    </div>
</div>