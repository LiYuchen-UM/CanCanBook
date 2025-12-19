<?php
require_once 'config/database.php';

// Get level filter if specified
$filterLevel = isset($_GET['level']) ? intval($_GET['level']) : 0;

// Get random story from database
if ($filterLevel >= 1 && $filterLevel <= 5) {
    $stmt = $pdo->prepare("SELECT * FROM stories WHERE level = ? ORDER BY RAND() LIMIT 1");
    $stmt->execute([$filterLevel]);
} else {
    $stmt = $pdo->query("SELECT * FROM stories ORDER BY RAND() LIMIT 1");
}
$story = $stmt->fetch();

if (!$story) {
    header('Location: start');
    exit;
}

$content = json_decode($story['content'], true);
$level = $story['level'];
$title = $story['title'];
$author = $story['author'] ?? null;

$levelNames = [
    1 => 'Beginner',
    2 => 'Elementary', 
    3 => 'Intermediate',
    4 => 'Upper Intermediate',
    5 => 'Advanced'
];

$levelColors = [
    1 => '#58cc02',
    2 => '#1cb0f6',
    3 => '#ff9600',
    4 => '#ff4b4b',
    5 => '#ce82ff'
];

$pageTitle = htmlspecialchars($title);
require_once 'includes/header.php';
?>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #58cc02;
            --blue: #1cb0f6;
            --orange: #ff9600;
            --red: #ff4b4b;
            --purple: #ce82ff;
            --bg: #235390;
            --card-bg: #ffffff;
            --text-dark: #3c3c3c;
            --text-light: #777777;
            --level-color: <?= $levelColors[$level] ?>;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        /* Ensure navbar styles are preserved */
        .navbar {
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(10px) !important;
            padding: 0 !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 100 !important;
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
            height: 64px !important;
            min-height: 64px !important;
            max-height: 64px !important;
            width: 100% !important;
        }
        
        .navbar .container {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            height: 100% !important;
            max-width: 100% !important;
            padding: 0 1rem !important;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(180deg, var(--bg) 0%, #1a3d6d 100%);
            min-height: 100vh;
            color: var(--text-dark);
        }
        
        .top-bar {
            background: rgba(0,0,0,0.2);
            padding: 0 1rem !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            height: 64px !important;
            min-height: 64px !important;
            max-height: 64px !important;
            width: 100% !important;
            box-sizing: border-box;
        }
        
        .back-btn {
            color: white;
            text-decoration: none;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .level-selector {
            position: relative;
        }
        
        .level-badge {
            background: var(--level-color);
            padding: 0.5rem 1.25rem;
            border-radius: 20px;
            font-weight: 700;
            color: white;
            font-size: 0.9rem;
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
        
        .refresh-btn {
            background: white;
            color: var(--bg);
            padding: 0.5rem 1.25rem;
            border-radius: 20px;
            font-weight: 700;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.15s;
            box-shadow: 0 3px 0 rgba(0,0,0,0.2);
        }
        
        .refresh-btn:hover {
            transform: translateY(-2px);
        }
        
        .refresh-btn:active {
            transform: translateY(3px);
            box-shadow: 0 0 0 rgba(0,0,0,0.2);
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .story-title {
            text-align: center;
            color: white;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }

        .story-meta {
            text-align: center;
            color: rgba(226,232,240,0.85);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        
        .dialogue-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 4px 0 rgba(0,0,0,0.1);
        }
        
        .speaker {
            font-weight: 800;
            color: var(--level-color);
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
        }
        
        .cantonese-line {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
            line-height: 1;
        }
        
        .char-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            transition: transform 0.15s;
        }
        
        .char-group:hover {
            transform: scale(1.1);
        }
        
        .jyutping {
            font-size: 0.7rem;
            color: var(--text-light);
            font-weight: 600;
            margin-bottom: 2px;
            white-space: nowrap;
        }
        
        .char {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        .english-translation {
            background: #f0f4f8;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            color: var(--text-light);
            font-size: 0.95rem;
            font-style: italic;
            border-left: 4px solid var(--level-color);
        }
        
        .play-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--level-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            transition: all 0.15s;
            box-shadow: 0 3px 0 rgba(0,0,0,0.2);
        }
        
        .play-btn:hover {
            transform: translateY(-2px);
        }
        
        .play-btn:active {
            transform: translateY(3px);
            box-shadow: 0 0 0 rgba(0,0,0,0.2);
        }
        
        @media (max-width: 600px) {
            .container { padding: 1rem; }
            .top-bar { 
                height: 64px !important;
                min-height: 64px !important;
                padding: 0 0.75rem !important;
            }
            .story-title { font-size: 1.5rem; }
            .dialogue-card { padding: 1rem 1.25rem; }
            .char { font-size: 1.3rem; }
            .jyutping { font-size: 0.65rem; }
        }
    </style>
    <div class="top-bar">
        <a href="/start" class="back-btn">← Back</a>
        <div class="level-selector">
            <span class="level-badge" onclick="toggleDropdown()">Level <?= $level ?> - <?= $levelNames[$level] ?></span>
            <div class="level-dropdown" id="levelDropdown">
                <a href="/stories/1" class="level-option"><span class="level-dot" style="background: #58cc02;"></span>Level 1 - Beginner</a>
                <a href="/stories/2" class="level-option"><span class="level-dot" style="background: #1cb0f6;"></span>Level 2 - Elementary</a>
                <a href="/stories/3" class="level-option"><span class="level-dot" style="background: #ff9600;"></span>Level 3 - Intermediate</a>
                <a href="/stories/4" class="level-option"><span class="level-dot" style="background: #ff4b4b;"></span>Level 4 - Upper Intermediate</a>
                <a href="/stories/5" class="level-option"><span class="level-dot" style="background: #ce82ff;"></span>Level 5 - Advanced</a>
            </div>
        </div>
        <a href="/stories/<?= $level ?>" class="refresh-btn">🔄 New Story</a>
    </div>
    
    <div class="container">
        <h1 class="story-title">📖 <?= htmlspecialchars($title) ?></h1>
        <?php if ($author): ?>
        <div class="story-meta"><?= htmlspecialchars($author) ?></div>
        <?php endif; ?>
        
        <?php foreach ($content as $line): ?>
        <div class="dialogue-card">
            <div class="speaker"><?= htmlspecialchars($line['speaker']) ?></div>
            
            <button class="play-btn" onclick="speakCantonese('<?= htmlspecialchars($line['cantonese'], ENT_QUOTES) ?>')">
                🔊 Play Audio
            </button>
            
            <div class="cantonese-line">
                <?php 
                $chars = preg_split('//u', $line['cantonese'], -1, PREG_SPLIT_NO_EMPTY);
                $jyutpingParts = explode(' ', $line['jyutping']);
                $jIndex = 0;
                foreach ($chars as $char):
                    // Skip punctuation for jyutping
                    $isPunctuation = preg_match('/[\p{P}\s]/u', $char);
                    $jyut = '';
                    if (!$isPunctuation && isset($jyutpingParts[$jIndex])) {
                        $jyut = $jyutpingParts[$jIndex];
                        $jIndex++;
                    }
                ?>
                <div class="char-group" onclick="speakCantonese('<?= htmlspecialchars($char, ENT_QUOTES) ?>')">
                    <span class="jyutping"><?= $jyut ?></span>
                    <span class="char"><?= htmlspecialchars($char) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="english-translation">
                <?= htmlspecialchars($line['english']) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <script>
        function speakCantonese(text) {
            if ('speechSynthesis' in window) {
                speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'zh-HK';
                utterance.rate = 0.8;
                speechSynthesis.speak(utterance);
            }
        }
        
        function toggleDropdown() {
            document.getElementById('levelDropdown').classList.toggle('show');
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.level-selector')) {
                document.getElementById('levelDropdown').classList.remove('show');
            }
        });
    </script>
<?php require_once 'includes/footer.php'; ?>

