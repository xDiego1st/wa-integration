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
            Route::get('/', [WhatsAppDashboardController::class, 'index'])->name('waku.dashboard');
            Route::get('/qr', [WhatsAppDashboardController::class, 'qr'])->name('waku.qr');
            Route::post('/connect', [WhatsAppDashboardController::class, 'connect'])->name('waku.connect');
            Route::post('/disconnect', [WhatsAppDashboardController::class, 'disconnect'])->name('waku.disconnect');
            Route::post('/send', [WhatsAppDashboardController::class, 'testSend'])->name('waku.send');
            Route::post('/sessions', [WhatsAppDashboardController::class, 'createSession'])->name('waku.create-session');
            Route::post('/sessions/{id}/delete', [WhatsAppDashboardController::class, 'deleteSession'])->name('waku.delete-session');
            Route::delete('/sessions/{id}', [WhatsAppDashboardController::class, 'deleteSession']);
        });
    }
}
