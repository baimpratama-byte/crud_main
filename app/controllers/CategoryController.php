<?php
/** Controller Kategori: CRUD kategori barang. */
class CategoryController extends Controller
{
    public function index(): void
    {
        $this->view('categories/index', ['categories' => Category::all()], 'Kategori Barang');
    }

    public function create(): void
    {
        $this->form('Tambah Kategori', [], [], null);
    }

    public function store(): void
    {
        $this->requirePost();
        $name = trim($_POST['name'] ?? '');
        $errors = $this->validate($name, null);
        if ($errors) {
            $this->form('Tambah Kategori', $_POST, $errors, null);
            return;
        }
        Category::create($name);
        Flash::set('success', 'Kategori "' . $name . '" berhasil ditambahkan.');
        $this->redirect('categories');
    }

    public function edit(): void
    {
        $cat = $this->findOrFail();
        $this->form('Edit Kategori', $cat, [], $cat);
    }

    public function update(): void
    {
        $this->requirePost();
        $cat = $this->findOrFail();
        $name = trim($_POST['name'] ?? '');
        $errors = $this->validate($name, (int) $cat['id']);
        if ($errors) {
            $this->form('Edit Kategori', $_POST, $errors, $cat);
            return;
        }
        Category::update((int) $cat['id'], $name);
        Flash::set('success', 'Kategori berhasil diperbarui menjadi "' . $name . '".');
        $this->redirect('categories');
    }

    public function destroy(): void
    {
        $this->requirePost();
        $cat = $this->findOrFail();
        $used = Category::countItems((int) $cat['id']);
        if ($used > 0) {
            // Relasi: kategori yang masih dipakai barang tidak boleh dihapus
            Flash::set('danger', 'Kategori "' . $cat['name'] . '" tidak dapat dihapus karena masih dipakai oleh '
                . $used . ' barang. Pindahkan atau hapus barangnya terlebih dahulu.');
            $this->redirect('categories');
        }
        Category::delete((int) $cat['id']);
        Flash::set('success', 'Kategori "' . $cat['name'] . '" berhasil dihapus.');
        $this->redirect('categories');
    }

    private function form(string $title, array $values, array $errors, ?array $cat): void
    {
        $this->view('categories/form', ['values' => $values, 'errors' => $errors, 'cat' => $cat], $title);
    }

    private function findOrFail(): array
    {
        $cat = Category::find($this->intParam('id'));
        if (!$cat) {
            throw new HttpException(404, 'Kategori tidak ditemukan.');
        }
        return $cat;
    }

    private function validate(string $name, ?int $exceptId): array
    {
        $errors = [];
        if ($name === '') {
            $errors['name'] = 'Nama kategori tidak boleh kosong.';
        } elseif (mb_strlen($name) > 50) {
            $errors['name'] = 'Nama kategori maksimal 50 karakter.';
        } elseif (Category::nameExists($name, $exceptId)) {
            $errors['name'] = 'Kategori dengan nama tersebut sudah ada.';
        }
        return $errors;
    }
}
