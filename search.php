<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>尋書大廳 - 書活 BookLoop</title>
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

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">尋書大廳</h1>
            <p class="text-gray-500 text-sm mt-1">在這裡尋找你需要的課本、參考書與課外讀物</p>
        </div>

        <div class="flex flex-col md:flex-row gap-8">

            <aside class="w-full md:w-64 flex-shrink-0 space-y-6">
                <form id="filterForm" class="bg-white p-6 rounded-2xl border border-gray-150 shadow-sm space-y-6">

                    <div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span>📌</span> 書籍狀態
                        </h3>
                        <div class="space-y-2.5">
                            <label class="flex items-center text-sm text-gray-600 font-medium cursor-pointer group">
                                <input type="checkbox" name="status[]" value="available" checked class="w-4 h-4 rounded text-brand focus:ring-brand border-gray-300 mr-2.5 accent-brand">
                                <span class="group-hover:text-gray-900 transition">僅顯示「待領取」</span>
                            </label>
                            <label class="flex items-center text-sm text-gray-600 font-medium cursor-pointer group">
                                <input type="checkbox" name="status[]" value="reserved" class="w-4 h-4 rounded text-brand focus:ring-brand border-gray-300 mr-2.5 accent-brand">
                                <span class="group-hover:text-gray-900 transition">顯示已預約</span>
                            </label>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span>📂</span> 書籍類別
                        </h3>
                        <div class="space-y-2.5">
                            <label class="flex items-center text-sm text-gray-600 font-medium cursor-pointer group">
                                <input type="checkbox" name="category[]" value="1" class="w-4 h-4 rounded text-brand focus:ring-brand border-gray-300 mr-2.5 accent-brand">
                                <span class="group-hover:text-gray-900 transition">資訊工程</span>
                            </label>
                            <label class="flex items-center text-sm text-gray-600 font-medium cursor-pointer group">
                                <input type="checkbox" name="category[]" value="2" class="w-4 h-4 rounded text-brand focus:ring-brand border-gray-300 mr-2.5 accent-brand">
                                <span class="group-hover:text-gray-900 transition">數位設計</span>
                            </label>
                            <label class="flex items-center text-sm text-gray-600 font-medium cursor-pointer group">
                                <input type="checkbox" name="category[]" value="3" class="w-4 h-4 rounded text-brand focus:ring-brand border-gray-300 mr-2.5 accent-brand">
                                <span class="group-hover:text-gray-900 transition">語言文學</span>
                            </label>
                            <label class="flex items-center text-sm text-gray-600 font-medium cursor-pointer group">
                                <input type="checkbox" name="category[]" value="4" class="w-4 h-4 rounded text-brand focus:ring-brand border-gray-300 mr-2.5 accent-brand">
                                <span class="group-hover:text-gray-900 transition">通識管理</span>
                            </label>
                        </div>
                    </div>

                </form>
            </aside>

            <section class="flex-grow space-y-6">

                <div class="relative w-full shadow-sm rounded-2xl">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="search-input" name="keyword" placeholder="搜尋書名、作者、ISBN..." class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:border-brand focus:ring-2 focus:ring-green-100 font-medium transition placeholder-gray-400">
                </div>

                <div class="text-sm text-gray-500 font-medium px-1">
                    找到 <span id="result-count" class="text-brand font-bold text-base">5</span> 本書籍
                </div>

                <div id="book-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    // 模擬第二階段切版的假資料陣列 (第三階段會換成 PDO 從資料庫撈取真實資料)
                    $mock_search_results = [
                        ['bstatus' => 'available', 'bimage_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=400', 'ccategory_name' => '資訊工程', 'btitle' => '網頁程式設計：PHP & MySQL 實戰', 'bauthor' => '張教授', 'donor_name' => '陳同學'],
                        ['bstatus' => 'available', 'bimage_url' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?q=80&w=400', 'ccategory_name' => '數位設計', 'btitle' => '設計的心理學', 'bauthor' => 'Don Norman', 'donor_name' => '林同學'],
                        ['bstatus' => 'reserved', 'bimage_url' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=400', 'ccategory_name' => '資訊工程', 'btitle' => 'Clean Code 無瑕的程式碼', 'bauthor' => 'Robert C. Martin', 'donor_name' => '王同學'],
                        ['bstatus' => 'available', 'bimage_url' => 'https://images.unsplash.com/photo-1474932430478-367d16b99031?q=80&w=400', 'ccategory_name' => '語言文學', 'btitle' => '百年孤寂', 'bauthor' => 'Gabriel García Márquez', 'donor_name' => '陳同學'],
                        ['bstatus' => 'donated', 'bimage_url' => 'https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?q=80&w=400', 'ccategory_name' => '通識管理', 'btitle' => '微積分 (下)', 'bauthor' => 'James Stewart', 'donor_name' => '林同學']
                    ];

                    // 迴圈載入共用卡片
                    foreach ($mock_search_results as $book) {
                        include 'components/book_card.php';
                    }
                    ?>
                </div>

            </section>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

    <script>
        $(document).ready(function() {
            // 監聽關鍵字輸入框事件
            $('#search-input').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                // 這裡預留第四階段的 AJAX 或前端篩選邏輯
                console.log("正在搜尋關鍵字: " + value);
            });

            // 監聽側邊欄核取方塊變更
            $('input[type="checkbox"]').change(function() {
                // 這裡預留第四階段動態發送篩選請求的程式碼
                console.log("篩選條件已變更");
            });
        });
    </script>
</body>

</html>