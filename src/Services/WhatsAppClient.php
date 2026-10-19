<?php

namespace WaIntegration\Laravel\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class WhatsAppClient
{
    protected string $baseUrl;
    protected ?string $appId;
    protected ?string $apiKey;
    protected int $timeout;
    protected ?string $defaultSessionId;
    protected ?string $defaultBotKey;
    protected ?string $activeBotKey = null;

    public function __construct(array $config = [])
    {
        $this->baseUrl = rtrim($config['base_url'] ?? 'http://localhost:3001/api/v1', '/');
        $this->appId = $config['app_id'] ?? null;
        $this->apiKey = $config['api_key'] ?? null;
        $this->timeout = (int) ($config['timeout'] ?? 15);
        $this->defaultSessionId = $config['default_session_id'] ?? null;
        $this->defaultBotKey = $config['default_bot_key'] ?? null;
    }

    /**
     * Scope subsequent requests to a specific Bot Key.
     */
    public function withBot(string $botKey): self
    {
        $clone = clone $this;
        $clone->activeBotKey = $botKey;
        return $clone;
    }

    protected function request(string $method, string $endpoint, array $data = [], array $headers = []): WhatsAppResponse
    {
        if (empty($this->appId) || empty($this->apiKey)) {
            return new WhatsAppResponse([], 401, 'WhatsApp credentials missing: WHATSAPP_APP_ID and WHATSAPP_API_KEY must be configured.');
        }

        $clientUrl = function_exists('config') ? config('app.url') : null;
        if (empty($clientUrl) && function_exists('request')) {
            try {
                $clientUrl = request()->getSchemeAndHttpHost();
            } catch (\Throwable $e) {
            }
        }
        if (empty($clientUrl) && function_exists('url')) {
            try {
                $clientUrl = url('/');
            } catch (\Throwable $e) {
            }
        }
        $clientName = function_exists('config') ? config('app.name') : 'Laravel Application';

        $defaultHeaders = [
            'X-App-Id' => $this->appId,
            'X-App-Key' => $this->apiKey,
            'X-Client-App-Url' => (string) ($clientUrl ?: 'http://localhost'),
            'X-Client-App-Name' => (string) $clientName,
            'Accept' => 'application/json',
        ];

        $effectiveBotKey = $this->activeBotKey ?? $this->defaultBotKey;
        if (!empty($effectiveBotKey)) {
            $defaultHeaders['X-Bot-Key'] = $effectiveBotKey;
        }

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(array_merge($defaultHeaders, $headers))
                ->$method("{$this->baseUrl}/{$endpoint}", $data);

            $body = $response->json() ?? [];
            return new WhatsAppResponse($body, $response->status(), $response->successful() ? null : ($body['message'] ?? $response->body()));
        } catch (Exception $e) {
            return new WhatsAppResponse([], 500, $e->getMessage());
        }
    }

    public function send(string $to, string $content, ?string $sessionId = null, ?string $idempotencyKey = null): WhatsAppResponse
    {
        $payload = [
            'to' => $to,
            'content' => $content,
            'sessionId' => $sessionId ?? $this->defaultSessionId,
            'type' => 'TEXT',
        ];

        $headers = [];
        if ($idempotencyKey) {
            $headers['Idempotency-Key'] = $idempotencyKey;
        }

        return $this->request('post', 'app/whatsapp/send', $payload, $headers);
    }

    public function sendMedia(string $to, string $content, string $type, string $mediaUrl, ?string $sessionId = null, ?string $idempotencyKey = null): WhatsAppResponse
    {
        $payload = [
            'to' => $to,
            'content' => $content,
            'type' => strtoupper($type),
            'mediaUrl' => $mediaUrl,
            'sessionId' => $sessionId ?? $this->defaultSessionId,
        ];

        $headers = [];
        if ($idempotencyKey) {
            $headers['Idempotency-Key'] = $idempotencyKey;
        }

        return $this->request('post', 'app/whatsapp/send', $payload, $headers);
    }

    public function sendImage(string $to, string $imageUrl, string $caption = '', ?string $sessionId = null): WhatsAppResponse
    {
        return $this->sendMedia($to, $caption, 'IMAGE', $imageUrl, $sessionId);
    }

    public function sendDocument(string $to, string $documentUrl, string $caption = '', ?string $sessionId = null): WhatsAppResponse
    {
        return $this->sendMedia($to, $caption, 'DOCUMENT', $documentUrl, $sessionId);
    }

    public function getStatus(): WhatsAppResponse
    {
        return $this->request('get', 'app/whatsapp/status');
    }

    public function getSessions(): WhatsAppResponse
    {
        return $this->request('get', 'app/whatsapp/sessions');
    }

    public function createSession(string $name): WhatsAppResponse
    {
        return $this->request('post', 'app/whatsapp/sessions', ['name' => $name]);
    }

    public function getPairing(?string $sessionId = null): WhatsAppResponse
    {
        $sid = $sessionId ?? $this->resolveDefaultSessionId(true);
        if (!$sid) {
            return new WhatsAppResponse([], 404, 'No session specified or found');
        }
        return $this->request('get', "app/whatsapp/sessions/{$sid}/pairing");
    }

    public function connectSession(?string $sessionId = null): WhatsAppResponse
    {
        $sid = $sessionId ?? $this->resolveDefaultSessionId(true);
        if (!$sid) {
            return new WhatsAppResponse([], 404, 'No session specified or found');
        }
        return $this->request('post', "app/whatsapp/sessions/{$sid}/connect");
    }

    public function disconnectSession(?string $sessionId = null): WhatsAppResponse
    {
        $sid = $sessionId ?? $this->resolveDefaultSessionId();
        if (!$sid) {
            return new WhatsAppResponse([], 404, 'No session specified or found');
        }
        return $this->request('post', "app/whatsapp/sessions/{$sid}/disconnect");
    }

    public function deleteSession(string $sessionId): WhatsAppResponse
    {
        return $this->request('delete', "app/whatsapp/sessions/{$sessionId}");
    }

    public function getMessages(int $page = 1, int $perPage = 20, ?string $status = null): WhatsAppResponse
    {
        $query = ['page' => $page, 'perPage' => $perPage];
        if ($status) {
            $query['status'] = $status;
        }
        return $this->request('get', 'app/whatsapp/messages', $query);
    }

    public function getMessage(string $id): WhatsAppResponse
    {
        return $this->request('get', "app/whatsapp/messages/{$id}");
    }

    public function getAppInfo(): WhatsAppResponse
    {
        return $this->request('get', 'app/info');
    }

    // ── AI Customer Service & Bot Configuration ──────────────────────────

    public function getBotConfig(?string $sessionId = null): WhatsAppResponse
    {
        $sid = $sessionId ?? $this->resolveDefaultSessionId();
        return $this->request('get', 'app/ai/config', ['sessionId' => $sid]);
    }

    public function updateBotConfig(array $data, ?string $sessionId = null): WhatsAppResponse
    {
        $payload = array_merge([
            'sessionId' => $sessionId ?? $this->resolveDefaultSessionId(),
        ], $data);
        return $this->request('post', 'app/ai/config', $payload);
    }

    public function toggleBot(bool $enabled, ?string $sessionId = null): WhatsAppResponse
    {
        return $this->updateBotConfig([
            'botMode' => $enabled ? 'HYBRID' : 'DISABLED',
        ], $sessionId);
    }

    public function getBots(): WhatsAppResponse
    {
        return $this->request('get', 'ai-cs/bots');
    }

    public function getBot(string $botId): WhatsAppResponse
    {
        return $this->request('get', "ai-cs/bots/{$botId}");
    }

    public function getPersonas(): WhatsAppResponse
    {
        return $this->request('get', 'app/ai/personas');
    }

    public function upsertPersona(array $data): WhatsAppResponse
    {
        return $this->request('post', 'app/ai/personas', $data);
    }

    // ── Live Conversation Inbox & Human Handover ─────────────────────────

    public function getConversations(array $filters = []): WhatsAppResponse
    {
        return $this->request('get', 'app/conversations', $filters);
    }

    public function getConversation(string $conversationId): WhatsAppResponse
    {
        return $this->request('get', "app/conversations/{$conversationId}");
    }

    public function sendConversationReply(string $conversationId, string $content): WhatsAppResponse
    {
        return $this->request('post', "app/conversations/{$conversationId}/reply", [
            'content' => $content,
        ]);
    }

    public function takeoverConversation(string $conversationId): WhatsAppResponse
    {
        return $this->request('post', "app/conversations/{$conversationId}/takeover");
    }

    public function returnConversationToBot(string $conversationId): WhatsAppResponse
    {
        return $this->request('post', "app/conversations/{$conversationId}/return-to-bot");
    }

    public function resolveConversation(string $conversationId): WhatsAppResponse
    {
        return $this->request('post', "app/conversations/{$conversationId}/resolve");
    }

    public function addConversationNote(string $conversationId, string $content): WhatsAppResponse
    {
        return $this->request('post', "app/conversations/{$conversationId}/notes", [
            'content' => $content,
        ]);
    }

    // ── Knowledge Base Management ────────────────────────────────────────

    public function getKnowledgeBases(): WhatsAppResponse
    {
        return $this->request('get', 'app/ai/knowledge');
    }

    public function createKnowledgeBase(string $name, ?string $description = null): WhatsAppResponse
    {
        return $this->request('post', 'app/ai/knowledge', [
            'name' => $name,
            'description' => $description,
        ]);
    }

    public function addKnowledgeDocument(string $knowledgeBaseId, string $title, string $content, string $contentType = 'text/plain'): WhatsAppResponse
    {
        return $this->request('post', "app/ai/knowledge/{$knowledgeBaseId}/documents", [
            'title' => $title,
            'content' => $content,
            'contentType' => $contentType,
        ]);
    }

    // ── AI Analytics ─────────────────────────────────────────────────────

    public function getAiUsageSummary(): WhatsAppResponse
    {
        return $this->request('get', 'app/ai/usage');
    }

    protected function resolveDefaultSessionId(bool $autoCreate = false): ?string
    {
        if ($this->defaultSessionId) {
            return $this->defaultSessionId;
        }
        $sessions = $this->getSessions();
        if ($sessions->isSuccessful() && !empty($sessions->data())) {
            return $sessions->data()[0]['id'] ?? null;
        }

        if ($autoCreate) {
            $appName = config('app.name', 'Default');
            $created = $this->createSession($appName);
            if ($created->isSuccessful() && !empty($created->data()['id'])) {
                return $created->data()['id'];
            }
        }

        return null;
    }
}
