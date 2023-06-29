<?php

return [
    'migration' => env('INISIATIF_USER_RUNNING_MIGRATION', false),

    'table_names' => [
        'users' => env('INISIATIF_USER_TABLE_NAME_USERS', 'users'),

        'branches' => env('INISIATIF_USER_TABLE_NAME_BRANCHES', 'branches'),

        'employees' => env('INISIATIF_USER_TABLE_NAME_EMPLOYEES', 'employees'),

        'volunteers' => env('INISIATIF_USER_TABLE_NAME_VOLUNTEERS', 'volunteers'),

        'personal_access_tokens' => env('INISIATIF_USER_TABLE_NAME_PERSONAL_ACCESS_TOKENS', 'personal_access_tokens')
    ],

    'models' => [
        'user' => Inisiatif\Package\User\Models\User::class,

        'branch' => Inisiatif\Package\User\Models\Branch::class,

        'employee' => Inisiatif\Package\User\Models\Employee::class,

        'volunteer' => Inisiatif\Package\User\Models\Volunteer::class,
    ]
];
