<?php

namespace WaIntegration\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class WhatsAppInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'waku:install 
                            {--force : Timpa berkas konfigurasi jika sudah ada} 
                            {--with-views : Publikasikan juga file view dashboard ke resources/views/vendor/whatsapp} 
                            {--without-env : Melewatkan penambahan variabel lingkungan ke file .env}';

    /**
     * The command aliases.
     *
     * @var array
     */
    protected $aliases = ['wa:install'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inisialisasi & instalasi otomatis paket WhatsApp Gateway (Waku Dashboard, konfigurasi, & .env)';

    public function handle(): int
    {
        $this->newLine();
        $this->alert('WAKU WhatsApp Gateway — Automated Installer');
        $this->info('Memulai inisialisasi paket dan konfigurasi rute...');

        // 1. Publikasikan Berkas Konfigurasi
        $this->publishConfiguration();

        // 2. Publikasikan Views jika diminta
        if ($this->option('with-views')) {
            $this->publishViews();
        }

        // 3. Tambahkan variabel lingkungan ke .env & .env.example
        if (!$this->option('without-env')) {
            $this->injectEnvironmentVariables(base_path('.env'));
            $this->injectEnvironmentVariables(base_path('.env.example'));
        } else {
            $this->line('  <comment>•</comment> Melewati penyematan variabel lingkungan karena flag <comment>--without-env</comment> aktif.');
        }

        // 4. Bersihkan Cache Laravel
        $this->newLine();
        $this->info('Menyegarkan cache Laravel...');
        try {
            $this->callSilently('config:clear');
            $this->callSilently('route:clear');
            $this->callSilently('view:clear');
            $this->line('  <info>✓</info> Cache konfigurasi, rute, dan tampilan berhasil disegarkan.');
        } catch (\Throwable $e) {
            $this->warn('  Peringatan saat membersihkan cache: ' . $e->getMessage());
        }

        // 5. Informasi Hasil & Panduan Langkah Berikutnya
        $dashboardPrefix = config('whatsapp.dashboard.prefix', 'waku/dashboard');
        $webhookPrefix = config('whatsapp.webhook.prefix', 'waku/api/webhook');

        $appUrl = config('app.url', 'http://localhost');
        if (!preg_match('/^https?:\/\//i', $appUrl)) {
            $appUrl = 'http://' . ltrim($appUrl, '/');
        }
        $appUrl = rtrim($appUrl, '/');

        $dashboardUrl = $appUrl . '/' . ltrim($dashboardPrefix, '/');
        $webhookUrl = $appUrl . '/' . ltrim($webhookPrefix, '/');

        $this->newLine();
        $this->info('----------------------------------------------------------------------');
        $this->info(' <bg=green;fg=black;options=bold> SUKSES </> Paket WhatsApp Gateway (WAKU) Berhasil Terpasang!');
        $this->info('----------------------------------------------------------------------');
        $this->newLine();

        $this->table(
            ['Komponen', 'Status & Lokasi URL'],
            [
                ['Berkas Konfigurasi', config_path('waku.php')],
                ['Panel Manajemen Web', $dashboardUrl],
                ['Endpoint Inbound Webhook', $webhookUrl],
                ['Rute Terisolasi', 'Namespace waku.* (Bebas benturan rute host)'],
                ['Autentikasi Dashboard', 'Wajib Login (Kustomisasi Role di config/waku.php)'],
            ]
        );

        $this->newLine();
        $this->line('<options=bold;fg=yellow>Langkah Selanjutnya:</>');
        $this->line('  1. Buka berkas <comment>.env</comment> dan lengkapi kredensial:');
        $this->line('     <fg=gray>WAKU_APP_ID=</><fg=cyan>wai_app_your_application_id</>');
        $this->line('     <fg=gray>WAKU_API_KEY=</><fg=cyan>wai_live_your_secret_api_key</>');
        $this->newLine();
        $this->line('  2. Sesuaikan hak akses role/user jika diperlukan di <comment>config/waku.php</comment>:');
        $this->line('     <fg=gray>\'allowed_roles\' => [\'SUPER-ADMIN\', \'ADMIN\'],</>');
        $this->newLine();
        $this->line('  3. Buka browser Anda dan akses Panel Manajemen Waku:');
        $this->line("     <options=bold;fg=green>{$dashboardUrl}</>");
        $this->line('     (Pindai QR Code live multi-device untuk menghubungkan nomor WhatsApp Anda)');
        $this->newLine();

        return Command::SUCCESS;
    }

    /**
     * Mempublikasikan berkas config/waku.php.
     */
    protected function publishConfiguration(): void
    {
        $params = ['--tag' => 'waku-config'];
        if ($this->option('force')) {
            $params['--force'] = true;
        }

        $this->callSilently('vendor:publish', $params);
        $this->line('  <info>✓</info> Berkas konfigurasi <comment>config/waku.php</comment> berhasil dipublikasikan.');
    }

    /**
     * Mempublikasikan berkas view dashboard jika diminta.
     */
    protected function publishViews(): void
    {
        $params = ['--tag' => 'waku-views'];
        if ($this->option('force')) {
            $params['--force'] = true;
        }

        $this->callSilently('vendor:publish', $params);
        $this->line('  <info>✓</info> Tampilan dashboard Blade berhasil disalin ke <comment>resources/views/vendor/waku/</comment>.');
    }

    /**
     * Menyematkan blok variabel lingkungan WAKU_* ke berkas .env.
     */
    protected function injectEnvironmentVariables(string $filePath): void
    {
        if (!File::exists($filePath)) {
            return;
        }

        $content = File::get($filePath);

        // Cek apakah variabel WAKU_ atau WHATSAPP_ sudah terdaftar
        if (str_contains($content, 'WAKU_BASE_URL') || str_contains($content, 'WHATSAPP_BASE_URL')) {
            $this->line('  <comment>•</comment> Variabel Waku/WhatsApp sudah ada pada berkas <comment>' . basename($filePath) . '</comment> (dilewati).');
            return;
        }

        $envBlock = <<<ENV

# ==============================================================================
# WAKU — PlatformCore WhatsApp Gateway Configuration (config/waku.php)
# ==============================================================================
WAKU_BASE_URL=http://localhost:3001/api/v1
WAKU_APP_ID=
WAKU_API_KEY=
WAKU_WEBHOOK_SECRET=

# Pengaturan Rute Unik & Akses Dashboard
WAKU_DASHBOARD_ENABLED=true
WAKU_DASHBOARD_PREFIX=waku/dashboard
WAKU_WEBHOOK_PREFIX=waku/api/webhook
WAKU_AUTH_REQUIRED=true
ENV;

        File::append($filePath, $envBlock . "\n");
        $this->line('  <info>✓</info> Blok variabel konfigurasi Waku berhasil disematkan ke <comment>' . basename($filePath) . '</comment>.');
    }
}
