# Walkthrough - Implementasi Menu Log Aktivitas Admin (Audit Trail)

Semua proses implementasi fitur **Log Aktivitas Admin (Audit Trail)** telah berhasil diselesaikan secara utuh di project SPK-MOORA.

## Perubahan yang Dilakukan

### 1. Migrasi Database (Database Migration)
*   **[NEW]** [2026_06_02_081248_create_activity_logs_table.php](file:///c:/laragon/www/SPK-MOORA/database/migrations/2026_06_02_081248_create_activity_logs_table.php): Membuat tabel `activity_logs` dengan relasi foreign key `user_id` ke tabel `users`.

### 2. Pembaruan Model Eloquent (Eloquent Models)
*   **[NEW]** [ActivityLog.php](file:///c:/laragon/www/SPK-MOORA/app/Models/ActivityLog.php): Model untuk pencatatan log. Dilengkapi method static `ActivityLog::log($description)` untuk merekam aktivitas secara real-time.
*   [User.php](file:///c:/laragon/www/SPK-MOORA/app/Models/User.php) (hasMany): Menambahkan relasi `activityLogs()`.

### 3. Pembuatan Halaman Log & Controller Baru
*   **[NEW]** [ActivityLogController.php](file:///c:/laragon/www/SPK-MOORA/app/Http/Controllers/Admin/ActivityLogController.php): Mengambil riwayat log terbaru (`latest()`) beserta data Admin yang melaksanakannya.
*   **[NEW]** [index.blade.php](file:///c:/laragon/www/SPK-MOORA/resources/views/admin/logs/index.blade.php): Halaman tabel log aktivitas admin. Menampilkan Waktu, Nama Admin (dan Email), serta deskripsi aktivitas yang dilengkapi dengan badge otomatis (**Tambah**, **Edit**, **Hapus**).

### 4. Routing & Integrasi Menu Sidebar
*   [web.php](file:///c:/laragon/www/SPK-MOORA/routes/web.php): Menambahkan rute `/admin/logs` di dalam grup middleware admin.
*   [sidebar.blade.php](file:///c:/laragon/www/SPK-MOORA/resources/views/layouts/sidebar.blade.php): Menambahkan link menu **Log Aktivitas** dengan icon jam history (`bi-clock-history`).

### 5. Sinkronisasi Zona Waktu (Timezone Sync)
*   [app.php](file:///c:/laragon/www/SPK-MOORA/config/app.php): Memperbarui timezone agar mengambil nilai dari environment `.env` dengan default `Asia/Jakarta` (WIB).
*   [.env](file:///c:/laragon/www/SPK-MOORA/.env) & [.env.example](file:///c:/laragon/www/SPK-MOORA/.env.example): Menambahkan `APP_TIMEZONE=Asia/Jakarta` dan mengubah `APP_LOCALE=id` agar format tanggal otomatis disajikan dalam Bahasa Indonesia (misal: *02 Jun 2026, 15:23 WIB*).

### 6. Integrasi Logging Aksi di Controllers
Setiap kali Admin melakukan penambahan, perubahan, atau penghapusan data, log akan otomatis tercatat.
*   [GunungController.php](file:///c:/laragon/www/SPK-MOORA/app/Http/Controllers/Admin/GunungController.php)
*   [TerminalController.php](file:///c:/laragon/www/SPK-MOORA/app/Http/Controllers/Admin/TerminalController.php)
*   [JalurController.php](file:///c:/laragon/www/SPK-MOORA/app/Http/Controllers/Admin/JalurController.php)
*   [BiayaController.php](file:///c:/laragon/www/SPK-MOORA/app/Http/Controllers/Admin/BiayaController.php)
*   [KriteriaController.php](file:///c:/laragon/www/SPK-MOORA/app/Http/Controllers/Admin/KriteriaController.php)
*   [SubKriteriaController.php](file:///c:/laragon/www/SPK-MOORA/app/Http/Controllers/Admin/SubKriteriaController.php)
*   [PenilaianController.php](file:///c:/laragon/www/SPK-MOORA/app/Http/Controllers/Admin/PenilaianController.php)

### 7. Konversi Autentikasi "Email" Menjadi "Username" (Opsi B)
*   **Migration**: [2026_06_02_152944_rename_email_to_username_in_users_table.php](file:///c:/laragon/www/SPK-MOORA/database/migrations/2026_06_02_152944_rename_email_to_username_in_users_table.php) mengganti nama kolom `email` menjadi `username` pada tabel `users`. Data email lama (seperti `admin@gmail.com`) dibersihkan otomatis menjadi username bersih (`admin`).
*   **Controllers & Model**: Memodifikasi [User.php](file:///c:/laragon/www/SPK-MOORA/app/Models/User.php), [AuthController.php](file:///c:/laragon/www/SPK-MOORA/app/Http/Controllers/AuthController.php), dan [UserController.php](file:///c:/laragon/www/SPK-MOORA/app/Http/Controllers/Admin/UserController.php) untuk menggunakan `username` dalam validasi, mass-assignment, dan autentikasi.
*   **Views**: Memperbarui kolom, form, dan label dari email menjadi username pada halaman:
    *   [login.blade.php](file:///c:/laragon/www/SPK-MOORA/resources/views/auth/login.blade.php) (Form login menggunakan Username).
    *   [index.blade.php](file:///c:/laragon/www/SPK-MOORA/resources/views/admin/user/index.blade.php) (Tabel menampilkan Username).
    *   [create.blade.php](file:///c:/laragon/www/SPK-MOORA/resources/views/admin/user/create.blade.php) & [edit.blade.php](file:///c:/laragon/www/SPK-MOORA/resources/views/admin/user/edit.blade.php) (Input form Username).
    *   [index.blade.php](file:///c:/laragon/www/SPK-MOORA/resources/views/admin/logs/index.blade.php) (Tabel log menampilkan `@username` pelaku log).
*   **Diagram ERD**: [erd_database.svg](file:///c:/laragon/www/SPK-MOORA/erd_database.svg) diperbarui untuk menggambarkan kolom `username` di tabel `users`.

---

## Hasil Pengujian (Verification Results)

Kami menjalankan pengujian sistem backend dan database secara langsung menggunakan script pengujian:
*   **Tabel `activity_logs`:** Berhasil dibuat di database (`ADA`).
*   **Pengujian Logging:** Aksi manual login dan log berhasil disimpan dan dibaca kembali dengan sukses.
*   **Log Output:**
    *   *Waktu:* `2026-06-02 15:35:27`
    *   *Admin:* `Admin (@admin)`
    *   *Aktivitas:* `Melakukan verifikasi sistem log pertama kali`

---

## Gambar Visual ERD Terbaru
*   Diagram ERD telah diperbarui secara otomatis di file [erd_database.svg](file:///c:/laragon/www/SPK-MOORA/erd_database.svg) dengan menyertakan tabel `activity_logs` yang terhubung secara langsung ke tabel `users`.

---

## Fitur Baru: Notifikasi Selamat Datang (Welcome Notification)
Telah ditambahkan sistem notifikasi sambutan untuk mempercantik dan mempersonalisasi halaman Dashboard Admin setelah login berhasil:
1. **Dynamic Banner Header**: Tulisan judul banner pada [index.blade.php](file:///c:/laragon/www/SPK-MOORA/resources/views/admin/dashboard/index.blade.php) sekarang dinamis menyapa pengguna dengan nama akun mereka (`Selamat Datang Kembali, {{ auth()->user()->name }}! 👋`).
2. **Dismissible Welcome Alert**: Menambahkan komponen alert modern dengan tema hijau hutan premium di bagian atas dashboard yang mendeteksi session flash data `welcome`.
3. **Session Flash**: Alert akan muncul otomatis tepat setelah proses autentikasi berhasil dari `AuthController.php`.

---

## Pembaruan Layout Dashboard
*   **Pembersihan Menu Aksi Cepat**: Bagian **Aksi Cepat Menu Admin** (Quick Actions Grid) telah dihapus dari Dashboard Admin untuk merapikan antarmuka dan memusatkan interaksi navigasi di sidebar utama.
