# Dokumen Pengujian Black Box - SPK-MOORA

Dokumen ini mendokumentasikan skenario pengujian fungsional sistem menggunakan metode **Black Box Testing** pada aplikasi Sistem Pendukung Keputusan Rekomendasi Rute Pendakian Gunung menggunakan metode **MOORA** (SPK-MOORA).

---

## 1. Modul Autentikasi Admin (Login & Logout)

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Menginput *username* (`admin`) dan *password* (`password`) yang terdaftar dengan benar lalu menekan tombol "Login". | Sistem menerima akses autentikasi, menampilkan alert sambutan dinamis, dan mengarahkan ke halaman Dashboard Admin. | Sistem berhasil memvalidasi akun, menampilkan alert, dan membuka Dashboard Admin. | Berhasil |
| 2 | Menginput *username* (`admin`) dan *password* yang salah (`salahpassword`) lalu menekan tombol "Login". | Sistem menolak login, menampilkan pesan validasi "Kredensial yang diberikan tidak cocok dengan catatan kami." | Sistem menampilkan pesan kesalahan kredensial dan tetap berada di halaman login. | Berhasil |
| 3 | Membiarkan kolom *username* dan *password* kosong, lalu menekan tombol "Login". | Sistem menolak aksi, memblokir pengiriman formulir, dan menampilkan pesan validasi "Username/Password wajib diisi". | Sistem memblokir proses masuk dan memunculkan pesan validasi pengisian wajib. | Berhasil |
| 4 | Menekan tombol "Logout" pada menu sidebar admin. | Sistem mengakhiri sesi login pengguna, menghapus data sesi, dan mengarahkan kembali ke halaman Login. | Sesi login berhasil dihancurkan dan halaman dialihkan ke tampilan Login. | Berhasil |

---

## 2. Modul Dashboard Admin

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Mengakses halaman utama dashboard admin di URL `/admin/dashboard`. | Sistem menampilkan 8 widget informasi kuantitas data (Pengguna, Log Aktivitas, Gunung, Terminal, Jalur, Armada Bus, Penilaian, Kriteria) secara dinamis. | Halaman dashboard memuat seluruh widget ringkasan data dari database secara lengkap. | Berhasil |
| 2 | Menutup (dismiss) banner alert sambutan selamat datang di bagian atas dashboard. | Sistem menyembunyikan alert sambutan secara mulus tanpa memuat ulang (*refresh*) halaman. | Banner alert tertutup dengan sukses setelah ikon close diklik. | Berhasil |
| 3 | Memeriksa tabel "Log Aktivitas Terbaru" di halaman dashboard admin. | Sistem menampilkan tabel riwayat log berisi maksimal 5 entri aktivitas terbaru yang dilakukan oleh admin. | Tabel log memuat 5 baris data aktivitas terakhir dengan informasi waktu dan deskripsi aksi. | Berhasil |

---

## 3. Modul Kelola Master Data Gunung (Akses: `manage_gunung`)

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Mengisi formulir data gunung dengan lengkap (Nama, Lokasi, Ketinggian, Deskripsi, dan File Gambar) lalu menekan tombol "Simpan". | Sistem menyimpan data gunung baru, mengunggah gambar ke storage, menampilkan alert sukses, dan menampilkan data di tabel daftar gunung. | Data berhasil tersimpan, file gambar terunggah, dan data muncul pada tabel daftar gunung. | Berhasil |
| 2 | Mengosongkan salah satu atau seluruh kolom pada form tambah gunung lalu menekan tombol "Simpan". | Sistem menolak penyimpanan data, mengembalikan pengguna ke form, dan menampilkan pesan kesalahan pengisian wajib. | Form ditolak dan memunculkan notifikasi error validasi input wajib. | Berhasil |
| 3 | Mengubah data deskripsi dan mengganti file gambar gunung lalu menekan tombol "Perbarui". | Sistem memperbarui data di database, menghapus file gambar lama di storage, menyimpan gambar baru, dan menampilkan alert sukses. | Data deskripsi diperbarui, gambar lama terhapus, gambar baru tersimpan, dan muncul alert sukses. | Berhasil |
| 4 | Menekan tombol "Hapus" pada salah satu baris data gunung. | Sistem menghapus data gunung dari database, menghapus file gambar terkait dari storage, dan memperbarui tabel secara otomatis. | Data gunung terhapus dari database beserta file gambarnya dari storage dengan sukses. | Berhasil |

---

## 4. Modul Kelola Master Data Terminal (Akses: `manage_terminal`)

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Menginputkan nama terminal baru pada form tambah lalu menekan tombol "Simpan". | Sistem menyimpan data terminal ke database, memunculkan alert sukses, dan menampilkan terminal baru pada tabel daftar terminal. | Terminal berhasil tersimpan ke database dan muncul langsung pada baris tabel. | Berhasil |
| 2 | Mengubah nama terminal pada form edit lalu menekan tombol "Perbarui". | Sistem menyimpan perubahan nama terminal di database dan langsung memperbarui baris nama di tabel. | Perubahan nama terminal berhasil diperbarui dan tersimpan di database. | Berhasil |
| 3 | Menekan tombol "Hapus" pada salah satu terminal yang tidak terikat relasi. | Sistem menghapus data terminal dari database dan memuat ulang tabel daftar terminal. | Terminal berhasil dihapus secara bersih dari database. | Berhasil |

---

## 5. Modul Kelola Master Data Jalur Pendakian (Akses: `manage_jalur`)

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Mengisi form tambah jalur (Gunung, Nama Jalur, Tarif Simaksi Weekday & Weekend, Waktu, Kesulitan) dengan benar lalu menekan "Simpan". | Sistem menyimpan data jalur baru, memetakan ke gunung terkait, menampilkan data pada tabel jalur, dan memunculkan alert sukses. | Jalur baru tersimpan dan terelasi dengan gunung yang dipilih dengan sukses. | Berhasil |
| 2 | Menginputkan angka negatif pada kolom tarif simaksi dan waktu lalu menekan "Simpan". | Sistem menolak input, menampilkan pesan validasi error bahwa nilai tarif dan waktu harus berupa angka positif minimal 0. | Sistem menampilkan pesan error dan memblokir penyimpanan data bernilai negatif. | Berhasil |
| 3 | Mengubah data jalur pendakian (Nama Jalur, Tarif Simaksi, Estimasi Waktu, dsb.) lalu menekan tombol "Perbarui". | Sistem menyimpan perubahan data jalur di database, memperbarui baris tabel, dan menampilkan alert sukses. | Perubahan data jalur berhasil diperbarui dan tersimpan di database. | Berhasil |
| 4 | Menekan tombol "Hapus" pada salah satu baris jalur pendakian. | Sistem menghapus data jalur dari database serta membersihkan relasi penilaian terkait dan memperbarui tampilan tabel. | Jalur berhasil dihapus dari database beserta relasi tabel penilaiannya. | Berhasil |

---

## 6. Modul Kelola Master Data Biaya & Armada Bus (Akses: `manage_biaya`)

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Mengisi data armada bus, rute terminal asal/tujuan, serta tarif reguler & weekend PP lalu menekan "Simpan". | Sistem menyimpan data armada beserta tarif bus reguler dan weekend PP ke database dan menampilkan data di tabel. | Data biaya bus tersimpan ke database dengan terminal asal/tujuan yang tepat. | Berhasil |
| 2 | Mengubah data armada bus (Nama Armada, Terminal Asal/Tujuan, Tarif Reguler/Weekend) pada form edit lalu menekan tombol "Perbarui". | Sistem menyimpan perubahan data biaya bus di database, memperbarui baris tabel, dan menampilkan alert sukses. | Perubahan data biaya bus berhasil diperbarui dan tersimpan di database dengan sukses. | Berhasil |
| 3 | Mengisi form tarif khusus periode event dengan tarif baru dan rentang tanggal lalu menekan "Terapkan Tarif Periode". | Sistem mengaktifkan tarif khusus untuk rentang tanggal tersebut, yang akan menggantikan tarif reguler/weekend saat pencarian pendaki dilakukan. | Skema tarif khusus berhasil disimpan dan aktif pada database sesuai periode tanggal. | Berhasil |
| 4 | Menekan tombol "Reset Tarif Khusus" pada baris armada bus. | Sistem menghapus nilai tarif khusus beserta rentang tanggalnya (diubah menjadi NULL) dan mengembalikan ke skema tarif reguler. | Data tarif khusus berhasil direset kembali menjadi kosong (reguler). | Berhasil |
| 5 | Menekan tombol "Hapus" pada armada bus. | Sistem menghapus data armada bus beserta rute tarifnya dari database. | Data armada bus berhasil dihapus dari database secara permanen. | Berhasil |

---

## 7. Modul Kelola Kriteria (Akses: `manage_kriteria`)

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Mengakses halaman Kriteria di URL `/admin/kriteria`. | Sistem memuat daftar kriteria keputusan lengkap dengan kode (C1-C5), nama kriteria, tipe (Benefit/Cost), dan bobot kriteria. | Halaman menampilkan tabel kriteria dengan bobot persentase total 100% dari database. | Berhasil |
| 2 | Mengisi formulir tambah kriteria (Kode, Nama, Tipe: Benefit/Cost, dan Bobot) dengan lengkap dan benar lalu menekan tombol "Simpan". | Sistem menyimpan kriteria baru ke database, menampilkan alert sukses, dan menampilkan kriteria di tabel. Aktivitas log dicatat. | Kriteria baru berhasil tersimpan ke database, muncul di tabel daftar, dan log aktivitas tercatat. | Berhasil |
| 3 | Mengosongkan kolom input wajib pada form tambah kriteria lalu menekan tombol "Simpan". | Sistem menolak aksi, memblokir pengiriman formulir, dan menampilkan pesan kesalahan validasi. | Sistem menolak penyimpanan dan menampilkan error validasi pengisian wajib. | Berhasil |
| 4 | Mengubah nama, tipe, atau bobot kriteria pada form edit lalu menekan tombol "Perbarui". | Sistem menyimpan perubahan kriteria di database, memperbarui baris tabel, dan menampilkan alert sukses. Aktivitas log dicatat. | Perubahan kriteria berhasil diperbarui di database dan tercatat di activity logs. | Berhasil |
| 5 | Menekan tombol "Hapus" pada salah satu kriteria. | Sistem menghapus data kriteria terkait dari database, memperbarui tabel daftar kriteria, dan mencatat log aktivitas. | Kriteria berhasil dihapus dari database dan tercatat di activity logs. | Berhasil |

---

## 8. Modul Kelola Sub-Kriteria (Akses: `manage_sub_kriteria`)

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Mengisi form tambah sub-kriteria (Kriteria, Nama Sub, dan Nilai Skala 1-5) lalu menekan "Simpan". | Sistem menyimpan sub-kriteria baru yang terhubung ke kriteria induk dengan nilai skala sub-kriteria yang sesuai. | Sub-kriteria berhasil disimpan dan dipetakan ke kriteria induk. | Berhasil |
| 2 | Mengubah bobot nilai sub-kriteria di form edit lalu menekan "Perbarui". | Sistem menyimpan perubahan nilai skala sub-kriteria di database dan memperbarui daftar tabel. | Nilai skala sub-kriteria diperbarui di database dengan sukses. | Berhasil |
| 3 | Menekan tombol "Hapus" pada sub-kriteria. | Sistem menghapus data sub-kriteria terkait dari database. | Data sub-kriteria terhapus dari database. | Berhasil |

---

## 9. Modul Kelola Penilaian Alternatif (Matriks Keputusan) (Akses: `manage_penilaian`)

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Memilih kombinasi alternatif (Jalur & Bus) dan mengisi nilai sub-kriteria untuk kriteria C1-C5 lalu menekan "Simpan Penilaian". | Sistem menyimpan entri penilaian alternatif ke database dan menampilkannya di matriks keputusan. | Penilaian alternatif berhasil disimpan dengan nilai kriteria yang tepat. | Berhasil |
| 2 | Menginput penilaian dengan alternatif (Jalur & Bus) yang sama dengan data yang sudah pernah diinput sebelumnya. | Sistem menolak penyimpanan dan memunculkan notifikasi error bahwa alternatif tersebut sudah memiliki penilaian. | Penyimpanan diblokir dan muncul validasi duplikasi alternatif. | Berhasil |
| 3 | Menekan tombol "Hapus Penilaian" pada baris matriks keputusan. | Sistem menghapus baris penilaian alternatif tersebut dari database dan tabel ter-refresh otomatis. | Baris penilaian alternatif terhapus dengan sukses dari database. | Berhasil |

---

## 10. Modul Hasil Perangkingan MOORA (Sisi Admin) (Akses: `view_hasil`)

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Mengakses halaman Hasil Perhitungan di URL `/admin/hasil`. | Sistem memuat 4 tabel kalkulasi MOORA secara berurutan: Matriks Keputusan ($x$), Matriks Ternormalisasi ($X$), Matriks Ternormalisasi Terbobot ($Y$), serta Tabel Preferensi Akhir ($Y_i$) & Perankingan. | Halaman menampilkan seluruh proses perhitungan rumus MOORA secara presisi dan urut. | Berhasil |
| 2 | Menekan tombol "Cetak Laporan PDF" pada halaman hasil perhitungan. | Sistem membuka jendela cetak bawaan browser (*print preview*) dengan format dokumen akademik/laporan formal (menyembunyikan sidebar dan tombol interaktif, menggunakan font Times New Roman, dan menata letak tabel secara rapi). | Jendela print preview terbuka dengan layout cetak dokumen laporan formal akademik yang bersih dan siap cetak. | Berhasil |

---

## 11. Modul Log Aktivitas Admin (Audit Trail) (Akses: `view_logs`)

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Melakukan aksi CRUD (contoh: menambah gunung baru) sebagai admin. | Sistem otomatis mencatat log baru berisi deskripsi tindakan, username admin, IP address, dan timestamp. | Log aktivitas berhasil terekam otomatis di database setelah aksi CRUD selesai. | Berhasil |
| 2 | Mengakses halaman riwayat log di URL `/admin/logs`. | Sistem memuat tabel riwayat log aktivitas admin secara berurutan dari yang terbaru ke terlama dengan pagination. | Halaman berhasil memuat seluruh log aktivitas dengan layout tabel yang rapi. | Berhasil |
| 3 | Menekan tombol "Ekspor CSV/Excel" di halaman log aktivitas. | Sistem mengekspor data tabel log dan memicu download berkas berformat `.csv` atau `.xlsx`. | Berkas log berhasil diunduh ke perangkat lokal. | Berhasil |
| 4 | Memilih opsi pembersihan "Hapus yang berusia lebih dari 30 hari" lalu menekan tombol "Hapus Log" (Superadmin). | Sistem menghapus log aktivitas yang bertanggal lebih lama dari 30 hari sebelumnya dan mencatat tindakan tersebut. | Log lama berhasil dihapus secara massal dari database dan tersisa log yang baru. | Berhasil |
| 5 | Memilih opsi pembersihan "Hapus Semua" lalu menekan tombol "Hapus Log" (Superadmin). | Sistem mengosongkan seluruh riwayat log di database dan menyisakan 1 baris log baru mengenai aksi pembersihan ini. | Tabel log berhasil dibersihkan dan menyisakan entri log pembersihan. | Berhasil |
| 6 | Mencoba mengakses rute pembersihan log `/admin/logs/clear` sebagai Admin biasa (bukan Superadmin). | Sistem menolak aksi dan memunculkan error status 403 Forbidden. | Proses ditolak oleh middleware otorisasi dan menampilkan pesan dilarang. | Berhasil |

---

## 12. Modul Kelola User & Hak Akses (Akses: `manage_users`)

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Mengisi form tambah admin (Nama, Username, Role, Password) lalu menekan "Simpan". | Sistem mendaftarkan akun admin baru, meng-hash password di database, dan menampilkannya di tabel daftar user. | Akun admin baru berhasil didaftarkan dengan password terenkripsi bcrypt. | Berhasil |
| 2 | Mengubah nama, username, role, atau password user admin pada form edit lalu menekan tombol "Perbarui". | Sistem menyimpan perubahan informasi pengguna tersebut di database dan kembali ke daftar user. | Akun admin berhasil diperbarui dengan sukses di database. | Berhasil |
| 3 | Menghapus centang izin hak akses `manage_gunung` untuk user admin tertentu di form kelola izin lalu menyimpan perubahan. | Sistem memperbarui tabel relasi permission user tersebut di database sehingga user tersebut kehilangan akses ke menu Gunung. | Hak akses diperbarui, dan user admin tersebut tidak dapat mengakses halaman Gunung (Error 403). | Berhasil |
| 4 | Menekan tombol "Hapus" pada akun admin lain. | Sistem menghapus akun admin tersebut dari database dan mencabut hak loginnya. | Akun admin berhasil dihapus dan tidak bisa lagi digunakan untuk login. | Berhasil |
| 5 | Menekan tombol "Hapus" pada akun sendiri yang sedang digunakan untuk masuk. | Sistem menolak aksi penghapusan diri sendiri, mengembalikan ke halaman dengan pesan error "Anda tidak bisa menghapus akun sendiri!". | Penghapusan akun sendiri berhasil ditolak secara aman oleh validasi sistem. | Berhasil |

---

## 13. Modul Pencarian & Rekomendasi MOORA (Sisi Pendaki / Publik)

| No | Skenario Pengujian | Hasil Yang Diharapkan | Hasil Pengujian | Kesimpulan |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Memasukkan input pencarian valid (budget, jumlah anggota, tanggal weekday, terminal asal) lalu menekan "Cari Rekomendasi". | Sistem menyaring alternatif yang total biayanya (tiket bus weekday PP + simaksi weekday kelompok) di bawah budget, lalu menampilkan daftar rekomendasi terurut skor preferensi MOORA tertinggi. | Hasil pencarian memuat alternatif jalur dan armada yang sesuai budget dengan ranking skor MOORA. | Berhasil |
| 2 | Memasukkan input pencarian dengan tanggal weekend (Sabtu/Minggu) lalu menekan "Cari Rekomendasi". | Sistem otomatis mendeteksi hari weekend dan menggunakan skema tarif weekend bus PP + simaksi weekend kelompok untuk menyaring alternatif sesuai budget. | Sistem menggunakan tarif weekend untuk kalkulasi budget dan menghasilkan alternatif teranking MOORA. | Berhasil |
| 3 | Memasukkan input pencarian dengan budget yang terlalu rendah (misal: 10.000). | Sistem tidak menampilkan daftar rute, melainkan menampilkan pesan peringatan: `"Maaf, tidak ada rekomendasi rute pendakian yang sesuai dengan budget kelompok Anda. Cobalah menaikkan budget."` | Pesan peringatan tampil dan tidak ada data alternatif rute pendakian yang lolos saringan. | Berhasil |
| 4 | Menekan tombol "Rincian Biaya" pada salah satu alternatif hasil rekomendasi. | Sistem menampilkan pop-up modal rincian biaya (receipt) yang memuat rincian tiket bus per orang & kelompok, simaksi per orang & kelompok, serta total biaya keseluruhan. | Pop-up modal rincian biaya terbuka dengan rincian biaya yang tepat dan presisi. | Berhasil |
| 5 | Menekan tombol "Cetak Rincian / PDF" pada pop-up modal rincian biaya. | Sistem membuka jendela cetak bawaan browser (*print preview*) dengan layout cetak A4 struk belanja satu lembar yang rapi. | Tampilan print preview terbuka dengan layout struk belanja yang bersih dan siap cetak. | Berhasil |
