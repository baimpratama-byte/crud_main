<?php
/**
 * Konfigurasi aplikasi.
 *
 * DB_DRIVER:
 *   - "mysql"  : untuk XAMPP/Laragon (import database/inventaris.sql terlebih dahulu)
 *   - "sqlite" : tanpa instalasi apa pun, database dibuat otomatis saat pertama dijalankan
 *
 * Nilai dapat diubah langsung di bawah ini atau lewat environment variable.
 */
return [
    'app' => [
        'name'       => 'Inventaris Gudang',
        'per_page'   => 10,                 // jumlah baris per halaman (pagination)
        'upload_max' => 2 * 1024 * 1024,    // batas ukuran foto: 2 MB
        'debug'      => false,              // true = tampilkan detail error
    ],
    'db' => [
        'driver'      => getenv('DB_DRIVER') ?: 'mysql',
        'host'        => getenv('DB_HOST') ?: '127.0.0.1',
        'port'        => getenv('DB_PORT') ?: 3306,
        'name'        => getenv('DB_NAME') ?: 'inventaris',
        'user'        => getenv('DB_USER') ?: 'root',
        'pass'        => getenv('DB_PASS') ?: '',
        'sqlite_path' => __DIR__ . '/../database/inventaris.sqlite',
    ],
];
