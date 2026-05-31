<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <div class="flex-shrink-0 flex items-center">
                <a href="index.php" class="flex items-center gap-2 text-2xl font-bold text-brand hover:opacity-80 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    書活
                </a>
            </div>

            <nav class="hidden md:flex space-x-8">
                <a href="search.php" class="text-gray-600 hover:text-brand font-medium transition">尋書大廳</a>
                <a href="about.php" class="text-gray-600 hover:text-brand font-medium transition">關於我們</a>
            </nav>

            <div class="flex items-center gap-4">
                <?php
                // 安全啟動 Session 檢查機制
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // 判斷使用者是否已經登入
                if (isset($_SESSION['user_id'])):
                    // 🌟 狀況 A：已登入狀態
                ?>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-650">
                            👋 你好，<strong class="text-gray-900"><?php echo htmlspecialchars($_SESSION['uname']); ?></strong>
                        </span>

                        <?php if ($_SESSION['urole'] === 'admin'): ?>
                            <a href="admin_panel.php" class="text-xs bg-rose-50 text-rose-700 border border-rose-100 px-3 py-1.5 rounded-lg font-bold hover:bg-rose-100 transition">
                                🛡️ 系統後台
                            </a>
                        <?php else: ?>
                            <a href="user_panel.php" class="text-xs bg-green-50 text-brand border border-green-100 px-3 py-1.5 rounded-lg font-bold hover:bg-green-100 transition">
                                📦 個人後台
                            </a>
                        <?php endif; ?>

                        <a href="api/auth_process.php?logout=1" class="text-xs text-gray-400 hover:text-red-500 font-medium transition ml-2">
                            登出
                        </a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="text-sm font-bold text-gray-650 hover:text-brand transition">登入</a>
                    <a href="register.php" class="bg-brand text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition shadow-sm">
                        註冊
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</header>