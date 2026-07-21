# KeuanganApp — Aplikasi Manajemen Keuangan Pribadi

Aplikasi berbasis **CodeIgniter 3** + **Bootstrap 5** untuk mencatat pemasukan/pengeluaran,
memantau cash flow bulanan, dan mengekspor laporan bulanan ke **Excel** dan **PDF**.

## Fitur
- 🔐 Login (session based)
- 📊 Dashboard: saldo total, ringkasan bulan berjalan, grafik cash flow tahunan, breakdown pengeluaran per kategori
- 💰 CRUD Transaksi (pemasukan & pengeluaran) dengan filter (bulan, tahun, tipe, kategori, kata kunci) + pagination
- 🏷️ CRUD Kategori (custom icon & warna, dipisah pemasukan/pengeluaran)
- 📅 Laporan Bulanan + **Export Excel (.xls)** dan **Export PDF** (menggunakan TCPDF, sudah termasuk di `application/third_party/tcpdf`)

## Kebutuhan Server
- PHP 7.2 – 8.1 (CodeIgniter 3.1.13)
- MySQL / MariaDB
- Ekstensi PHP: mysqli, mbstring, gd (untuk TCPDF)
- Apache dengan `mod_rewrite` (sudah disiapkan `.htaccess`)

## Instalasi

1. **Import database**
   ```bash
   mysql -u root -p < database/keuangan_app.sql
   ```
   Database `keuangan_app` beserta 1 user default akan otomatis dibuat.

2. **Buat hash password untuk user admin**
   Password di file SQL adalah placeholder. Generate hash asli lalu update:
   ```bash
   php -r "echo password_hash('admin123', PASSWORD_DEFAULT), PHP_EOL;"
   ```
   ```sql
   UPDATE users SET password = '<hasil_hash_di_atas>' WHERE username = 'admin';
   ```

3. **Konfigurasi koneksi database**
   Edit `application/config/database.php`:
   ```php
   'hostname' => 'localhost',
   'username' => 'root',
   'password' => 'password_anda',
   'database' => 'keuangan_app',
   ```

4. **Konfigurasi base URL**
   Edit `application/config/config.php`:
   ```php
   $config['base_url'] = 'http://localhost/keuangan-app/';
   ```

5. **Ganti encryption key** (wajib untuk keamanan session)
   Edit `application/config/config.php`:
   ```php
   $config['encryption_key'] = 'string-acak-minimal-32-karakter';
   ```

6. Pastikan folder berikut punya izin tulis (`chmod 755` atau `775`):
   - `application/cache/`
   - `application/cache/sessions/`
   - `application/logs/`

7. Buka di browser: `http://localhost/keuangan-app/`
   Login default: **admin / admin123** (setelah mengganti hash di langkah 2)

## Struktur Folder Penting
```
application/
├── config/          # konfigurasi (database, routes, autoload)
├── controllers/      # Auth, Dashboard, Kategori, Transaksi, Laporan
├── models/            # User_model, Kategori_model, Transaksi_model
├── views/             # semua tampilan (Bootstrap 5)
├── third_party/tcpdf/ # library PDF (untuk export laporan)
├── helpers/app_helper.php # helper format Rupiah, tanggal, dll
database/keuangan_app.sql  # skema database + data awal
assets/css/style.css        # styling admin theme
```

## Catatan
- Export Excel menggunakan trik HTML table dengan header `application/vnd.ms-excel`
  agar file `.xls` bisa langsung dibuka oleh Microsoft Excel tanpa perlu library tambahan.
- Export PDF menggunakan library **TCPDF** yang sudah disertakan di dalam proyek,
  tidak perlu instalasi Composer.
- Setiap user memiliki kategori & transaksi masing-masing (kolom `user_id`), jadi aplikasi
  ini sudah siap dikembangkan menjadi multi-user.
