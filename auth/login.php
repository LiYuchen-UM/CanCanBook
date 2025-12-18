<?php
session_start();
require_once '../config/google.php';

// Build Google OAuth URL
$params = [
    'client_id' => GOOGLE_CLIENT_ID,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'email profile',
    'access_type' => 'online',
    'prompt' => 'select_account'
];

$authUrl = GOOGLE_AUTH_URL . '?' . http_build_query($params);
header('Location: ' . $authUrl);
exit;

