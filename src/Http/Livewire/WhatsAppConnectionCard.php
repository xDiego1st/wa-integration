<?php

namespace WaIntegration\Laravel\Http\Livewire;

use Livewire\Component;
use WaIntegration\Laravel\Facades\WhatsApp;

class WhatsAppConnectionCard extends Component
{
    public ?string $sessionId = null;
    public string $status = 'DISCONNECTED';
    public ?string $qrCode = null;
    public ?string $phone = null;
    public ?string $lastConnectedAt = null;
    public ?string $errorMessage = null;

    public function mount(?string $sessionId = null)
    {
        $this->sessionId = $sessionId;
        $this->refreshStatus();
    }

    public function refreshStatus()
    {
        $this->errorMessage = null;
        $res = WhatsApp::getPairing($this->sessionId);

        if ($res->isSuccessful()) {
            $data = $res->data();
            $this->sessionId = $data['sessionId'] ?? $this->sessionId;
            $this->status = $data['status'] ?? 'DISCONNECTED';
            $this->qrCode = $data['qrCode'] ?? null;
            $this->phone = $data['phone'] ?? null;
            $this->lastConnectedAt = $data['lastConnectedAt'] ?? null;
        } else {
            $this->errorMessage = $res->error();
        }
    }

    public function connect()
    {
        $this->errorMessage = null;
        $res = WhatsApp::connectSession($this->sessionId);
        if ($res->isSuccessful()) {
            $this->status = 'CONNECTING';
            $this->refreshStatus();
        } else {
            $this->errorMessage = $res->error();
        }
    }

    public function disconnect()
    {
        $this->errorMessage = null;
        $res = WhatsApp::disconnectSession($this->sessionId);
        if ($res->isSuccessful()) {
            $this->status = 'DISCONNECTED';
            $this->qrCode = null;
            $this->phone = null;
        } else {
            $this->errorMessage = $res->error();
        }
    }

    public function render()
    {
        return view('whatsapp::livewire.connection-card');
    }
}
