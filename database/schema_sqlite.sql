-- Skema + data contoh untuk mode SQLite (dibuat otomatis saat pertama dijalankan)
PRAGMA foreign_keys = ON;

CREATE TABLE categories (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  name       VARCHAR(50) NOT NULL UNIQUE,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE items (
  id          INTEGER PRIMARY KEY AUTOINCREMENT,
  name        VARCHAR(100) NOT NULL,
  category_id INTEGER NOT NULL,
  stock       INTEGER NOT NULL DEFAULT 0 CHECK (stock >= 0),
  price       NUMERIC NOT NULL DEFAULT 0 CHECK (price >= 0),
  entry_date  DATE NOT NULL,
  photo       VARCHAR(255) NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE RESTRICT ON UPDATE CASCADE
);
CREATE INDEX idx_items_name ON items (name);
CREATE INDEX idx_items_category ON items (category_id);

INSERT INTO categories (id, name) VALUES
  (1, 'Elektronik'), (2, 'Furnitur'), (3, 'Alat Tulis Kantor'), (4, 'Peralatan Gudang');

INSERT INTO items (name, category_id, stock, price, entry_date) VALUES
  ('Laptop', 1, 10, 7000000, '2024-10-14'),
  ('Monitor LED 24 inci', 1, 8, 1850000, '2024-10-15'),
  ('Printer Inkjet', 1, 4, 1250000, '2024-10-18'),
  ('Keyboard Wireless', 1, 25, 225000, '2024-10-20'),
  ('Meja Kerja', 2, 12, 950000, '2024-10-21'),
  ('Kursi Kantor', 2, 20, 675000, '2024-10-22'),
  ('Lemari Arsip', 2, 3, 1800000, '2024-10-25'),
  ('Kertas A4 (rim)', 3, 150, 58000, '2024-11-01'),
  ('Pulpen (lusin)', 3, 60, 24000, '2024-11-02'),
  ('Stapler', 3, 0, 35000, '2024-11-03'),
  ('Troli Barang', 4, 5, 850000, '2024-11-05'),
  ('Rak Besi Bertingkat', 4, 7, 1100000, '2024-11-06'),
  ('Forklift Manual', 4, 2, 4500000, '2024-11-08');
