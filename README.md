# WhatsApp Gateway Package for Laravel
## `xdiego1st/wa-integration`

[![Latest Version on Packagist](https://img.shields.io/packagist/v/xdiego1st/wa-integration.svg?style=flat-square)](https://packagist.org/packages/xdiego1st/wa-integration)
[![Total Downloads](https://img.shields.io/packagist/dt/xdiego1st/wa-integration.svg?style=flat-square)](https://packagist.org/packages/xdiego1st/wa-integration)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Paket Laravel resmi untuk menghubungkan aplikasi Anda (Blade, Livewire, Inertia, Filament) ke **PlatformCore WhatsApp Integration Gateway**. 

Begitu di-install, aplikasi Anda **langsung mendapatkan halaman manajemen WhatsApp lengkap (`/whatsapp`)** untuk scan QR Multi-Device, uji kirim pesan, serta petunjuk teknis integrasi API interaktif.

---

## Fitur Utama

- **Halaman Manajemen Bawaan (`/waku/dashboard`)**: Dashboard visual instan untuk scan QR Code live (polling real-time), cek status koneksi, dan uji kirim pesan tanpa perlu menulis kode UI. Rute terisolasi bebas benturan dengan rute host.
- **Expressive Facade**: Kirim pesan dalam 1 baris kode: `WhatsApp::send('08123456789', 'Halo!');`.
- **Dukungan Media Lengkap**: Kirim file faktur PDF (`sendDocument`), foto bukti pembayaran (`sendImage`), dan media lainnya.
- **Isolasi Multi-Tenant**: Setiap aplikasi konsumen memiliki kredensial API Key dan sesi Baileys terisolasi.
- **Webhook HMAC-SHA256**: Menerima pesan masuk dan tanda terima status delivery secara otomatis di `/waku/api/webhook`.
- **Artisan CLI Otomatis**: Inisialisasi satu langkah (`php artisan waku:install`), monitor status (`php artisan waku:status`), uji kirim pesan (`php artisan waku:test-send`), dan uninstaller bersih (`php artisan waku:uninstall`).
- **Kompatibilitas Penuh**: Laravel 10, 11, 12, dan 13 (PHP 8.1 - 8.5).

---

## 1. Instalasi Otomatis (Satu Langkah)

Jalankan perintah Composer di root proyek Laravel Anda:

```bash
composer require xdiego1st/wa-integration
```

Lalu jalankan installer otomatis:
```bash
php artisan waku:install
```

Perintah `php artisan waku:install` akan secara otomatis:
1. Mempublikasikan berkas konfigurasi `config/whatsapp.php`.
2. Menyematkan variabel lingkungan `WHATSAPP_*` langsung ke dalam berkas `.env` dan `.env.example`.
3. Mendaftarkan rute dashboard unik terisolasi (`/waku/dashboard`) dan webhook (`/waku/api/webhook`).
4. Menyegarkan seluruh cache aplikasi Laravel (`config:clear`, `route:clear`, `view:clear`).

*(Opsi): Tambahkan flag `--with-views` jika ingin menyalin view dashboard ke `resources/views/vendor/whatsapp/`.*

---

## 2. Lengkapi Kredensial Environment (`.env`)

Buka berkas `.env` di proyek Laravel Anda dan isi kredensial yang telah disematkan otomatis:

```ini
WHATSAPP_BASE_URL=http://localhost:3001/api/v1
WHATSAPP_APP_ID=wai_app_your_application_id
WHATSAPP_API_KEY=wai_live_your_secret_api_key
WHATSAPP_WEBHOOK_SECRET=wai_sec_your_webhook_secret

# Pengaturan Rute Unik (Mencegah Benturan dengan Rute Aplikasi Host)
WHATSAPP_DASHBOARD_ENABLED=true
WHATSAPP_DASHBOARD_PREFIX=waku/dashboard
WHATSAPP_WEBHOOK_PREFIX=waku/api/webhook
```

> **Tips:** Kredensial `WHATSAPP_APP_ID` dan `WHATSAPP_API_KEY` diperoleh dari menu **Multi-Application Hub** pada Gateway PlatformCore.

---

## 3. Halaman Manajemen WhatsApp Langsung (`/waku/dashboard`)

Setelah konfigurasi di atas selesai, buka browser Anda dan akses:

👉 **`http://aplikasi-anda.test/waku/dashboard`**

Anda akan langsung melihat:
1. **Status Koneksi & Live QR Code Scanner**: Scan QR code langsung dari ponsel untuk menghubungkan nomor WhatsApp.
2. **Kirim Pesan Tes (Sandbox)**: Form pengujian kirim pesan teks, gambar, dan dokumen dengan feedback diagnostik langsung.
3. **Riwayat Pesan Terkini**: Tabel log status pengiriman pesan (`SENT`, `DELIVERED`, `READ`).
4. **Petunjuk Teknis API & Code Snippets**: Contoh kode lengkap siap pakai.

---

## 4. Penggunaan dalam Kode Laravel

### A. Mengirim Pesan Teks
```php
use WaIntegration\Laravel\Facades\WhatsApp;

// Mengirim pesan teks sederhana
$response = WhatsApp::send('08123456789', 'Halo! Pesanan #1042 Anda sedang dikirimkan.');

if ($response->isSuccessful()) {
    $messageId = $response->messageId();
} else {
    logger()->error('Gagal kirim pesan WA: ' . $response->error());
}
```

### B. Mengirim Gambar & Dokumen PDF
```php
use WaIntegration\Laravel\Facades\WhatsApp;

// Kirim invoice PDF
WhatsApp::sendDocument(
    to: '08123456789',
    documentUrl: 'https://domain-anda.com/invoices/INV-2026.pdf',
    caption: 'Berikut faktur tagihan Anda.'
);

// Kirim gambar
WhatsApp::sendImage(
    to: '08123456789',
    imageUrl: 'https://domain-anda.com/storage/resi.jpg',
    caption: 'Foto resi pengiriman barang.'
);
```

### C. Menerima Webhook Pesan Masuk
Endpoint webhook otomatis aktif di `/api/whatsapp/webhook`. Dengarkan event di `EventServiceProvider` atau service provider Anda:

```php
use Illuminate\Support\Facades\Event;

Event::listen('whatsapp.message.received', function ($data, $appId) {
    $pengirim = $data['from'];     // e.g. 628123456789
    $pesan    = $data['content'];  // Isi pesan chat
    
    // Logika balasan otomatis (auto-reply / chatbot)
});
```

### D. Perintah Terminal (Artisan)
```bash
# Cek status koneksi sesi
php artisan wa:status

# Uji coba kirim pesan
php artisan wa:test-send 08123456789 "Tes pesan dari terminal Laravel"

# Pencopotan paket bersih (Clean Uninstall)
php artisan wa:uninstall --force
```

---

## 5. Cara Upgrade / Pembaruan Versi

Untuk memperbarui pustaka ke versi stabil terbaru tanpa downtime:

```bash
# 1. Update paket via Composer
composer update xdiego1st/wa-integration

# 2. Sinkronkan tampilan dashboard terbaru (jika view dipublikasikan)
php artisan vendor:publish --tag=whatsapp-views --force

# 3. Bersihkan seluruh cache aplikasi Laravel
php artisan optimize:clear
```

---

## 6. Panduan Uninstall Bersih (Zero-Residue Clean Uninstall)

Jika Anda perlu mencopot paket ini dari aplikasi Laravel, ikuti panduan berikut agar seluruh berkas konfigurasi, view vendor, cache, dan variabel lingkungan dibersihkan secara total tanpa meninggalkan file sampah.

### Metode A: Otomatis via Artisan Command (Direkomendasikan)
```bash
# 1. Bersihkan seluruh konfigurasi, views, dan variabel lingkungan
php artisan wa:uninstall --force

# 2. Copot pustaka dari Composer
composer remove xdiego1st/wa-integration
```

*Perintah `php artisan wa:uninstall` akan menghapus `config/whatsapp.php`, menghapus direktori `resources/views/vendor/whatsapp`, membersihkan baris `WHATSAPP_*` di `.env`, dan mengosongkan cache Laravel.*

### Metode B: Manual Step-by-Step
```bash
# 1. Hapus konfigurasi
rm config/whatsapp.php

# 2. Hapus views terpublikasi (jika ada)
rm -rf resources/views/vendor/whatsapp

# 3. Bersihkan cache Laravel
php artisan optimize:clear

# 4. Hapus paket dari composer
composer remove xdiego1st/wa-integration
```

---

## 7. Cara Mendaftarkan Package Ini ke Packagist.org

Agar package ini dapat di-install oleh publik atau tim Anda melalui `composer require`, ikuti 4 langkah mudah berikut:

### Langkah 1: Push ke Repository GitHub Anda
Repository GitHub: `https://github.com/xDiego1st/wa-integration.git`
```bash
git remote set-url origin https://github.com/xDiego1st/wa-integration.git
git push -u origin main --force
```

### Langkah 2: Buat Tag Versi Release
Buat tag rilis awal di repository GitHub Anda:
```bash
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0
```

### Langkah 3: Submit ke Packagist.org
1. Buka [Packagist.org](https://packagist.org) dan login menggunakan akun GitHub Anda (`xDiego1st`).
2. Klik tombol **Submit** di menu atas.
3. Masukkan URL repository GitHub Anda:
   `https://github.com/xDiego1st/wa-integration`
4. Klik **Check**, lalu klik **Submit**. Package Anda kini resmi terdaftar di Packagist!

### Langkah 4: Otomatisasi Update (GitHub Webhook)
Agar Packagist otomatis memperbarui package setiap kali Anda melakukan push tag baru:
1. Di halaman package Packagist Anda, buka petunjuk **GitHub Service Hook / Webhook**.
2. Masuk ke GitHub Repo &rarr; **Settings** &rarr; **Webhooks** &rarr; **Add Webhook**.
3. Masukkan Payload URL dari Packagist: `https://packagist.org/api/github?username=xDiego1st`.

Sekarang seluruh proyek Laravel di mana pun dapat meng-install package Anda cukup dengan:
```bash
composer require xdiego1st/wa-integration
```

---

## Lisensi
Didistribusikan di bawah lisensi MIT. Silakan gunakan secara bebas pada proyek komersial maupun pribadi.
