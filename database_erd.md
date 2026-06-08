# Entity Relationship Diagram (ERD) - SPK-MOORA

Dokumen ini berisi visualisasi dan deskripsi relasi antar-tabel (Entity Relationship Diagram) untuk database project **SPK-MOORA** (Sistem Pendukung Keputusan pemilihan jalur pendakian gunung menggunakan metode MOORA) setelah penambahan hak akses dinamis (Roles & Permissions) dan tracking penciptaan data (`user_id`).

## Diagram ERD (Mermaid)

Berikut adalah visualisasi ERD database. Diagram ini menunjukkan relasi antar entitas utama seperti Hak Akses (Roles/Permissions), Pengguna (Users), Gunung, Jalur, Terminal, Biaya, Kriteria, Sub-Kriteria, Penilaian, dan Log Aktivitas.

```mermaid
erDiagram
    users {
        bigint id PK
        bigint role_id FK
        string name
        string username UK
        string password
        string remember_token
        timestamp created_at
        timestamp updated_at
    }

    roles {
        bigint id PK
        string name UK
        timestamp created_at
        timestamp updated_at
    }

    permissions {
        bigint id PK
        string name UK
        string label
        timestamp created_at
        timestamp updated_at
    }

    role_permission {
        bigint role_id FK
        bigint permission_id FK
    }

    gunungs {
        bigint id PK
        bigint user_id FK
        string nama_gunung
        string lokasi
        int ketinggian
        text deskripsi
        string gambar
        timestamp created_at
        timestamp updated_at
    }

    jalurs {
        bigint id PK
        bigint gunung_id FK
        bigint user_id FK
        string nama_jalur
        decimal biaya_simaksi_weekday
        decimal biaya_simaksi_weekend
        decimal estimasi_jam
        enum tingkat_kesulitan "Sangat Mudah, Mudah, Sedang, Sulit, Sangat Sulit"
        timestamp created_at
        timestamp updated_at
    }

    terminals {
        bigint id PK
        bigint user_id FK
        string nama_terminal
        string lokasi
        enum tipe "Starting Point, Ending Point"
        timestamp created_at
        timestamp updated_at
    }

    biayas {
        bigint id PK
        bigint jalur_id FK
        bigint start_terminal_id FK
        bigint end_terminal_id FK
        bigint user_id FK
        string nama_armada
        int estimasi_perjalanan
        int harga_pp
        int harga_weekend
        date start_date
        date end_date
        int harga_periode
        timestamp created_at
        timestamp updated_at
    }

    kriterias {
        bigint id PK
        bigint user_id FK
        string kode_kriteria "Contoh: C1, C2"
        string nama_kriteria "Contoh: Biaya, Ketinggian"
        enum tipe "Benefit, Cost"
        decimal bobot
        timestamp created_at
        timestamp updated_at
    }

    sub_kriterias {
        bigint id PK
        bigint kriteria_id FK
        string nama_sub
        int bobot "Nilai skala 1-5"
        timestamp created_at
        timestamp updated_at
    }

    penilaians {
        bigint id PK
        bigint jalur_id FK
        bigint biaya_id FK
        bigint kriteria_id FK
        bigint user_id FK
        int nilai
        timestamp created_at
        timestamp updated_at
    }

    activity_logs {
        bigint id PK
        bigint user_id FK
        text activity
        timestamp created_at
        timestamp updated_at
    }

    roles ||--o{ users : "defines role (1:N)"
    roles ||--o{ role_permission : "pivot (1:N)"
    permissions ||--o{ role_permission : "pivot (1:N)"
    users ||--o{ activity_logs : "performed (1:N)"
    
    users ||--o{ gunungs : "created (1:N)"
    users ||--o{ terminals : "created (1:N)"
    users ||--o{ jalurs : "created (1:N)"
    users ||--o{ biayas : "created (1:N)"
    users ||--o{ kriterias : "created (1:N)"
    users ||--o{ penilaians : "created (1:N)"

    gunungs ||--o{ jalurs : "has many (1:N)"
    jalurs ||--o{ biayas : "has many (1:N)"
    jalurs ||--o{ penilaians : "has many (1:N)"
    terminals ||--o{ biayas : "starts at (1:N)"
    terminals ||--o{ biayas : "ends at (1:N)"
    biayas ||--o{ penilaians : "has many (1:N)"
    kriterias ||--o{ sub_kriterias : "has many (1:N)"
    kriterias ||--o{ penilaians : "has many (1:N)"
```

---

## Deskripsi Tabel & Relasi

### 1. Tabel Autentikasi & Hak Akses (RBAC)

*   **`users`**: Menyimpan akun pengguna (Superadmin, Admin) yang memiliki kredensial login (`username`, `password`).
    *   *Relasi*: Terhubung ke tabel `roles` melalui `role_id` (Many-to-One).
*   **`roles`**: Menyimpan peran pengguna (contoh: `Superadmin`, `Admin`).
*   **`permissions`**: Menyimpan daftar hak akses spesifik (contoh: `manage_users`, `view_logs`, `manage_gunung`, `manage_kriteria`, `view_hasil`).
*   **`role_permission`**: Tabel pivot penghubung Many-to-Many antara `roles` dan `permissions`.

### 2. Tabel Utama Pendakian & Transportasi (Master Data)

Seluruh data master kini terhubung ke `users` melalui kolom `user_id` untuk mencatat siapa admin yang menginput/memodifikasi data tersebut.

*   **`gunungs`**: Menyimpan data gunung yang tersedia (nama, lokasi, ketinggian, deskripsi, gambar).
    *   *Relasi*: Terhubung ke `users` via `user_id`. Banyak jalur (`jalurs`) terhubung ke satu gunung melalui `gunung_id`.
*   **`jalurs`**: Menyimpan rute/jalur pendakian spesifik untuk setiap gunung.
    *   *Relasi*: Terhubung ke `users` via `user_id`. Terhubung ke `gunungs` via `gunung_id` (Many-to-One).
*   **`terminals`**: Menyimpan titik transit awal dan akhir perjalanan armada bus.
    *   *Relasi*: Terhubung ke `users` via `user_id`. Memiliki tipe (`tipe`) berupa 'Starting Point' atau 'Ending Point'.
*   **`biayas`**: Menyimpan data biaya transportasi (armada, harga tiket PP, weekend price, dan harga periode khusus).
    *   *Relasi*: 
        *   Terhubung ke `users` via `user_id`.
        *   Terhubung ke `jalurs` via `jalur_id` (opsional).
        *   Terhubung ke `terminals` sebagai titik awal (`start_terminal_id`) dan titik akhir (`end_terminal_id`).

### 3. Tabel SPK (Metode MOORA)

*   **`kriterias`**: Menyimpan kriteria penilaian untuk MOORA (misalnya C1 s/d C6). Memiliki `tipe` ('Benefit' atau 'Cost') dan `bobot` kriteria.
    *   *Relasi*: Terhubung ke `users` via `user_id`.
*   **`sub_kriterias`**: Menyimpan sub-kriteria/skala nilai parameter dari setiap kriteria (misal: "Sangat Murah" dengan bobot 5).
    *   *Relasi*: Banyak sub-kriteria terhubung ke satu kriteria (`kriterias`) via `kriteria_id`.
*   **`penilaians`**: Tabel transaksi penilaian alternatif (Jalur + Armada) berdasarkan kriteria yang ditentukan.
    *   *Relasi*: 
        *   Terhubung ke `users` via `user_id`.
        *   Mencatat `nilai` konkret (skor 1-5) untuk kombinasi `jalur_id`, `biaya_id`, dan `kriteria_id`.

### 4. Tabel Audit Trail

*   **`activity_logs`**: Menyimpan catatan riwayat aktivitas admin (log audit) seperti penambahan, pengubahan, atau penghapusan data secara otomatis.
    *   *Relasi*: Terhubung ke `users` melalui `user_id` (Many-to-One).
