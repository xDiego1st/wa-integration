<?php

namespace WaIntegration\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use WaIntegration\Laravel\Services\WhatsAppClient;
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
            __DIR__ . '/../config/whatsapp.php',
            'whatsapp'
        );

        $this->app->singleton(WhatsAppClient::class, function ($app) {
            return new WhatsAppClient($app['config']['whatsapp'] ?? []);
        });

        $this->app->alias(WhatsAppClient::class, 'whatsapp');
    }

    public function boot(): void
    {
        // 1. Publish Configuration & Views
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/whatsapp.php' => config_path('whatsapp.php'),
            ], 'whatsapp-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/whatsapp'),
            ], 'whatsapp-views');

            $this->commands([
                WhatsAppStatusCommand::class,
                WhatsAppTestSendCommand::class,
                WhatsAppUninstallCommand::class,
            ]);
        }

        // 2. Load Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'whatsapp');

        // 3. Register Livewire Component if Livewire is present
        if (class_exists(\Livewire\Livewire::class)) {
            \Livewire\Livewire::component('whatsapp-connection-card', WhatsAppConnectionCard::class);
        }

        // 4. Register Webhook Route
        Route::post('/api/whatsapp/webhook', [WhatsAppWebhookController::class, 'handle'])
            ->name('whatsapp.webhook')
            ->middleware('api');

        // 5. Register Management Dashboard Routes
        $this->registerDashboardRoutes();
    }

    protected function registerDashboardRoutes(): void
    {
        if (!config('whatsapp.dashboard.enabled', true)) {
            return;
        }

        $prefix = config('whatsapp.dashboard.prefix', 'whatsapp');
        $middleware = config('whatsapp.dashboard.middleware', ['web']);

        Route::group([
            'prefix' => $prefix,
            'middleware' => $middleware,
        ], function () {
            Route::get('/', [WhatsAppDashboardController::class, 'index'])->name('whatsapp.dashboard');
            Route::get('/qr', [WhatsAppDashboardController::class, 'qr'])->name('whatsapp.qr');
            Route::post('/connect', [WhatsAppDashboardController::class, 'connect'])->name('whatsapp.connect');
            Route::post('/disconnect', [WhatsAppDashboardController::class, 'disconnect'])->name('whatsapp.disconnect');
            Route::post('/send', [WhatsAppDashboardController::class, 'testSend'])->name('whatsapp.send');
            Route::post('/sessions', [WhatsAppDashboardController::class, 'createSession'])->name('whatsapp.create-session');
        });
    }
}
