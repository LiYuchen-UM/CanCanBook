<?php
require_once 'config/database.php';

// Get level filter if specified
$filterLevel = isset($_GET['level']) ? intval($_GET['level']) : 0;

// Get random quiz question from database
if ($filterLevel >= 1 && $filterLevel <= 5) {
    $stmt = $pdo->prepare("SELECT * FROM quiz_questions WHERE level = ? ORDER BY RAND() LIMIT 1");
    $stmt->execute([$filterLevel]);
} else {
    $stmt = $pdo->query("SELECT * FROM quiz_questions ORDER BY RAND() LIMIT 1");
}
$quiz = $stmt->fetch();

if (!$quiz) {
    header('Location: start.php');
    exit;
}

$level = $quiz['level'];

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Quiz - CanCanBook</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #58cc02;
            --green-dark: #46a302;
            --blue: #1cb0f6;
            --blue-dark: #1899d6;
            --red: #ff4b4b;
            --bg: #235390;
            --card-bg: #ffffff;
            --text-dark: #3c3c3c;
            --text-light: #777777;
            --level-color: <?= $levelColors[$level] ?>;
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
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .back-btn {
            color: white;
            text-decoration: none;
            font-weight: 700;
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
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .quiz-title {
            text-align: center;
            color: white;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 2rem;
        }
        
        .quiz-card {
            background: var(--card-bg);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 6px 0 rgba(0,0,0,0.1);
        }
        
        .question {
            font-size: 1.3rem;
            font-weight: 700;
            line-height: 1.6;
            margin-bottom: 2rem;
            color: var(--text-dark);
        }
        
        .options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .option {
            background: #f0f4f8;
            border: 3px solid transparent;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
            box-shadow: 0 4px 0 #d1d1d1;
            position: relative;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .option:hover:not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 0 #d1d1d1;
            background: #e8f0f8;
        }
        
        .option:active:not(.disabled) {
            transform: translateY(4px);
            box-shadow: 0 0 0 #d1d1d1;
        }
        
        .option .letter {
            width: 36px;
            height: 36px;
            background: var(--blue);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            flex-shrink: 0;
        }
        
        .option.correct {
            background: #d7ffb8;
            border-color: var(--green);
            box-shadow: 0 4px 0 var(--green-dark);
        }
        
        .option.correct .letter {
            background: var(--green);
        }
        
        .option.wrong {
            background: #ffdfe0;
            border-color: var(--red);
            animation: shake 0.5s;
        }
        
        .option.wrong .letter {
            background: var(--red);
        }
        
        .option.disabled {
            pointer-events: none;
            opacity: 0.6;
        }
        
        .option.disabled.correct {
            opacity: 1;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-8px); }
            40%, 80% { transform: translateX(8px); }
        }
        
        .explanation {
            display: none;
            margin-top: 1.5rem;
            padding: 1rem 1.25rem;
            background: #e8f7ff;
            border-radius: 12px;
            border-left: 4px solid var(--blue);
            color: var(--text-dark);
            font-size: 1rem;
            line-height: 1.5;
        }
        
        .explanation.show {
            display: block;
            animation: fadeIn 0.3s;
        }
        
        .next-btn {
            display: none;
            margin-top: 1.5rem;
            width: 100%;
            padding: 1rem;
            background: var(--green);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 0 var(--green-dark);
            transition: all 0.15s;
        }
        
        .next-btn.show {
            display: block;
        }
        
        .next-btn:hover {
            transform: translateY(-2px);
        }
        
        .next-btn:active {
            transform: translateY(4px);
            box-shadow: 0 0 0 var(--green-dark);
        }
        
        /* Confetti */
        .confetti-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
            overflow: hidden;
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            top: -10px;
            animation: confetti-fall 3s linear forwards;
        }
        
        @keyframes confetti-fall {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }
        
        @media (max-width: 600px) {
            .container { padding: 1rem; }
            .top-bar { padding: 0.75rem 1rem; }
            .quiz-title { font-size: 1.5rem; }
            .quiz-card { padding: 1.5rem; }
            .question { font-size: 1.1rem; }
            .option { padding: 1rem; font-size: 1rem; }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <a href="start.php" class="back-btn">← Back</a>
        <div class="level-selector">
            <span class="level-badge" onclick="toggleDropdown()">Level <?= $level ?> - <?= $levelNames[$level] ?></span>
            <div class="level-dropdown" id="levelDropdown">
                <a href="quiz.php?level=1" class="level-option"><span class="level-dot" style="background: #58cc02;"></span>Level 1 - Beginner</a>
                <a href="quiz.php?level=2" class="level-option"><span class="level-dot" style="background: #1cb0f6;"></span>Level 2 - Elementary</a>
                <a href="quiz.php?level=3" class="level-option"><span class="level-dot" style="background: #ff9600;"></span>Level 3 - Intermediate</a>
                <a href="quiz.php?level=4" class="level-option"><span class="level-dot" style="background: #ff4b4b;"></span>Level 4 - Upper Intermediate</a>
                <a href="quiz.php?level=5" class="level-option"><span class="level-dot" style="background: #ce82ff;"></span>Level 5 - Advanced</a>
            </div>
        </div>
        <a href="quiz.php<?= $filterLevel ? '?level='.$filterLevel : '' ?>" class="refresh-btn">🔄 New Quiz</a>
    </div>
    
    <div class="container">
        <h1 class="quiz-title">🧠 Quick Quiz</h1>
        
        <div class="quiz-card">
            <div class="question"><?= htmlspecialchars($quiz['question']) ?></div>
            
            <div class="options">
                <div class="option" data-answer="A" onclick="selectAnswer(this, 'A')">
                    <span class="letter">A</span>
                    <span><?= htmlspecialchars($quiz['option_a']) ?></span>
                </div>
                <div class="option" data-answer="B" onclick="selectAnswer(this, 'B')">
                    <span class="letter">B</span>
                    <span><?= htmlspecialchars($quiz['option_b']) ?></span>
                </div>
                <div class="option" data-answer="C" onclick="selectAnswer(this, 'C')">
                    <span class="letter">C</span>
                    <span><?= htmlspecialchars($quiz['option_c']) ?></span>
                </div>
            </div>
            
            <?php if ($quiz['explanation']): ?>
            <div class="explanation" id="explanation">
                💡 <?= htmlspecialchars($quiz['explanation']) ?>
            </div>
            <?php endif; ?>
            
            <button class="next-btn" id="nextBtn" onclick="location.href='quiz.php<?= $filterLevel ? '?level='.$filterLevel : '' ?>'">
                Next Question →
            </button>
        </div>
    </div>
    
    <div class="confetti-container" id="confettiContainer"></div>
    
    <script>
        const correctAnswer = '<?= $quiz['correct_answer'] ?>';
        let answered = false;
        
        function selectAnswer(element, answer) {
            if (answered) return;
            answered = true;
            
            // Disable all options
            document.querySelectorAll('.option').forEach(opt => {
                opt.classList.add('disabled');
            });
            
            if (answer === correctAnswer) {
                element.classList.add('correct');
                launchConfetti();
                addExperience(<?= $level ?>);
            } else {
                element.classList.add('wrong');
                // Show correct answer
                document.querySelector(`.option[data-answer="${correctAnswer}"]`).classList.add('correct');
            }
            
            // Show explanation and next button
            const explanation = document.getElementById('explanation');
            if (explanation) explanation.classList.add('show');
            document.getElementById('nextBtn').classList.add('show');
        }
        
        function launchConfetti() {
            const container = document.getElementById('confettiContainer');
            const colors = ['#58cc02', '#1cb0f6', '#ff9600', '#ff4b4b', '#ce82ff', '#ffd700'];
            
            for (let i = 0; i < 100; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.width = (Math.random() * 10 + 5) + 'px';
                    confetti.style.height = (Math.random() * 10 + 5) + 'px';
                    confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                    confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                    container.appendChild(confetti);
                    
                    setTimeout(() => confetti.remove(), 4000);
                }, i * 20);
            }
        }
        
        function toggleDropdown() {
            document.getElementById('levelDropdown').classList.toggle('show');
        }
        
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.level-selector')) {
                document.getElementById('levelDropdown').classList.remove('show');
            }
        });
        
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
    </script>
</body>
</html>

