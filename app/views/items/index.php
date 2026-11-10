<?php
$qs = array_filter(['q' => $search, 'category' => $cat ?: null], fn($v) => $v !== null && $v !== '');
$from = $total ? (($page - 1) * $perPage + 1) : 0;
$to   = min($total, $page * $perPage);
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h3 class="mb-0"><i class="bi bi-box-seam me-2"></i>Daftar Barang</h3>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-download me-1"></i>Export
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?= url('items', 'export', $qs) ?>">
                    <i class="bi bi-file-earmark-excel text-success me-2"></i>Excel (.xlsx)</a></li>
                <li><a class="dropdown-item" href="<?= url('items', 'print', $qs) ?>" target="_blank">
                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i>PDF / Cetak</a></li>
            </ul>
        </div>
        <a href="<?= url('items', 'create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Barang
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <!-- Pencarian & filter kategori (di atas tabel) -->
        <form method="get" action="index.php" class="row g-2 mb-3" id="filterForm">
            <input type="hidden" name="page" value="items">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="search" name="q" id="searchInput" value="<?= e($search) ?>" class="form-control"
                           placeholder="Cari nama barang..." autocomplete="off">
                </div>
            </div>
            <div class="col-8 col-md-4">
                <select name="category" id="categoryFilter" class="form-select" aria-label="Filter kategori">
                    <option value="">Semua kategori</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int) $c['id'] ?>" <?= $cat === (int) $c['id'] ? 'selected' : '' ?>>
                            <?= e($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-4 col-md-2 d-flex gap-1">
                <button class="btn btn-outline-primary flex-fill" type="submit">Cari</button>
                <?php if ($qs): ?>
                    <a href="<?= url('items') ?>" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-x-lg"></i></a>
                <?php endif; ?>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th class="text-end">Stok</th>
                    <th class="text-end">Harga</th>
                    <th>Tanggal Masuk</th>
                    <th class="text-end">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!$items): ?>
                    <tr><td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <?= $qs ? 'Tidak ada barang yang cocok dengan pencarian.' : 'Belum ada data barang.' ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td><?= (int) $it['id'] ?></td>
                        <td>
                            <?php if ($it['photo']): ?>
                                <a href="uploads/<?= e($it['photo']) ?>" target="_blank">
                                    <img src="uploads/<?= e($it['photo']) ?>" alt="Foto <?= e($it['name']) ?>" class="thumb">
                                </a>
                            <?php else: ?>
                                <div class="thumb thumb-empty"><i class="bi bi-image"></i></div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-medium"><?= e($it['name']) ?></td>
                        <td><span class="badge text-bg-secondary"><?= e($it['category_name']) ?></span></td>
                        <td class="text-end">
                            <?= (int) $it['stock'] ?>
                            <?php if ((int) $it['stock'] === 0): ?>
                                <span class="badge text-bg-danger ms-1">Habis</span>
                            <?php elseif ((int) $it['stock'] <= 5): ?>
                                <span class="badge text-bg-warning ms-1">Menipis</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end text-nowrap"><?= rupiah($it['price']) ?></td>
                        <td class="text-nowrap"><?= tanggal($it['entry_date']) ?></td>
                        <td class="text-end text-nowrap">
                            <a href="<?= url('items', 'edit', ['id' => $it['id']]) ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i><span class="d-none d-lg-inline ms-1">Edit</span>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger btn-delete"
                                    data-action="<?= e(url('items', 'destroy', ['id' => $it['id']])) ?>"
                                    data-name="<?= e($it['name']) ?>">
                                <i class="bi bi-trash"></i><span class="d-none d-lg-inline ms-1">Delete</span>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
            <small class="text-muted">Menampilkan <?= $from ?>&ndash;<?= $to ?> dari <?= $total ?> barang</small>
            <?php if ($pages > 1): ?>
                <nav aria-label="Navigasi halaman">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('items', 'index', $qs + ['p' => $page - 1]) ?>">&laquo;</a>
                        </li>
                        <?php for ($p = 1; $p <= $pages; $p++): ?>
                            <?php if ($p === 1 || $p === $pages || abs($p - $page) <= 2): ?>
                                <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= url('items', 'index', $qs + ['p' => $p]) ?>"><?= $p ?></a>
                                </li>
                            <?php elseif (abs($p - $page) === 3): ?>
                                <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('items', 'index', $qs + ['p' => $page + 1]) ?>">&raquo;</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>
