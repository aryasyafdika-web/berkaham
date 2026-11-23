# Pencatatan Obat Anak — Apotek Berkah

Aplikasi Laravel untuk mengelola kategori, data dan stok obat anak, memantau tanggal kedaluwarsa, serta mencetak atau mengekspor laporan persediaan.

## Persyaratan

- PHP 8.2 atau lebih baru beserta ekstensi Laravel yang diperlukan.
- Composer.
- MySQL 8 atau kompatibel.
- Node.js dan npm.

## Instalasi lokal

1. Buat database MySQL:

   ```sql
   CREATE DATABASE berkah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. Pasang dependensi dan siapkan konfigurasi:

   ```bash
   composer install
   copy .env.example .env
   ```

   Pada PowerShell, gunakan `Copy-Item .env.example .env` sebagai pengganti `copy` bila diperlukan.

3. Edit `.env` dan sesuaikan koneksi database:

   ```dotenv
   APP_NAME="Apotek Berkah"
   APP_URL=http://localhost:8000
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=berkah
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Buat kunci aplikasi, migrasikan database, isi data contoh, dan siapkan frontend:

   ```bash
   php artisan key:generate
   php artisan migrate --seed
   npm install
   npm run build
   ```

   Migrasi membuat sembilan kategori bawaan serta tabel obat, pengaturan, dan riwayat stok. Seeder menambahkan beberapa data obat contoh.

5. Jalankan aplikasi:

   ```bash
   php artisan serve
   ```

   Buka `http://localhost:8000` untuk menggunakan aplikasi.

## Fitur

- Dashboard ringkasan total obat, kategori, stok, stok menipis, obat segera kedaluwarsa dan obat kedaluwarsa, beserta grafik kategori.
- Pengelolaan kategori dan data obat, pencarian, filter, pengurutan tanggal kedaluwarsa, detail, tambah, ubah dan hapus.
- Penyesuaian stok masuk/keluar dengan riwayat perubahan stok; stok tidak dapat dikurangi hingga kurang dari nol.
- Peringatan obat yang kedaluwarsa dalam 30 hari atau sudah kedaluwarsa.
- Laporan data, stok, stok hampir habis, kedaluwarsa dan berdasarkan kategori; laporan dapat difilter.
- Tombol cetak membuka dialog cetak browser. Untuk PDF, pilih **Save as PDF**. Ekspor Excel menggunakan berkas CSV UTF-8 yang dapat dibuka di Excel.
- Pengaturan ambang stok minimum untuk obat baru; batas minimum masing-masing obat dapat dikelola pada data obat.
- Aplikasi dapat digunakan langsung tanpa login.

## Menjalankan pengujian

```bash
php artisan test
```
