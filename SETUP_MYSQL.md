# Setup MySQL dengan Laragon untuk PortoConnect

## Langkah-langkah Setup

### 1. Pastikan Laragon Berjalan
- Buka Laragon
- Pastikan MySQL service sudah running (hijau)
- Pastikan Apache/Nginx sudah running

### 2. Buat Database di Adminer

1. Buka Adminer di browser: `http://localhost/adminer` atau klik "Adminer" di Laragon
2. Login dengan:
   - **System**: MySQL
   - **Server**: localhost atau 127.0.0.1
   - **Username**: root
   - **Password**: (kosongkan jika default Laragon)
   - **Database**: (kosongkan dulu)

3. Setelah login, buat database baru:
   - Klik "Create database"
   - Nama database: `portoconnect` (atau nama lain sesuai keinginan)
   - Pilih collation: `utf8mb4_unicode_ci`
   - Klik "Save"

### 3. Konfigurasi File .env

1. Di folder `backend`, copy file `.env.example` menjadi `.env`:
   ```bash
   cd backend
   copy .env.example .env
   ```
   Atau manual: rename `.env.example` menjadi `.env`

2. Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=portoconnect
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   
   **Catatan**: 
   - Jika password MySQL di Laragon tidak kosong, isi di `DB_PASSWORD`
   - Sesuaikan `DB_DATABASE` dengan nama database yang dibuat di Adminer

3. Generate application key:
   ```bash
   php artisan key:generate
   ```

### 4. Jalankan Migration

Jalankan migration untuk membuat semua tabel:
```bash
php artisan migrate
```

Jika ada error, coba:
```bash
php artisan migrate:fresh
```

**Perhatian**: `migrate:fresh` akan menghapus semua tabel yang ada dan membuat ulang!

### 5. Verifikasi Database

1. Buka Adminer lagi
2. Pilih database `portoconnect`
3. Pastikan semua tabel sudah dibuat:
   - users
   - mahasiswas
   - perusahaans
   - admins
   - portofolios
   - projects
   - skills
   - certificates
   - experiences
   - search_histories
   - activity_logs
   - dan tabel lainnya

### 6. Buat Storage Link (untuk file upload)

```bash
php artisan storage:link
```

## Troubleshooting

### Error: "Access denied for user 'root'@'localhost'"
- Pastikan password MySQL di `.env` sesuai dengan setting Laragon
- Cek di Laragon > Menu > Database > MySQL > Root Password

### Error: "Unknown database 'portoconnect'"
- Pastikan database sudah dibuat di Adminer
- Cek nama database di `.env` sesuai dengan yang dibuat

### Error: "SQLSTATE[HY000] [2002] No connection could be made"
- Pastikan MySQL service di Laragon sudah running
- Cek `DB_HOST` di `.env` (gunakan `127.0.0.1` atau `localhost`)

### Error saat migration: "Table already exists"
- Hapus semua tabel di database melalui Adminer, atau
- Gunakan `php artisan migrate:fresh` (hati-hati, akan hapus semua data!)

## Konfigurasi Default Laragon

Laragon biasanya menggunakan:
- **Host**: 127.0.0.1 atau localhost
- **Port**: 3306
- **Username**: root
- **Password**: (kosong/default)

Jika password sudah diubah, sesuaikan di file `.env`.

