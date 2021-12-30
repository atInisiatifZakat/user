<?php

return [
    'table_names' => [
        'users' => env('USER_TABLE_NAME_USERS', 'users'),

        'employees' => env('USER_TABLE_NAME_EMPLOYEES', 'employees'),

        'volunteers' => env('USER_TABLE_NAME_VOLUNTEERS', 'volunteers'),

        'personal_access_tokens' => env('USER_TABLE_NAME_PERSONAL_ACCESS_TOKENS', 'personal_access_tokens')
    ],
];
