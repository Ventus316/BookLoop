<?php $page_title = '建立新帳號 - 書活 BookLoop'; ?>
<!DOCTYPE html>
<html lang="zh-TW">
<?php include 'components/head.php'; ?>

<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex">

    <?php include 'components/auth_sidebar.php'; ?>

    <div class="w-full md:w-1/2 lg:w-7/12 flex flex-col justify-center p-8 sm:p-12 lg:p-20 bg-white">
        <div class="max-w-md w-full mx-auto space-y-6">

            <a href="index.php" class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                返回首頁
            </a>

            <div>
                <h3 class="text-3xl font-black text-gray-900 mb-2">建立新帳號</h3>
                <p class="text-gray-500 text-sm">加入書活，與校園社群分享知識</p>
            </div>

            <div class="bg-gray-100 p-1 rounded-xl flex">
                <a href="login.php" class="w-1/2 text-center py-2.5 text-sm font-medium rounded-lg text-gray-400 hover:text-gray-600 transition">登入</a>
                <a href="register.php" class="w-1/2 text-center py-2.5 text-sm font-bold rounded-lg bg-white text-gray-800 shadow-sm transition">註冊</a>
            </div>

            <form id="registerForm" action="api/auth_process.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="register">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">學號 <span class="text-red-500">*</span></label>
                        <input type="text" name="ustudent_id" required placeholder="例如: s1121400" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:border-brand focus:ring-2 focus:ring-green-100 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">姓名/暱稱 <span class="text-red-500">*</span></label>
                        <input type="text" name="uname" required placeholder="例如: 陳同學" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:border-brand focus:ring-2 focus:ring-green-100 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Email 信箱 <span class="text-red-500">*</span></label>
                    <input type="email" name="uemail" required placeholder="school@example.edu.tw" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:border-brand focus:ring-2 focus:ring-green-100 transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">密碼 <span class="text-red-500">*</span></label>
                    <input type="password" name="upassword" required placeholder="請輸入至少 6 位數密碼" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:border-brand focus:ring-2 focus:ring-green-100 transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">主要面交地點 <span class="text-red-500">*</span></label>
                    <input type="text" name="ulocation" required placeholder="例如: 圖書館大廳、資傳系辦" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:border-brand focus:ring-2 focus:ring-green-100 transition">
                </div>

                <button type="submit" class="w-full bg-brand text-white py-3.5 rounded-xl font-bold hover:bg-green-700 transition shadow-md flex items-center justify-center gap-2 mt-4">
                    註冊帳號
                </button>
            </form>

        </div>
    </div>

    <script src="assets/script.js"></script>
</body>

</html>