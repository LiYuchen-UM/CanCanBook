<?php
session_start();
require_once 'config/database.php';

$userLevel = null;
$userExp = null;
$userNextReq = null;

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT level, experience FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    if ($row = $stmt->fetch()) {
        $userLevel = (int)$row['level'];
        $userExp = (int)$row['experience'];
        $userNextReq = $userLevel * 100;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CanCanBook - Learn Cantonese Interactively</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">📚 CanCanBook</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="start.php">Learn</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li><span style="color: #e2e8f0;">Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></span></li>
                <?php if ($userLevel !== null): ?>
                <li>
                    <span style="display: inline-block; background: rgba(15,23,42,0.9); padding: 0.4rem 0.8rem; border-radius: 999px; border: 1px solid rgba(148,163,184,0.6); font-size: 0.8rem; color: #e2e8f0;">
                        Lv <?= $userLevel ?> · <?= $userExp ?>/<?= $userNextReq ?> EXP
                    </span>
                </li>
                <?php endif; ?>
                <li><a href="auth/logout.php" class="btn btn-outline">Logout</a></li>
                <?php else: ?>
                <li><a href="auth/login.php" class="btn btn-primary">Sign in with Google</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <header class="hero">
        <div class="container">
            <h1>Learn Cantonese Through Interactive Games, Stories & Quizzes</h1>
            <p>Practice real Cantonese with word-matching games, interactive stories, native-like audio, and quick quizzes that keep learning fun and focused.</p>
            <a href="start.php" class="btn btn-large">Start Learning</a>
        </div>
    </header>

    <section class="features">
        <div class="container">
            <h2>Why CanCanBook?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <span class="feature-icon">🎮</span>
                    <h3>Word Matching Practice</h3>
                    <p>Match English and Cantonese words with interactive Duolingo-style games and listening-only mode.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">📖</span>
                    <h3>Interactive Stories</h3>
                    <p>Read Cantonese dialogues with per-character Jyutping and English translations for every line.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">🧠</span>
                    <h3>Quick Cantonese Quizzes</h3>
                    <p>Test slang, idioms, and real-life expressions with multiple-choice questions and instant feedback.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">📊</span>
                    <h3>Level & XP System</h3>
                    <p>Earn experience from games and quizzes, level up your profile, and see your progress at a glance.</p>
                </div>
            </div>
        </div>
    </section>


    <footer class="footer">
        <div class="container">
            <p>
                &copy; <?= date('Y') ?> CanCanBook Licensed under the MIT License.
                <a href="https://github.com/LiYuchen-UM/CanCanBook" target="_blank" rel="noopener noreferrer" aria-label="GitHub" style="margin-left: 12px; display: inline-flex; vertical-align: middle;">
                    <img src="assets/github-mark.svg" alt="GitHub" style="width: 32px; height: 32px;">
                </a>
            </p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>

