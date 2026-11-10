<?php
/** Model Barang (tabel: items) - berelasi N:1 ke categories. */
class Item
{
    private const SELECT = 'SELECT i.*, c.name AS category_name
                            FROM items i
                            JOIN categories c ON c.id = i.category_id';

    /** Bangun klausa WHERE dari pencarian nama dan filter kategori. */
    private static function where(string $search, int $categoryId): array
    {
        $conds = [];
        $params = [];
        if ($search !== '') {
            $conds[] = 'i.name LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }
        if ($categoryId > 0) {
            $conds[] = 'i.category_id = :cat';
            $params[':cat'] = $categoryId;
        }
        return [$conds ? ' WHERE ' . implode(' AND ', $conds) : '', $params];
    }

    public static function count(string $search = '', int $categoryId = 0): int
    {
        [$where, $params] = self::where($search, $categoryId);
        $st = Database::conn()->prepare(
            'SELECT COUNT(*) FROM items i JOIN categories c ON c.id = i.category_id' . $where
        );
        $st->execute($params);
        return (int) $st->fetchColumn();
    }

    /** Daftar barang dengan pagination (null = tanpa batas, untuk export). */
    public static function list(string $search = '', int $categoryId = 0, ?int $page = 1, int $perPage = 10): array
    {
        [$where, $params] = self::where($search, $categoryId);
        $sql = self::SELECT . $where . ' ORDER BY i.id DESC';
        if ($page !== null) {
            $sql .= ' LIMIT :limit OFFSET :offset';
        }
        $st = Database::conn()->prepare($sql);
        foreach ($params as $k => $v) {
            $st->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        if ($page !== null) {
            $st->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $st->bindValue(':offset', max(0, ($page - 1) * $perPage), PDO::PARAM_INT);
        }
        $st->execute();
        return $st->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $st = Database::conn()->prepare(self::SELECT . ' WHERE i.id = :id');
        $st->execute([':id' => $id]);
        return $st->fetch() ?: null;
    }

    public static function create(array $d): int
    {
        $st = Database::conn()->prepare(
            'INSERT INTO items (name, category_id, stock, price, entry_date, photo)
             VALUES (:name, :cat, :stock, :price, :date, :photo)'
        );
        $st->execute(self::bind($d));
        return (int) Database::conn()->lastInsertId();
    }

    public static function update(int $id, array $d): void
    {
        $st = Database::conn()->prepare(
            'UPDATE items SET name = :name, category_id = :cat, stock = :stock,
                              price = :price, entry_date = :date, photo = :photo
             WHERE id = :id'
        );
        $st->execute(self::bind($d) + [':id' => $id]);
    }

    public static function delete(int $id): void
    {
        $st = Database::conn()->prepare('DELETE FROM items WHERE id = :id');
        $st->execute([':id' => $id]);
    }

    private static function bind(array $d): array
    {
        return [
            ':name'  => $d['name'],
            ':cat'   => $d['category_id'],
            ':stock' => $d['stock'],
            ':price' => $d['price'],
            ':date'  => $d['entry_date'],
            ':photo' => $d['photo'] ?: null,
        ];
    }
}
