<?php
return [
    'credentials' => [
        'key_file' => env('FIREBASE_CREDENTIALS'),
    ],
    'database' => [
        'url' => env('FIREBASE_DATABASE_URL'),
    ],
];
