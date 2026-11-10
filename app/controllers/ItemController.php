<?php
/** Controller Barang: CRUD, pencarian, filter, pagination, upload foto, export. */
class ItemController extends Controller
{
    private const ALLOWED_MIME = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    // ---------- READ ----------
    public function index(): void
    {
        $search  = trim($_GET['q'] ?? '');
        $cat     = $this->intParam('category');
        $perPage = (int) config('app.per_page', 10);
        $total   = Item::count($search, $cat);
        $pages   = max(1, (int) ceil($total / $perPage));
        $page    = min(max(1, $this->intParam('p', 1)), $pages);

        $this->view('items/index', [
            'items'      => Item::list($search, $cat, $page, $perPage),
            'categories' => Category::all(),
            'search'     => $search,
            'cat'        => $cat,
            'total'      => $total,
            'page'       => $page,
            'pages'      => $pages,
            'perPage'    => $perPage,
        ], 'Daftar Barang');
    }

    // ---------- CREATE ----------
    public function create(): void
    {
        $this->form('Tambah Barang', ['entry_date' => date('Y-m-d')], [], null);
    }

    public function store(): void
    {
        $this->requirePost();
        [$data, $errors] = $this->validate($_POST, $_FILES['photo'] ?? null, null);

        if ($errors) {
            $this->form('Tambah Barang', $_POST, $errors, null);
            return;
        }
        Item::create($data);
        Flash::set('success', 'Barang "' . $data['name'] . '" berhasil ditambahkan.');
        $this->redirect('items');
    }

    // ---------- UPDATE ----------
    public function edit(): void
    {
        $item = $this->findOrFail();
        $this->form('Edit Barang', $item, [], $item);
    }

    public function update(): void
    {
        $this->requirePost();
        $item = $this->findOrFail();
        [$data, $errors] = $this->validate($_POST, $_FILES['photo'] ?? null, $item);

        if ($errors) {
            $this->form('Edit Barang', $_POST + ['photo' => $item['photo']], $errors, $item);
            return;
        }
        Item::update((int) $item['id'], $data);
        if ($item['photo'] && $item['photo'] !== $data['photo']) {
            $this->deletePhoto($item['photo']);
        }
        Flash::set('success', 'Barang "' . $data['name'] . '" berhasil diperbarui.');
        $this->redirect('items');
    }

    // ---------- DELETE ----------
    public function destroy(): void
    {
        $this->requirePost();
        $item = $this->findOrFail();
        Item::delete((int) $item['id']);
        $this->deletePhoto($item['photo']);
        Flash::set('success', 'Barang "' . $item['name'] . '" berhasil dihapus.');
        $this->redirect('items');
    }

    // ---------- EXPORT (bonus) ----------
    /** Export ke file Excel (.xlsx) sesuai pencarian/filter yang sedang aktif. */
    public function export(): void
    {
        $rows = Item::list(trim($_GET['q'] ?? ''), $this->intParam('category'), null);
        $header = ['No', 'ID', 'Nama Barang', 'Kategori', 'Stok', 'Harga', 'Tanggal Masuk'];
        $data = [];
        foreach ($rows as $n => $r) {
            $data[] = [$n + 1, (int) $r['id'], $r['name'], $r['category_name'],
                       (int) $r['stock'], (float) $r['price'], $r['entry_date']];
        }

        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        XlsxWriter::write($tmp, 'Inventaris', $header, $data);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="inventaris-' . date('Ymd-His') . '.xlsx"');
        header('Content-Length: ' . filesize($tmp));
        readfile($tmp);
        unlink($tmp);
        exit;
    }

    /** Halaman cetak - gunakan "Save as PDF" pada dialog cetak browser. */
    public function print(): void
    {
        $this->bare('items/print', [
            'items'  => Item::list(trim($_GET['q'] ?? ''), $this->intParam('category'), null),
            'search' => trim($_GET['q'] ?? ''),
        ]);
    }

    // ---------- Helper ----------
    private function form(string $title, array $values, array $errors, ?array $item): void
    {
        $this->view('items/form', [
            'values'     => $values,
            'errors'     => $errors,
            'item'       => $item,
            'categories' => Category::all(),
        ], $title);
    }

    private function findOrFail(): array
    {
        $item = Item::find($this->intParam('id'));
        if (!$item) {
            throw new HttpException(404, 'Barang tidak ditemukan.');
        }
        return $item;
    }

    /** Validasi input form. Mengembalikan [data bersih, daftar error]. */
    private function validate(array $in, ?array $file, ?array $existing): array
    {
        $errors = [];
        $name   = trim($in['name'] ?? '');
        $catId  = (int) ($in['category_id'] ?? 0);
        $stock  = trim((string) ($in['stock'] ?? ''));
        $price  = trim((string) ($in['price'] ?? ''));
        $date   = trim($in['entry_date'] ?? '');

        if ($name === '') {
            $errors['name'] = 'Nama barang tidak boleh kosong.';
        } elseif (mb_strlen($name) > 100) {
            $errors['name'] = 'Nama barang maksimal 100 karakter.';
        }

        if ($catId <= 0) {
            $errors['category_id'] = 'Kategori harus dipilih.';
        } elseif (!Category::find($catId)) {
            $errors['category_id'] = 'Kategori yang dipilih tidak valid.';
        }

        if ($stock === '') {
            $errors['stock'] = 'Jumlah stok tidak boleh kosong.';
        } elseif (!is_numeric($stock)) {
            $errors['stock'] = 'Jumlah stok harus berupa angka.';
        } elseif ((float) $stock < 0) {
            $errors['stock'] = 'Jumlah stok tidak boleh negatif.';
        } elseif (!preg_match('/^\d{1,9}$/', $stock)) {
            $errors['stock'] = 'Jumlah stok harus berupa bilangan bulat (maksimal 9 digit).';
        }

        if ($price === '') {
            $errors['price'] = 'Harga barang tidak boleh kosong.';
        } elseif (!is_numeric($price)) {
            $errors['price'] = 'Harga barang harus berupa angka.';
        } elseif ((float) $price < 0) {
            $errors['price'] = 'Harga barang tidak boleh negatif.';
        } elseif ((float) $price > 9999999999999) {
            $errors['price'] = 'Harga barang terlalu besar.';
        }

        $dt = DateTime::createFromFormat('Y-m-d', $date);
        if ($date === '') {
            $errors['entry_date'] = 'Tanggal masuk tidak boleh kosong.';
        } elseif (!$dt || $dt->format('Y-m-d') !== $date) {
            $errors['entry_date'] = 'Format tanggal tidak valid (gunakan YYYY-MM-DD).';
        }

        // Foto (opsional)
        $photo = $existing['photo'] ?? null;
        if (!empty($in['remove_photo'])) {
            $photo = null;
        }
        if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $newPhoto = $this->storePhoto($file, $errors);
            if ($newPhoto) {
                $photo = $newPhoto;
            }
        }

        return [[
            'name'        => $name,
            'category_id' => $catId,
            'stock'       => (int) $stock,
            'price'       => $price === '' ? 0 : (float) $price,
            'entry_date'  => $date,
            'photo'       => $photo,
        ], $errors];
    }

    /** Validasi & simpan foto. Mengembalikan nama file atau null jika gagal. */
    private function storePhoto(array $file, array &$errors): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['photo'] = in_array($file['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)
                ? 'Ukuran foto terlalu besar.'
                : 'Upload foto gagal. Silakan coba lagi.';
            return null;
        }
        if ($file['size'] > (int) config('app.upload_max')) {
            $errors['photo'] = 'Ukuran foto maksimal ' . (config('app.upload_max') / 1048576) . ' MB.';
            return null;
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!isset(self::ALLOWED_MIME[$mime])) {
            $errors['photo'] = 'Format foto harus JPG, PNG, WEBP, atau GIF.';
            return null;
        }
        $dir = __DIR__ . '/../../uploads';
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            $errors['photo'] = 'Folder upload tidak dapat dibuat.';
            return null;
        }
        $filename = bin2hex(random_bytes(8)) . '.' . self::ALLOWED_MIME[$mime];
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $filename)) {
            $errors['photo'] = 'Foto tidak dapat disimpan. Periksa izin folder uploads.';
            return null;
        }
        return $filename;
    }

    private function deletePhoto(?string $filename): void
    {
        if ($filename) {
            $path = __DIR__ . '/../../uploads/' . basename($filename);
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }
}
