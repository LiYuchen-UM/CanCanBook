<?php
$pageTitle = 'Choose Your Path';
require_once 'includes/header.php';
?>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #58cc02;
            --green-dark: #46a302;
            --blue: #1cb0f6;
            --blue-dark: #1899d6;
            --orange: #ff9600;
            --purple: #ce82ff;
            --bg: #235390;
            --card-bg: #ffffff;
            --text-dark: #3c3c3c;
            --text-light: #777777;
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
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 3rem;
        }
        
        .header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        
        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        
        .back-link:hover { opacity: 1; }
        
        .options-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        
        .option-card {
            background: var(--card-bg);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            text-align: center;
            text-decoration: none;
            color: var(--text-dark);
            transition: all 0.2s;
            box-shadow: 0 6px 0 rgba(0,0,0,0.15);
            position: relative;
            top: 0;
        }
        
        .option-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 0 rgba(0,0,0,0.15);
        }
        
        .option-card:active {
            transform: translateY(6px);
            box-shadow: 0 0 0 rgba(0,0,0,0.15);
        }
        
        .option-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .option-card h2 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
        }
        
        .option-card p {
            color: var(--text-light);
            font-size: 1rem;
            line-height: 1.5;
        }
        
        .option-card .badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
            margin-top: 1rem;
            text-transform: uppercase;
        }
        
        .stories-card .badge {
            background: var(--orange);
            color: white;
        }
        
        .vocab-card .badge {
            background: var(--green);
            color: white;
        }
        
        .quiz-card .badge {
            background: var(--purple);
            color: white;
        }
        
        @media (max-width: 900px) {
            .options-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .container { padding: 1rem; }
            .header h1 { font-size: 1.8rem; }
            .option-card { padding: 2rem 1.5rem; }
            .option-icon { font-size: 3rem; }
            .option-card h2 { font-size: 1.3rem; }
        }
    </style>
    <div class="container">
        <a href="/" class="back-link">← Back to Home</a>
        
        <div class="header">
            <h1>👋 What would you like to learn?</h1>
            <p>Choose your learning path</p>
        </div>
        
        <div class="options-grid">
            <a href="/stories" class="option-card stories-card">
                <span class="option-icon">📖</span>
                <h2>Cantonese Stories</h2>
                <p>Learn through engaging interactive stories with audio, vocabulary, and comprehension activities.</p>
                <span class="badge">Immersive</span>
            </a>
            
            <a href="/books" class="option-card vocab-card">
                <span class="option-icon">🎯</span>
                <h2>Vocabulary Practice</h2>
                <p>Master Cantonese words through fun matching games. Choose your difficulty level and start practicing!</p>
                <span class="badge">5 Levels</span>
            </a>
            
            <a href="/quiz" class="option-card quiz-card">
                <span class="option-icon">🧠</span>
                <h2>Quick Quiz</h2>
                <p>Test your Cantonese knowledge with fun multiple-choice questions about vocabulary and idioms!</p>
                <span class="badge">Challenge</span>
            </a>
        </div>
    </div>
<?php require_once 'includes/footer.php'; ?>

