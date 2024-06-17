<?php

require 'config.php';

$githubOAuthUrl = 'https://github.com/login/oauth/authorize?' . http_build_query([
'client_id' => GITHUB_CLIENT_ID,
'redirect_uri' => GITHUB_REDIRECT_URI,
'scope' => 'user',
'state' => bin2hex(random_bytes(16))
]);

header('Location: ' . $githubOAuthUrl);
exit;
