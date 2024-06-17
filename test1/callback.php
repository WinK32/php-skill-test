<?php

use Auth\User;
use Auth\OAuth;

session_start();
require_once 'config.php';
require_once 'Database/Database.php';
require_once 'Auth/User.php';
require_once 'Auth/OAuth.php';

if (isset($_GET['code'])) {
    $oauth = new OAuth(GITHUB_CLIENT_ID, GITHUB_CLIENT_SECRET);

    $accessToken = $oauth->getAccessToken($_GET['code']);
    $userData = $oauth->getUserData($accessToken);

    $user = new User();
    $authenticatedUser = $user->findOrCreateUser($userData);

    $_SESSION['user'] = $authenticatedUser;
    header('Location: dashboard.php');
    exit;
} else {
    // Handle error
    echo "GitHub OAuth failed.";
}
