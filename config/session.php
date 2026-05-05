<?php
use Illuminate\Support\Str;

return [

    /*
     | Driver: file — simpan session di storage/framework/sessions/
     */
    'driver'          => env('SESSION_DRIVER', 'file'),

    /*
     | Lifetime: 240 menit (4 jam) — cukup panjang agar tidak expired
     */
    'lifetime'        => env('SESSION_LIFETIME', 240),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    'encrypt'         => env('SESSION_ENCRYPT', false),

    'files'           => storage_path('framework/sessions'),

    'connection'      => env('SESSION_CONNECTION'),

    'table'           => env('SESSION_TABLE', 'sessions'),

    'store'           => env('SESSION_STORE'),

    'lottery'         => [2, 100],

    'cookie'          => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'
    ),

    'path'            => env('SESSION_PATH', '/'),

    'domain'          => env('SESSION_DOMAIN'),

    /*
     | PENTING: false untuk development localhost (bukan HTTPS)
     */
    'secure'          => env('SESSION_SECURE_COOKIE', false),

    'http_only'       => env('SESSION_HTTP_ONLY', true),

    /*
     | lax = mengizinkan redirect antar halaman yang sama domain
     */
    'same_site'       => env('SESSION_SAME_SITE', 'lax'),

    'partitioned'     => false,
];