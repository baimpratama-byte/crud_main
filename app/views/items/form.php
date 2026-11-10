<?php
$isEdit = $item !== null;
$action = $isEdit ? url('items', 'update', ['id' => $item['id']]) : url('items', 'store');
$field = function (string $key) use ($errors): string {
    return isset($errors[$key]) ? ' is-invalid' : '';
};
$err = function (string $key) use ($errors): string {
    return isset($errors[$key]) ? '<div class="invalid-feedback d-block">' . e($errors[$key]) . '</div>' : '';
};
$currentPhoto = $isEdit ? ($item['photo'] ?? null) : null;
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <h3 class="mb-3">
            <i class="bi <?= $isEdit ? 'bi-pencil-square' : 'bi-plus-circle' ?> me-2"></i>
            <?= $isEdit ? 'Edit Barang' : 'Tambah Barang' ?>
        </h3>

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <strong><i class="bi bi-exclamation-triangle-fill me-1"></i>Data belum valid.</strong>
                Periksa kembali isian yang ditandai merah.
            </div>
        <?php endif; ?>

        <form method="post" action="<?= e($action) ?>" enctype="multipart/form-data"
              class="card shadow-sm border-0" data-loading data-validate>
            <div class="card-body p-4">
                <?= csrf_field() ?>

                <?php if ($isEdit): ?>
                    <div class="mb-3">
                        <label class="form-label">ID Barang</label>
                        <input type="text" class="form-control" value="<?= (int) $item['id'] ?>" disabled>
                        <div class="form-text">ID dibuat otomatis (auto-increment).</div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" maxlength="100" required
                           class="form-control<?= $field('name') ?>" value="<?= e(old($values, 'name')) ?>"
                           placeholder="Contoh: Laptop Lenovo ThinkPad">
                    <?= $err('name') ?>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori Barang <span class="text-danger">*</span></label>
                    <select id="category_id" name="category_id" required class="form-select<?= $field('category_id') ?>">
                        <option value="">-- Pilih kategori --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= (int) $c['id'] ?>"
                                <?= (int) old($values, 'category_id', 0) === (int) $c['id'] ? 'selected' : '' ?>>
                                <?= e($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?= $err('category_id') ?>
                    <?php if (!$categories): ?>
                        <div class="form-text text-danger">
                            Belum ada kategori. <a href="<?= url('categories', 'create') ?>">Tambah kategori dulu</a>.
                        </div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                        <input type="number" id="stock" name="stock" min="0" step="1" required inputmode="numeric"
                               class="form-control<?= $field('stock') ?>" value="<?= e(old($values, 'stock')) ?>">
                        <?= $err('stock') ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Harga Barang (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="price" name="price" min="0" step="any" required inputmode="decimal"
                               class="form-control<?= $field('price') ?>" value="<?= e(old($values, 'price')) ?>">
                        <?= $err('price') ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="entry_date" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" id="entry_date" name="entry_date" required
                           class="form-control<?= $field('entry_date') ?>"
                           value="<?= e(old($values, 'entry_date')) ?>">
                    <?= $err('entry_date') ?>
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">Foto Barang <span class="text-muted">(opsional)</span></label>
                    <input type="file" id="photo" name="photo" accept="image/*" capture="environment"
                           class="form-control<?= $field('photo') ?>">
                    <?= $err('photo') ?>
                    <div class="form-text">Pilih dari galeri atau ambil langsung dengan kamera. JPG/PNG/WEBP/GIF, maks 2 MB.</div>
                    <div class="mt-2 d-flex align-items-center gap-3">
                        <img id="photoPreview" class="thumb thumb-lg <?= $currentPhoto ? '' : 'd-none' ?>"
                             src="<?= $currentPhoto ? 'uploads/' . e($currentPhoto) : '' ?>" alt="Pratinjau foto">
                        <?php if ($currentPhoto): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="remove_photo">
                                <label class="form-check-label" for="remove_photo">Hapus foto saat ini</label>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white border-0 px-4 pb-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <span class="spinner-border spinner-border-sm d-none me-1" aria-hidden="true"></span>
                    <i class="bi bi-save me-1"></i><?= $isEdit ? 'Simpan Perubahan' : 'Simpan' ?>
                </button>
                <a href="<?= url('items') ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
