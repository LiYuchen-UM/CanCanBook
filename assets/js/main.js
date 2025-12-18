// Main JavaScript for LinguaBook

// Flashcard flip functionality
document.querySelectorAll('.flashcard').forEach(card => {
    card.addEventListener('click', () => {
        card.classList.toggle('flipped');
    });
});

// Quiz functionality
function checkAnswer(element, isCorrect) {
    const options = element.parentElement.querySelectorAll('.quiz-option');
    options.forEach(opt => opt.style.pointerEvents = 'none');
    
    if (isCorrect) {
        element.classList.add('correct');
        playSound('correct');
    } else {
        element.classList.add('wrong');
        playSound('wrong');
        // Show correct answer
        options.forEach(opt => {
            if (opt.dataset.correct === 'true') {
                opt.classList.add('correct');
            }
        });
    }
}

// Audio playback
function playAudio(audioId) {
    const audio = document.getElementById(audioId);
    if (audio) {
        audio.currentTime = 0;
        audio.play();
    }
}

// Sound effects
function playSound(type) {
    // Placeholder for sound effects
    console.log(`Playing ${type} sound`);
}

// Matching game
let selectedCard = null;

function selectMatchCard(element) {
    if (element.classList.contains('matched')) return;
    
    if (selectedCard === null) {
        selectedCard = element;
        element.classList.add('selected');
    } else {
        if (selectedCard.dataset.match === element.dataset.match && selectedCard !== element) {
            selectedCard.classList.add('matched');
            element.classList.add('matched');
            selectedCard.classList.remove('selected');
            playSound('correct');
        } else {
            selectedCard.classList.remove('selected');
            playSound('wrong');
        }
        selectedCard = null;
    }
}

// Progress tracking
function updateProgress(chapterId, activityId, score) {
    fetch('api/progress.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ chapter_id: chapterId, activity_id: activityId, score: score })
    })
    .then(response => response.json())
    .then(data => console.log('Progress saved:', data))
    .catch(error => console.error('Error:', error));
}

// Fill in the blank
function checkFillBlank(input, correctAnswer) {
    const userAnswer = input.value.trim().toLowerCase();
    const correct = correctAnswer.toLowerCase();
    
    if (userAnswer === correct) {
        input.classList.add('correct');
        input.style.borderColor = '#10b981';
        playSound('correct');
        return true;
    } else {
        input.classList.add('wrong');
        input.style.borderColor = '#ef4444';
        playSound('wrong');
        return false;
    }
}

