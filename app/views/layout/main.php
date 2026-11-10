<?php
$current = $_GET['page'] ?? 'items';
$nav = [
    'items'      => ['Barang', 'bi-box-seam'],
    'categories' => ['Kategori', 'bi-tags'],
    'erd'        => ['Relasi Data (ERD)', 'bi-diagram-3'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?: 'Beranda') ?> | <?= e(config('app.name')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="<?= url('items') ?>">
            <i class="bi bi-boxes me-1"></i><?= e(config('app.name')) ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"
                aria-controls="nav" aria-expanded="false" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <?php foreach ($nav as $key => [$label, $icon]): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $current === $key ? 'active fw-semibold' : '' ?>"
                           href="<?= url($key, $key === 'erd' ? 'erd' : 'index') ?>">
                            <i class="bi <?= $icon ?> me-1"></i><?= e($label) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 flex-grow-1">
    <?php foreach ($flash as $f): ?>
        <div class="alert alert-<?= e($f['type']) ?> alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi <?= $f['type'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> me-2"></i>
            <?= e($f['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endforeach; ?>

    <?= $content ?>
</main>

<footer class="text-center text-muted small py-3 border-top bg-white">
    Sistem Manajemen Inventaris Barang &middot; Soal Praktek CRUD Web Programming
</footer>

<!-- Modal konfirmasi hapus (dipakai oleh semua tombol Hapus) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" class="modal-content" id="deleteForm" data-loading>
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-1"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                Yakin ingin menghapus <strong id="deleteName"></strong>?
                <div class="text-muted small mt-1">Tindakan ini tidak dapat dibatalkan.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <span class="spinner-border spinner-border-sm d-none me-1" aria-hidden="true"></span>Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
