<?php
$isEdit = $cat !== null;
$action = $isEdit ? url('categories', 'update', ['id' => $cat['id']]) : url('categories', 'store');
?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <h3 class="mb-3">
            <i class="bi <?= $isEdit ? 'bi-pencil-square' : 'bi-plus-circle' ?> me-2"></i>
            <?= $isEdit ? 'Edit Kategori' : 'Tambah Kategori' ?>
        </h3>
        <form method="post" action="<?= e($action) ?>" class="card shadow-sm border-0" data-loading data-validate>
            <div class="card-body p-4">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" maxlength="50" required
                           class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>"
                           value="<?= e(old($values, 'name')) ?>" placeholder="Contoh: Elektronik">
                    <?php if (isset($errors['name'])): ?>
                        <div class="invalid-feedback d-block"><?= e($errors['name']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer bg-white border-0 px-4 pb-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <span class="spinner-border spinner-border-sm d-none me-1" aria-hidden="true"></span>
                    <i class="bi bi-save me-1"></i>Simpan
                </button>
                <a href="<?= url('categories') ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
