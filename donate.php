<?php $page_title = '書活 BookLoop | 讓知識在校園流動'; ?>
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
                返回管理後台
            </a>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="bg-brand px-8 py-10 text-white">
                    <h2 class="text-3xl font-black tracking-tight mb-2">我要捐書</h2>
                    <p class="text-green-100 text-sm">分享您的閒置書籍，讓知識流向需要的人。</p>
                </div>

                <form id="donateForm" action="api/upload_book.php" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            ISBN 自動抓取 (推薦)
                        </label>
                        <div class="flex gap-3">
                            <input type="text" id="bisbn" name="bisbn" placeholder="請輸入書籍背面的 ISBN 13 碼" class="flex-grow px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition">
                            <button type="button" id="btn-fetch-isbn" class="bg-green-100 text-brand px-5 py-2.5 rounded-lg font-bold hover:bg-green-200 transition whitespace-nowrap">
                                自動填入
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">系統將串接 Google Books API 自動為您帶入書名與作者。</p>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">書名 <span class="text-red-500">*</span></label>
                            <input type="text" id="btitle" name="btitle" required placeholder="請輸入完整書名" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">作者</label>
                            <input type="text" id="bauthor" name="bauthor" placeholder="請輸入作者姓名" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">書籍類別 <span class="text-red-500">*</span></label>
                            <select name="bcategory_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition bg-white">
                                <option value="" disabled selected>請選擇類別...</option>
                                <option value="1">資訊工程</option>
                                <option value="2">數位設計</option>
                                <option value="3">語言文學</option>
                                <option value="4">通識管理</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">書籍實體照片 <span class="text-red-500">*</span></label>
                            <div class="relative w-full h-32 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-gray-100 hover:border-brand transition flex items-center justify-center cursor-pointer overflow-hidden group" onclick="document.getElementById('bimage').click()">

                                <img id="image-preview" src="" alt="預覽圖片" class="absolute inset-0 w-full h-full object-cover hidden z-10">

                                <div id="upload-prompt" class="text-center z-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-gray-400 group-hover:text-brand transition mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span class="text-sm text-gray-500 font-bold">點擊或拖曳圖片至此</span>
                                    <p class="text-xs text-gray-400 mt-0.5">支援 JPG, PNG 格式 (最大 5MB)</p>
                                </div>

                                <input type="file" id="bimage" name="bimage" accept="image/png, image/jpeg" class="hidden">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5 flex justify-between">
                            <span>書況補充描述</span>
                            <span class="text-xs text-gray-400 font-normal">選填</span>
                        </label>
                        <textarea name="bdescription" rows="3" placeholder="例如：內頁有少許螢光筆劃記，九成新。方便約在圖書館面交..." class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition resize-none"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" onclick="history.back()" class="px-6 py-2.5 rounded-lg font-bold text-gray-600 hover:bg-gray-100 transition">
                            取消
                        </button>
                        <button type="submit" class="bg-brand text-white px-8 py-2.5 rounded-lg font-bold hover:bg-green-700 transition shadow-md">
                            確認送出，發布捐贈
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>