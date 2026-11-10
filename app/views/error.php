<div class="text-center py-5">
    <div class="display-1 text-primary fw-bold"><?= (int) $status ?></div>
    <h4 class="mb-3">Terjadi Kesalahan</h4>
    <p class="text-muted"><?= e($message) ?></p>
    <a href="<?= url('items') ?>" class="btn btn-primary"><i class="bi bi-house me-1"></i>Kembali ke Beranda</a>
</div>
