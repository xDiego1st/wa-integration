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

        $dashboardUrl = url($dashboardPrefix);
        $webhookUrl = url($webhookPrefix);

        $this->newLine();
        $this->info('----------------------------------------------------------------------');
        $this->info(' <bg=green;fg=black;options=bold> SUKSES </> Paket WhatsApp Gateway (WAKU) Berhasil Terpasang!');
        $this->info('----------------------------------------------------------------------');
        $this->newLine();

        $this->table(
            ['Komponen', 'Status & Lokasi URL'],
            [
                ['Berkas Konfigurasi', config_path('whatsapp.php')],
                ['Panel Manajemen Web', $dashboardUrl],
                ['Endpoint Inbound Webhook', $webhookUrl],
                ['Rute Terisolasi', 'Namespace waku.* (Bebas benturan rute host)'],
            ]
        );

        $this->newLine();
        $this->line('<options=bold;fg=yellow>Langkah Selanjutnya:</>');
        $this->line('  1. Buka berkas <comment>.env</comment> dan lengkapi kredensial:');
        $this->line('     <fg=gray>WHATSAPP_APP_ID=</><fg=cyan>wai_app_your_application_id</>');
        $this->line('     <fg=gray>WHATSAPP_API_KEY=</><fg=cyan>wai_live_your_secret_api_key</>');
        $this->newLine();
        $this->line('  2. Buka browser Anda dan akses Panel Manajemen Waku:');
        $this->line("     <options=bold;fg=green>{$dashboardUrl}</>");
        $this->line('     (Pindai QR Code live multi-device untuk menghubungkan nomor WhatsApp Anda)');
        $this->newLine();

        return Command::SUCCESS;
    }

    /**
     * Mempublikasikan berkas config/whatsapp.php.
     */
    protected function publishConfiguration(): void
    {
        $params = ['--tag' => 'whatsapp-config'];
        if ($this->option('force')) {
            $params['--force'] = true;
        }

        $this->callSilently('vendor:publish', $params);
        $this->line('  <info>✓</info> Berkas konfigurasi <comment>config/whatsapp.php</comment> berhasil dipublikasikan.');
    }

    /**
     * Mempublikasikan berkas view dashboard jika diminta.
     */
    protected function publishViews(): void
    {
        $params = ['--tag' => 'whatsapp-views'];
        if ($this->option('force')) {
            $params['--force'] = true;
        }

        $this->callSilently('vendor:publish', $params);
        $this->line('  <info>✓</info> Tampilan dashboard Blade berhasil disalin ke <comment>resources/views/vendor/whatsapp/</comment>.');
    }

    /**
     * Menyematkan blok variabel lingkungan WHATSAPP_* ke berkas .env.
     */
    protected function injectEnvironmentVariables(string $filePath): void
    {
        if (!File::exists($filePath)) {
            return;
        }

        $content = File::get($filePath);

        // Cek apakah variabel WHATSAPP_ sudah terdaftar
        if (str_contains($content, 'WHATSAPP_BASE_URL') || str_contains($content, 'WHATSAPP_APP_ID')) {
            $this->line('  <comment>•</comment> Variabel WhatsApp sudah ada pada berkas <comment>' . basename($filePath) . '</comment> (dilewati).');
            return;
        }

        $envBlock = <<<ENV

# ==============================================================================
# WAKU — PlatformCore WhatsApp Gateway Configuration
# ==============================================================================
WHATSAPP_BASE_URL=http://localhost:3001/api/v1
WHATSAPP_APP_ID=
WHATSAPP_API_KEY=
WHATSAPP_WEBHOOK_SECRET=

# Pengaturan Rute Unik (Mencegah Benturan dengan Rute Aplikasi Host)
WHATSAPP_DASHBOARD_ENABLED=true
WHATSAPP_DASHBOARD_PREFIX=waku/dashboard
WHATSAPP_WEBHOOK_PREFIX=waku/api/webhook
ENV;

        File::append($filePath, $envBlock . "\n");
        $this->line('  <info>✓</info> Blok variabel konfigurasi WhatsApp berhasil disematkan ke <comment>' . basename($filePath) . '</comment>.');
    }
}
