<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h3 class="mb-0"><i class="bi bi-tags me-2"></i>Kategori Barang</h3>
    <a href="<?= url('categories', 'create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th style="width:80px">ID</th>
                <th>Nama Kategori</th>
                <th class="text-center">Jumlah Barang</th>
                <th class="text-end">Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$categories): ?>
                <tr><td colspan="4" class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>Belum ada kategori.
                </td></tr>
            <?php endif; ?>
            <?php foreach ($categories as $c): ?>
                <tr>
                    <td><?= (int) $c['id'] ?></td>
                    <td class="fw-medium"><?= e($c['name']) ?></td>
                    <td class="text-center">
                        <a href="<?= url('items', 'index', ['category' => $c['id']]) ?>" class="badge text-bg-secondary text-decoration-none">
                            <?= (int) $c['item_count'] ?> barang
                        </a>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="<?= url('categories', 'edit', ['id' => $c['id']]) ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-square"></i><span class="d-none d-lg-inline ms-1">Edit</span>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger btn-delete"
                                data-action="<?= e(url('categories', 'destroy', ['id' => $c['id']])) ?>"
                                data-name="kategori &quot;<?= e($c['name']) ?>&quot;">
                            <i class="bi bi-trash"></i><span class="d-none d-lg-inline ms-1">Hapus</span>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
