# Pengujian terisolasi untuk AI coding

Jalankan dari workspace Shatomedia:

```bash
python3 pengelola-order-fresh/tools/test-isolated.py
python3 pengelola-order-fresh/tools/test-isolated.py --filter OrderCreationTest
```

Runner memerlukan Docker dan image lokal nextcloud:29-apache (runtime PHP 8.2
beserta SQLite). Tag diselesaikan ke image ID sebelum run; tidak menarik image
atau mengubah container Nextcloud yang sedang melayani pengguna.

Dependency default berasal dari salinan vendor Sales, tetapi semua versi pada
composer.lock (termasuk development) harus cocok. Gunakan --vendor untuk salinan
dependency lain yang cocok. Runner tidak menjalankan composer update/install
atau mengubah vendor sumber.

Setiap run menyalin source terpilih dan dependency ke direktori sementara.
.env, config cache, database dump dan storage produksi tidak disalin. Container
hanya mendapat mount direktori sementara tersebut, bukan repo asli atau socket
Docker. Jaringan nonaktif, root filesystem read-only, kemampuan Linux dibuang,
CPU dibatasi 1 dan RAM 1 GiB. SQLite :memory: dihapus bersama proses test.

phpunit.isolated.xml terpisah dari phpunit.xml lama. Bootstrap menolak lingkungan
selain testing/SQLite in-memory, .env dan cached config. APP_KEY pada konfigurasi
ini hanya konstanta untuk test; bukan kunci produksi.

Gunakan tests/Feature untuk invoice, aktivitas, stok/order dan piutang. Kegagalan
assertion tidak boleh disembunyikan dengan menonaktifkan test. SQLite tidak
sepenuhnya meniru perilaku MySQL: sebelum deployment perubahan SQL/transaksi,
tambahkan verifikasi pada MySQL terisolasi jika diperlukan.

Cakupan runner: source pengelola-order-fresh. Ini tidak membuktikan salinan
sales-shatomedia/pengelola-order identik atau siap deployment. Jangan menyalin
seluruh aplikasi Fresh ke Sales tanpa membandingkan perubahan terlebih dahulu.

## Hasil awal — 14 September 2026

Suite penuh: **12 test, 29 assertion, semuanya lulus** pada PHP 8.2.29 /
PHPUnit 10.4.2. Bootstrap juga diuji menolak APP_ENV=production / koneksi MySQL
sebelum memuat aplikasi. PHP lint dan git diff --check lulus.

Dua penyesuaian portabilitas source dilakukan agar test nyata dapat berjalan:
- PermissionSeeder memakai Schema::withoutForeignKeyConstraints alih-alih
  SET FOREIGN_KEY_CHECKS khusus MySQL.
- Agregasi piutang memakai CASE yang setara dengan GREATEST(..., 0), termasuk
  tetap mengabaikan nilai NULL pada SUM. Test kelebihan pembayaran tetap lulus.

Perubahan berada di branch chore/isolated-ai-tests dan belum di-deploy.
Suite ini bukan bukti seluruh perilaku MySQL atau seluruh fitur bisnis tercakup.
