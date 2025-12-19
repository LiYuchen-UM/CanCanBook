<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

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
    <title><?= $pageTitle ?? 'CanCanBook' ?> - Learn Cantonese Interactively</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/index" class="logo"><img src="/favicon.ico" alt="" style="width: 1.5rem; height: 1.5rem; vertical-align: middle; margin-right: 0.5rem;"> CanCanBook</a>
            <ul class="nav-links">
                <li><a href="/index">Home</a></li>
                <li><a href="/start">Learn</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li><span style="color: #e2e8f0;">Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></span></li>
                <?php if ($userLevel !== null): ?>
                <li>
                    <span style="display: inline-block; background: rgba(15,23,42,0.9); padding: 0.4rem 0.8rem; border-radius: 999px; border: 1px solid rgba(148,163,184,0.6); font-size: 0.8rem; color: #e2e8f0;">
                        Lv <?= $userLevel ?> · <?= $userExp ?>/<?= $userNextReq ?> EXP
                    </span>
                </li>
                <?php endif; ?>
                <li><a href="/auth/logout" class="btn btn-outline">Logout</a></li>
                <?php else: ?>
                <li><a href="/auth/login" class="btn btn-primary">Sign in with Google</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

