<?php
// 如果該頁面沒有設定標題，就給予預設值
$page_title = isset($page_title) ? $page_title : '書活 BookLoop | 讓知識在校園流動';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#059669',
                        /* 全站主色 */
                        admin: '#9f1239' /* 管理員後臺專用色 */
                    }
                }
            }
        }
    </script>
</head>