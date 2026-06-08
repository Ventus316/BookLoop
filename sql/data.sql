-- ==========================================
-- 1. 預填分類表 (Category)
-- ==========================================
INSERT INTO `Category` (`ccategory_name`)
VALUES ('資訊工程'), ('數位設計'), ('語言文學'), ('通識管理');

-- ==========================================
-- 2. 預填用戶表 (User)
-- 包含 1 個管理員與 8 個一般學生
-- 備註：upassword 已轉換為 BCRYPT 雜湊，前端登入明文皆為 '123456'
-- ==========================================
INSERT INTO `User` (
    `ustudent_id`, `uname`, `uemail`, `upassword`, `ulocation`, `urole`, `status`
)
VALUES 
    ('s1121400', '系統管理員', 'admin@yzu.edu.tw', '$2y$10$PHcT2yHCkaqwlfb7ZjXk5uzX5oRvvuMRrrBJfKwfdRfhMeYTU7WkK', '資傳系辦', 'admin', 'active'),
    ('s1121499', '陳同學', 'user1@yzu.edu.tw', '$2y$10$PHcT2yHCkaqwlfb7ZjXk5uzX5oRvvuMRrrBJfKwfdRfhMeYTU7WkK', '一館大廳', 'user', 'active'),
    ('s1121500', '林同學', 'user2@yzu.edu.tw', '$2y$10$PHcT2yHCkaqwlfb7ZjXk5uzX5oRvvuMRrrBJfKwfdRfhMeYTU7WkK', '圖書館前', 'user', 'active'),
    ('s1121501', '王同學', 'user3@yzu.edu.tw', '$2y$10$PHcT2yHCkaqwlfb7ZjXk5uzX5oRvvuMRrrBJfKwfdRfhMeYTU7WkK', '三館超商', 'user', 'active'),
    ('s1121502', '張同學', 'user4@yzu.edu.tw', '$2y$10$PHcT2yHCkaqwlfb7ZjXk5uzX5oRvvuMRrrBJfKwfdRfhMeYTU7WkK', '二館咖啡廳', 'user', 'active'),
    ('s1121503', '李同學', 'user5@yzu.edu.tw', '$2y$10$PHcT2yHCkaqwlfb7ZjXk5uzX5oRvvuMRrrBJfKwfdRfhMeYTU7WkK', '活動中心', 'user', 'active'),
    ('s1121504', '黃同學', 'user6@yzu.edu.tw', '$2y$10$PHcT2yHCkaqwlfb7ZjXk5uzX5oRvvuMRrrBJfKwfdRfhMeYTU7WkK', '四館自習室', 'user', 'active'),
    ('s1121505', '吳同學', 'user7@yzu.edu.tw', '$2y$10$PHcT2yHCkaqwlfb7ZjXk5uzX5oRvvuMRrrBJfKwfdRfhMeYTU7WkK', '圖書館二樓', 'user', 'active'),
    ('s1121506', '趙同學', 'user8@yzu.edu.tw', '$2y$10$PHcT2yHCkaqwlfb7ZjXk5uzX5oRvvuMRrrBJfKwfdRfhMeYTU7WkK', '宿舍區', 'user', 'banned');

-- ==========================================
-- 3. 預填書籍表 (Book) — 共 26 本（全部真實中文書名）
-- ==========================================
INSERT INTO `Book` (
    `bisbn`, `btitle`, `bauthor`, `bimage_url`, `bstatus`, `bdonor_id`, `bcategory_id`
)
VALUES 
    ('9789865021234', 'Clean Code 無瑕的程式碼', 'Robert C. Martin', 'assets/images/book_cleancode.jpg', 'reserved', 4, 1),
    ('9789573274710', '設計的心理學', 'Don Norman', 'assets/images/book_design.jpg', 'available', 3, 2),
    ('9789863445343', '百年孤寂', 'Gabriel García Márquez', 'assets/images/book_novel.jpg', 'reserved', 4, 3),
    ('9789862803151', '微積分（下）', 'James Stewart', 'assets/images/book_calculus.jpg', 'donated', 2, 4),
    ('9789862803144', '演算法導論（第四版）', 'Thomas H. Cormen', 'assets/images/book_algo.jpg', 'available', 3, 1),
    ('9789862017050', '網頁程式設計：PHP & MySQL 實戰', 'Luke Welling', 'assets/images/book_php.jpg', 'available', 2, 1),
    ('9789865432110', '鳥哥的 Linux 私房菜 基礎學習篇', '鳥哥', 'assets/images/bird.jpg', 'available', 2, 1),
    ('9789863201234', '資料結構與演算法 Python 版', 'Brad Miller', 'assets/images/ds.jpg', 'available', 5, 1),
    ('9789571368901', '深度學習入門', 'Ian Goodfellow', 'assets/images/deep.jpg', 'reserved', 3, 1),
    ('9789862809876', 'SQL 資料庫實務', 'Alan Beaulieu', 'assets/images/sql.jpg', 'reserved', 4, 1),
    ('9789865438765', 'Python 資料科學手冊', 'Jake VanderPlas', 'assets/images/python.jpg', 'donated', 6, 1),
    ('9789862019876', '重構 改善既有程式的設計', 'Martin Fowler', 'assets/images/refactoring.jpg', 'available', 7, 1),
    ('9789863205678', '不要讓我思考：網頁可用性經典指南', 'Steve Krug', 'assets/images/dont.jpg', 'available', 2, 2),
    ('9789573334567', '設計的心理學（修訂版）', 'Don Norman', 'assets/images/design2.jpg', 'reserved', 5, 2),
    ('9789865436543', 'Refactoring UI', 'Adam Wathan', 'assets/images/refactoringui.jpg', 'donated', 4, 2),
    ('9789861754321', '解憂雜貨店', '東野圭吾', 'assets/images/doya.jpg', 'available', 7, 3),
    ('9789865432198', '小王子', 'Antoine de Saint-Exupéry', 'assets/images/prince.jpg', 'available', 3, 3),
    ('9789571365432', '傲慢與偏見', 'Jane Austen', 'assets/images/pride.jpg', 'reserved', 6, 3),
    ('9789862806543', '挪威的森林', '村上春樹', 'assets/images/norway.jpg', 'donated', 4, 3),
    ('9789862018765', '人間失格', '太宰治', 'assets/images/world.jpg', 'available', 8, 3),
    ('9789862018765', '原子習慣', 'James Clear', 'assets/images/habit.jpg', 'available', 5, 4),
    ('9789570851234', '高效能人士的七個習慣', 'Stephen R. Covey', 'assets/images/7habits.jpg', 'reserved', 2, 4),
    ('9789863209876', '思考，快與慢', 'Daniel Kahneman', 'assets/images/think.jpg', 'reserved', 6, 4),
    ('9789573337890', '心理學與生活', 'Richard J. Gerrig', 'assets/images/life.jpg', 'donated', 7, 4),
    ('9789865432109', '薩皮恩斯：人類簡史', 'Yuval Noah Harari', 'assets/images/human.jpg', 'donated', 3, 4),
    ('9789862807654', '領導力 21 法則', 'John C. Maxwell', 'assets/images/21.jpg', 'available', 8, 4);

-- ==========================================
-- 4. 預填轉手紀錄表 (Record)
-- ==========================================
INSERT INTO `Record` (
    `ctrade_code`, `cbook_id`, `creceiver_id`, `crecord_status`
)
VALUES 
    ('TRD-202605-001', 3, 2, 'pending'),
    ('TRD-202605-002', 4, 3, 'completed'),
    ('TRD-202605-003', 6, 2, 'pending'),
    ('TRD-202606-006', 9, 3, 'pending'),
    ('TRD-202606-007', 10, 5, 'pending'),
    ('TRD-202606-008', 13, 6, 'pending'),
    ('TRD-202606-009', 16, 4, 'completed'),
    ('TRD-202606-010', 19, 7, 'completed'),
    ('TRD-202606-011', 20, 2, 'pending'),
    ('TRD-202606-012', 21, 5, 'completed'),
    ('TRD-202606-013', 22, 8, 'completed');

-- ==========================================
-- 5. 預填互動表 (Interaction)
-- ==========================================
INSERT INTO `Interaction` (
    `ibook_id`, `iuser_id`, `iinteraction_type`, `icontent`
)
VALUES 
    (1, 3, 'like', NULL),
    (1, 4, 'like', NULL),
    (1, 3, 'comment', '這本書的範例很實用，推薦給修課的學弟妹！'),
    (2, 2, 'collect', NULL),
    (2, 4, 'comment', '請問書本內頁有劃記嗎？'),
    (5, 2, 'like', NULL),
    (6, 3, 'collect', NULL),
    (7, 3, 'like', NULL),
    (8, 4, 'collect', NULL),
    (9, 5, 'like', NULL),
    (10, 2, 'comment', '這本 Linux 書寫得非常清楚，強推！'),
    (12, 6, 'like', NULL),
    (13, 7, 'comment', 'UI 設計的經典，值得細讀'),
    (15, 3, 'like', NULL),
    (16, 5, 'collect', NULL),
    (17, 4, 'comment', '經典文學，值得一讀再讀'),
    (19, 6, 'like', NULL),
    (21, 2, 'collect', NULL),
    (23, 7, 'like', NULL),
    (24, 8, 'comment', '習慣養成這本書幫助很大');