-- =========================================================
-- Database: keuangan_app
-- Aplikasi Manajemen Keuangan Pribadi (CodeIgniter 3)
-- =========================================================

CREATE DATABASE IF NOT EXISTS `keuangan_app` DEFAULT CHARACTER SET utf8mb4;
USE `keuangan_app`;

-- ---------------------------------------------------------
-- Table: users
-- ---------------------------------------------------------
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `saldo_awal` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default user: username = admin, password = admin123
INSERT INTO `users` (`name`, `username`, `email`, `password`, `saldo_awal`, `created_at`) VALUES
('Administrator', 'admin', 'admin@example.com', '$2y$10$8K1p/a0dURXAmMoyHCseF.gwHlKq9DGnpJTqZBaFYVeShN1M2FEXe', 0, NOW());
-- NOTE: hash di atas contoh placeholder. Ganti password lewat halaman "Lupa reset"
-- atau jalankan: php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
-- lalu update kolom password user admin secara manual di database.

-- ---------------------------------------------------------
-- Table: categories (kategori pemasukan / pengeluaran)
-- ---------------------------------------------------------
CREATE TABLE `categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `type` ENUM('income','expense') NOT NULL,
  `icon` VARCHAR(50) DEFAULT 'fa-wallet',
  `color` VARCHAR(20) DEFAULT '#0d6efd',
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_categories_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Kategori bawaan untuk user admin (id 1)
INSERT INTO `categories` (`user_id`,`name`,`type`,`icon`,`color`,`created_at`) VALUES
(1,'Gaji','income','fa-money-bill-wave','#198754',NOW()),
(1,'Bonus/THR','income','fa-gift','#20c997',NOW()),
(1,'Pendapatan Lain','income','fa-hand-holding-usd','#0dcaf0',NOW()),
(1,'Makan & Minum','expense','fa-utensils','#dc3545',NOW()),
(1,'Transportasi','expense','fa-car','#fd7e14',NOW()),
(1,'Tagihan & Utilitas','expense','fa-file-invoice-dollar','#ffc107',NOW()),
(1,'Belanja','expense','fa-shopping-cart','#6f42c1',NOW()),
(1,'Kesehatan','expense','fa-briefcase-medical','#d63384',NOW()),
(1,'Hiburan','expense','fa-film','#6610f2',NOW()),
(1,'Cicilan/Utang','expense','fa-credit-card','#212529',NOW()),
(1,'Tabungan/Investasi','expense','fa-piggy-bank','#0d6efd',NOW()),
(1,'Lainnya','expense','fa-ellipsis-h','#6c757d',NOW());

-- ---------------------------------------------------------
-- Table: transactions (pemasukan & pengeluaran)
-- ---------------------------------------------------------
CREATE TABLE `transactions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  `type` ENUM('income','expense') NOT NULL,
  `amount` DECIMAL(15,2) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `trx_date` DATE NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  KEY `trx_date` (`trx_date`),
  CONSTRAINT `fk_transactions_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_transactions_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Table: budgets (opsional: batas anggaran per kategori per bulan)
-- ---------------------------------------------------------
CREATE TABLE `budgets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  `month` TINYINT NOT NULL,
  `year` SMALLINT NOT NULL,
  `amount_limit` DECIMAL(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_budget` (`user_id`,`category_id`,`month`,`year`),
  CONSTRAINT `fk_budgets_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_budgets_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
