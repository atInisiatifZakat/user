<?php

declare(strict_types=1);

return [
    /**
     * Migrations
     * -----------------------------------------------------------------------------------------------------------------
     * Konfigurasi apakah paket file migration pada paket ini ikut di jalankan saat user menjalankan perintah
     * `php artisan migrate` tanpa harus mempublish file migration-nya
     */
    'migration' => env('INISIATIF_USER_RUNNING_MIGRATION', false),

    /**
     * Table Names
     * -----------------------------------------------------------------------------------------------------------------
     * Konfigurasi untuk nama tabel masing - masih model. Untuk postgres dengan beda schema bisa menggunakan dot, contah
     * `public.users` dan seterusnya
     */
    'table_names' => [
        'users' => env('INISIATIF_USER_TABLE_NAME_USERS', 'users'),

        'branches' => env('INISIATIF_USER_TABLE_NAME_BRANCHES', 'branches'),

        'employees' => env('INISIATIF_USER_TABLE_NAME_EMPLOYEES', 'employees'),

        'volunteers' => env('INISIATIF_USER_TABLE_NAME_VOLUNTEERS', 'volunteers'),

        'personal_access_tokens' => env('INISIATIF_USER_TABLE_NAME_PERSONAL_ACCESS_TOKENS', 'personal_access_tokens'),
    ],

    /**
     * Models
     * -----------------------------------------------------------------------------------------------------------------
     * Ubah value ini untuk mengganti model yang digunakan
     */
    'models' => [
        'user' => Inisiatif\Package\User\Models\User::class,

        'branch' => Inisiatif\Package\User\Models\Branch::class,

        'employee' => Inisiatif\Package\User\Models\Employee::class,

        'volunteer' => Inisiatif\Package\User\Models\Volunteer::class,
    ],

    /**
     * Add hashing password using `md5` before call `attempt` in guard
     */
    'hashing_password_before_attempt' => true,


    /**
     * Pin
     * -----------------------------------------------------------------------------------------------------------------
     * Menentukan berapa kali boleh salah memasukkan PIN 
     * dan waktu tunggu setelah mencapai batas maksimal memasukan pin yang salah.
     */

    'pin' => [
        'max_attempts' => env('INISIATIF_USER_PIN_MAX_ATTEMPTS', 3),
        'decay_minutes' => env('INISIATIF_USER_PIN_DECAY_MINUTES', 30),
    ]
];
