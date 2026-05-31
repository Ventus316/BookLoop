-- 1. 建立用戶表 (User)
CREATE TABLE `User` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用戶唯一識別碼',
  `ustudent_id` varchar(50) NOT NULL COMMENT '學號',
  `uname` varchar(50) NOT NULL COMMENT '姓名/暱稱',
  `uemail` varchar(100) NOT NULL COMMENT '學校信箱',
  `upassword` varchar(255) NOT NULL COMMENT 'BCRYPT加密密碼',
  `ulocation` varchar(100) NOT NULL COMMENT '常駐面交地點',
  `urole` varchar(20) NOT NULL DEFAULT 'user' COMMENT '系統權限: admin / user',
  `status` varchar(20) NOT NULL DEFAULT 'active' COMMENT '帳號狀態: active / banned',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uemail_unique` (`uemail`),
  UNIQUE KEY `ustudent_id_unique` (`ustudent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. 建立分類表 (Category)
CREATE TABLE `Category` (
    `ccategory_id` INT AUTO_INCREMENT NOT NULL,
    `ccategory_name` VARCHAR(50) NOT NULL COMMENT '分類名稱（如：資工、設計、文學）',
    PRIMARY KEY (`ccategory_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 3. 建立書籍表 (Book)
CREATE TABLE `Book` (
    `bbook_id` INT AUTO_INCREMENT NOT NULL,
    `bisbn` VARCHAR(20) DEFAULT NULL COMMENT '國際標準書號',
    `btitle` VARCHAR(100) NOT NULL COMMENT '書名',
    `bauthor` VARCHAR(100) DEFAULT NULL COMMENT '作者',
	`bimage_url` VARCHAR(255) DEFAULT 'assets/images/default.png' COMMENT '封面圖片路徑',
    `bstatus` VARCHAR(50) DEFAULT 'available' COMMENT '狀態 (available, reserved, donated)',
    `bdonor_id` INT NOT NULL COMMENT '捐贈者用戶 ID',
    `bcategory_id` INT NOT NULL COMMENT '書籍分類 ID',
    `bcreated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '發佈時間',
    PRIMARY KEY (`bbook_id`),
    FOREIGN KEY (`bdonor_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`bcategory_id`) REFERENCES `Category` (`ccategory_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 4. 建立轉手紀錄表 (Record)
CREATE TABLE `Record` (
    `ccrecord_id` INT AUTO_INCREMENT NOT NULL,
    `ctrade_code` VARCHAR(50) NOT NULL COMMENT '交易編號',
    `cbook_id` INT NOT NULL COMMENT '關聯書籍 ID',
    `creceiver_id` INT NOT NULL COMMENT '領取者用戶 ID',
    `crecord_status` VARCHAR(50) DEFAULT 'pending' COMMENT '交易狀態 (pending, completed)',
    `crecord_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '領取/交易時間',
    PRIMARY KEY (`ccrecord_id`),
    FOREIGN KEY (`cbook_id`) REFERENCES `Book` (`bbook_id`) ON DELETE CASCADE,
    FOREIGN KEY (`creceiver_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 5. 建立互動表 (Interaction) - 用於合併點讚、收藏、留言
CREATE TABLE `Interaction` (
    `iinteraction_id` INT AUTO_INCREMENT NOT NULL,
    `ibook_id` INT NOT NULL COMMENT '書籍 ID',
    `iuser_id` INT NOT NULL COMMENT '用戶 ID',
    `iinteraction_type` VARCHAR(20) NOT NULL COMMENT '互動類型 (like, collect, comment)',
    `icontent` TEXT DEFAULT NULL COMMENT '留言內容 (若為點讚/收藏則為 null)',
    `icreated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '互動時間',
    PRIMARY KEY (`iinteraction_id`),
    FOREIGN KEY (`ibook_id`) REFERENCES `Book` (`bbook_id`) ON DELETE CASCADE,
    FOREIGN KEY (`iuser_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;