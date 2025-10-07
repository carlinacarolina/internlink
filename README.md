# InternLink

InternLink membantu sekolah mengelola program magang industri siswa dan supervisor. Proyek ini dibangun dengan Laravel 12 dan Vite tanpa ketergantungan pada Docker.

## Persyaratan
- PHP 8.2 dengan ekstensi `pgsql`, `intl`, `bcmath`, `mbstring`, `openssl`, `pcntl`
- Composer 2.6+
- PostgreSQL 14+ (menyediakan ekstensi `citext`)
- Node.js 20+ dan npm 10+
- Redis (opsional, antrian bawaan memakai database)

## Langkah Instalasi
1. **Clone repositori dan masuk ke folder proyek**
   ```bash
   git clone <repository-url>
   cd internlink
   ```

2. **Salin dan perbarui konfigurasi lingkungan**
   ```bash
   cp .env.example .env
   ```
   Buat basis data PostgreSQL lalu sesuaikan nilai `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` pada `.env`. Pastikan ekstensi PostgreSQL `citext` tersedia (`CREATE EXTENSION IF NOT EXISTS citext;`).

3. **Instal dependensi PHP dan JavaScript**
   ```bash
   composer install
   npm install
   ```

4. **Generate kunci aplikasi dan migrasikan basis data**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

5. **Jalankan lingkungan pengembangan**
   Jalankan semua proses pengembangan secara paralel:
   ```bash
   composer run dev
   ```
   Perintah di atas menjalankan server Laravel (`php artisan serve`), queue listener, log tailer, dan Vite. Jika ingin menyalakan proses secara terpisah, gunakan:
   ```bash
   php artisan serve
   php artisan queue:listen --tries=1
   php artisan pail --timeout=0
   npm run dev
   ```

6. **Akses aplikasi**
   Buka `http://127.0.0.1:8000` di browser. Vite berjalan pada port `5173`.

## Perintah Umum
- `php artisan migrate:fresh --seed` – reset skema dan seed ulang data contoh.
- `php artisan test` – menjalankan test suite backend.
- `npm run build` – build aset produksi.
- `php artisan queue:work` – jalankan worker antrian permanen (gunakan supervisor di produksi).

## Catatan Produksi
- Gunakan server web (Nginx/Apache) dengan PHP-FPM 8.2.
- Konfigurasikan tugas terjadwal dengan `php artisan schedule:run` setiap menit.
- Jalankan queue worker terpisah dan monitor log aplikasi.
- Perbarui `APP_ENV`, `APP_DEBUG`, dan kredensial rahasia secara tepat di `.env`.

## Kontribusi
Silakan buat pull request dengan deskripsi rinci. Ikuti panduan keamanan di `agents/security.md` saat memodifikasi autentikasi atau middleware.
