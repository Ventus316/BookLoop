<?php
// 模擬第三階段從資料庫 (PDO) 撈出的書籍詳細資料
$mock_book = [
    'bstatus' => 'available',
    'bimage_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=600',
    'ccategory_name' => '資訊工程',
    'btitle' => '演算法導論 (第四版)',
    'bauthor' => 'Thomas H. Cormen',
    'bisbn' => '9789862803144',
    'bdescription' => '近全新，僅翻閱過幾次，無劃記。', // 對應前一頁捐書的補充描述
    'donor_name' => '陳同學',
    'created_at' => '2026-04-10',
    'likes' => 12,
    'collects' => 5
];
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $mock_book['btitle']; ?> - 書活 BookLoop</title>
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

    <main class="flex-grow max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <div>
            <a href="search.php" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                返回列表
            </a>
        </div>

        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">

            <div class="md:w-5/12 relative h-80 md:h-auto bg-gray-100">
                <div class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 bg-green-100 text-green-700 z-10 backdrop-blur-sm bg-opacity-90 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>開放領取中
                </div>
                <img src="<?php echo $mock_book['bimage_url']; ?>" alt="<?php echo $mock_book['btitle']; ?>" class="w-full h-full object-cover">
            </div>

            <div class="md:w-7/12 p-8 md:p-10 flex flex-col">
                <div class="text-xs font-bold text-brand bg-green-50 px-2.5 py-1 rounded-md inline-block w-max mb-3">
                    <?php echo $mock_book['ccategory_name']; ?>
                </div>

                <h1 class="text-3xl font-black text-gray-900 leading-tight mb-2">
                    <?php echo $mock_book['btitle']; ?>
                </h1>
                <p class="text-gray-500 font-medium mb-6"><?php echo $mock_book['bauthor']; ?></p>

                <div class="grid grid-cols-2 gap-4 bg-gray-50 p-5 rounded-xl border border-gray-100 mb-6">
                    <div>
                        <p class="text-xs text-gray-400 font-bold mb-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                            ISBN
                        </p>
                        <p class="text-sm font-medium text-gray-800"><?php echo $mock_book['bisbn']; ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold mb-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            書況簡述
                        </p>
                        <p class="text-sm font-medium text-gray-800 line-clamp-2"><?php echo $mock_book['bdescription'] ?: '無特別說明'; ?></p>
                    </div>
                </div>

                <div class="flex gap-4 mb-8">
                    <button id="btn-like" class="flex-1 border border-gray-200 py-2.5 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition flex justify-center items-center gap-2 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-red-500 transition icon-heart" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        點讚 (<span id="like-count"><?php echo $mock_book['likes']; ?></span>)
                    </button>
                    <button id="btn-collect" class="flex-1 border border-gray-200 py-2.5 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition flex justify-center items-center gap-2 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-yellow-500 transition icon-bookmark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                        收藏 (<span id="collect-count"><?php echo $mock_book['collects']; ?></span>)
                    </button>
                </div>

                <div class="mt-auto bg-green-50 p-5 rounded-xl border border-green-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand text-white flex items-center justify-center font-bold text-lg">
                            <?php echo mb_substr($mock_book['donor_name'], 0, 1, 'UTF-8'); ?>
                        </div>
                        <div>
                            <p class="text-xs text-brand font-bold mb-0.5">知識傳遞者 (捐贈人)</p>
                            <p class="text-sm font-bold text-gray-800"><?php echo $mock_book['donor_name']; ?></p>
                        </div>
                    </div>
                    <button class="bg-brand text-white px-5 py-2.5 rounded-lg font-bold hover:bg-green-700 transition shadow-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        我想領取
                    </button>
                </div>

            </div>
        </section>

        <div class="flex flex-col md:flex-row gap-6 items-start">

            <section class="w-full md:w-1/3 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    知識足跡
                </h3>

                <div class="relative border-l-2 border-gray-100 ml-3 space-y-6">
                    <div class="relative pl-6">
                        <div class="absolute w-3 h-3 bg-brand rounded-full -left-[7px] top-1.5 shadow-[0_0_0_4px_#d1fae5]"></div>
                        <p class="text-sm font-bold text-gray-800"><?php echo str_replace('-', '/', $mock_book['created_at']); ?></p>
                        <p class="text-sm text-gray-500 mt-1"><?php echo $mock_book['donor_name']; ?> 上傳了此書籍</p>
                    </div>
                    <div class="relative pl-6">
                        <div class="absolute w-3 h-3 bg-gray-300 rounded-full -left-[7px] top-1.5"></div>
                        <p class="text-sm font-bold text-gray-400">等待中</p>
                        <p class="text-sm text-gray-400 mt-1">等待下一位讀者領取</p>
                    </div>
                </div>
            </section>

            <section class="w-full md:w-2/3 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    書況詢問與留言
                </h3>

                <div id="comment-list" class="space-y-4 mb-6 max-h-64 overflow-y-auto pr-2">
                    <div id="empty-state" class="text-center py-8 text-gray-400 text-sm italic">
                        目前還沒有人留言，來搶頭香吧！
                    </div>
                </div>

                <div class="flex items-start gap-3 border-t border-gray-100 pt-5">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <form id="commentForm" class="flex-grow flex gap-2 relative">
                        <input type="text" id="comment-input" required placeholder="詢問書況或面交地點..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:border-brand bg-gray-50 focus:bg-white transition text-sm">
                        <button type="submit" class="bg-gray-400 text-white px-5 rounded-xl font-bold hover:bg-brand transition flex-shrink-0">
                            送出
                        </button>
                    </form>
                </div>
            </section>

        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>