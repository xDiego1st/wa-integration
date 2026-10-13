<?php

namespace WaIntegration\Laravel\Console\Commands;

use Illuminate\Console\Command;
use WaIntegration\Laravel\Facades\WhatsApp;

class WhatsAppStatusCommand extends Command
{
    protected $signature = 'waku:status';
    protected $aliases = ['wa:status'];
    protected $description = 'Check WhatsApp Integration Platform and Session status';

    public function handle(): int
    {
        $this->info('Checking WhatsApp Integration Platform status...');

        $status = WhatsApp::getStatus();
        if (!$status->isSuccessful()) {
            $this->error("Failed to connect to gateway: {$status->error()}");
            return Command::FAILURE;
        }

        $appInfo = WhatsApp::getAppInfo();
        if ($appInfo->isSuccessful()) {
            $this->table(
                ['Application Property', 'Value'],
                [
                    ['Application ID', $appInfo->data()['id'] ?? 'N/A'],
                    ['Application Name', $appInfo->data()['name'] ?? 'N/A'],
                    ['Rate Limit', ($appInfo->data()['rateLimit'] ?? '60') . ' req/min'],
                    ['Webhook URL', $appInfo->data()['webhookUrl'] ?? 'Not set'],
                ]
            );
        }

        $sessions = WhatsApp::getSessions();
        if ($sessions->isSuccessful()) {
            $rows = array_map(function ($s) {
                return [
                    $s['id'],
                    $s['name'],
                    $s['phone'] ?? '-',
                    $s['status'],
                    $s['lastConnectedAt'] ?? '-',
                ];
            }, $sessions->data());

            $this->newLine();
            $this->info('Active WhatsApp Sessions:');
            $this->table(['Session ID', 'Name', 'Phone', 'Status', 'Last Connected'], $rows);
        }

        return Command::SUCCESS;
    }
}
