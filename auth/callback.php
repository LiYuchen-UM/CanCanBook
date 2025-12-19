<?php
session_start();
require_once '../config/database.php';
require_once '../config/google.php';

if (!isset($_GET['code'])) {
    header('Location: ../index');
    exit;
}

// Exchange code for access token
$tokenData = [
    'code' => $_GET['code'],
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'grant_type' => 'authorization_code'
];

$ch = curl_init(GOOGLE_TOKEN_URL);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$token = json_decode($response, true);

if (!isset($token['access_token'])) {
    header('Location: ../index.php?error=auth_failed');
    exit;
}

// Get user info from Google
$ch = curl_init(GOOGLE_USERINFO_URL);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token['access_token']]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$userInfo = json_decode($response, true);

if (!isset($userInfo['id'])) {
    header('Location: ../index.php?error=user_info_failed');
    exit;
}

// Check if user exists, if not create
$stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ?");
$stmt->execute([$userInfo['id']]);
$user = $stmt->fetch();

if (!$user) {
    // Create new user
    $stmt = $pdo->prepare("INSERT INTO users (google_id, email, name, avatar) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $userInfo['id'],
        $userInfo['email'],
        $userInfo['name'],
        $userInfo['picture'] ?? null
    ]);
    $userId = $pdo->lastInsertId();
} else {
    $userId = $user['id'];
    // Update last login and info
    $stmt = $pdo->prepare("UPDATE users SET name = ?, avatar = ?, last_login = NOW() WHERE id = ?");
    $stmt->execute([$userInfo['name'], $userInfo['picture'] ?? null, $userId]);
}

// Set session
$_SESSION['user_id'] = $userId;
$_SESSION['user_name'] = $userInfo['name'];
$_SESSION['user_email'] = $userInfo['email'];
$_SESSION['user_avatar'] = $userInfo['picture'] ?? null;

header('Location: ../index');
exit;

