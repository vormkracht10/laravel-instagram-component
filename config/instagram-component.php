<?php

return [
    'account' => [
        'username' => env('INSTAGRAM_COMPONENT_ACCOUNT_USERNAME'),
        'password' => env('INSTAGRAM_COMPONENT_ACCOUNT_PASSWORD'),
    ],

    'imap' => [
        'host' => env('INSTAGRAM_COMPONENT_IMAP_HOST'),
        'username' => env('INSTAGRAM_COMPONENT_IMAP_USERNAME'),
        'password' => env('INSTAGRAM_COMPONENT_IMAP_PASSWORD'),
    ],

    'client' => [
        'user_agent' => env('INSTAGRAM_COMPONENT_USER_AGENT', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5.1 Mobile/15E148 Safari/604.1'),
        'language' => env('INSTAGRAM_COMPONENT_LANGUAGE', 'en-US'),
    ],

    'proxy' => [
        'url' => env('INSTAGRAM_COMPONENT_PROXY_URL'),
    ],
];
