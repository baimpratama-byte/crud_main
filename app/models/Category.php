<?php
/** Model Kategori (tabel: categories). */
class Category
{
    /** Semua kategori beserta jumlah barang di dalamnya. */
    public static function all(): array
    {
        $sql = 'SELECT c.id, c.name, COUNT(i.id) AS item_count
                FROM categories c
                LEFT JOIN items i ON i.category_id = c.id
                GROUP BY c.id, c.name
                ORDER BY c.name ASC';
        return Database::conn()->query($sql)->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $st = Database::conn()->prepare('SELECT * FROM categories WHERE id = :id');
        $st->execute([':id' => $id]);
        return $st->fetch() ?: null;
    }

    public static function nameExists(string $name, ?int $exceptId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM categories WHERE LOWER(name) = LOWER(:n)';
        $params = [':n' => $name];
        if ($exceptId) {
            $sql .= ' AND id <> :id';
            $params[':id'] = $exceptId;
        }
        $st = Database::conn()->prepare($sql);
        $st->execute($params);
        return (int) $st->fetchColumn() > 0;
    }

    public static function create(string $name): int
    {
        $st = Database::conn()->prepare('INSERT INTO categories (name) VALUES (:n)');
        $st->execute([':n' => $name]);
        return (int) Database::conn()->lastInsertId();
    }

    public static function update(int $id, string $name): void
    {
        $st = Database::conn()->prepare('UPDATE categories SET name = :n WHERE id = :id');
        $st->execute([':n' => $name, ':id' => $id]);
    }

    public static function countItems(int $id): int
    {
        $st = Database::conn()->prepare('SELECT COUNT(*) FROM items WHERE category_id = :id');
        $st->execute([':id' => $id]);
        return (int) $st->fetchColumn();
    }

    public static function delete(int $id): void
    {
        $st = Database::conn()->prepare('DELETE FROM categories WHERE id = :id');
        $st->execute([':id' => $id]);
    }
}
