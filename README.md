# CanCanBook - Interactive Cantonese Learning Web App

An interactive Cantonese learning site that combines word-matching games, story reading, quick quizzes, and a simple level/XP system.  
Frontend is classic PHP + vanilla JS, backed by MySQL.

## Main Features

- **Word Matching Practice (`learn.php`)**
  - English ↔ Cantonese matching with Duolingo-style cards
  - Optional “hide Cantonese text” listening mode (listen and match by sound only)
  - 5 difficulty levels, words stored in `level_vocabulary`
  - Correct matches fill a progress bar and award XP based on level

- **Interactive Stories (`stories.php`)**
  - Dialog-based Cantonese stories with:
    - Per-character display: Chinese character + Jyutping on top
    - Line-by-line English translation
  - Click any character or line-level “Play Audio” button to use browser `speechSynthesis` (`zh-HK`)
  - Stories are stored as JSON in the `stories` table; each story has a `level` and `author`

- **Quick Quiz (`quiz.php`)**
  - Single multiple-choice Cantonese question per page
  - 5 levels of difficulty (e.g. idioms, slang, real-life expressions)
  - Correct answers trigger a confetti animation and grant XP

- **Level & XP System**
  - Users start at **Level 1, 0 XP**
  - XP from word-matching (per completed round): `level × 5`
  - XP from quiz: +`level` per correct answer
  - XP thresholds:
    - 1 → 2: 100 XP
    - 2 → 3: 200 XP
    - 3 → 4: 300 XP  
    - … in general: **required XP = current level × 100**
  - XP & level are stored in `users.level` and `users.experience`
  - Current level and XP are displayed in the top navbar: `Lv X · current/required EXP`

- **Google Login Only**
  - Users can only log in via Google OAuth (no password auth)
  - Basic profile stored in `users` table (`google_id`, `email`, `name`, `avatar`)

## Tech Stack

- **Backend**: PHP 7.4+ (plain PHP, no framework)
- **Database**: MySQL 5.7+
- **Frontend**: HTML/CSS/JavaScript
- **Auth**: Google OAuth 2.0 (server-side token exchange with cURL)

## Database Overview

See `database/schema.sql` for the full schema and initial seed data.

## Setup & Run

1. **Clone & install**
   - Put the project into your PHP web root (e.g. `htdocs`, `public_html`, or a virtual host).

2. **Database**
   - Create a MySQL database.
   - Import the schema:
     ```sql
     SOURCE database/schema.sql;
     ```

3. **Configure DB connection**
   - Edit `config/database.php` and set:
     - `DB_HOST`
     - `DB_NAME`
     - `DB_USER`
     - `DB_PASS`

4. **Configure Google OAuth**
   - Go to Google Cloud Console → “APIs & Services” → “Credentials”
   - Create OAuth 2.0 Client ID (Web application)
   - Set **Authorized redirect URI** to something like:
     - `http://your-domain.com/auth/callback.php`
   - Put values into `config/google.php`:
     ```php
     define('GOOGLE_CLIENT_ID', 'YOUR_CLIENT_ID');
     define('GOOGLE_CLIENT_SECRET', 'YOUR_CLIENT_SECRET');
     define('GOOGLE_REDIRECT_URI', 'http://your-domain.com/auth/callback.php');
     ```

5. **Run**
   - Serve the site via Apache/Nginx or PHP’s built-in server, e.g.:
     ```bash
     php -S localhost:8000
     ```
   - Visit `http://localhost:8000/index.php`

## Speech & Browser Support

- Cantonese audio is handled via the browser’s **Web Speech API** (`window.speechSynthesis`)
  - Language: `zh-HK`
  - Quality depends on OS/browser voices installed

## Notes & Customization

- To add more vocabulary: insert into `level_vocabulary` with the appropriate `level`.
- To add more stories: insert into `stories` with properly formatted JSON `content`.
- To add more quiz questions: insert into `quiz_questions` with `level` and options/answer.

favicon.ico by https://icon-icons.com/icon/bookmarks-favorites-eye-vision-show/1263

