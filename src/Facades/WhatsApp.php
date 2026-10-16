<?php

namespace WaIntegration\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use WaIntegration\Laravel\Services\WhatsAppClient;
use WaIntegration\Laravel\Services\WhatsAppResponse;

/**
 * @method static WhatsAppResponse send(string $to, string $content, ?string $sessionId = null, ?string $idempotencyKey = null)
 * @method static WhatsAppResponse sendMedia(string $to, string $content, string $type, string $mediaUrl, ?string $sessionId = null, ?string $idempotencyKey = null)
 * @method static WhatsAppResponse sendImage(string $to, string $imageUrl, string $caption = '', ?string $sessionId = null)
 * @method static WhatsAppResponse sendDocument(string $to, string $documentUrl, string $caption = '', ?string $sessionId = null)
 * @method static WhatsAppResponse getStatus()
 * @method static WhatsAppResponse getSessions()
 * @method static WhatsAppResponse createSession(string $name)
 * @method static WhatsAppResponse getPairing(?string $sessionId = null)
 * @method static WhatsAppResponse connectSession(?string $sessionId = null)
 * @method static WhatsAppResponse disconnectSession(?string $sessionId = null)
 * @method static WhatsAppResponse deleteSession(string $sessionId)
 * @method static WhatsAppResponse getMessages(int $page = 1, int $perPage = 20, ?string $status = null)
 * @method static WhatsAppResponse getMessage(string $id)
 * @method static WhatsAppResponse getAppInfo()
 * @method static WhatsAppResponse getBotConfig(?string $sessionId = null)
 * @method static WhatsAppResponse updateBotConfig(array $data, ?string $sessionId = null)
 * @method static WhatsAppResponse toggleBot(bool $enabled, ?string $sessionId = null)
 * @method static WhatsAppResponse getPersonas()
 * @method static WhatsAppResponse upsertPersona(array $data)
 * @method static WhatsAppResponse getConversations(array $filters = [])
 * @method static WhatsAppResponse getConversation(string $conversationId)
 * @method static WhatsAppResponse sendConversationReply(string $conversationId, string $content)
 * @method static WhatsAppResponse takeoverConversation(string $conversationId)
 * @method static WhatsAppResponse returnConversationToBot(string $conversationId)
 * @method static WhatsAppResponse resolveConversation(string $conversationId)
 * @method static WhatsAppResponse addConversationNote(string $conversationId, string $content)
 * @method static WhatsAppResponse getKnowledgeBases()
 * @method static WhatsAppResponse createKnowledgeBase(string $name, ?string $description = null)
 * @method static WhatsAppResponse addKnowledgeDocument(string $knowledgeBaseId, string $title, string $content, string $contentType = 'text/plain')
 * @method static WhatsAppResponse getAiUsageSummary()
 *
 * @see \WaIntegration\Laravel\Services\WhatsAppClient
 */
class WhatsApp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return WhatsAppClient::class;
    }
}
