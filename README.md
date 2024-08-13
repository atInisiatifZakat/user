# User Packages

Package `inisiatif/user` digunakan di lingkungan Inisiatif Zakat Indonesia untuk proses auth
pada aplikasi yang di buat.

> Dokumentasi ini di peruntukan untuk v2

> v2 di buat ulang dari v1 dan tidak ada jalan migrasi dari v1 ke v2 harus rewrite

## Version map


| Version | PHP      | Laravel    |
|---------|----------|------------|
| 2.x     | 8.1, 8.2 | 9, 10      |
| 3.x     | 8.2, 8.3 | 9, 10, 11  |

## Install

Untuk menginstall paket ini bisa menggunakan `composer` dengan menjalankan perintah berikut
di terminal :

```bash
composer require inisiatif/user
```
Anda bisa juga mempublish dan menjalankan migrasi dengan perintah

```bash
php artisan vendor:publish --tag=user-migrations
php artisan migrate
```

Anda juga bisa mempublish file `config` dengan perintah

```bash
php artisan vendor:publish --tag=user-config
```

Berikut ini adalah isi dari file `config`

```php
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
    ],

    /**
     * Passport
     * -----------------------------------------------------------------------------------------------------------------
     * Untuk keperluan development di local, kita bisa mendisable verifikasi ssl saat menggunakan passport
     */
    'disable_ssl_verify_passport' => env('INISIATIF_PASSPORT_SSL_DISABLE', false),
];
```

## Penggunaan

#### Ubah `user` model auth

Ubah config di `auth.providers.users.model` menjadi `Inisiatif\Package\User\Models\User::class`

#### Rest API

Paket ini mengekspose route api yang bisa di gunakan untuk melakukan 

1. Login dan Logout menggunakan token
2. List dan delete user token
3. Menampilkan profile user
4. Update `pin` user

sebelum menggunakan route api, sebelumnya tambahkan kode berikut pada file route api,
biasanya ada di `routes/api.php`

```php
use Inisiatif\Package\User;

User\Routes::authToken();
User\Routes::userToken();
User\Routes::userProfile();
User\Routes::personalIdentification();
```

1. POST `/auth/token` : untuk login dan membuat token baru, secara default token di buat dengan `expired_at` `null`
2. DELETE `/auth/token` : untuk logout / hapus token yang sedang di gunakan
3. GET `/user-token` : untuk menampilkan list token untuk user yang sedang login
4. DELETE `/user-token/{tokenId}` : untuk mengapus token berdasarkan `tokenId`
5. GET `/user-information` : untuk menampilkan informasi user yang sedang login
6. PUT `/personal-identification-number` : untuk mengupdate `pin` user

### Passport

Mulai versi `3.3` ditambahkan fitur untuk auth menggunakan OAuth2 via `laravel/passport`.

Pertama harus menamhakan configurasi di `config/services.php`

```php
<?php

return [
    // Configurasi yang lain
    
    'passport' => [
        'client_id' => env('INISIATIF_PASSPORT_CLIENT_ID'),
        'callback_url' => env('INISIATIF_PASSPORT_CALLBACK_URL'),
        'base_url' => env('INISIATIF_PASSPORT_BASE_URL', 'http://me.inisiatif.id'),
    ],
]
```

Kedua tambahkan router baru pada `routes/web.php`

```php
use Inisiatif\Package\User;

User\Routes::passport();
```

ini akan menambahkan route baru yaitu `/ouath/passport/redirect` dan `/oauth/passport/callback`.

## Testing
```bash
copmoser test
```

## Fixing code style
```bash
composer format
```

## Static code analyse 
```bash
composer analyse
```
