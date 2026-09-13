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

SQLite tidak membuktikan seluruh perilaku MySQL. Suite yang tersedia juga belum
mencakup seluruh fitur keuangan tambahan. Tidak ada deployment otomatis.

## Hasil 14 September 2026

Suite Sales lulus: 12 test, 29 assertion (PHP 8.2.29, PHPUnit 10.4.2).
Enam dependency PDF yang tidak ada pada vendor aplikasi berhasil dipasang ke
.test-deps/vendor sesuai lockfile. Vendor aplikasi asli tidak berubah.
Pemeriksaan diff membuktikan dashboard Sales identik dengan HEAD kecuali
penggantian ekspresi piutang. Seluruh fitur keuangan tambahannya dipertahankan.
