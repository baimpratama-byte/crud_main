<h3 class="mb-3"><i class="bi bi-diagram-3 me-2"></i>Relasi Data (Entity Relationship Diagram)</h3>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <p class="mb-3">
            Satu <strong>Kategori</strong> dapat memiliki banyak <strong>Barang</strong>,
            dan setiap Barang wajib termasuk ke tepat satu Kategori
            (relasi <strong>One-to-Many / 1:N</strong>).
        </p>

        <div class="row g-4 align-items-center justify-content-center text-center">
            <div class="col-md-4">
                <div class="erd-box">
                    <div class="erd-title bg-primary text-white">categories</div>
                    <ul class="list-unstyled mb-0 text-start px-3 py-2 small">
                        <li><i class="bi bi-key-fill text-warning"></i> <strong>id</strong> <span class="text-muted">INT, PK, auto-increment</span></li>
                        <li>name <span class="text-muted">VARCHAR(50), unik</span></li>
                        <li>created_at <span class="text-muted">TIMESTAMP</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="fw-semibold">1 &nbsp;&mdash;&mdash;&mdash;&lt;&nbsp; N</div>
                <small class="text-muted">satu kategori memiliki banyak barang<br>(FK <code>items.category_id</code>)</small>
            </div>
            <div class="col-md-5">
                <div class="erd-box">
                    <div class="erd-title bg-success text-white">items</div>
                    <ul class="list-unstyled mb-0 text-start px-3 py-2 small">
                        <li><i class="bi bi-key-fill text-warning"></i> <strong>id</strong> <span class="text-muted">INT, PK, auto-increment</span></li>
                        <li>name <span class="text-muted">VARCHAR(100)</span></li>
                        <li><i class="bi bi-link-45deg text-primary"></i> <strong>category_id</strong> <span class="text-muted">INT, FK &rarr; categories.id</span></li>
                        <li>stock <span class="text-muted">INT &ge; 0</span></li>
                        <li>price <span class="text-muted">DECIMAL(15,2) &ge; 0</span></li>
                        <li>entry_date <span class="text-muted">DATE</span></li>
                        <li>photo <span class="text-muted">VARCHAR(255), opsional</span></li>
                        <li>created_at <span class="text-muted">TIMESTAMP</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <h6 class="fw-semibold">Aturan integritas relasi</h6>
        <ul class="mb-0">
            <li>Barang <strong>tidak dapat dibuat</strong> tanpa memilih kategori yang sudah ada (dropdown).</li>
            <li>Kategori yang <strong>masih dipakai</strong> oleh barang tidak dapat dihapus
                (<code>ON DELETE RESTRICT</code>) &mdash; aplikasi menampilkan pesan yang jelas.</li>
            <li>Nama kategori bersifat unik agar tidak ada duplikasi.</li>
            <li>Mengubah ID kategori akan ikut memperbarui barang terkait (<code>ON UPDATE CASCADE</code>).</li>
        </ul>
    </div>
</div>
