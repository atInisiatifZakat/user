<?php

return [
    'table_names' => [
        'users' => env('USER_TABLE_NAME_USERS', 'users'),

        'personal_access_tokens' => env('USER_TABLE_NAME_PERSONAL_ACCESS_TOKENS', 'personal_access_tokens')
    ],
];
