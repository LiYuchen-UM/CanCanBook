-- Users table (Google OAuth)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    google_id VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    avatar VARCHAR(500),
    level INT DEFAULT 1,
    experience INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Stories table
CREATE TABLE stories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(200) DEFAULT NULL,
    content JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO stories (level, title, author, content) VALUES
(1, 'At the Dim Sum Restaurant', 'CantoneseBook Team', '[
    {"speaker": "阿明", "cantonese": "唔该，三位，有冇位呀？", "jyutping": "m4 goi1, saam1 wai2, jau5 mou5 wai2 aa3?", "english": "Excuse me, three people, are there any seats?"},
    {"speaker": "知客", "cantonese": "仲有张细枱，不过要搭枱喎，得唔得？", "jyutping": "zung6 jau5 zoeng1 sai3 toi2, bat1 gwo3 jiu3 daap3 toi2 wo3, dak1 m4 dak1?", "english": "There is still a small table, but you need to share, is that okay?"},
    {"speaker": "阿明", "cantonese": "冇所谓啦。喂，你想饮咩茶？普洱定寿眉？", "jyutping": "mou5 so2 wai6 laa1. wai3, nei5 soeng2 jam2 me1 caa4? pou2 nei5 ding6 sau6 mei4?", "english": "No problem. Hey, what tea do you want to drink? Pu-erh or Shoumei?"},
    {"speaker": "朋友", "cantonese": "普洱啦。顺便点埋嘢食先，我要虾饺、烧卖，仲有个凤爪。", "jyutping": "pou2 nei5 laa1. seon6 bin6 dim2 maai4 je5 sik6 sin1, ngo5 jiu3 haa1 gaau2, siu1 maai6, zung6 jau5 go3 fung6 zaau2.", "english": "Pu-erh then. Let me order some food too, I want shrimp dumplings, siu mai, and chicken feet."},
    {"speaker": "阿明", "cantonese": "咦，呢度啲肠粉好似几正喎，整多碟啦！", "jyutping": "ji4, ni1 dou6 di1 coeng4 fan2 hou2 ci5 gei2 zeng3 wo3, zing2 do1 dip6 laa1!", "english": "Oh, the rice rolls here seem pretty good, let us get a plate!"}
]');

-- Vocabulary by level
CREATE TABLE level_vocabulary (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level INT NOT NULL,
    english VARCHAR(100) NOT NULL,
    cantonese VARCHAR(100) NOT NULL,
    jyutping VARCHAR(150) NOT NULL
);

INSERT INTO level_vocabulary (level, english, cantonese, jyutping) VALUES
(1, 'Hello', '你好', 'nei5 hou2');


-- Quiz questions table
CREATE TABLE quiz_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level INT NOT NULL,
    question TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    correct_answer CHAR(1) NOT NULL,
    explanation TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO quiz_questions (level, question, option_a, option_b, option_c, correct_answer, explanation) VALUES
(5, '喺公司入面，如果隔籬組做錯嘢，但係老細竟然怪落你度，要你出嚟負責。呢種咁慘嘅情況，最啱用邊一組詞語？', '放飛機、扮豬食老虎', '泥菩薩過江、陰功', '食死貓、孭鑊', 'C', '「食死貓」意思係被人冤枉頂罪，「孭鑊」就係背黑鍋嘅意思。');
