<?php $page_title = '歡迎回來 - 書活 BookLoop'; ?>
<!DOCTYPE html>
<html lang="zh-TW">
<?php include 'components/head.php'; ?>

<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex">

    <?php include 'components/auth_sidebar.php'; ?>

    <div class="w-full md:w-1/2 lg:w-7/12 flex flex-col justify-center p-8 sm:p-12 lg:p-20 bg-white">
        <div class="max-w-md w-full mx-auto space-y-8">

            <a href="index.php" class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                返回首頁
            </a>

            <div>
                <h3 class="text-3xl font-black text-gray-900 mb-2">歡迎回來</h3>
                <p class="text-gray-500 text-sm">請輸入您的帳號密碼以繼續</p>
            </div>

            <div class="bg-gray-100 p-1 rounded-xl flex">
                <a href="login.php" class="w-1/2 text-center py-2.5 text-sm font-bold rounded-lg bg-white text-gray-800 shadow-sm transition">登入</a>
                <a href="register.php" class="w-1/2 text-center py-2.5 text-sm font-medium rounded-lg text-gray-400 hover:text-gray-600 transition">註冊</a>
            </div>

            <form id="loginForm" action="api/auth_process.php" method="POST" class="space-y-5">
                <input type="hidden" name="action" value="login">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email 信箱 <span class="text-red-500">*</span></label>
                    <input type="email" name="uemail" required placeholder="school@example.edu.tw" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-brand focus:ring-2 focus:ring-green-100 transition">
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <label class="text-sm font-bold text-gray-700">密碼 <span class="text-red-500">*</span></label>
                        <a href="#" class="text-xs font-semibold text-brand hover:underline">忘記密碼？</a>
                    </div>
                    <input type="password" name="upassword" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-brand focus:ring-2 focus:ring-green-100 transition">
                </div>

                <button type="submit" class="w-full bg-brand text-white py-3.5 rounded-xl font-bold hover:bg-green-700 transition shadow-md flex items-center justify-center gap-2 mt-2">
                    登入系統
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </form>

        </div>
    </div>

    <script src="assets/script.js"></script>
</body>

</html>