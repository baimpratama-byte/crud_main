<?php
/**
 * Front controller - semua request melewati file ini.
 * URL: index.php?page=<items|categories|erd>&action=<index|create|store|edit|update|destroy|...>
 */
declare(strict_types=1);

session_start();

require __DIR__ . '/app/helpers.php';
spl_autoload_register(function (string $class): void {
    foreach (['core', 'models', 'controllers'] as $dir) {
        $file = __DIR__ . "/app/$dir/$class.php";
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

// Peta halaman -> controller. Hanya halaman di sini yang bisa diakses.
$routes = [
    'items'      => ItemController::class,
    'categories' => CategoryController::class,
    'erd'        => PageController::class,
];
// Method yang boleh dipanggil lewat URL
$allowed = ['index', 'create', 'store', 'edit', 'update', 'destroy', 'export', 'print', 'erd'];

try {
    $page   = $_GET['page'] ?? 'items';
    $action = $_GET['action'] ?? ($page === 'erd' ? 'erd' : 'index');

    if (!isset($routes[$page]) || !in_array($action, $allowed, true)) {
        throw new HttpException(404, 'Halaman tidak ditemukan.');
    }
    $controller = new $routes[$page]();
    if (!method_exists($controller, $action)) {
        throw new HttpException(404, 'Halaman tidak ditemukan.');
    }
    $controller->$action();
} catch (HttpException $e) {
    http_response_code($e->status);
    $status = $e->status;
    $message = $e->getMessage();
    $pageTitle = 'Terjadi Kesalahan';
    $flash = [];
    ob_start();
    require __DIR__ . '/app/views/error.php';
    $content = ob_get_clean();
    require __DIR__ . '/app/views/layout/main.php';
} catch (Throwable $e) {
    // Error tak terduga (mis. database mati) -> tampilkan pesan ramah, bukan stack trace
    error_log((string) $e);
    http_response_code(500);
    $status = 500;
    $message = $e instanceof PDOException
        ? 'Tidak dapat terhubung ke database atau query gagal. Periksa konfigurasi di config/config.php.'
        : 'Terjadi kesalahan pada server.';
    if (config('app.debug')) {
        $message .= ' [' . get_class($e) . ': ' . $e->getMessage() . ']';
    }
    $pageTitle = 'Terjadi Kesalahan';
    $flash = [];
    ob_start();
    require __DIR__ . '/app/views/error.php';
    $content = ob_get_clean();
    require __DIR__ . '/app/views/layout/main.php';
}
