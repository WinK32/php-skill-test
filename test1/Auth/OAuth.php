<?php

namespace Auth;

class OAuth
{
    private readonly string $clientId;
    private readonly string $clientSecret;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function getAccessToken(string $code): string
    {
        $url = 'https://github.com/login/oauth/access_token';

        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
        ];

        $response = $this->httpPost($url, $params);
        parse_str($response, $result);

        return $result['access_token'];
    }

    public function getUserData(string $token): array
    {
        $url = 'https://api.github.com/user';

        $options = [
            'http' => [
                'header' => "Authorization: token $token"
                    . "\r\n"
                    . "User-Agent: BugTrackerApp",
            ],
        ];

        $context = stream_context_create($options);

        return json_decode(file_get_contents($url, false, $context), true);
    }

    private function httpPost(string $url, array $params): string
    {
        $options = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded",
                'method' => 'POST',
                'content' => http_build_query($params),
            ],
        ];

        $context = stream_context_create($options);

        return file_get_contents($url, false, $context);
    }
}