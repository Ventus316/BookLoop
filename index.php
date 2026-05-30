<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書活 BookLoop | 讓知識在校園流動</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#059669',
                        /* 對齊原型圖的深綠色 */
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <?php include 'components/header.php'; ?>

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
            <h1 class="text-4xl font-bold text-gray-300 mt-20">網頁內容建置中...</h1>
            <p class="text-gray-400 mt-4">Header 與 Footer 已成功載入！</p>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

</body>

</html>