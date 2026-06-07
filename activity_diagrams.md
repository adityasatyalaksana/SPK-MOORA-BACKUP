# Activity Diagram - SPK-MOORA

Dokumen ini mendefinisikan dan memetakan **Activity Diagram** (Diagram Aktivitas) dengan format **3 Kolom (Swimlane)** untuk alur kerja sistem pendakian gunung dan sistem rekomendasi **SPK-MOORA**.

Dalam diagram ini, seluruh proses terjadi di dalam lingkup internal sistem aplikasi itu sendiri (tanpa melibatkan pihak ketiga eksternal seperti agen bus atau basecamp), terbagi menjadi 3 kolom tanggung jawab:
1. **Pendaki**: Aktor pengguna yang mencari rekomendasi, memilih rute ideal, dan mencetak rincian biaya.
2. **Sistem SPK-MOORA**: Sistem aplikasi web (Laravel) yang memproses validasi, penyaringan budget, perhitungan algoritme MOORA, visualisasi hasil, hingga pembuatan file cetak PDF.
3. **Admin**: Aktor pengelola yang menyiapkan data pendukung (data gunung, terminal, jalur, biaya bus) serta melakukan pembobotan kriteria dan penilaian alternatif.

---

## Activity Diagram Sistem SPK-MOORA (3 Kolom)

### Ilustrasi Visual
![Activity Diagram SPK-MOORA 3 Kolom](/C:/Users/SATYA/.gemini/antigravity-ide/brain/8e28ec10-b59e-4ef4-b608-37fdaddca1c1/diagram_sistem_berjalan_gabungan_1780737103276.png)

### Alur Diagram (Mermaid)
```mermaid
graph TD
    subgraph AktorPendaki [Pendaki]
        OpenWeb[Membuka Halaman Cari Rekomendasi] --> InputForm[Menginput Budget, Jumlah Anggota,<br>Tanggal Keberangkatan & Terminal Awal]
        InputForm --> ClickSearch[Klik Tombol Cari Rekomendasi]
        ShowResults --> SelectRoute[Klik Detail Estimasi Budget<br>Pilihan Rute yang Ideal]
        SelectRoute --> ClickPrint[Klik Cetak Rincian Biaya PDF]
    end

    subgraph SistemWeb [Sistem SPK-MOORA]
        ClickSearch --> Validate{Validasi Input?}
        Validate -- Tidak Valid --> ShowError[Kembalikan Pesan Error Validasi]
        Validate -- Valid --> FilterBudget[Saring Rute Transportasi & Simaksi<br>Total Estimasi <= Budget]
        
        FilterBudget --> CheckRoutes{Ada Rute Lolos?}
        CheckRoutes -- Tidak Ada --> ShowEmpty[Tampilkan Info: Rute Tidak Ditemukan]
        
        CheckRoutes -- Ya --> CalculateMOORA[Hitung Perangkingan MOORA:<br>Normalisasi Matriks & Perkalian Bobot Kriteria]
        
        CalculateMOORA --> SortResults[Urutkan Rekomendasi berdasarkan Skor Yi terbesar]
        SortResults --> ShowResults
        
        ClickPrint --> PrintWindow[Cetak PDF A4<br>Detail Gunung, Terminal, & Rincian Budget]
        PrintWindow --> EndSystem([Selesai])
        
        SaveDB[Menyimpan Data ke Database MySQL] --> OpenWeb
    end

    subgraph AktorAdmin [Admin]
        StartAdmin([Mulai]) --> LoginAdmin[Melakukan Login Admin]
        LoginAdmin --> ManageData[Kelola Data:<br>Gunung, Jalur, Terminal, & Biaya Tiket Bus]
        ManageData --> ManageCriteria[Kelola Bobot Kriteria & Sub-Kriteria]
        ManageCriteria --> InputRatings[Input Nilai Penilaian Alternatif<br>Matriks Keputusan Awal]
        InputRatings --> SaveDB
    end

    ShowError --> InputForm
    ShowEmpty --> OpenWeb

    %% Styles
    classDef startEnd fill:#1e293b,stroke:#0f172a,stroke-width:2px,color:#fff;
    classDef process fill:#f8fafc,stroke:#cbd5e1,stroke-width:1px,color:#1e293b;
    classDef decision fill:#fef3c7,stroke:#d97706,stroke-width:2px,color:#92400e;
    
    class StartAdmin,EndSystem startEnd;
    class OpenWeb,InputForm,ClickSearch,ShowError,FilterBudget,ShowEmpty,CalculateMOORA,SortResults,ShowResults,SelectRoute,ClickPrint,PrintWindow,LoginAdmin,ManageData,ManageCriteria,InputRatings,SaveDB process;
    class Validate,CheckRoutes decision;
```

---

## Penjelasan Alur Proses Kerja Sistem

Berikut adalah urutan langkah demi langkah berdasarkan diagram aktivitas di atas:

1. **Persiapan Data oleh Admin**:
   * **Admin** memulai alur kerja dengan masuk ke halaman admin (`Login Admin`).
   * Admin mengelola master data (`Kelola Data`), termasuk data Gunung, Jalur Pendakian, Terminal transit, dan tarif Biaya tiket bus.
   * Admin mengatur preferensi kriteria dan bobot kriteria (`Kelola Bobot Kriteria & Sub-Kriteria`).
   * Admin memasukkan skor penilaian untuk setiap alternatif jalur pendakian berdasarkan kriteria yang ditentukan (`Input Nilai Penilaian Alternatif`).
   * Nilai-nilai tersebut dikirim ke **Sistem SPK-MOORA** untuk disimpan secara permanen di database MySQL (`Menyimpan Data ke Database MySQL`).

2. **Proses Pencarian Rekomendasi oleh Pendaki**:
   * **Pendaki** membuka antarmuka rekomendasi (`Membuka Halaman Cari Rekomendasi`).
   * Pendaki memasukkan kriteria pencarian berupa nominal budget, total anggota kelompok, tanggal keberangkatan, dan terminal asal (`Menginput Budget, Jumlah Anggota, Tanggal & Terminal Awal`).
   * Pendaki mengklik tombol cari rekomendasi (`Klik Tombol Cari Rekomendasi`).

3. **Perhitungan & Filter oleh Sistem**:
   * **Sistem SPK-MOORA** melakukan pemeriksaan data (`Validasi Input?`). Jika tidak valid, pendaki diminta melengkapi kembali inputnya.
   * Sistem kemudian mencocokkan tanggal keberangkatan (weekday/weekend/event khusus) serta terminal asal untuk menyaring rute transportasi dan biaya simaksi (`Saring Rute Transportasi & Simaksi`). Jalur yang memiliki total estimasi biaya lebih besar dari budget pendaki akan langsung disaring keluar.
   * Sistem mengecek apakah ada rute yang lolos filter budget (`Ada Rute Lolos?`). Jika tidak ada, sistem mengembalikan informasi bahwa rute tidak ditemukan.
   * Jika ada rute yang lolos, sistem memproses data matriks alternatif yang lolos menggunakan metode MOORA (`Hitung Perangkingan MOORA`). Proses ini melibatkan normalisasi matriks keputusan awal dan perkalian dengan bobot kriteria yang sebelumnya telah ditentukan oleh Admin.
   * Hasil kalkulasi diurutkan dari nilai preferensi $Y_i$ terbesar hingga terkecil (`Urutkan Rekomendasi berdasarkan Skor Yi terbesar`).
   * Sistem menampilkan urutan perangkingan tersebut ke halaman web pendaki (`Menampilkan List Perangkingan Hasil MOORA`).

4. **Pilihan Rute & Cetak PDF**:
   * **Pendaki** meninjau hasil perangkingan, lalu memilih alternatif terbaik menurutnya dan mengklik tombol detail (`Klik Detail Estimasi Budget`).
   * Pendaki mengklik tombol cetak (`Klik Cetak Rincian Biaya PDF`).
   * **Sistem SPK-MOORA** memproses permintaan tersebut dan membuka jendela cetak untuk menghasilkan berkas cetak PDF berformat A4 secara bersih dan responsif (`Cetak PDF A4`).
   * Alur proses selesai (`Selesai`).
