<?php
// Google OAuth Configuration
// Get your credentials from: https://console.cloud.google.com/apis/credentials

define('GOOGLE_CLIENT_ID', 'YOUR_CLIENT_ID');
define('GOOGLE_CLIENT_SECRET', 'YOUR_CLIENT_SECRET');
define('GOOGLE_REDIRECT_URI', 'https://your-domain.com/auth/callback.php');

// OAuth URLs
define('GOOGLE_AUTH_URL', 'https://accounts.google.com/o/oauth2/v2/auth');
define('GOOGLE_TOKEN_URL', 'https://oauth2.googleapis.com/token');
define('GOOGLE_USERINFO_URL', 'https://www.googleapis.com/oauth2/v2/userinfo');

