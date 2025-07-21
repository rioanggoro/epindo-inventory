# Proyek Recruitment - Junior IT Web Developer - PT. Elektroplating Superindo

Aplikasi web untuk mengelola barang masuk, proses produksi, dan penjualan (invoice) dengan fitur manajemen stok real-time. Proyek ini dikembangkan sebagai bagian dari proses rekrutmen untuk posisi Junior IT Web Developer di PT. Elektroplating Superindo.

## Daftar Isi

1.  [Deskripsi Proyek](#deskripsi-proyek)
2.  [Fitur Aplikasi](#fitur-aplikasi)
3.  [Teknologi yang Digunakan](#teknologi-yang-digunakan)
4.  [Persyaratan Sistem](#persyaratan-sistem)
5.  [Langkah-langkah Instalasi](#langkah-langkah-instalasi)
6.  [Penggunaan Aplikasi](#penggunaan-aplikasi)
7.  [Struktur Database](#struktur-database)
8.  [Catatan Penting](#catatan-penting)

---

## 1. Deskripsi Proyek

Sistem ini dirancang untuk memenuhi kebutuhan PT. Elektroplating Superindo dalam mengelola alur barang dari kedatangan bahan baku, proses produksi menjadi produk jadi, hingga pencatatan penjualan produk jadi dalam bentuk invoice. Tujuan utama aplikasi ini adalah menyediakan visibilitas stok dan efisiensi dalam pencatatan transaksi.

## 2. Fitur Aplikasi

Aplikasi ini mengimplementasikan fitur-fitur utama sebagai berikut:

-   **Manajemen Barang Masuk (Incoming)**
    -   CRUD (Create, Read, Update, Delete) data barang masuk bahan baku.
    -   Stok bahan baku otomatis bertambah saat barang masuk disimpan.
    -   Stok bahan baku otomatis disesuaikan saat data barang masuk diubah atau dihapus.
-   **Manajemen Produksi**
    -   CRUD data proses produksi produk jadi.
    -   **Validasi Stok:** Tidak bisa melakukan produksi jika stok bahan baku tidak mencukupi.
    -   Stok bahan baku otomatis berkurang setelah produksi.
    -   Stok produk jadi otomatis bertambah setelah produksi.
    -   Penyesuaian stok bahan baku dan produk jadi saat data produksi diubah atau dihapus.
-   **Manajemen Penjualan (Invoice)**
    -   CRUD data pencatatan invoice penjualan produk jadi.
    -   **Validasi Stok:** Tidak bisa membuat invoice jika stok produk jadi tidak mencukupi.
    -   **Validasi Unik:** Nomor invoice harus unik.
    -   Stok produk jadi otomatis berkurang saat invoice dibuat.
    -   Penyesuaian stok produk jadi saat data invoice diubah atau dihapus.
-   **Cetak Invoice (PDF)**
    -   Fitur untuk mencetak invoice yang sudah tercatat ke dalam format PDF.
-   **Manajemen Stok (Inventaris)**
    -   Tampilan terpisah untuk memantau stok bahan baku dan produk jadi secara real-time.
    -   Fitur CRUD dasar untuk item stok (opsional, untuk inisialisasi/koreksi manual).
-   **Tampilan Antarmuka (UI)**
    -   Menggunakan Tailwind CSS untuk styling yang responsif dan modern.
    -   Halaman Dashboard sebagai ringkasan informasi stok dan akses cepat.

## 3. Teknologi yang Digunakan

-   **Backend:** PHP 8.1+ dengan Laravel Framework 12
-   **Database:** MySQL
-   **Frontend:** HTML, Blade Templates, Tailwind CSS
-   **Asset Bundling:** Vite
-   **Lainnya:**
    -   `barryvdh/laravel-dompdf` untuk generasi PDF.
    -   Composer (PHP Dependency Manager)
    -   NPM / Yarn (JavaScript Package Manager)

## 4. Persyaratan Sistem

Pastikan sistem Anda memenuhi persyaratan berikut untuk menjalankan aplikasi:

-   PHP >= 8.2
-   Composer
-   Node.js (LTS Version) & npm / Yarn
-   Web Server (Apache/Nginx) atau gunakan Laravel Sail / PHP built-in server
-   MySQL / MariaDB Database Server

## 5. Langkah-langkah Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan aplikasi secara lokal:

1.  **Clone Repositori:**

    ```bash
    git clone https://github.com/rioanggoro/epindo-inventory
    cd epindo-inventory
    ```

2.  **Instal Dependensi Composer:**

    ```bash
    composer install
    ```

3.  **Salin File Environment:**

    ```bash
    cp .env.example .env
    ```

4.  **Buat Application Key:**

    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi Database:**
    Buka file `.env` dan sesuaikan pengaturan database Anda:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=epindo_inventory # Ganti dengan nama database Anda
    DB_USERNAME=root             # Ganti dengan username database Anda
    DB_PASSWORD=                 # Ganti dengan password database Anda
    ```

    Pastikan Anda telah membuat database dengan nama yang sama di server MySQL/MariaDB Anda.

6.  **Jalankan Migrasi Database:**
    Ini akan membuat semua tabel yang dibutuhkan (incomings, produksis, invoices, stocks).

    ```bash
    php artisan migrate
    ```

7.  **Instal Dependensi NPM:**

    ```bash
    npm install
    ```

8.  **Jalankan Vite Development Server:**
    Buka terminal baru dan biarkan perintah ini berjalan di latar belakang:

    ```bash
    npm run dev
    ```

    Ini diperlukan agar styling Tailwind CSS dapat dikompilasi dan dimuat oleh browser.

9.  **Jalankan Server Laravel:**
    Buka terminal baru lainnya dan jalankan:
    ```bash
    php artisan serve
    ```

## 6. Penggunaan Aplikasi

Setelah semua langkah instalasi selesai:

1.  Buka browser Anda dan navigasi ke `http://127.0.0.1:8000/`. Anda akan melihat halaman Dashboard.
2.  Gunakan navigasi di bagian atas halaman untuk mengakses modul:
    -   **Incoming/Barang Masuk:** Untuk mencatat kedatangan bahan baku.
    -   **Produksi:** Untuk mencatat proses produksi menjadi produk jadi.
    -   **Invoice:** Untuk mencatat penjualan produk jadi dan mencetak invoice.
    -   **Stok:** Untuk melihat ringkasan stok bahan baku dan produk jadi.

## 7. Struktur Database

Aplikasi ini menggunakan tabel-tabel utama berikut:

-   **`incomings`**: Mencatat setiap kedatangan bahan baku.
    -   `id`, `item_name`, `quantity`, `incoming_date`, `created_at`, `updated_at`
-   **`produksis`**: Mencatat setiap proses produksi.
    -   `id`, `product_name`, `quantity_produced`, `raw_material_used`, `raw_material_item_name`, `production_date`, `created_at`, `updated_at`
-   **`invoices`**: Mencatat setiap invoice penjualan.
    -   `id`, `invoice_number` (unik), `customer_name`, `product_sold_name`, `quantity_sold`, `total_amount`, `invoice_date`, `created_at`, `updated_at`
-   **`stocks`**: Menyimpan ringkasan stok terkini dari setiap item.
    -   `id`, `item_name` (unik), `type` (enum: `raw_material`, `finished_product`), `current_stock`, `created_at`, `updated_at`

## 8. Catatan Penting

-   Validasi stok bahan baku (untuk produksi) dan produk jadi (untuk penjualan) diimplementasikan di sisi backend.
-   Transaksi database digunakan untuk memastikan konsistensi data stok.
-   Nomor invoice dijamin unik melalui validasi database.
