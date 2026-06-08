# Use Case Diagram - SPK-MOORA

Dokumen ini mendefinisikan **Use Case Diagram** untuk sistem **SPK-MOORA** (Sistem Pendukung Keputusan Pemilihan Jalur Pendakian Gunung menggunakan Metode MOORA) yang disesuaikan dengan contoh struktur formal skripsi Anda (menggunakan relasi `<<include>>` antara fungsi **Mengelola** dan **Menampilkan**, serta relasi `<<extend>>` pada **Login/Logout** dan **Pencarian**).

---

## 1. Gambar Diagram Use Case (Visual)

Berikut adalah gambar diagram Use Case untuk sistem SPK-MOORA yang dihasilkan sesuai dengan model desain sistem:

![Use Case Diagram SPK-MOORA](usecase_diagram.png)

---

## 2. Diagram Use Case (Sintaksis Mermaid)

Berikut adalah representasi diagram use case menggunakan sintaksis Mermaid. Diagram ini membagi aktor utama menjadi **Superadmin** di sisi kiri, serta **Pendaki (User/Guest)** di sisi kanan.

```mermaid
graph LR
    %% Definisi Aktor
    Superadmin((Superadmin))
    Pendaki((Pendaki))

    subgraph Batasan Sistem [Sistem SPK-MOORA]
        %% Otorisasi
        Login(Login)
        Logout(Logout)
        
        %% Modul Gunung
        M_Gunung(Mengelola Gunung)
        V_Gunung(Menampilkan Daftar Gunung)
        V_Profile(Melihat Profil Gunung)
        
        %% Modul Jalur
        M_Jalur(Mengelola Jalur Pendakian)
        V_Jalur(Menampilkan Daftar Jalur)
        
        %% Modul Terminal
        M_Terminal(Mengelola Terminal Transit)
        V_Terminal(Menampilkan Daftar Terminal)
        
        %% Modul Biaya
        M_Biaya(Mengelola Biaya & Armada)
        V_Biaya(Menampilkan Daftar Biaya)
        
        %% Modul Kriteria
        M_Kriteria(Mengelola Kriteria & Bobot)
        V_Kriteria(Menampilkan Daftar Kriteria)
        
        %% Modul Sub Kriteria
        M_Sub(Mengelola Sub-Kriteria)
        V_Sub(Menampilkan Daftar Sub-Kriteria)
        
        %% Modul Penilaian
        M_Penilaian(Mengisi Penilaian Alternatif)
        V_Penilaian(Menampilkan Daftar Penilaian)
        
        %% Modul Pengguna
        M_User(Mengelola User & Hak Akses)
        V_User(Menampilkan Daftar User)

        %% Perhitungan & Output
        Hitung_MOORA(Menampilkan Perhitungan MOORA)
        Cari_Rekomendasi(Mencari Rekomendasi)
        Cetak_PDF(Mencetak Rencana Perjalanan PDF)
        
        %% Keamanan
        View_Log(Memantau Log Aktivitas Admin)
    end

    %% Relasi Aktor Kiri (Superadmin)
    Superadmin --> Login
    Superadmin --> M_Gunung
    Superadmin --> M_Jalur
    Superadmin --> M_Terminal
    Superadmin --> M_Biaya
    Superadmin --> M_Kriteria
    Superadmin --> M_Sub
    Superadmin --> M_Penilaian
    Superadmin --> M_User
    Superadmin --> View_Log

    %% Relasi Aktor Kanan (Pendaki/Guest)
    V_Profile --> Pendaki
    Cari_Rekomendasi --> Pendaki
    Cetak_PDF --> Pendaki

    %% Relasi <<include>> (Mengelola -> Menampilkan)
    M_Gunung -.->|&lt;&lt;include&gt;&gt;| V_Gunung
    M_Jalur -.->|&lt;&lt;include&gt;&gt;| V_Jalur
    M_Terminal -.->|&lt;&lt;include&gt;&gt;| V_Terminal
    M_Biaya -.->|&lt;&lt;include&gt;&gt;| V_Biaya
    M_Kriteria -.->|&lt;&lt;include&gt;&gt;| V_Kriteria
    M_Sub -.->|&lt;&lt;include&gt;&gt;| V_Sub
    M_Penilaian -.->|&lt;&lt;include&gt;&gt;| V_Penilaian
    M_User -.->|&lt;&lt;include&gt;&gt;| V_User

    %% Relasi <<extend>>
    Logout -.->|&lt;&lt;extend&gt;&gt;| Login
    Cari_Rekomendasi -.->|&lt;&lt;extend&gt;&gt;| Hitung_MOORA
```

---

## 2. Deskripsi Hubungan Relasi Use Case

Berdasarkan diagram di atas, berikut adalah penjelasan relasi asosiasi, `<<include>>`, dan `<<extend>>` yang terjadi di dalam sistem:

### **A. Hubungan `<<include>>` (Ketergantungan Wajib)**
Hubungan ini menunjukkan bahwa proses **Mengelola** suatu data master wajib menyertakan proses **Menampilkan** data tersebut pada layar antarmuka pengguna:
1.  **Mengelola Gunung `<<include>>` Menampilkan Daftar Gunung**: Proses penambahan/pengubahan data gunung memerlukan sistem untuk menampilkan visualisasi data gunung yang ada terlebih dahulu.
2.  **Mengelola Jalur `<<include>>` Menampilkan Daftar Jalur**: Menambah/mengubah jalur pendakian mewajibkan sistem menampilkan daftarnya pada dashboard.
3.  **Mengelola Terminal `<<include>>` Menampilkan Daftar Terminal**: Memperbarui koordinat/lokasi transit memerlukan penampilan data terminal.
4.  **Mengelola Biaya & Armada `<<include>>` Menampilkan Daftar Biaya**: Proses pengelolaan tarif transportasi wajib menampilkan list tarif bus yang aktif.
5.  **Mengelola Kriteria & Bobot `<<include>>` Menampilkan Daftar Kriteria**: Penyetelan bobot MOORA didahului dengan penampilan nilai bobot kriteria saat ini.
6.  **Mengelola Sub-Kriteria `<<include>>` Menampilkan Daftar Sub-Kriteria**: Manajemen skala pembobotan menyertakan penampilan rentang nilai parameter.
7.  **Mengisi Penilaian `<<include>>` Menampilkan Daftar Penilaian**: Pengisian skor alternatif (skala 1-5) menyertakan tampilan matriks keputusan.
8.  **Mengelola User & Hak Akses `<<include>>` Menampilkan Daftar User**: Manajemen pendaftaran admin oleh Superadmin memerlukan tampilan daftar user yang aktif.

### **B. Hubungan `<<extend>>` (Ketergantungan Opsional/Kondisional)**
Hubungan ini menggambarkan perluasan alur kerja yang hanya terjadi pada kondisi tertentu:
1.  **Logout `<<extend>>` Login**: Aksi *Logout* merupakan perluasan opsional setelah aktor berhasil melakukan *Login* ke dalam sistem. Aktor tidak dapat logout jika belum berada dalam status login.
2.  **Mencari Rekomendasi `<<extend>>` Menampilkan Perhitungan MOORA**: Ketika pendaki melakukan pencarian rekomendasi rute dengan menginput nominal budget dan stasiun asal, sistem secara opsional memperluas proses untuk menampilkan rincian hasil komputasi normalisasi matriks MOORA di layar hasil pencarian.
