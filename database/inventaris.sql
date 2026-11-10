    -- ============================================================
-- SQL Dump: Sistem Manajemen Inventaris Barang (MySQL / MariaDB)
-- Cara import:  phpMyAdmin > Import > pilih file ini
--          atau: mysql -u root < inventaris.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS `inventaris`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `inventaris`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `items`;
DROP TABLE IF EXISTS `categories`;
SET FOREIGN_KEY_CHECKS = 1;

-- Tabel Kategori (sisi "1" pada relasi 1:N)
CREATE TABLE `categories` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(50)  NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Barang (sisi "N" pada relasi 1:N)
CREATE TABLE `items` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100)  NOT NULL,
  `category_id` INT UNSIGNED  NOT NULL,
  `stock`       INT UNSIGNED  NOT NULL DEFAULT 0,
  `price`       DECIMAL(15,2) UNSIGNED NOT NULL DEFAULT 0,
  `entry_date`  DATE          NOT NULL,
  `photo`       VARCHAR(255)  NULL DEFAULT NULL,
  `created_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_items_name` (`name`),
  KEY `idx_items_category` (`category_id`),
  CONSTRAINT `fk_items_category` FOREIGN KEY (`category_id`)
    REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data contoh
INSERT INTO `categories` (`id`, `name`) VALUES
  (1, 'Elektronik'),
  (2, 'Furnitur'),
  (3, 'Alat Tulis Kantor'),
  (4, 'Peralatan Gudang');

INSERT INTO `items` (`name`, `category_id`, `stock`, `price`, `entry_date`) VALUES
  ('Laptop',                 1, 10,  7000000, '2024-10-14'),
  ('Monitor LED 24 inci',    1,  8,  1850000, '2024-10-15'),
  ('Printer Inkjet',         1,  4,  1250000, '2024-10-18'),
  ('Keyboard Wireless',      1, 25,   225000, '2024-10-20'),
  ('Meja Kerja',             2, 12,   950000, '2024-10-21'),
  ('Kursi Kantor',           2, 20,   675000, '2024-10-22'),
  ('Lemari Arsip',           2,  3,  1800000, '2024-10-25'),
  ('Kertas A4 (rim)',        3, 150,   58000, '2024-11-01'),
  ('Pulpen (lusin)',         3, 60,    24000, '2024-11-02'),
  ('Stapler',                3,  0,    35000, '2024-11-03'),
  ('Troli Barang',           4,  5,   850000, '2024-11-05'),
  ('Rak Besi Bertingkat',    4,  7,  1100000, '2024-11-06'),
  ('Forklift Manual',        4,  2,  4500000, '2024-11-08');
