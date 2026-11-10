<?php
/** Controller dasar: render view, redirect, flash message, proteksi CSRF. */
abstract class Controller
{
    protected function view(string $view, array $data = [], string $title = ''): void
    {
        extract($data);
        $flash = Flash::pull();
        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();
        $pageTitle = $title;
        require __DIR__ . '/../views/layout/main.php';
    }

    /** Render tanpa layout (untuk halaman cetak). */
    protected function bare(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }

    protected function redirect(string $page, string $action = 'index', array $params = []): void
    {
        header('Location: ' . url($page, $action, $params));
        exit;
    }

    protected function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new HttpException(405, 'Metode tidak diizinkan.');
        }
        $token = $_POST['_token'] ?? '';
        if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
            throw new HttpException(419, 'Sesi formulir kedaluwarsa. Silakan muat ulang halaman dan coba lagi.');
        }
    }

    protected function intParam(string $key, int $default = 0): int
    {
        return isset($_REQUEST[$key]) ? (int) $_REQUEST[$key] : $default;
    }
}
