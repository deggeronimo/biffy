<?php

return [
    'clientId' => env('gauth_clientId'),
    'clientSecret' => env('gauth_clientSecret'),
    'redirectUri' => env('gauth_redirectUri'),
    'accessType' => 'offline',
    'approvalPrompt' => 'auto',
    'scopes' => [
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile',
    ]
];