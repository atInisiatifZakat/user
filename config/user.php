<?php

return [
    'table_names' => [
        'users' => env('USER_TABLE_NAME_USERS', 'users'),

        'auth_tokens' => env('USER_TABLE_NAME_AUTH_TOKENS', 'auth_tokens'),

        'auth_token_blacklists' => env('USER_TABLE_NAME_AUTH_TOKEN_BLACKLISTS', 'auth_token_blacklists'),
    ],
];
