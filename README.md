# WhatsApp Gateway Package for Laravel
## `wa-integration/laravel`

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wa-integration/laravel.svg?style=flat-square)](https://packagist.org/packages/wa-integration/laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/wa-integration/laravel.svg?style=flat-square)](https://packagist.org/packages/wa-integration/laravel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Paket Laravel resmi untuk menghubungkan aplikasi Anda (Blade, Livewire, Inertia, Filament) ke **PlatformCore WhatsApp Integration Gateway**. 

Begitu di-install, aplikasi Anda **langsung mendapatkan halaman manajemen WhatsApp lengkap (`/whatsapp`)** untuk scan QR Multi-Device, uji kirim pesan, serta petunjuk teknis integrasi API interaktif.

---

## Fitur Utama

- **Halaman Manajemen Bawaan (`/whatsapp`)**: Dashboard visual instan untuk scan QR Code live (polling real-time), cek status koneksi, dan uji kirim pesan tanpa perlu menulis kode UI.
- **Expressive Facade**: Kirim pesan dalam 1 baris kode: `WhatsApp::send('08123456789', 'Halo!');`.
- **Dukungan Media Lengkap**: Kirim file faktur PDF (`sendDocument`), foto bukti pembayaran (`sendImage`), dan media lainnya.
- **Isolasi Multi-Tenant**: Setiap aplikasi konsumen memiliki kredensial API Key dan sesi Baileys terisolasi.
- **Webhook HMAC-SHA256**: Menerima pesan masuk dan tanda terima status delivery secara otomatis dengan verifikasi keamanan tanda tangan.
- **Artisan CLI**: Monitor status dan uji kirim pesan langsung dari terminal (`php artisan wa:status`, `php artisan wa:test-send`).
- **Kompatibilitas**: Laravel 10, 11, dan 12 (PHP 8.1 - 8.4).

---

## 1. Instalasi

Jalankan perintah Composer di root proyek Laravel Anda:

```bash
composer require wa-integration/laravel
```

Publikasikan file konfigurasi:
```bash
php artisan vendor:publish --tag=whatsapp-config
```

(Opsional) Publikasikan view jika ingin memodifikasi tampilan dashboard bawaan:
```bash
php artisan vendor:publish --tag=whatsapp-views
```

---

## 2. Konfigurasi Environment (`.env`)

Buka file `.env` di proyek Laravel Anda dan tambahkan kredensial gateway:

```ini
WHATSAPP_BASE_URL=http://localhost:3001/api/v1
WHATSAPP_APP_ID=wai_app_your_application_id
WHATSAPP_API_KEY=wai_live_your_secret_api_key
WHATSAPP_WEBHOOK_SECRET=wai_sec_your_webhook_secret

# Opsional: Pengaturan Dashboard Bawaan
WHATSAPP_DASHBOARD_ENABLED=true
WHATSAPP_DASHBOARD_PREFIX=whatsapp
```

> **Tips:** Kredensial `WHATSAPP_APP_ID` dan `WHATSAPP_API_KEY` diperoleh dari menu **Multi-Application Hub** pada Gateway PlatformCore.

---

## 3. Halaman Manajemen WhatsApp Langsung (`/whatsapp`)

Setelah konfigurasi di atas selesai, buka browser Anda dan akses:

👉 **`http://aplikasi-anda.test/whatsapp`**

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
```

---

## 5. Cara Mendaftarkan Package Ini ke Packagist.org

Agar package ini dapat di-install oleh publik atau tim Anda melalui `composer require`, ikuti 4 langkah mudah berikut:

### Langkah 1: Buat Repository GitHub Publik
1. Masuk ke GitHub dan buat repository baru, misalnya:
   `https://github.com/username-anda/wa-integration-laravel`
2. Push isi folder `packages/laravel-wa-integration` ke repository tersebut:
   ```bash
   cd packages/laravel-wa-integration
   git init
   git add .
   git commit -m "feat: initial release of WhatsApp Laravel Gateway package"
   git branch -M main
   git remote add origin https://github.com/username-anda/wa-integration-laravel.git
   git push -u origin main
   ```

### Langkah 2: Buat Tag Versi Release
Buat tag rilis awal di repository GitHub Anda:
```bash
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0
```

### Langkah 3: Submit ke Packagist.org
1. Buka [Packagist.org](https://packagist.org) dan login menggunakan akun GitHub Anda.
2. Klik tombol **Submit** di menu atas.
3. Masukkan URL repository GitHub Anda:
   `https://github.com/username-anda/wa-integration-laravel`
4. Klik **Check**, lalu klik **Submit**. Package Anda kini terdaftar di Packagist!

### Langkah 4: Otomatisasi Update (GitHub Webhook)
Agar Packagist otomatis memperbarui package setiap kali Anda melakukan push tag baru:
1. Di halaman package Packagist Anda, buka petunjuk **GitHub Service Hook / Webhook**.
2. Masuk ke GitHub Repo &rarr; **Settings** &rarr; **Webhooks** &rarr; **Add Webhook**.
3. Masukkan Payload URL dari Packagist: `https://packagist.org/api/github?username=USERNAME_ANDA`.

Sekarang seluruh proyek Laravel di mana pun dapat meng-install package Anda cukup dengan:
```bash
composer require wa-integration/laravel
```

---

## Lisensi
Didistribusikan di bawah lisensi MIT. Silakan gunakan secara bebas pada proyek komersial maupun pribadi.
