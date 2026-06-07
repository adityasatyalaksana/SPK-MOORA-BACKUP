# Analisis Sistem Usulan - SPK-MOORA

Dokumen ini menyajikan analisis mendalam terhadap **Sistem Usulan (Aplikasi Web SPK-MOORA)** yang dikembangkan dalam proyek ini. Analisis ini disusun secara akademis dan sistematis untuk membantu kebutuhan penulisan bab analisis dan perancangan pada dokumen karya ilmiah (Skripsi/Tugas Akhir).

---

## 1. Visualisasi Diagram Analisis Sistem Usulan (Use Case Diagram)

Use Case Diagram menggambarkan fungsi-fungsi utama (kebutuhan fungsional) yang disediakan oleh sistem aplikasi SPK-MOORA serta bagaimana aktor (Pendaki dan Admin) berinteraksi dengan fungsi-fungsi tersebut secara internal di dalam sistem.

### Gambar Use Case Diagram Sistem Usulan
![Use Case Diagram SPK-MOORA](/C:/Users/SATYA/.gemini/antigravity-ide/brain/8e28ec10-b59e-4ef4-b608-37fdaddca1c1/use_case_sistem_usulan_1780738991391.png)

---

## 2. Deskripsi Umum Sistem Usulan

Sistem Usulan adalah sebuah aplikasi sistem pendukung keputusan (SPK) berbasis web yang dirancang untuk mempermudah pendaki gunung dalam merencanakan perjalanan mereka. Sistem ini mengintegrasikan estimasi budget perjalanan kelompok/individu secara real-time dengan metode **MOORA (Multi-Objective Optimization on the basis of Ratio Analysis)** untuk menghasilkan rekomendasi rute pendakian yang paling optimal.

### Tujuan Utama Sistem Usulan:
*   **Mengotomatisasi Perhitungan Estimasi Biaya**: Menghilangkan kebutuhan perhitungan manual yang rumit dan rentan kesalahan (human error).
*   **Memberikan Keputusan yang Objektif**: Menghasilkan rekomendasi rute berdasarkan kriteria-kriteria terukur (Benefit & Cost) menggunakan metode ilmiah MOORA.
*   **Meningkatkan Fleksibilitas Waktu & Budget**: Memperhitungkan skema tarif dinamis bus (tiket reguler PP, weekend, dan tarif periode event khusus) serta tiket simaksi (weekday/weekend).
*   **Dokumentasi yang Efisien**: Menyediakan fitur cetak rincian biaya dalam format A4 ringkas berbentuk tiket/struk belanja (receipt) premium yang siap cetak atau simpan ke PDF.

---

## 3. Perbandingan Sistem Berjalan vs. Sistem Usulan

Berikut adalah perbandingan kelebihan Sistem Usulan dibandingkan dengan prosedur pencarian manual terdahulu (Sistem Berjalan):

| Parameter Perbandingan | Sistem Berjalan (Manual) | Sistem Usulan (Aplikasi SPK-MOORA) |
| :--- | :--- | :--- |
| **Kecepatan Proses** | Lambat. Pendaki harus mencari informasi satu per satu di berbagai platform media sosial/blog. | Instan. Seluruh data gunung, jalur, dan tarif bus sudah terpusat di database dan diproses secara otomatis. |
| **Akurasi Perhitungan** | Rendah. Berisiko salah hitung biaya kelompok, terutama ketika memperhitungkan variabel weekday/weekend/hari besar. | Tinggi. Logika pemrograman PHP/Laravel secara otomatis menghitung tarif bus dan simaksi secara akurat berdasarkan tanggal keberangkatan. |
| **Dasar Keputusan** | Subjektif. Hanya mengandalkan insting pendaki atau opini tanpa perbandingan terbobot yang seimbang. | Objektif & Ilmiah. Menggunakan perhitungan MOORA yang menyeimbangkan kriteria biaya (*cost*) dan fasilitas/kesulitan (*benefit*). |
| **Output / Dokumentasi** | Tidak ada dokumentasi resmi (biasanya dicatat di kertas cakar atau pesan WhatsApp). | Tanda terima (Receipt) PDF siap cetak dengan layout A4 yang terstruktur rapi. |

---

## 4. Analisis Kebutuhan Fungsional (Functional Requirements)

Sistem usulan ini membagi hak akses dan kebutuhan fungsional ke dalam dua peran pengguna utama, yaitu **Pendaki (User)** dan **Admin/Superadmin**.

### A. Kebutuhan Fungsional Pendaki (Public User)
1.  **Form Input Kriteria Pencarian**: Pendaki dapat memasukkan parameter rencana perjalanan meliputi:
    *   Nominal budget yang dimiliki kelompok.
    *   Jumlah anggota kelompok pendaki.
    *   Tanggal keberangkatan (mempengaruhi harga tiket bus dan tiket simaksi).
    *   Terminal keberangkatan awal.
2.  **Sistem Filter Otomatis**: Sistem secara cerdas menyaring jalur yang total biaya perjalanannya (Simaksi + Bus Tiket PP dikali jumlah anggota) tidak melebihi budget pendaki.
3.  **Halaman Rekomendasi MOORA**: Menampilkan daftar alternatif yang lolos filter budget, diurutkan berdasarkan skor akhir preferensi ($Y_i$) tertinggi (terbaik).
4.  **Detail Estimasi Rincian**: Menampilkan modal pop-up dengan desain premium seperti tiket/struk perjalanan untuk melihat breakdown biaya per orang, biaya total kelompok, detail bus, terminal asal/tujuan, dan jalur gunung.
5.  **Cetak PDF Rincian**: Menyediakan opsi bagi pendaki untuk mengekspor rincian biaya ke dalam format PDF satu lembar A4 secara langsung.

### B. Kebutuhan Fungsional Admin
1.  **Manajemen Autentikasi**: Login dan logout untuk mengamankan halaman dashboard administrator.
2.  **Kelola Master Data Pendakian**:
    *   Data Gunung (Nama, lokasi, ketinggian, deskripsi, gambar).
    *   Data Jalur Pendakian (Nama jalur, biaya simaksi weekday, biaya simaksi weekend, estimasi waktu mendaki, tingkat kesulitan).
    *   Data Terminal (Terminal transit asal/starting point dan terminal kedatangan/ending point).
    *   Data Biaya Bus (Nama armada bus, estimasi perjalanan bus, harga tiket reguler PP, harga weekend PP, serta harga periode khusus beserta range tanggalnya).
3.  **Kelola Model Keputusan SPK**:
    *   Menentukan kode, nama, tipe (Benefit/Cost), dan bobot kriteria.
    *   Mengatur sub-kriteria (skala penilaian 1-5).
    *   Menginput nilai penilaian matriks keputusan untuk setiap alternatif jalur dan biaya bus.
4.  **Audit Trail (Activity Log)**: Sistem secara otomatis mencatat setiap aktivitas penambahan, pengubahan, atau penghapusan data yang dilakukan oleh Admin di database untuk keamanan sistem.

---

## 5. Analisis Logika & Alur Algoritme MOORA dalam Sistem Usulan

Algoritme MOORA di dalam sistem usulan ini bekerja secara dinamis di kelas `RekomendasiController`. Langkah-langkah matematisnya adalah sebagai berikut:

### Langkah 1: Pembentukan Matriks Keputusan ($x$)
Sistem menarik seluruh data alternatif (kombinasi Jalur dan Biaya Bus aktif) dan kriteria yang ada di database. Nilai awal matriks keputusan disimbolkan dengan $x_{ij}$ (nilai alternatif $i$ pada kriteria $j$).

### Langkah 2: Perhitungan Pembagi Normalisasi Global
Untuk menjaga keadilan nilai normalisasi antar data admin dan pencarian user, sistem menghitung nilai pembagi normalisasi secara global untuk setiap kriteria $j$:
$$\text{Pembagi}_j = \sqrt{\sum_{i=1}^{m} x_{ij}^2}$$
*Di mana $m$ adalah jumlah seluruh alternatif yang terdaftar secara global di database.*

### Langkah 3: Normalisasi Matriks Keputusan ($X_{ij}$)
Setiap nilai keputusan awal dibagi dengan nilai pembagi kriteria masing-masing untuk menghilangkan perbedaan satuan (meter, rupiah, jam, tingkat kesulitan):
$$X_{ij} = \frac{x_{ij}}{\text{Pembagi}_j}$$

### Langkah 4: Perkalian dengan Bobot Kriteria ($Y_{ij}$)
Nilai ternormalisasi dikalikan dengan bobot kriteria ($w_j$) yang telah diatur oleh Admin:
$$Y_{ij} = X_{ij} \times w_j$$

### Langkah 5: Kalkulasi Skor Preferensi Akhir ($Y_i$)
Sistem menjumlahkan nilai kriteria bertipe *Benefit* dan menguranginya dengan penjumlahan kriteria bertipe *Cost* untuk setiap alternatif $i$:
$$Y_i = \sum_{j=1}^{g} Y_{ij} - \sum_{j=g+1}^{n} Y_{ij}$$
* Di mana kriteria $1$ s/d $g$ adalah *Benefit* dan kriteria $g+1$ s/d $n$ adalah *Cost*.
* Alternatif dengan nilai preferensi $Y_i$ terbesar akan menduduki peringkat teratas dan direkomendasikan sebagai pilihan paling ideal bagi pendaki.

---

## 6. Kesimpulan Analisis Sistem Usulan

Sistem Usulan ini telah memenuhi standar kelayakan untuk diterapkan sebagai solusi pengganti sistem manual. Integrasi antara framework Laravel 12 dan metode MOORA menghasilkan aplikasi yang:
1.  **Dinamis**: Mampu beradaptasi dengan perubahan harga tiket bus pada hari libur atau weekend tanpa merusak formula perankingan utama.
2.  **Transparan & Ilmiah**: Memberikan penjelasan biaya yang mendetail serta urutan rekomendasi berdasarkan rumus matematika terukur.
3.  **User-Friendly**: Memudahkan proses pendokumentasian biaya bagi kelompok pendaki melalui satu tombol cetak PDF yang teroptimasi.
