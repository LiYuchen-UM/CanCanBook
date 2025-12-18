<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$expToAdd = intval($data['exp'] ?? 0);

if ($expToAdd <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid experience']);
    exit;
}

$userId = $_SESSION['user_id'];

// Get current user data
$stmt = $pdo->prepare("SELECT level, experience FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$currentLevel = $user['level'];
$currentExp = $user['experience'] + $expToAdd;

// Calculate level ups
// Level 1->2: 100, Level 2->3: 200, Level 3->4: 300, etc.
// Required exp for level N = N * 100
$newLevel = $currentLevel;
while (true) {
    $requiredExp = $newLevel * 100;
    if ($currentExp >= $requiredExp) {
        $currentExp -= $requiredExp;
        $newLevel++;
    } else {
        break;
    }
}

// Update user
$stmt = $pdo->prepare("UPDATE users SET level = ?, experience = ? WHERE id = ?");
$stmt->execute([$newLevel, $currentExp, $userId]);

// Update session
$_SESSION['user_level'] = $newLevel;
$_SESSION['user_exp'] = $currentExp;

$leveledUp = $newLevel > $currentLevel;

echo json_encode([
    'success' => true,
    'level' => $newLevel,
    'experience' => $currentExp,
    'exp_needed' => $newLevel * 100,
    'leveled_up' => $leveledUp
]);

