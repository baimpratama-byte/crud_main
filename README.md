# Sistem Manajemen Inventaris Barang

Aplikasi web CRUD inventaris gudang — PHP 8 native (pola **MVC**), PDO, Bootstrap 5.

## Cara Menjalankan

### Opsi A — XAMPP / Laragon (MySQL)
1. Salin folder `inventaris` ke `C:\xampp\htdocs\`.
2. Jalankan **Apache** dan **MySQL** di XAMPP.
3. Buka `http://localhost/phpmyadmin` → **Import** → pilih `database/inventaris.sql`.
4. Buka `http://localhost/inventaris/`.

Jika user/password MySQL berbeda, ubah `config/config.php` (bagian `db`).

### Opsi B — Tanpa MySQL (SQLite, paling cepat)
Butuh PHP 8+ dengan ekstensi `pdo_sqlite`. Di terminal, dalam folder `inventaris`:

```bash
# Linux / macOS
DB_DRIVER=sqlite php -S localhost:8000

# Windows (PowerShell)
$env:DB_DRIVER="sqlite"; php -S localhost:8000
```
Buka `http://localhost:8000/`. Database `database/inventaris.sqlite` dibuat otomatis beserta data contoh.

> Bootstrap dan ikon dimuat dari CDN, jadi perlu koneksi internet saat membuka aplikasi.

## Fitur (sesuai soal)
| Fitur | Keterangan |
|---|---|
| CRUD Barang | Tambah, daftar, edit (form terisi otomatis), hapus (dialog konfirmasi) |
| CRUD Kategori | Halaman terpisah; kategori yang masih dipakai barang tidak bisa dihapus |
| Relasi | `categories` 1 — N `items` (FK `ON DELETE RESTRICT`); lihat menu **Relasi Data (ERD)** |
| Dropdown kategori | Saat tambah/edit barang |
| Pencarian + filter | Cari nama barang, filter kategori, **debounce 400 ms** |
| Validasi | Nama wajib, stok & harga angka ≥ 0, format tanggal; pesan error jelas (server + browser) |
| DatePicker | `input type="date"` |
| Foto barang | Upload atau kamera (`capture`), pratinjau, validasi tipe & ukuran |
| Error handling | Halaman 404/405/419/500 ramah, loading spinner pada tombol submit |
| Bonus | Pagination, Export **Excel (.xlsx)** dan **PDF** (halaman cetak → Save as PDF), notifikasi sukses |
| Keamanan | Prepared statement (anti SQL injection), escape output (anti XSS), token CSRF |

## Struktur Folder
```
inventaris/
├── index.php                  Front controller / router
├── config/config.php          Konfigurasi database & aplikasi
├── app/
│   ├── core/                  Database, Controller dasar, Flash, XlsxWriter
│   ├── models/                Item, Category        (M)
│   ├── views/                 Tampilan Bootstrap    (V)
│   └── controllers/           Item, Category, Page  (C)
├── assets/                    CSS & JS
├── uploads/                   Foto barang
└── database/
    ├── inventaris.sql         SQL dump MySQL (dikumpulkan ke guru)
    └── schema_sqlite.sql      Skema untuk mode SQLite
```

## ERD
```
categories (1) ──────< (N) items
 id PK                     id PK
 name UNIQUE               name
                           category_id FK → categories.id
                           stock, price, entry_date, photo
```
