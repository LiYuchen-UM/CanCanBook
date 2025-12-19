-- import vocabulary
INSERT INTO level_vocabulary (level, english, cantonese, jyutping) VALUES
-- Example (1, 'Hello', '你好', 'nei5 hou2');
(1, 'Example1', '例子1', 'lai6 zi2 1'),
(2, 'Example2', '例子2', 'lai6 zi2 2');

-- import quiz
-- For level 1,2 pleasse give both Cantonese and English in question
INSERT INTO quiz_questions (level, question, option_a, option_b, option_c, correct_answer, explanation) VALUES
-- Example: (1, '你同兩個朋友去茶樓，你想問知客「有無三個人嘅位」？ (You are at a restaurant with two friends. How do you ask the waiter if there are seats for three people?)', '唔該，三位，有冇位呀？', '呢度啲腸粉好正喎！', '我想要蝦餃同燒賣。', 'A', ''),
-- Example: (5, '喺公司入面，如果隔籬組做錯嘢，但係老細竟然怪落你度，要你出嚟負責。呢種咁慘嘅情況，最啱用邊一組詞語？', '放飛機、扮豬食老虎', '泥菩薩過江、陰功', '食死貓、孭鑊', 'C', '「食死貓」意思係被人冤枉頂罪，「孭鑊」就係背黑鍋嘅意思。');
(1, '例子1', '选项A', '选项B', '选项C', 'A', '解释（此字段可留空）');

-- import story
INSERT INTO stories (level, title, author, content) VALUES
-- Example: (1, 'At the Dim Sum Restaurant', 'CantoneseBook Team', '[{"speaker": "阿明", "cantonese": "唔该，有冇位呀？", "jyutping": "m4 goi1, jau5 mou5 wai2 aa3?", "english": "Excuse me, are there any seats?"}]');
(1, '标题', '作者', '[
    {"speaker": "发言人", "cantonese": "例子1", "jyutping": "lai6 zi2 1", "english": "Example1"},
    {"speaker": "发言人", "cantonese": "例子2", "jyutping": "lai6 zi2 2", "english": "Example2"}
]');