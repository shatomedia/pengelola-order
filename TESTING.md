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

## Uji alur transaksi setelah login

`SalesTransactionFlowTest` melakukan POST login dengan akun sintetis, memeriksa
password salah ditolak, lalu login benar. Melalui route aplikasi, test membuka
halaman bisnis, membuat order Rp50.000 (stok turun 2), mencatat DP Rp20.000
(piutang Rp30.000), melunasi order, membuka kedua invoice, mencatat pemasukan
Rp10.000 dan pengeluaran Rp15.000, serta memeriksa laba dashboard Rp45.000.
Penghapusan transaksi diverifikasi pada database dan stok kembali ke nilai awal;
logout menutup akses dashboard. Tidak menggunakan actingAs untuk alur ini.

Suite MariaDB sesudah penambahan test: **16 test, 100 assertion lulus**,
PHP 8.2.29 / PHPUnit 10.4.2. Test memakai request HTTP internal Laravel, bukan
browser; JavaScript, tampilan cetak fisik, dan CSRF browser belum tercakup.
Data dan akun sintetis berada di database sementara yang dibuang runner.

Pemeriksaan HTTPS produksi: /login HTTP 200; /dashboard, /order, /pemasukan,
/pengeluaran, /laporan-keuangan HTTP 302 menuju /login tanpa sesi. Pengguna
mengonfirmasi belum ada akun khusus pengujian produksi; transaksi setelah login
di produksi belum diuji. Tidak membuat akun atau transaksi percobaan produksi.

## CI GitHub Actions

Workflow `.github/workflows/sales-tests.yml` menjalankan suite pada PR menuju main,
push ke main, atau pemicu manual. Runner GitHub ubuntu-24.04 menarik image PHP dan
MariaDB berdasarkan digest yang sama dengan image pengujian lokal, memasang
Composer dependency dari lockfile tanpa plugins/scripts, membangun asset dengan
npm ci dan Vite, lalu menjalankan SQLite dan MariaDB berurutan. Instalasi dan
build memerlukan internet; container PHPUnit hanya memiliki jaringan terisolasi.

Workflow memiliki izin contents:read, checkout tanpa menyimpan kredensial, batas
20 menit, dan membatalkan run lama pada ref yang sama. Tidak memakai secret VPS,
self-hosted runner, atau tahap deployment. Status CI belum menjadi required check
sampai aturan branch diatur terpisah. Versi image dan dependency perlu ditinjau
berkala; digest menjamin runtime konsisten, bukan jaminan bebas kerentanan.

Referensi sintaks: https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-syntax
Referensi checkout: https://github.com/actions/checkout

## Pencegahan tekanan RAM HomeLab

Runner PHPUnit menolak mulai jika MemAvailable kurang dari 1024 MiB (SQLite)
atau 1536 MiB (MariaDB), sebelum menyalin dependency atau membuat container.
Salinan sementara berada di `.test-work/` pada checkout dan filesystem tmpfs/ramfs
ditolak. Folder ini gitignored, isi sementara dibersihkan setelah proses normal.
Ambang adalah guard operasional, bukan jaminan bebas OOM jika workload lain naik.
Browser prepare juga menolak eksekusi di luar GitHub Actions sebelum membuat
fixture. Ini mencegah pengulangan sisa fixture 804 MiB di /tmp Beelink.
Gunakan workflow Sales tests untuk pengujian ketika HomeLab sedang sibuk.
