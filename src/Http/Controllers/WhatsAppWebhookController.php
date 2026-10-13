<?php

namespace WaIntegration\Laravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $signature = $request->header('X-WA-Signature');
        $secret = config('whatsapp.webhook_secret');

        if ($secret) {
            $computed = hash_hmac('sha256', $request->getContent(), $secret);
            if (!hash_equals($computed, (string)$signature)) {
                Log::warning('WhatsApp Webhook rejected: Invalid HMAC signature');
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        $event = $request->input('event');
        $data = $request->input('data', []);
        $appId = $request->input('appId');

        Log::info("WhatsApp Webhook received [{$event}] for App {$appId}", $data);

        // Fire generic event
        Event::dispatch('whatsapp.webhook.received', [$event, $data, $appId]);

        // Fire specific event if listeners exist
        if ($event) {
            Event::dispatch("whatsapp.{$event}", [$data, $appId]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook received and processed',
        ]);
    }
}
