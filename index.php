<?php
$pageTitle = 'CanCanBook';
require_once 'includes/header.php';
?>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        .navbar,
        .navbar * {
            font-family: 'Nunito', sans-serif !important;
        }
    </style>

    <header class="hero">
        <div class="container">
            <h1>Learn Oral Cantonese Through Interactive Games, Stories & Quizzes</h1>
            <p>Practice real Cantonese with word-matching games, interactive stories, native-like audio, and quick quizzes that keep learning fun and focused.</p>
            <a href="/start" class="btn btn-large" id="startLearningBtn">Start Learning</a>
        </div>
    </header>

    <section class="features">
        <div class="container">
            <div class="video-container">
                <iframe width="960" height="540" src="https://www.youtube.com/embed/xThI1i_fWDo?si=4r7qvo1gMa98wjEI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
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
                    <p>Log in to earn experience from games and quizzes, level up your profile, and see your progress at a glance.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Login Check Modal -->
    <div id="loginModal" class="login-modal" style="display: none;">
        <div class="login-modal-content">
            <h2>⚠️ Not Logged In</h2>
            <p>You are not logged in. You won't be able to earn experience points (XP) or level up your profile.</p>
            <div class="login-modal-buttons">
                <a href="/auth/login" class="btn btn-primary">Sign in with Google</a>
                <button onclick="continueWithoutLogin()" class="btn btn-outline">Continue Without Login</button>
            </div>
        </div>
    </div>

    <style>
        .video-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 2rem 0 3rem 0;
        }

        .video-container iframe {
            max-width: 100%;
            height: auto;
            aspect-ratio: 16 / 9;
        }

        @media (max-width: 768px) {
            .video-container iframe {
                width: 100%;
                height: auto;
            }
        }

        .login-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        }

        .login-modal-content {
            background: white;
            border-radius: 24px;
            padding: 2.5rem;
            max-width: 450px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: pop-in 0.3s;
        }

        @keyframes pop-in {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .login-modal-content h2 {
            color: var(--primary);
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .login-modal-content p {
            color: #1e293b;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .login-modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .login-modal-buttons .btn {
            flex: 1;
            min-width: 150px;
            max-width: 200px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startBtn = document.getElementById('startLearningBtn');
            const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
            
            if (!isLoggedIn) {
                startBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('loginModal').style.display = 'flex';
                });
            }
        });

        function continueWithoutLogin() {
            document.getElementById('loginModal').style.display = 'none';
            window.location.href = '/start';
        }
    </script>

<?php require_once 'includes/footer.php'; ?>

