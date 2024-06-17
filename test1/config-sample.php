<?php
// copy this file into config.php
// and make a proper setup with your parameters

// Database configuration
const DB_FILE = __DIR__ . '/database.sqlite';

const APP_URL_PREFIX = '/test1';

// GitHub OAuth configuration
const GITHUB_CLIENT_ID = 'INSERT CLIENT ID';
const GITHUB_CLIENT_SECRET = 'INSERT CLIENT SECRET';
const GITHUB_REDIRECT_URI = 'INSERT CLIENT REDIRECT URI'; // Example: http://php-test.test/test1/callback.php

session_start();

error_reporting(0);
