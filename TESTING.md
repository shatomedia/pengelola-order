# Test Sales tanpa database produksi

Aplikasi ini adalah repo Git tersendiri di dalam wrapper sales-shatomedia.
Lakukan git status/branch/commit dari folder aplikasi ini untuk perubahan PHP.

## Persiapan

```bash
bash tools/prepare-test-deps.sh
python3 tools/test-isolated.py
python3 tools/test-isolated.py --filter DashboardPiutangTest
```

Persiapan memerlukan internet dan Docker; memakai image lokal nextcloud:29-apache
sebagai runtime PHP 8.2, bukan container Nextcloud produksi. Composer diverifikasi
checksum, menginstal versi composer.lock dengan dependency development tanpa
plugins atau lifecycle scripts ke .test-deps/ (gitignored). Vendor aplikasi
asli tidak disentuh. Siapkan satu pekerjaan build/test pada satu waktu.

Runner memverifikasi versi dependency, menyalin source dan vendor ke lokasi
sementara, lalu menjalankan PHPUnit dalam container tanpa jaringan. Hanya salinan
sementara yang dipasang; tidak ada mount database, storage produksi atau socket
Docker. CPU dibatasi 1, RAM 1 GiB, root filesystem read-only. SQLite :memory:
dan semua data hasil test hilang setelah proses berakhir.

phpunit.isolated.xml/ bootstrap test menolak .env, cached config dan konfigurasi
non-testing. phpunit.xml lama bukan jaminan isolasi. Jangan jalankan migrate:fresh
atau test langsung dengan konfigurasi produksi.

## Perbedaan dengan Fresh

Dashboard Sales memiliki pemasukan/pengeluaran, tren keuangan, pengeluaran
berulang, serta daftar piutang yang tidak identik dengan Fresh. Jangan menyalin
controller Fresh secara utuh. Patch portabilitas hanya mengganti ekspresi SQL
piutang dengan CASE setara dan operasi foreign key seeder dengan Schema Laravel.

SQLite tidak membuktikan seluruh perilaku MySQL. Test keuangan tambahan dan mode
MariaDB tersedia di bawah; status verifikasi terbaru dicatat terpisah. Tidak ada deployment otomatis.

## Hasil 14 September 2026

Suite Sales lulus: 12 test, 29 assertion (PHP 8.2.29, PHPUnit 10.4.2).
Enam dependency PDF yang tidak ada pada vendor aplikasi berhasil dipasang ke
.test-deps/vendor sesuai lockfile. Vendor aplikasi asli tidak berubah.
Pemeriksaan diff membuktikan dashboard Sales identik dengan HEAD kecuali
penggantian ekspresi piutang. Seluruh fitur keuangan tambahannya dipertahankan.

## Pengujian keuangan dan MariaDB

```bash
python3 tools/test-isolated.py --filter DashboardFinanceTest
python3 tools/test-isolated.py --database mariadb
```

Mode MariaDB memerlukan image lokal `mariadb:10.11` (dapat dipilih dengan
`--database-image`). Ini menguji driver MySQL terhadap MariaDB, bukan klaim
kesamaan versi dengan database produksi. Runner tidak menarik image otomatis.
Container database memakai network `none`; container PHP berbagi namespace
jaringan database dan mengaksesnya melalui loopback. Tidak ada port yang
dipublikasikan atau jalur ke jaringan produksi. Data database berada di tmpfs
256 MiB; RAM database dibatasi 512 MiB dan PHP 1 GiB. Container database dan
volume anonim dibuang dalam blok finally, termasuk ketika test gagal.
Jika runner dihentikan paksa dengan SIGKILL atau host mati, periksa container
berawalan `sales-test-db-` sebelum menjalankan ulang. Jangan hapus container lain.

Data uji sintetis mencakup pemasukan berdasarkan jumlah dibayar (sesuai perilaku
aplikasi saat ini: dikelompokkan menurut tanggal order), pengecualian pengeluaran
draft, batas tahun/bulan, laba negatif, tren enam bulan pada akhir bulan,
dan status pengeluaran berulang serta kelebihan anggaran.
Test ini belum mencakup seluruh alur CRUD, otorisasi, atau pencatatan pembayaran
lintas bulan. Gunakan runner, bukan PHPUnit langsung dengan konfigurasi lokal.

### Status perubahan lokal

Ditambahkan tiga test keuangan. Ditemukan dan diperbaiki pengulangan bulan pada
kedua grafik dashboard: tanggal acuan dinormalisasi ke awal bulan sebelum
mengurangi bulan. Reproduksi Carbon pada 31 Maret 2026 sebelumnya menghasilkan
Oktober, Desember, Desember, Januari, Maret, Maret; setelah perbaikan menghasilkan
Oktober sampai Maret berurutan. Lint PHP dan kompilasi sintaks runner Python lulus.
Suite SQLite terbaru lulus: **15 test, 40 assertion**, PHP 8.2.29 / PHPUnit 10.4.2.
Ini mencakup tiga test keuangan baru dan perbaikan grafik akhir bulan.
Suite MariaDB juga lulus: **15 test, 40 assertion**, PHP 8.2.29 / PHPUnit 10.4.2,
menggunakan image lokal mariadb:10.11 dan driver MySQL. Runner selesai dengan
exit code 0, termasuk langkah pembersihan container database sementara.
Pemeriksaan konektivitas saat dilanjutkan berhasil: router 192.168.1.1:80,
IP publik 1.1.1.1:443 dan 8.8.8.8:443, serta resolusi DNS example.com.
Hasil ini adalah pemeriksaan sesaat, bukan bukti kestabilan jaringan jangka panjang.
Hasil 12 test di atas merupakan catatan suite sebelumnya.
Perubahan ini belum dipublikasikan atau dideploy.
