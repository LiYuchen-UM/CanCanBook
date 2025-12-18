<?php
require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'CantoneseBook' ?> - Learn Cantonese Interactively</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">📚 CanCanBook</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="books.php">Books</a></li>
            </ul>
        </div>
    </nav>

