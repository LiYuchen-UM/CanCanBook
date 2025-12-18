<?php
$pageTitle = 'Chapter';
require_once 'includes/header.php';

$chapterId = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT c.*, b.title as book_title FROM chapters c JOIN books b ON c.book_id = b.id WHERE c.id = ?");
$stmt->execute([$chapterId]);
$chapter = $stmt->fetch();

if (!$chapter) {
    header('Location: books.php');
    exit;
}

// Get activities
$stmt = $pdo->prepare("SELECT * FROM activities WHERE chapter_id = ? ORDER BY order_num");
$stmt->execute([$chapterId]);
$activities = $stmt->fetchAll();

// Get vocabulary
$stmt = $pdo->prepare("SELECT * FROM vocabulary WHERE chapter_id = ?");
$stmt->execute([$chapterId]);
$vocabulary = $stmt->fetchAll();
?>

<main class="container" style="padding: 3rem 0;">
    <nav class="breadcrumb" style="margin-bottom: 2rem;">
        <a href="books.php">Books</a> &gt; 
        <a href="book.php?id=<?= $chapter['book_id'] ?>"><?= htmlspecialchars($chapter['book_title']) ?></a> &gt; 
        <span><?= htmlspecialchars($chapter['title']) ?></span>
    </nav>

    <h1><?= htmlspecialchars($chapter['title']) ?></h1>
    
    <div class="chapter-content" style="margin: 2rem 0;">
        <?= $chapter['content'] ?>
    </div>

    <?php if ($vocabulary): ?>
    <section class="vocabulary-section">
        <h2>📝 Vocabulary</h2>
        <div class="vocabulary-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin: 1rem 0;">
            <?php foreach ($vocabulary as $word): ?>
            <div class="flashcard" onclick="this.classList.toggle('flipped')">
                <div class="flashcard-inner">
                    <div class="flashcard-front">
                        <?= htmlspecialchars($word['word']) ?>
                        <?php if ($word['audio_file']): ?>
                        <button onclick="event.stopPropagation(); playAudio('audio-<?= $word['id'] ?>')" style="position: absolute; bottom: 10px; right: 10px;">🔊</button>
                        <audio id="audio-<?= $word['id'] ?>" src="<?= $word['audio_file'] ?>"></audio>
                        <?php endif; ?>
                    </div>
                    <div class="flashcard-back">
                        <?= htmlspecialchars($word['translation']) ?>
                        <?php if ($word['pronunciation']): ?>
                        <small style="display: block; margin-top: 0.5rem;">[<?= htmlspecialchars($word['pronunciation']) ?>]</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php foreach ($activities as $activity): ?>
    <section class="activity-container">
        <h3><?= htmlspecialchars($activity['title']) ?></h3>
        <p class="instructions" style="color: var(--text-muted); margin-bottom: 1rem;"><?= htmlspecialchars($activity['instructions']) ?></p>
        
        <?php 
        $data = json_decode($activity['data'], true);
        switch ($activity['type']):
            case 'quiz': ?>
                <div class="quiz" data-activity-id="<?= $activity['id'] ?>">
                    <?php foreach ($data['questions'] as $i => $q): ?>
                    <div class="quiz-question" style="margin-bottom: 2rem;">
                        <p style="font-weight: 500; margin-bottom: 1rem;"><?= ($i+1) ?>. <?= htmlspecialchars($q['question']) ?></p>
                        <?php foreach ($q['options'] as $opt): ?>
                        <div class="quiz-option" onclick="checkAnswer(this, <?= $opt === $q['answer'] ? 'true' : 'false' ?>)" data-correct="<?= $opt === $q['answer'] ? 'true' : 'false' ?>">
                            <?= htmlspecialchars($opt) ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php break;
            
            case 'matching': ?>
                <div class="matching-game" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                    <?php foreach ($data['pairs'] as $pair): ?>
                    <div class="match-card quiz-option" onclick="selectMatchCard(this)" data-match="<?= $pair['id'] ?>">
                        <?= htmlspecialchars($pair['word']) ?>
                    </div>
                    <div class="match-card quiz-option" onclick="selectMatchCard(this)" data-match="<?= $pair['id'] ?>">
                        <?= htmlspecialchars($pair['translation']) ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php break;
            
            case 'fill_blank': ?>
                <div class="fill-blank">
                    <?php foreach ($data['sentences'] as $s): ?>
                    <p style="margin: 1rem 0;">
                        <?= str_replace('___', '<input type="text" class="fill-input" onblur="checkFillBlank(this, \'' . htmlspecialchars($s['answer']) . '\')" style="width: 150px; padding: 0.5rem; border-radius: 8px; border: 2px solid var(--bg-card); background: var(--bg-dark); color: var(--text);">', htmlspecialchars($s['sentence'])) ?>
                    </p>
                    <?php endforeach; ?>
                </div>
            <?php break;
            
            case 'listening': ?>
                <div class="listening-exercise">
                    <div class="audio-player">
                        <button onclick="playAudio('listening-<?= $activity['id'] ?>')">▶</button>
                        <audio id="listening-<?= $activity['id'] ?>" src="<?= $data['audio_file'] ?>"></audio>
                        <span>Listen and answer</span>
                    </div>
                    <div style="margin-top: 1rem;">
                        <?php foreach ($data['questions'] as $q): ?>
                        <input type="text" placeholder="<?= htmlspecialchars($q['hint'] ?? 'Type your answer...') ?>" 
                               onblur="checkFillBlank(this, '<?= htmlspecialchars($q['answer']) ?>')"
                               style="width: 100%; padding: 1rem; margin: 0.5rem 0; border-radius: 8px; border: 2px solid var(--bg-card); background: var(--bg-dark); color: var(--text);">
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php break;
        endswitch; ?>
    </section>
    <?php endforeach; ?>
</main>

<?php require_once 'includes/footer.php'; ?>

