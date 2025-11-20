# Setup Google OAuth untuk PortoConnect

## Error yang Terjadi
Error: "Missing required parameter: client_id" terjadi karena Google OAuth belum dikonfigurasi dengan benar.

## Langkah Setup Google OAuth

### 1. Buat Google OAuth Credentials

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru atau pilih project yang sudah ada
3. Aktifkan **Google+ API**:
   - Pergi ke "APIs & Services" > "Library"
   - Cari "Google+ API" dan klik "Enable"
4. Buat OAuth 2.0 Credentials:
   - Pergi ke "APIs & Services" > "Credentials"
   - Klik "Create Credentials" > "OAuth client ID"
   - Pilih "Web application"
   - Isi:
     - **Name**: PortoConnect
     - **Authorized JavaScript origins**: 
       - `http://localhost:8000`
       - `http://localhost:5173` (jika frontend di port berbeda)
     - **Authorized redirect URIs**:
       - `http://localhost:8000/auth/google/callback`
   - Klik "Create"
   - **Copy Client ID dan Client Secret**

### 2. Update File .env

Tambahkan ke file `backend/.env`:

```env
GOOGLE_CLIENT_ID=your-client-id-here
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
FRONTEND_URL=http://localhost:5173
```

### 3. Clear Config Cache

Jalankan di terminal:
```bash
cd backend
php artisan config:clear
php artisan cache:clear
```

### 4. Test Google Login

1. Buka aplikasi di browser
2. Klik "Login dengan Google"
3. Pilih akun Google
4. Setelah berhasil, akan redirect ke dashboard sesuai role

## Catatan Penting

- Pastikan **Authorized redirect URIs** di Google Console sesuai dengan `GOOGLE_REDIRECT_URI` di `.env`
- Jika menggunakan domain production, tambahkan juga di Google Console
- Client Secret jangan di-share atau di-commit ke git

## Troubleshooting

### Error: "redirect_uri_mismatch"
- Pastikan redirect URI di Google Console sama persis dengan yang di `.env`
- Pastikan tidak ada trailing slash atau perbedaan http/https

### Error: "invalid_client"
- Pastikan Client ID dan Client Secret sudah benar
- Clear config cache: `php artisan config:clear`

### Error: "access_denied"
- User membatalkan proses login
- Atau aplikasi belum di-approve oleh Google (untuk production)

