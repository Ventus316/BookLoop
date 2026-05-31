<?php $page_title = '書活 BookLoop | 讓知識在校園流動'; ?>
<!DOCTYPE html>
<html lang="zh-TW">
<?php include 'components/head.php'; ?>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">

    <?php include 'components/header.php'; ?>

    <main class="flex-grow">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 flex flex-col md:flex-row items-center justify-between gap-12">
            <div class="md:w-1/2 space-y-6">
                <div class="inline-block px-4 py-1.5 bg-yellow-100 text-yellow-800 text-sm font-bold rounded-full">
                    ⭐ 讓資源延續，讓知識循環
                </div>
                <h1 class="text-5xl md:text-6xl font-black text-gray-900 leading-tight">
                    讓知識在 <br> <span class="text-brand">校園流動</span>
                </h1>
                <p class="text-lg text-gray-500 leading-relaxed max-w-lg">
                    打破書櫃的空間限制，將閒置的課本與讀物傳遞給下一位需要的同學。建立透明的捐贈歷程，打造綠色永續的校園閱讀社群。
                </p>
                <div class="flex flex-wrap gap-4 pt-4">
                    <a href="search.php" class="bg-brand text-white px-8 py-3 rounded-lg font-bold hover:bg-green-700 transition shadow-md flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                        立即尋書
                    </a>
                    <a href="donate.php" class="bg-white text-brand border-2 border-brand px-8 py-3 rounded-lg font-bold hover:bg-green-50 transition flex items-center gap-2">
                        我要捐書
                    </a>
                </div>
            </div>

            <div class="md:w-5/12 bg-white p-8 rounded-3xl shadow-xl border border-gray-100 relative overflow-hidden w-full">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-green-100 rounded-full opacity-50"></div>
                <div class="space-y-8 relative z-10">
                    <div class="text-center">
                        <p class="text-gray-500 font-medium mb-1">目前已累計捐贈書籍</p>
                        <div class="text-4xl font-black text-brand">1,254 <span class="text-lg text-gray-400 font-medium">本</span></div>
                    </div>
                    <hr class="border-gray-100">
                    <div class="text-center">
                        <p class="text-gray-500 font-medium mb-1">成功傳遞知識次數</p>
                        <div class="text-4xl font-black text-yellow-500">982 <span class="text-lg text-gray-400 font-medium">次</span></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mb-12">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">最新待領取書籍</h2>
                    <p class="text-gray-500 text-sm">搶先發現校園內剛上架的二手好書</p>
                </div>
                <a href="search.php" class="text-brand font-medium hover:text-green-800 transition flex items-center gap-1">
                    查看全部 <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php
                // 切版階段：建立假資料陣列來模擬迴圈 (第三階段時這裡會換成從資料庫 PDO 撈取)
                $mock_books = [
                    ['bstatus' => 'available', 'bimage_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=400', 'ccategory_name' => '資訊工程', 'btitle' => '演算法導論 (第四版)', 'bauthor' => 'Thomas H. Cormen', 'donor_name' => '陳同學'],
                    ['bstatus' => 'available', 'bimage_url' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?q=80&w=400', 'ccategory_name' => '數位設計', 'btitle' => '設計的心理學', 'bauthor' => 'Don Norman', 'donor_name' => '林同學'],
                    ['bstatus' => 'reserved', 'bimage_url' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=400', 'ccategory_name' => '資訊工程', 'btitle' => 'Clean Code 無瑕的程式碼', 'bauthor' => 'Robert C. Martin', 'donor_name' => '張同學'],
                    ['bstatus' => 'available', 'bimage_url' => 'https://images.unsplash.com/photo-1474932430478-367d16b99031?q=80&w=400', 'ccategory_name' => '語言文學', 'btitle' => '百年孤寂', 'bauthor' => 'Gabriel García Márquez', 'donor_name' => '王同學']
                ];

                // 迴圈印出共用卡片
                foreach ($mock_books as $book) {
                    include 'components/book_card.php';
                }
                ?>
            </div>
        </section>
    </main>

    <?php include 'components/footer.php'; ?>

</body>

</html>