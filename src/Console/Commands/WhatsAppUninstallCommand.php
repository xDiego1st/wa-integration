<?php

namespace WaIntegration\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class WhatsAppUninstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wa:uninstall 
                            {--force : Jalankan pencopotan langsung tanpa konfirmasi interaktif} 
                            {--keep-env : Pertahankan variabel lingkungan WHATSAPP_* di berkas .env}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mencopot seluruh jejak paket WhatsApp Gateway dari aplikasi Laravel secara bersih (Clean Uninstall)';

    public function handle(): int
    {
        $this->newLine();
        $this->alert('WhatsApp Gateway Laravel Package — Clean Uninstaller');

        if (!$this->option('force')) {
            $confirmed = $this->confirm(
                'Apakah Anda yakin ingin mencopot seluruh berkas konfigurasi, view, dan konfigurasi paket WhatsApp Gateway ini?',
                false
            );

            if (!$confirmed) {
                $this->warn('Operasi pencopotan dibatalkan oleh pengguna.');
                return Command::SUCCESS;
            }
        }

        $this->info('Memulai pembersihan jejak paket...');

        // 1. Hapus Berkas Konfigurasi
        $configPath = config_path('whatsapp.php');
        if (File::exists($configPath)) {
            File::delete($configPath);
            $this->line('  <info>✓</info> Berkas konfigurasi <comment>config/whatsapp.php</comment> berhasil dihapus.');
        } else {
            $this->line('  <comment>•</comment> Berkas <comment>config/whatsapp.php</comment> tidak ditemukan (dilewati).');
        }

        // 2. Hapus Views Vendor
        $viewsPath = resource_path('views/vendor/whatsapp');
        if (File::isDirectory($viewsPath)) {
            File::deleteDirectory($viewsPath);
            $this->line('  <info>✓</info> Direktori view <comment>resources/views/vendor/whatsapp</comment> berhasil dihapus.');
        } else {
            $this->line('  <comment>•</comment> Direktori view kustom <comment>resources/views/vendor/whatsapp</comment> tidak ditemukan.');
        }

        // 3. Bersihkan Variabel .env (kecuali --keep-env)
        if (!$this->option('keep-env')) {
            $this->cleanEnvFile(base_path('.env'));
            $this->cleanEnvFile(base_path('.env.example'));
        } else {
            $this->line('  <comment>•</comment> Melewati pembersihan .env karena flag <comment>--keep-env</comment> aktif.');
        }

        // 4. Bersihkan Cache Laravel
        $this->newLine();
        $this->info('Membersihkan cache konfigurasi, rute, dan tampilan...');
        try {
            $this->callSilently('config:clear');
            $this->callSilently('route:clear');
            $this->callSilently('view:clear');
            $this->line('  <info>✓</info> Cache Laravel berhasil dibersihkan.');
        } catch (\Throwable $e) {
            $this->warn('  Gagal membersihkan sebagian cache: ' . $e->getMessage());
        }

        // 5. Ringkasan & Langkah Akhir
        $this->newLine();
        $this->info('----------------------------------------------------------------------');
        $this->info(' <bg=green;fg=black;options=bold> SUKSES </> Seluruh aset & residu paket telah dibersihkan secara total.');
        $this->info('----------------------------------------------------------------------');
        $this->newLine();
        $this->line('Langkah terakhir, jalankan perintah Composer berikut untuk mencopot pustaka:');
        $this->line('  <options=bold;fg=yellow>composer remove xdiego1st/wa-integration</>');
        $this->newLine();

        return Command::SUCCESS;
    }

    /**
     * Membersihkan baris konfigurasi WHATSAPP_* dari berkas lingkungan.
     */
    protected function cleanEnvFile(string $filePath): void
    {
        if (!File::exists($filePath)) {
            return;
        }

        $content = File::get($filePath);
        $lines = explode("\n", $content);
        $filteredLines = [];
        $removedCount = 0;

        foreach ($lines as $line) {
            $trimmed = trim($line);
            // Cek apakah baris berkaitan dengan WhatsApp package
            if (
                str_starts_with($trimmed, 'WHATSAPP_') ||
                $trimmed === '# WhatsApp Gateway Integration' ||
                $trimmed === '# Pengaturan Dashboard Bawaan'
            ) {
                $removedCount++;
                continue;
            }
            $filteredLines[] = $line;
        }

        if ($removedCount > 0) {
            // Hilangkan blank lines berlebih di akhir berkas
            $newContent = rtrim(implode("\n", $filteredLines)) . "\n";
            File::put($filePath, $newContent);
            $fileName = basename($filePath);
            $this->line("  <info>✓</info> Menghapus <comment>{$removedCount}</comment> baris variabel WhatsApp dari berkas <comment>{$fileName}</comment>.");
        }
    }
}
