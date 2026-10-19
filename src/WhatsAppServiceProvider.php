<?php

namespace WaIntegration\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use WaIntegration\Laravel\Services\WhatsAppClient;
use WaIntegration\Laravel\Console\Commands\WhatsAppInstallCommand;
use WaIntegration\Laravel\Console\Commands\WhatsAppStatusCommand;
use WaIntegration\Laravel\Console\Commands\WhatsAppTestSendCommand;
use WaIntegration\Laravel\Console\Commands\WhatsAppUninstallCommand;
use WaIntegration\Laravel\Http\Controllers\WhatsAppWebhookController;
use WaIntegration\Laravel\Http\Controllers\WhatsAppDashboardController;
use WaIntegration\Laravel\Http\Livewire\WhatsAppConnectionCard;

class WhatsAppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/waku.php',
            'waku'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/waku.php',
            'whatsapp'
        );

        $this->app->singleton(WhatsAppClient::class, function ($app) {
            $config = $app['config']['waku'] ?? $app['config']['whatsapp'] ?? [];
            return new WhatsAppClient($config);
        });

        $this->app->alias(WhatsAppClient::class, 'whatsapp');
        $this->app->alias(WhatsAppClient::class, 'waku');
    }

    public function boot(): void
    {
        // 1. Publish Configuration & Views
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/waku.php' => config_path('waku.php'),
            ], 'waku-config');

            // Backward compatibility publish tag
            $this->publishes([
                __DIR__ . '/../config/waku.php' => config_path('waku.php'),
            ], 'whatsapp-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/whatsapp'),
            ], 'whatsapp-views');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/waku'),
            ], 'waku-views');

            $this->commands([
                WhatsAppInstallCommand::class,
                WhatsAppStatusCommand::class,
                WhatsAppTestSendCommand::class,
                WhatsAppUninstallCommand::class,
            ]);
        }

        // 2. Load Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'whatsapp');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'waku');

        // 3. Register Livewire Component if Livewire is present
        if (class_exists(\Livewire\Livewire::class)) {
            \Livewire\Livewire::component('whatsapp-connection-card', WhatsAppConnectionCard::class);
        }

        // 4. Register Inbound Webhook Route (Default: /waku/api/webhook)
        $webhookPrefix = config('waku.webhook.prefix') ?? config('whatsapp.webhook.prefix', 'waku/api/webhook');
        $webhookMiddleware = config('waku.webhook.middleware') ?? config('whatsapp.webhook.middleware', ['api']);

        Route::post($webhookPrefix, [WhatsAppWebhookController::class, 'handle'])
            ->name('waku.webhook')
            ->middleware($webhookMiddleware);

        // 5. Register Management Dashboard Routes (Default: /waku/dashboard)
        $this->registerDashboardRoutes();
    }

    protected function registerDashboardRoutes(): void
    {
        $enabled = config('waku.dashboard.enabled') ?? config('whatsapp.dashboard.enabled', true);
        if (!$enabled) {
            return;
        }

        $prefix = config('waku.dashboard.prefix') ?? config('whatsapp.dashboard.prefix', 'waku/dashboard');
        $middleware = config('waku.dashboard.middleware') ?? config('whatsapp.dashboard.middleware', ['web', 'auth']);

        Route::group([
            'prefix' => $prefix,
            'middleware' => $middleware,
        ], function () {
            // Main Dashboard & Sessions
            Route::get('/', [WhatsAppDashboardController::class, 'index'])->name('waku.dashboard');
            Route::get('/qr', [WhatsAppDashboardController::class, 'qr'])->name('waku.qr');
            Route::post('/connect', [WhatsAppDashboardController::class, 'connect'])->name('waku.connect');
            Route::post('/disconnect', [WhatsAppDashboardController::class, 'disconnect'])->name('waku.disconnect');
            Route::post('/send', [WhatsAppDashboardController::class, 'testSend'])->name('waku.send');
            Route::post('/sessions', [WhatsAppDashboardController::class, 'createSession'])->name('waku.create-session');
            Route::post('/sessions/{id}/delete', [WhatsAppDashboardController::class, 'deleteSession'])->name('waku.delete-session');
            Route::delete('/sessions/{id}', [WhatsAppDashboardController::class, 'deleteSession']);

            // Live Chat Inbox & Human Handover
            Route::get('/conversations', [WhatsAppDashboardController::class, 'getConversations'])->name('waku.conversations');
            Route::get('/conversations/{id}', [WhatsAppDashboardController::class, 'getConversation'])->name('waku.conversation.detail');
            Route::post('/conversations/{id}/reply', [WhatsAppDashboardController::class, 'sendConversationReply'])->name('waku.conversation.reply');
            Route::post('/conversations/{id}/takeover', [WhatsAppDashboardController::class, 'takeoverConversation'])->name('waku.conversation.takeover');
            Route::post('/conversations/{id}/return-to-bot', [WhatsAppDashboardController::class, 'returnConversationToBot'])->name('waku.conversation.return-to-bot');
            Route::post('/conversations/{id}/resolve', [WhatsAppDashboardController::class, 'resolveConversation'])->name('waku.conversation.resolve');
            Route::post('/conversations/{id}/notes', [WhatsAppDashboardController::class, 'addConversationNote'])->name('waku.conversation.notes');

            // AI CS & BYOK
            Route::post('/ai/config', [WhatsAppDashboardController::class, 'updateBotConfig'])->name('waku.ai.config');
            Route::post('/ai/persona', [WhatsAppDashboardController::class, 'upsertPersona'])->name('waku.ai.persona');
            Route::post('/ai/playground/chat', [WhatsAppDashboardController::class, 'chatPlayground'])->name('waku.ai.playground.chat');
            Route::post('/ai/playground/test', [WhatsAppDashboardController::class, 'testPlayground'])->name('waku.ai.playground.test');

            // Knowledge Base
            Route::get('/knowledge', [WhatsAppDashboardController::class, 'getKnowledgeBases'])->name('waku.knowledge');
            Route::post('/knowledge', [WhatsAppDashboardController::class, 'createKnowledgeBase'])->name('waku.knowledge.create');
            Route::post('/knowledge/{id}/documents', [WhatsAppDashboardController::class, 'addKnowledgeDocument'])->name('waku.knowledge.document.add');
            Route::delete('/knowledge/documents/{docId}', [WhatsAppDashboardController::class, 'deleteKnowledgeDocument'])->name('waku.knowledge.document.delete');
            Route::post('/knowledge/documents/{docId}/delete', [WhatsAppDashboardController::class, 'deleteKnowledgeDocument']);

            // Chatbot Automation Flows
            Route::get('/flows', [WhatsAppDashboardController::class, 'getFlows'])->name('waku.flows');
            Route::post('/flows', [WhatsAppDashboardController::class, 'upsertFlow'])->name('waku.flows.save');

            // Broadcast & Campaigns
            Route::get('/broadcasts', [WhatsAppDashboardController::class, 'getBroadcasts'])->name('waku.broadcasts');
            Route::post('/broadcasts', [WhatsAppDashboardController::class, 'createBroadcast'])->name('waku.broadcasts.create');
            Route::get('/broadcasts/{id}/stats', [WhatsAppDashboardController::class, 'getBroadcastStats'])->name('waku.broadcasts.stats');

            // Teams & Workload
            Route::get('/teams', [WhatsAppDashboardController::class, 'getTeams'])->name('waku.teams');
            Route::get('/teams/workload', [WhatsAppDashboardController::class, 'getAgentsWorkload'])->name('waku.teams.workload');

            // Automations & Business Hours
            Route::get('/automations', [WhatsAppDashboardController::class, 'getAutomations'])->name('waku.automations');
            Route::post('/automations/rules', [WhatsAppDashboardController::class, 'createAutomationRule'])->name('waku.automations.rules.create');
            Route::get('/automations/business-hours', [WhatsAppDashboardController::class, 'getBusinessHours'])->name('waku.automations.business-hours');
            Route::put('/automations/business-hours', [WhatsAppDashboardController::class, 'saveBusinessHours'])->name('waku.automations.business-hours.save');
            Route::post('/automations/business-hours', [WhatsAppDashboardController::class, 'saveBusinessHours']);
        });
    }
}
