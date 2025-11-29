# 📦 Storage Management System

Sistem manajemen gudang berbasis web yang dibangun dengan Laravel 11, dilengkapi dengan fitur peminjaman barang, barcode generator, role-based access control, dan notifikasi real-time.

---

## 🎯 Fitur Utama

### 1. **Manajemen Laporan Barang**
- ✅ CRUD laporan barang masuk/keluar
- ✅ Export data ke CSV/Excel
- ✅ Statistik barang per satuan
- ✅ Riwayat aktivitas lengkap

### 2. **Sistem Peminjaman Multi-Level**
- ✅ Workflow approval bertingkat (User → Petugas → Manajer → Gudang)
- ✅ Upload surat izin peminjaman (PDF/Word/Gambar)
- ✅ Tracking status real-time
- ✅ Notifikasi otomatis ke setiap role

### 3. **Barcode Generator**
- ✅ Generate QR Code & Barcode 1D
- ✅ Per-item barcode dengan quantity
- ✅ Print-ready layout (grid 2 kolom)
- ✅ Scan barcode untuk info barang

### 4. **Role-Based Access Control**
- ✅ 5 Role: Admin, Petugas Pengajuan, Manajer Persetujuan, Petugas Barang Keluar, User
- ✅ Role tersimpan di database (bukan hardcoded)
- ✅ Permission management per role

### 5. **Keamanan**
- ✅ Two-Factor Authentication (2FA) dengan Google Authenticator
- ✅ Email verification
- ✅ Session management

### 6. **UI/UX Modern**
- ✅ Dark mode support
- ✅ Responsive design (mobile-friendly)
- ✅ Animated toast notifications
- ✅ Custom confirmation modals
- ✅ Sidebar collapsible

---

## 🛠️ Teknologi yang Digunakan

| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| **PHP** | 8.2+ | Backend language |
| **Laravel** | 11.x | PHP Framework |
| **MySQL/SQLite** | - | Database |
| **Node.js** | 18+ | Frontend build tool |
| **NPM** | 9+ | Package manager |
| **Tailwind CSS** | 3.x | CSS Framework |
| **Vite** | 5.x | Frontend bundler |

### Package Laravel yang Digunakan:
- `milon/barcode` - Generate barcode & QR code
- `pragmarx/google2fa` - Two-factor authentication
- `maatwebsite/excel` - Export Excel/CSV

---

## 📋 Prasyarat Instalasi

Sebelum memulai, pastikan komputer Anda sudah terinstall:

### 1. **PHP 8.2 atau lebih tinggi**
   - Download: https://www.php.net/downloads
   - Untuk Windows: gunakan XAMPP atau Laragon
   - Cek versi: `php -v`

### 2. **Composer**
   - Download: https://getcomposer.org/download/
   - Cek versi: `composer -V`

### 3. **Node.js & NPM**
   - Download: https://nodejs.org/ (pilih versi LTS)
   - Cek versi: `node -v` dan `npm -v`

### 4. **Database** (pilih salah satu)
   - **SQLite** (default, tidak perlu install)
   - **MySQL** (download XAMPP/Laragon atau MySQL standalone)

### 5. **Git** (opsional, untuk clone repository)
   - Download: https://git-scm.com/downloads

---

## 🚀 Cara Instalasi (Step-by-Step untuk Pemula)

### **Step 1: Clone atau Download Project**

**Opsi A: Menggunakan Git**
```bash
git clone <repository-url>
cd webshabib
```

**Opsi B: Download ZIP**
1. Download project sebagai ZIP
2. Extract ke folder yang diinginkan (misal: `D:\webshabib`)
3. Buka terminal/command prompt di folder tersebut

---

### **Step 2: Install Dependencies PHP**

Jalankan perintah berikut untuk menginstall semua package Laravel:

```bash
composer install
```

> ⏱️ Proses ini membutuhkan waktu 2-5 menit tergantung koneksi internet.

---

### **Step 3: Install Dependencies JavaScript**

Jalankan perintah berikut untuk menginstall package frontend:

```bash
npm install
```

> ⏱️ Proses ini membutuhkan waktu 3-10 menit.

---

### **Step 4: Setup Environment File**

1. **Copy file `.env.example` menjadi `.env`:**
   ```bash
   cp .env.example .env
   ```
   
   Atau di Windows (jika `cp` tidak dikenali):
   ```bash
   copy .env.example .env
   ```

2. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

---

### **Step 5: Konfigurasi Database**

#### **Opsi A: Menggunakan SQLite (Default, Mudah)**

1. Buka file `.env`
2. Pastikan konfigurasi database seperti ini:
   ```env
   DB_CONNECTION=sqlite
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=laravel
   # DB_USERNAME=root
   # DB_PASSWORD=
   ```

3. Buat file database SQLite:
   ```bash
   touch database/database.sqlite
   ```
   
   Atau di Windows:
   ```bash
   type nul > database/database.sqlite
   ```

#### **Opsi B: Menggunakan MySQL (Lihat Tutorial di Bawah)**

---

### **Step 6: Migrasi Database**

Jalankan perintah untuk membuat tabel-tabel database:

```bash
php artisan migrate
```

> Jika muncul pertanyaan "Would you like to create it?", ketik `yes`

---

### **Step 7: Seed Data Awal**

Jalankan seeder untuk membuat data awal (user, role, dll):

```bash
php artisan db:seed
```

**Default User yang Dibuat:**
| Email | Password | Role |
|-------|----------|------|
| `admin@storage.com` | `password` | Main Admin |
| `admin1@storage.com` | `password` | Petugas Pengajuan |
| `admin2@storage.com` | `password` | Manajer Persetujuan |
| `admin3@storage.com` | `password` | Petugas Barang Keluar |
| `user@storage.com` | `password` | User |

---

### **Step 8: Create Storage Link**

Buat symbolic link untuk storage (agar file upload bisa diakses):

```bash
php artisan storage:link
```

---

### **Step 9: Jalankan Aplikasi**

Buka **2 terminal** dan jalankan perintah berikut:

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Vite Dev Server (untuk compile CSS/JS):**
```bash
npm run dev
```

---

### **Step 10: Akses Aplikasi**

Buka browser dan akses:
```
http://localhost:8000
```

Login dengan salah satu user di atas (password: `password`)

---

## 🔄 Tutorial: Migrasi dari SQLite ke MySQL

Jika Anda ingin menggunakan MySQL sebagai database production, ikuti langkah berikut:

### **Langkah 1: Install MySQL**

**Menggunakan XAMPP (Recommended untuk Windows):**
1. Download XAMPP: https://www.apachefriends.org/
2. Install XAMPP
3. Buka XAMPP Control Panel
4. Start **Apache** dan **MySQL**

**Atau menggunakan Laragon:**
1. Download Laragon: https://laragon.org/download/
2. Install dan jalankan Laragon
3. Start All

---

### **Langkah 2: Buat Database MySQL**

**Opsi A: Menggunakan phpMyAdmin (Mudah)**
1. Buka browser, akses: `http://localhost/phpmyadmin`
2. Klik tab **"Databases"**
3. Buat database baru dengan nama: `storage_management`
4. Collation: `utf8mb4_unicode_ci`
5. Klik **"Create"**

**Opsi B: Menggunakan Command Line**
```bash
# Login ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE storage_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Keluar
exit;
```

---

### **Langkah 3: Update File `.env`**

Buka file `.env` dan ubah konfigurasi database:

**Dari (SQLite):**
```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

**Menjadi (MySQL):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=storage_management
DB_USERNAME=root
DB_PASSWORD=
```

> **Catatan:** 
> - Jika MySQL Anda menggunakan password, isi `DB_PASSWORD` dengan password MySQL Anda
> - Jika menggunakan Laragon, biasanya username: `root`, password: kosong

---

### **Langkah 4: Clear Cache Laravel**

```bash
php artisan config:clear
php artisan cache:clear
```

---

### **Langkah 5: Migrasi Ulang Database**

**⚠️ PERINGATAN: Ini akan menghapus semua data yang ada!**

```bash
# Drop semua tabel dan migrasi ulang
php artisan migrate:fresh

# Seed data awal
php artisan db:seed
```

Atau jika ingin sekaligus:
```bash
php artisan migrate:fresh --seed
```

---

### **Langkah 6: Verifikasi Koneksi**

Cek apakah koneksi berhasil:

```bash
php artisan tinker
```

Lalu ketik:
```php
DB::connection()->getPdo();
```

Jika muncul objek PDO, berarti koneksi berhasil! Ketik `exit` untuk keluar.

---

### **Langkah 7: Test Aplikasi**

1. Restart Laravel server (Ctrl+C lalu `php artisan serve`)
2. Akses `http://localhost:8000`
3. Login dengan user default
4. Coba buat laporan atau peminjaman untuk memastikan database berfungsi

---

## 📝 Migrasi Data dari SQLite ke MySQL (Opsional)

Jika Anda sudah punya data di SQLite dan ingin pindahkan ke MySQL:

### **Opsi 1: Export-Import Manual**

1. **Export data dari SQLite:**
   ```bash
   sqlite3 database/database.sqlite .dump > backup.sql
   ```

2. **Import ke MySQL:**
   ```bash
   mysql -u root -p storage_management < backup.sql
   ```

### **Opsi 2: Menggunakan Package Laravel**

Install package:
```bash
composer require --dev barryvdh/laravel-ide-helper
```

Buat script custom untuk copy data (advanced).

---

## 🔧 Troubleshooting

### **Error: "Class 'Milon\Barcode\...' not found"**
```bash
composer dump-autoload
php artisan config:clear
```

### **Error: "SQLSTATE[HY000] [2002] Connection refused"**
- Pastikan MySQL sudah running (cek XAMPP/Laragon)
- Cek kredensial di file `.env`

### **Error: "npm run dev" tidak jalan**
```bash
rm -rf node_modules package-lock.json
npm install
npm run dev
```

### **Error: "Vite manifest not found"**
```bash
npm run build
```

### **Storage link tidak berfungsi (file upload tidak muncul)**
```bash
php artisan storage:link
```

Jika masih error, buat manual:
- Windows: `mklink /D public\storage storage\app\public`
- Linux/Mac: `ln -s ../storage/app/public public/storage`

---

## 📚 Dokumentasi Tambahan

### **Struktur Folder Penting**

```
webshabib/
├── app/
│   ├── Http/Controllers/     # Logic controller
│   ├── Models/                # Database models
│   └── Notifications/         # Email/notification templates
├── database/
│   ├── migrations/            # Database schema
│   ├── seeders/               # Data awal
│   └── factories/             # Fake data untuk testing
├── resources/
│   ├── views/                 # Blade templates (UI)
│   └── css/                   # Tailwind CSS
├── routes/
│   └── web.php                # Route definitions
├── storage/
│   └── app/public/documents/  # Upload files
└── public/
    └── storage/               # Symbolic link ke storage
```

### **Perintah Artisan Berguna**

```bash
# Clear semua cache
php artisan optimize:clear

# Lihat semua route
php artisan route:list

# Buat controller baru
php artisan make:controller NamaController

# Buat model baru
php artisan make:model NamaModel -m

# Rollback migrasi terakhir
php artisan migrate:rollback

# Fresh install (hapus semua + seed)
php artisan migrate:fresh --seed
```

---

## 🤝 Kontribusi

Jika ingin berkontribusi:
1. Fork repository
2. Buat branch baru (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📄 Lisensi

Project ini menggunakan lisensi MIT. Bebas digunakan untuk keperluan pribadi maupun komersial.

---

## 💡 Tips untuk Pemula

1. **Selalu backup database** sebelum migrasi atau update
2. **Gunakan SQLite** untuk development, **MySQL** untuk production
3. **Jangan commit file `.env`** ke Git (sudah ada di `.gitignore`)
4. **Gunakan `php artisan tinker`** untuk testing query database
5. **Baca error message** dengan teliti, biasanya sudah jelas solusinya

---

## 📞 Support

Jika ada pertanyaan atau masalah:
- Buat Issue di repository
- Email: [email-anda]
- Documentation: https://laravel.com/docs/11.x

---

**Selamat menggunakan Storage Management System! 🚀**
