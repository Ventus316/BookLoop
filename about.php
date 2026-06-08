<?php $page_title = '書活 BookLoop | 讓知識在校園流動'; ?>
<!DOCTYPE html>
<html lang="zh-TW">
<?php include 'components/head.php'; ?>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">

    <?php include 'components/header.php'; ?>

    <main class="flex-grow">

        <section class="relative bg-brand py-24 px-4 sm:px-6 lg:px-8 overflow-hidden text-center flex flex-col items-center justify-center">
            <div class="absolute inset-0 bg-black opacity-20 z-0"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px] opacity-10 z-0"></div>

            <div class="relative z-10 max-w-3xl mx-auto space-y-6">
                <div class="inline-flex items-center justify-center px-4 py-1.5 border border-green-300 rounded-full text-green-100 text-sm font-bold mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    書活 BookLoop
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white leading-tight tracking-wide">
                    打破空間限制，<br>讓每本書找到下一個歸屬。
                </h1>
                <p class="text-lg text-green-50 font-medium max-w-2xl mx-auto mt-4 leading-relaxed">
                    在數位時代，實體書籍依然擁有無法取代的溫度。我們希望透過這個平台，減少校園內的資源浪費，延續知識的價值。
                </p>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-gray-900 mb-4">平台核心理念</h2>
                <div class="w-16 h-1 bg-brand mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 text-center hover:-translate-y-1 transition duration-300">
                    <div class="w-16 h-16 mx-auto bg-green-50 text-brand rounded-2xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">SDGs 12 責任消費</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        提倡二手書籍的循環利用，減少過度印刷與浪費。讓閒置在書櫃中的課本重新發揮價值，實踐綠色校園。
                    </p>
                </div>

                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 text-center hover:-translate-y-1 transition duration-300">
                    <div class="w-16 h-16 mx-auto bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">SDGs 4 優質教育</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        降低獲取學習資源的門檻。讓學弟妹能以無負擔的方式取得教材，確保每個人都能享有平等的教育機會。
                    </p>
                </div>

                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 text-center hover:-translate-y-1 transition duration-300">
                    <div class="w-16 h-16 mx-auto bg-yellow-50 text-yellow-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">透明的知識足跡</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        每本書都有它的故事。我們詳細記錄書籍的傳承歷程，建立社群信任感，讓每一次的交接都充滿意義。
                    </p>
                </div>
            </div>
        </section>

        <section class="bg-white border-t border-gray-100 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="text-center mb-16">
                    <h2 class="text-3xl font-black text-gray-900 mb-4">認識開發團隊</h2>
                    <div class="w-16 h-1 bg-brand mx-auto rounded-full mb-6"></div>
                    <p class="text-gray-500 max-w-2xl mx-auto">
                        我們是來自元智大學資訊傳播學系的專案團隊，結合前端、後端與設計專長，共同打造了這個二手書循環平台。
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    <?php
                    // 團隊成員資料陣列 (四人菁英團隊)
                    $team_members = [
                        [
                            'name' => '李柏融',
                            'role' => '後端',
                            'color' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                            'desc' => '負責系統核心架構設計、資料庫建置與 API 核心邏輯撰寫。',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>'
                        ],
                        [
                            'name' => '王晴右',
                            'role' => '後端',
                            'color' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'desc' => '負責資安防護實作、RBAC 權限控管與伺服器端狀態機開發。',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>'
                        ],
                        [
                            'name' => '王宥慈',
                            'role' => '前端',
                            'color' => 'bg-sky-50 text-sky-600 border-sky-100',
                            'desc' => '負責 UI/UX 介面設計、Tailwind 與視覺動線優化。',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>'
                        ],
                        [
                            'name' => '李忻育',
                            'role' => '前端',
                            'color' => 'bg-teal-50 text-teal-600 border-teal-100',
                            'desc' => '負責 AJAX 非同步串接、DOM 操作與前端互動邏輯實作。',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>'
                        ]
                    ];

                    // 迴圈輸出無頭像的現代風格卡片
                    foreach ($team_members as $member):
                    ?>
                        <div class="bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden group hover:border-brand hover:shadow-md transition duration-300 flex flex-col h-full">
                            <div class="h-24 <?php echo $member['color']; ?> flex items-center justify-center transition-colors">
                                <div class="bg-white p-3 rounded-xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                                    <?php echo $member['icon']; ?>
                                </div>
                            </div>

                            <div class="p-6 text-center flex flex-col flex-grow">
                                <h3 class="text-lg font-black text-gray-900 mb-1"><?php echo $member['name']; ?></h3>
                                <p class="text-xs font-bold text-brand mb-4"><?php echo $member['role']; ?></p>
                                <p class="text-xs text-gray-500 leading-relaxed mb-6 flex-grow">
                                    <?php echo $member['desc']; ?>
                                </p>

                                <div class="mt-auto flex justify-center gap-3 pt-4 border-t border-gray-100">
                                    <a href="#" class="text-gray-300 hover:text-brand transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-300 hover:text-brand transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </section>

    </main>

    <?php include 'components/footer.php'; ?>

</body>

</html>