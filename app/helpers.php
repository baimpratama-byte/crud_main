<?php
/** Fungsi bantu global untuk view dan controller. */

function config(string $key, $default = null)
{
    static $cfg = null;
    $cfg ??= require __DIR__ . '/../config/config.php';
    $value = $cfg;
    foreach (explode('.', $key) as $part) {
        if (!is_array($value) || !array_key_exists($part, $value)) {
            return $default;
        }
        $value = $value[$part];
    }
    return $value;
}

/** Escape output HTML. */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Bangun URL: url('items', 'edit', ['id' => 3]). */
function url(string $page = 'items', string $action = 'index', array $params = []): string
{
    $query = array_merge(['page' => $page, 'action' => $action], $params);
    return 'index.php?' . http_build_query($query);
}

function rupiah($value): string
{
    return 'Rp ' . number_format((float) $value, 0, ',', '.');
}

function tanggal(?string $date): string
{
    if (!$date) {
        return '-';
    }
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d ? $d->format('d/m/Y') : e($date);
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

/** Ambil nilai lama form (setelah validasi gagal) atau nilai dari data. */
function old(array $data, string $key, $default = '')
{
    return $data[$key] ?? $default;
}
