<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>建立新帳號 - 書活 BookLoop</title>
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

<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex">

    <div class="hidden md:flex md:w-1/2 lg:w-5/12 bg-brand relative overflow-hidden flex-col justify-between p-12 text-white">
        <div class="absolute inset-0 bg-black opacity-20 z-0"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] opacity-10 z-0"></div>

        <div class="relative z-10">
            <a href="index.php" class="flex items-center gap-2 text-2xl font-bold tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                書活
            </a>
        </div>

        <div class="relative z-10 my-auto space-y-6 max-w-md">
            <h2 class="text-4xl font-black leading-tight">讓每本書都能<br>遇見下一個讀者。</h2>
            <p class="text-green-100 leading-relaxed text-sm">加入書活平台，建立你的專屬知識存摺。只需使用學校 Email 與學號註冊，即可輕鬆捐贈與尋找所需的教材資源。</p>
        </div>

        <div class="relative z-10 text-xs text-green-200">
            &copy; BookLoop Team. 元智資傳專案小組
        </div>
    </div>

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