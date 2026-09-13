# Pengelola Order Fresh

Laravel 10, PHP ^8.1, Vite; aplikasi order, inventori, invoice, laporan dan log aktivitas.

## Struktur dan perhatian khusus

- Logika di app/, route di routes/, UI di resources/, test di tests/.
- Test fitur tersedia untuk invoice, log aktivitas, pembuatan order, pengembalian stok saat penghapusan order, dan piutang dashboard.
- Jika mengubah order/stok/pembayaran, periksa transaksi database dan konsistensi nilai; pilih test yang relevan setelah database testing dipastikan terisolasi.
- phpunit.xml belum mengaktifkan SQLite in-memory. Vendor dan node_modules belum tersedia saat audit.
- npm menyediakan dev dan build. Jangan menyalin seluruh repo ini ke sales-shatomedia tanpa membandingkan versi dan jalur deployment.
- backup/ dan update.zip adalah arsip, bukan sumber utama perubahan.

## Alur kerja

- Baca AGENTS.md workspace induk dan instruksi ini sebelum mengubah file.
- Cek `git status --short` dan branch saat ini pada repo ini. Pertahankan perubahan pengguna; jangan reset, clean, stash, atau commit massal otomatis.
- Untuk pekerjaan kode baru, gunakan branch tugas setelah memeriksa kondisi repo. Jangan mencampur perubahan di repo lain.
- Baca hanya konfigurasi yang diperlukan. Jangan menampilkan .env, kredensial, token, private key, atau dump database.
- Batasi perubahan pada tugas. Jangan mengubah vendor, node_modules, arsip, upload, atau storage aplikasi sebagai pengganti perbaikan source.

## Verifikasi

- Perubahan PHP: `php -l path/file.php` pada file yang berubah (tidak mengeksekusi aplikasi).
- Perubahan frontend: `npm run build` dari akar aplikasi bila dependency tersedia; satu build pada satu waktu di Beelink.
- Sebelum `php artisan test` atau PHPUnit, pastikan database testing terisolasi dan config cache tidak menunjuk produksi. APP_ENV=testing saja tidak menjamin isolasi database.
- Jangan menjalankan migrate, migrate:fresh, seed, queue worker, atau Composer lifecycle scripts terhadap lingkungan produksi sebagai pemeriksaan rutin.
- Jangan mengganti .env atau APP_KEY untuk membuat test lolos. Jika dependency/lingkungan uji belum siap, laporkan batas verifikasinya.
- Deployment hanya pada cakupan yang diminta pengguna, setelah perubahan dan hasil test dapat ditinjau.

## Runner terisolasi

Untuk test bisnis gunakan `python3 tools/test-isolated.py` dari repo ini; lihat
TESTING.md. Runner menyalin source ke container offline dengan SQLite in-memory.
Konfigurasi phpunit.xml lama tetap belum menjamin isolasi; gunakan runner, bukan
menjalankan artisan test langsung pada lingkungan yang belum diverifikasi.
