<?php

namespace WaIntegration\Laravel\Services;

class WhatsAppResponse
{
    protected array $data;
    protected bool $successful;
    protected int $statusCode;
    protected ?string $error;

    public function __construct(array $response, int $statusCode = 200, ?string $error = null)
    {
        $this->data = $response['data'] ?? $response;
        $this->successful = ($response['success'] ?? false) || ($statusCode >= 200 && $statusCode < 300);
        $this->statusCode = $statusCode;
        $this->error = $error ?? ($response['error'] ?? ($response['message'] ?? null));
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function messageId(): ?string
    {
        return $this->data['messageId'] ?? null;
    }

    public function providerId(): ?string
    {
        return $this->data['providerId'] ?? null;
    }

    public function status(): ?string
    {
        return $this->data['status'] ?? null;
    }

    public function error(): ?string
    {
        return $this->error;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function json(): array
    {
        return [
            'success' => $this->successful,
            'statusCode' => $this->statusCode,
            'data' => $this->data,
            'error' => $this->error,
        ];
    }
}
