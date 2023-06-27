<?php

return [
    'table_names' => [
        'users' => env('USER_TABLE_NAME_USERS', 'users'),

        'employees' => env('USER_TABLE_NAME_EMPLOYEES', 'employees'),

        'volunteers' => env('USER_TABLE_NAME_VOLUNTEERS', 'volunteers'),

        'personal_access_tokens' => env('USER_TABLE_NAME_PERSONAL_ACCESS_TOKENS', 'personal_access_tokens')
    ],

    'models' => [
        'user' => Inisiatif\Package\User\Models\User::class,

        'branch' => Inisiatif\Package\User\Models\Branch::class,

        'employee' => Inisiatif\Package\User\Models\Employee::class,

        'volunteer' => Inisiatif\Package\User\Models\Volunteer::class,
    ]
];
