<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Level - CanCanBook</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #58cc02;
            --green-dark: #46a302;
            --blue: #1cb0f6;
            --blue-dark: #1899d6;
            --orange: #ff9600;
            --red: #ff4b4b;
            --purple: #ce82ff;
            --bg: #235390;
            --card-bg: #ffffff;
            --text-dark: #3c3c3c;
            --text-light: #777777;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(180deg, var(--bg) 0%, #1a3d6d 100%);
            min-height: 100vh;
            color: var(--text-dark);
        }
        
        .container {
            max-width: 800px;
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
        
        .level-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .level-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            cursor: pointer;
            text-decoration: none;
            color: var(--text-dark);
            transition: transform 0.15s;
            box-shadow: 0 4px 0 rgba(0,0,0,0.2);
            position: relative;
            top: 0;
        }
        
        .level-card:hover {
            transform: translateY(-2px);
        }
        
        .level-card:active {
            transform: translateY(4px);
            box-shadow: 0 0 0 rgba(0,0,0,0.2);
        }
        
        .level-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            flex-shrink: 0;
        }
        
        .level-1 .level-icon { background: #e5f8e0; }
        .level-2 .level-icon { background: #e0f4fc; }
        .level-3 .level-icon { background: #fff4e0; }
        .level-4 .level-icon { background: #ffe0e0; }
        .level-5 .level-icon { background: #f0e0ff; }
        
        .level-info h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .level-info p {
            color: var(--text-light);
            font-size: 0.95rem;
        }
        
        .level-badge {
            margin-left: auto;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        
        .level-1 .level-badge { background: var(--green); color: white; }
        .level-2 .level-badge { background: var(--blue); color: white; }
        .level-3 .level-badge { background: var(--orange); color: white; }
        .level-4 .level-badge { background: var(--red); color: white; }
        .level-5 .level-badge { background: var(--purple); color: white; }
        
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
        
        @media (max-width: 600px) {
            .container { padding: 1rem; }
            .header h1 { font-size: 1.8rem; }
            .level-card { padding: 1rem 1.25rem; gap: 1rem; }
            .level-icon { width: 50px; height: 50px; font-size: 1.5rem; }
            .level-info h3 { font-size: 1.1rem; }
            .level-badge { padding: 0.3rem 0.75rem; font-size: 0.75rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="start.php" class="back-link">← Back</a>
        
        <div class="header">
            <h1>🇭🇰 Learn Cantonese</h1>
            <p>Choose your current level to get started</p>
        </div>
        
        <div class="level-grid">
            <a href="learn.php?level=1" class="level-card level-1">
                <div class="level-icon">🌱</div>
                <div class="level-info">
                    <h3>Complete Beginner</h3>
                    <p>I'm new to Cantonese, starting from zero</p>
                </div>
                <span class="level-badge">Level 1</span>
            </a>
            
            <a href="learn.php?level=2" class="level-card level-2">
                <div class="level-icon">📚</div>
                <div class="level-info">
                    <h3>Elementary</h3>
                    <p>I know basic greetings and simple phrases</p>
                </div>
                <span class="level-badge">Level 2</span>
            </a>
            
            <a href="learn.php?level=3" class="level-card level-3">
                <div class="level-icon">💬</div>
                <div class="level-info">
                    <h3>Intermediate</h3>
                    <p>I can have simple daily conversations</p>
                </div>
                <span class="level-badge">Level 3</span>
            </a>
            
            <a href="learn.php?level=4" class="level-card level-4">
                <div class="level-icon">🎯</div>
                <div class="level-info">
                    <h3>Upper Intermediate</h3>
                    <p>I can discuss various topics comfortably</p>
                </div>
                <span class="level-badge">Level 4</span>
            </a>
            
            <a href="learn.php?level=5" class="level-card level-5">
                <div class="level-icon">👑</div>
                <div class="level-info">
                    <h3>Advanced</h3>
                    <p>I want to master idioms and nuances</p>
                </div>
                <span class="level-badge">Level 5</span>
            </a>
        </div>
    </div>
</body>
</html>

