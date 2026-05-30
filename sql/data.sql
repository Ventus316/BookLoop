-- ==========================================
-- 1. 預填分類表 (Category)
-- ==========================================
INSERT INTO
    `Category` (`ccategory_name`)
VALUES ('資訊工程'),
    ('數位設計'),
    ('語言文學'),
    ('通識管理');

-- ==========================================
-- 2. 預填用戶表 (User)
-- 包含 1 個管理員與 3 個一般學生
-- ==========================================
INSERT INTO
    `User` (
        `ustudent_id`,
        `uname`,
        `uemail`,
        `upassword`,
        `ulocation`,
        `urole`
    )
VALUES (
        's1121400',
        '系統管理員',
        'admin@yzu.edu.tw',
        '123456',
        '資傳系辦',
        'admin'
    ),
    (
        's1121499',
        '陳同學',
        'user1@yzu.edu.tw',
        '123456',
        '一館大廳',
        'user'
    ),
    (
        's1121500',
        '林同學',
        'user2@yzu.edu.tw',
        '123456',
        '圖書館前',
        'user'
    ),
    (
        's1121501',
        '王同學',
        'user3@yzu.edu.tw',
        '123456',
        '三館超商',
        'user'
    );

-- ==========================================
-- 3. 預填書籍表 (Book)
-- 包含不同狀態 (available, reserved, donated) 供前端切換樣式
-- ==========================================
INSERT INTO
    `Book` (
        `bisbn`,
        `btitle`,
        `bauthor`,
        `bimage_url`,
        `bstatus`,
        `bdonor_id`,
        `bcategory_id`
    )
VALUES (
        '9789865021234',
        '網頁程式設計：PHP & MySQL 實戰',
        '張教授',
        'assets/images/default.png',
        'available',
        2,
        1
    ),
    (
        '9789573274710',
        '設計的心理學',
        'Don Norman',
        'assets/images/default.png',
        'available',
        3,
        2
    ),
    (
        '9789863445343',
        '百年孤寂',
        'Gabriel',
        'assets/images/default.png',
        'reserved',
        4,
        3
    ),
    (
        '9789862803151',
        '微積分 (下)',
        'James Stewart',
        'assets/images/default.png',
        'donated',
        2,
        4
    ),
    (
        '9789862803144',
        '演算法導論 (第四版)',
        'Thomas',
        'assets/images/default.png',
        'available',
        3,
        1
    ),
    (
        '9789862017050',
        'Clean Code 無瑕的程式碼',
        'Robert C. Martin',
        'assets/images/default.png',
        'reserved',
        4,
        1
    );

-- ==========================================
-- 4. 預填轉手紀錄表 (Record)
-- 針對狀態為 reserved (預約中) 與 donated (已捐出) 的書籍建立交易紀錄
-- ==========================================
INSERT INTO
    `Record` (
        `ctrade_code`,
        `cbook_id`,
        `creceiver_id`,
        `crecord_status`
    )
VALUES (
        'TRD-202605-001',
        3,
        2,
        'pending'
    ), -- 陳同學(2) 預約了 百年孤寂(3)
    (
        'TRD-202605-002',
        4,
        3,
        'completed'
    ), -- 林同學(3) 已成功領取 微積分(4)
    (
        'TRD-202605-003',
        6,
        2,
        'pending'
    );
-- 陳同學(2) 預約了 Clean Code(6)

-- ==========================================
-- 5. 預填互動表 (Interaction)
-- 混合點讚 (like)、收藏 (collect) 與留言 (comment)
-- ==========================================
INSERT INTO
    `Interaction` (
        `ibook_id`,
        `iuser_id`,
        `iinteraction_type`,
        `icontent`
    )
VALUES (1, 3, 'like', NULL),
    (1, 4, 'like', NULL),
    (
        1,
        3,
        'comment',
        '這本書的範例很實用，推薦給修課的學弟妹！'
    ),
    (2, 2, 'collect', NULL),
    (
        2,
        4,
        'comment',
        '請問書本內頁有劃記嗎？'
    ),
    (5, 2, 'like', NULL),
    (6, 3, 'collect', NULL);