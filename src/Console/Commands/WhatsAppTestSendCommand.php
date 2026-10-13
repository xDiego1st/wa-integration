<?php

namespace WaIntegration\Laravel\Console\Commands;

use Illuminate\Console\Command;
use WaIntegration\Laravel\Facades\WhatsApp;

class WhatsAppTestSendCommand extends Command
{
    protected $signature = 'wa:test-send {to : Phone number (e.g. 08123456789 or 628123456789)} {content : Text message to send}';
    protected $description = 'Send a test WhatsApp message via the configured application connection';

    public function handle(): int
    {
        $to = $this->argument('to');
        $content = $this->argument('content');

        $this->info("Sending message to {$to}...");

        $response = WhatsApp::send($to, $content);

        if ($response->isSuccessful()) {
            $this->info("✓ Message successfully sent or queued!");
            $this->line("  Message ID : " . ($response->messageId() ?? 'N/A'));
            $this->line("  Provider ID: " . ($response->providerId() ?? 'N/A'));
            return Command::SUCCESS;
        }

        $this->error("✗ Failed to send message: " . $response->error());
        return Command::FAILURE;
    }
}
