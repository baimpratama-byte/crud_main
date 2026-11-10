<?php
/**
 * Koneksi database (PDO) - mendukung MySQL dan SQLite.
 * Satu koneksi dipakai bersama (singleton sederhana).
 */
class Database
{
    private static ?PDO $pdo = null;

    public static function conn(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $db = config('db');
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        if ($db['driver'] === 'sqlite') {
            $isNew = !file_exists($db['sqlite_path']);
            $pdo = new PDO('sqlite:' . $db['sqlite_path'], null, null, $options);
            $pdo->exec('PRAGMA foreign_keys = ON');
            if ($isNew) {
                // Buat tabel + data contoh otomatis pada eksekusi pertama
                $pdo->exec(file_get_contents(__DIR__ . '/../../database/schema_sqlite.sql'));
            }
        } else {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                $db['host'], $db['port'], $db['name']
            );
            $options[PDO::ATTR_EMULATE_PREPARES] = false;
            $pdo = new PDO($dsn, $db['user'], $db['pass'], $options);
        }

        return self::$pdo = $pdo;
    }
}
