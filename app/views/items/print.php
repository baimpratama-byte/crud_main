<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris Barang</title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; font-size: 12px; margin: 28px; color: #163747; background: #f5f6f4; }
        h2 { margin: 0 0 6px; color: #163747; }
        .meta { color: #87939a; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e9e8; padding: 8px 10px; text-align: left; }
        th { background: #f0f3f2; color: #163747; font-size: 11px; }
        .r { text-align: right; }
        .noprint { margin-bottom: 18px; }
        .noprint button { background: #397f91; border: 0; border-radius: 6px; color: #fff; cursor: pointer; padding: 9px 13px; }
        .noprint button:hover { background: #2f6d7d; }
        .noprint small { color: #87939a; margin-left: 6px; }
        @media print {
            body { margin: 12mm; }
            .noprint { display: none; }
            th { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
<div class="noprint">
    <button onclick="window.print()">Cetak / Simpan sebagai PDF</button>
    <small>&mdash; pada dialog cetak pilih "Save as PDF".</small>
</div>
<h2>Laporan Inventaris Barang</h2>
<div class="meta">
    Dicetak: <?= date('d/m/Y H:i') ?><?= $search !== '' ? ' &middot; Pencarian: "' . e($search) . '"' : '' ?>
    &middot; Total: <?= count($items) ?> barang
</div>
<table>
    <thead>
    <tr><th>No</th><th>ID</th><th>Nama Barang</th><th>Kategori</th><th class="r">Stok</th><th class="r">Harga</th><th>Tanggal Masuk</th></tr>
    </thead>
    <tbody>
    <?php foreach ($items as $n => $it): ?>
        <tr>
            <td><?= $n + 1 ?></td>
            <td><?= (int) $it['id'] ?></td>
            <td><?= e($it['name']) ?></td>
            <td><?= e($it['category_name']) ?></td>
            <td class="r"><?= (int) $it['stock'] ?></td>
            <td class="r"><?= rupiah($it['price']) ?></td>
            <td><?= tanggal($it['entry_date']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<script>window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 400); });</script>
</body>
</html>
