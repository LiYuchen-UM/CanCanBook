<?php
require_once 'config/database.php';

$level = isset($_GET['level']) ? intval($_GET['level']) : 1;
if ($level < 1 || $level > 5) $level = 1;

// Fetch 5 distinct random words from database for this level
$stmt = $pdo->prepare("SELECT DISTINCT english, cantonese, jyutping FROM level_vocabulary WHERE level = ? ORDER BY RAND() LIMIT 5");
$stmt->execute([$level]);
$dbWords = $stmt->fetchAll();

$words = [];
foreach ($dbWords as $row) {
    $words[] = [
        'en' => $row['english'],
        'yue' => $row['cantonese'],
        'jyutping' => $row['jyutping']
    ];
}

$levelNames = [
    1 => 'Complete Beginner',
    2 => 'Elementary', 
    3 => 'Intermediate',
    4 => 'Upper Intermediate',
    5 => 'Advanced'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn Cantonese - Level <?= $level ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #58cc02;
            --green-dark: #46a302;
            --green-light: #d7ffb8;
            --blue: #1cb0f6;
            --blue-dark: #1899d6;
            --red: #ff4b4b;
            --red-light: #ffdfe0;
            --bg: #235390;
            --card-bg: #ffffff;
            --text-dark: #3c3c3c;
            --text-light: #777777;
            --selected: #84d8ff;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(180deg, var(--bg) 0%, #1a3d6d 100%);
            min-height: 100vh;
            color: var(--text-dark);
        }
        
        .top-bar {
            background: rgba(0,0,0,0.2);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .back-btn {
            color: white;
            text-decoration: none;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .progress-bar {
            flex: 1;
            max-width: 400px;
            height: 16px;
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
            margin: 0 2rem;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--green);
            border-radius: 10px;
            transition: width 0.5s ease;
            width: 0%;
        }
        
        .level-selector {
            position: relative;
        }
        
        .level-badge {
            background: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            color: var(--bg);
            cursor: pointer;
            transition: all 0.15s;
        }
        
        .level-badge:hover {
            transform: scale(1.05);
        }
        
        .level-badge::after {
            content: ' ▼';
            font-size: 0.7rem;
        }
        
        .level-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
            padding: 0.5rem;
            margin-top: 0.5rem;
            z-index: 100;
            min-width: 200px;
        }
        
        .level-dropdown.show {
            display: block;
            animation: fadeIn 0.2s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(-50%) translateY(-10px); }
            to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
        
        .level-option {
            display: block;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 700;
            transition: background 0.15s;
        }
        
        .level-option:hover {
            background: #f0f4f8;
        }
        
        .level-option .level-dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }
        
        .hide-toggle {
            background: white;
            color: var(--bg);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
            box-shadow: 0 3px 0 rgba(0,0,0,0.2);
        }
        
        .hide-toggle:hover {
            transform: translateY(-2px);
        }
        
        .hide-toggle:active {
            transform: translateY(3px);
            box-shadow: 0 0 0 rgba(0,0,0,0.2);
        }
        
        .hide-toggle.active {
            background: var(--blue);
            color: white;
        }
        
        body.hide-mode .yue-card .yue-text,
        body.hide-mode .yue-card .jyutping {
            display: none;
        }
        
        body.hide-mode .yue-card .speaker {
            position: static;
            transform: none;
            font-size: 2rem;
        }
        
        body.hide-mode .yue-card {
            min-height: 100px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .instruction {
            text-align: center;
            color: white;
            margin-bottom: 2rem;
        }
        
        .instruction h2 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .instruction p {
            opacity: 0.9;
        }
        
        .game-area {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .column {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .column-title {
            color: white;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .word-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            font-size: 1.2rem;
            font-weight: 700;
            text-align: center;
            cursor: pointer;
            transition: all 0.15s;
            box-shadow: 0 4px 0 #d1d1d1;
            position: relative;
            top: 0;
            border: 3px solid transparent;
            user-select: none;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .word-card:hover:not(.matched):not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 0 #d1d1d1;
        }
        
        .word-card:active:not(.matched):not(.disabled) {
            transform: translateY(4px);
            box-shadow: 0 0 0 #d1d1d1;
        }
        
        .word-card.selected {
            border-color: var(--blue);
            background: #e8f7ff;
            box-shadow: 0 4px 0 var(--blue-dark);
        }
        
        .word-card.correct {
            background: var(--green-light);
            border-color: var(--green);
            box-shadow: 0 4px 0 var(--green-dark);
            animation: pulse-green 0.5s;
        }
        
        .word-card.wrong {
            background: var(--red-light);
            border-color: var(--red);
            animation: shake 0.5s;
        }
        
        .word-card.matched {
            opacity: 0.5;
            pointer-events: none;
            background: var(--green-light);
            border-color: var(--green);
        }
        
        .word-card.disabled {
            pointer-events: none;
        }
        
        .word-card .jyutping {
            font-size: 0.8rem;
            color: var(--text-light);
            font-weight: 600;
            margin-top: 0.25rem;
        }
        
        .word-card .speaker {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            opacity: 0.6;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-8px); }
            40%, 80% { transform: translateX(8px); }
        }
        
        @keyframes pulse-green {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .complete-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            align-items: center;
            justify-content: center;
            z-index: 100;
        }
        
        .complete-modal.show {
            display: flex;
        }
        
        .modal-content {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            text-align: center;
            max-width: 400px;
            animation: pop-in 0.3s;
        }
        
        @keyframes pop-in {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .modal-content h2 {
            color: var(--green);
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .modal-content p {
            color: var(--text-light);
            margin-bottom: 2rem;
        }
        
        .btn {
            display: inline-block;
            padding: 1rem 2.5rem;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.15s;
            box-shadow: 0 4px 0 var(--green-dark);
            background: var(--green);
            color: white;
            min-width: 180px;
            text-align: center;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn:active {
            transform: translateY(4px);
            box-shadow: 0 0 0 var(--green-dark);
        }
        
        .btn-secondary {
            background: var(--blue);
            box-shadow: 0 4px 0 var(--blue-dark);
        }
        
        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .modal-buttons .btn {
            flex: 1;
            min-width: 150px;
            max-width: 200px;
        }
        
        @media (max-width: 700px) {
            .game-area {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .container { padding: 1rem; }
            
            .top-bar { padding: 0.75rem 1rem; }
            
            .progress-bar { margin: 0 1rem; }
            
            .word-card {
                padding: 1rem;
                font-size: 1.1rem;
            }
            
            .instruction h2 { font-size: 1.4rem; }
            
            .modal-content { margin: 1rem; padding: 2rem; }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <a href="books.php" class="back-btn">✕ Exit</a>
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <div class="level-selector">
            <span class="level-badge" onclick="toggleLevelDropdown()">Level <?= $level ?></span>
            <div class="level-dropdown" id="levelDropdown">
                <a href="learn.php?level=1" class="level-option"><span class="level-dot" style="background: #58cc02;"></span>Level 1 - Beginner</a>
                <a href="learn.php?level=2" class="level-option"><span class="level-dot" style="background: #1cb0f6;"></span>Level 2 - Elementary</a>
                <a href="learn.php?level=3" class="level-option"><span class="level-dot" style="background: #ff9600;"></span>Level 3 - Intermediate</a>
                <a href="learn.php?level=4" class="level-option"><span class="level-dot" style="background: #ff4b4b;"></span>Level 4 - Upper Intermediate</a>
                <a href="learn.php?level=5" class="level-option"><span class="level-dot" style="background: #ce82ff;"></span>Level 5 - Advanced</a>
            </div>
        </div>
        <button class="hide-toggle" id="hideToggle" onclick="toggleHideMode()">👁 Hide Text</button>
    </div>
    
    <div class="container">
        <div class="instruction">
            <h2>Match the Words</h2>
            <p id="instructionText">Click an English word, then click its Cantonese translation</p>
        </div>
        
        <div class="game-area">
            <div class="column">
                <div class="column-title">🇬🇧 English</div>
                <?php 
                $shuffledEn = $words;
                shuffle($shuffledEn);
                foreach ($shuffledEn as $i => $word): 
                ?>
                <div class="word-card en-card" data-pair="<?= md5($word['en']) ?>" data-word="<?= htmlspecialchars($word['en']) ?>">
                    <?= htmlspecialchars($word['en']) ?>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="column">
                <div class="column-title">🇭🇰 Cantonese</div>
                <?php 
                $shuffledYue = $words;
                shuffle($shuffledYue);
                foreach ($shuffledYue as $i => $word): 
                ?>
                <div class="word-card yue-card" data-pair="<?= md5($word['en']) ?>" data-jyutping="<?= htmlspecialchars($word['jyutping']) ?>" data-yue="<?= htmlspecialchars($word['yue']) ?>">
                    <span class="yue-text"><?= htmlspecialchars($word['yue']) ?></span>
                    <div class="jyutping"><?= htmlspecialchars($word['jyutping']) ?></div>
                    <span class="speaker">🔊</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="complete-modal" id="completeModal">
        <div class="modal-content">
            <h2>🎉 Excellent!</h2>
            <p>You've completed all the matches for Level <?= $level ?>!</p>
            <div class="modal-buttons">
                <a href="learn.php?level=<?= $level ?>" class="btn">Practice Again</a>
                <?php if ($level < 5): ?>
                <a href="learn.php?level=<?= $level + 1 ?>" class="btn btn-secondary">Next Level →</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        const totalPairs = 5;
        let matchedPairs = 0;
        let selectedCard = null;
        let isProcessing = false;
        
        // Speech synthesis for Cantonese 浏览器内置合成发音
        let hideMode = false;
        
        function speakCantonese(text, jyutping) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'zh-HK';
                utterance.rate = 0.8;
                speechSynthesis.speak(utterance);
            }
        }
        
        function toggleHideMode() {
            hideMode = !hideMode;
            document.body.classList.toggle('hide-mode', hideMode);
            const btn = document.getElementById('hideToggle');
            btn.classList.toggle('active', hideMode);
            btn.textContent = hideMode ? '👁 Show Text' : '👁 Hide Text';
            document.getElementById('instructionText').textContent = hideMode 
                ? 'Listen to the audio and match with English words' 
                : 'Click an English word, then click its Cantonese translation';
        }
        
        function toggleLevelDropdown() {
            document.getElementById('levelDropdown').classList.toggle('show');
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.level-selector')) {
                document.getElementById('levelDropdown').classList.remove('show');
            }
        });
        
        // Add experience points
        function addExperience(exp) {
            fetch('api/add_exp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ exp: exp })
            })
            .then(res => res.json())
            .then(data => {
                if (data.leveled_up) {
                    alert('🎉 Level Up! You are now Level ' + data.level + '!');
                }
            })
            .catch(err => console.log('Exp update failed'));
        }
        
        // Update progress bar
        function updateProgress() {
            const percent = (matchedPairs / totalPairs) * 100;
            document.getElementById('progressFill').style.width = percent + '%';
        }
        
        // Handle card click
        document.querySelectorAll('.word-card').forEach(card => {
            card.addEventListener('click', function() {
                if (isProcessing || this.classList.contains('matched')) return;
                
                const isYueCard = this.classList.contains('yue-card');
                
                // Play audio for Cantonese cards
                if (isYueCard) {
                    const text = this.dataset.yue;
                    speakCantonese(text);
                }
                
                // If no card selected yet
                if (!selectedCard) {
                    selectedCard = this;
                    this.classList.add('selected');
                    return;
                }
                
                // If clicking the same card, deselect
                if (selectedCard === this) {
                    selectedCard.classList.remove('selected');
                    selectedCard = null;
                    return;
                }
                
                // If clicking same type of card, switch selection
                if ((selectedCard.classList.contains('en-card') && this.classList.contains('en-card')) ||
                    (selectedCard.classList.contains('yue-card') && this.classList.contains('yue-card'))) {
                    selectedCard.classList.remove('selected');
                    selectedCard = this;
                    this.classList.add('selected');
                    return;
                }
                
                // Check for match
                isProcessing = true;
                const pair1 = selectedCard.dataset.pair;
                const pair2 = this.dataset.pair;
                
                if (pair1 === pair2) {
                    // Correct match
                    selectedCard.classList.remove('selected');
                    selectedCard.classList.add('correct');
                    this.classList.add('correct');
                    
                    setTimeout(() => {
                        selectedCard.classList.remove('correct');
                        selectedCard.classList.add('matched');
                        this.classList.add('matched');
                        selectedCard = null;
                        isProcessing = false;
                        matchedPairs++;
                        updateProgress();
                        
                        // Check if complete
                        if (matchedPairs === totalPairs) {
                            addExperience(<?= $level * 5 ?>);
                            setTimeout(() => {
                                document.getElementById('completeModal').classList.add('show');
                            }, 500);
                        }
                    }, 600);
                } else {
                    // Wrong match
                    selectedCard.classList.remove('selected');
                    selectedCard.classList.add('wrong');
                    this.classList.add('wrong');
                    
                    setTimeout(() => {
                        selectedCard.classList.remove('wrong');
                        this.classList.remove('wrong');
                        selectedCard = null;
                        isProcessing = false;
                    }, 600);
                }
            });
        });
    </script>
</body>
</html>

