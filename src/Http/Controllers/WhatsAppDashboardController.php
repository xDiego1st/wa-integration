<?php

namespace WaIntegration\Laravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use WaIntegration\Laravel\Facades\WhatsApp;

class WhatsAppDashboardController extends Controller
{
    /**
     * Render the main WhatsApp Management Dashboard.
     */
    public function index(Request $request)
    {
        $appInfoRes = WhatsApp::getAppInfo();
        $sessionsRes = WhatsApp::getSessions();
        $messagesRes = WhatsApp::getMessages(1, 15);

        $appInfo = $appInfoRes->isSuccessful() ? $appInfoRes->data() : null;
        $sessions = $sessionsRes->isSuccessful() ? ($sessionsRes->data() ?? []) : [];
        $messages = $messagesRes->isSuccessful() ? ($messagesRes->data() ?? []) : [];

        $config = [
            'baseUrl' => config('whatsapp.base_url'),
            'appId' => config('whatsapp.app_id'),
            'hasApiKey' => !empty(config('whatsapp.api_key')),
            'hasWebhookSecret' => !empty(config('whatsapp.webhook_secret')),
            'webhookUrl' => url('/api/whatsapp/webhook'),
        ];

        return view('whatsapp::dashboard', compact('appInfo', 'sessions', 'messages', 'config'));
    }

    /**
     * Get live QR code and connection status for live scanning polling.
     */
    public function qr(Request $request)
    {
        $sessionId = $request->query('session_id') ?? config('whatsapp.default_session_id');
        $res = WhatsApp::getPairing($sessionId);

        return response()->json([
            'success' => $res->isSuccessful(),
            'status' => $res->status(),
            'data' => $res->data(),
            'error' => $res->error(),
        ], $res->isSuccessful() ? 200 : $res->status());
    }

    /**
     * Initiate connection / QR pairing for a session.
     */
    public function connect(Request $request)
    {
        $sessionId = $request->input('session_id') ?? config('whatsapp.default_session_id');
        $res = WhatsApp::connectSession($sessionId);

        return response()->json([
            'success' => $res->isSuccessful(),
            'data' => $res->data(),
            'error' => $res->error(),
        ], $res->isSuccessful() ? 200 : 400);
    }

    /**
     * Disconnect an active session.
     */
    public function disconnect(Request $request)
    {
        $sessionId = $request->input('session_id') ?? config('whatsapp.default_session_id');
        $res = WhatsApp::disconnectSession($sessionId);

        return response()->json([
            'success' => $res->isSuccessful(),
            'data' => $res->data(),
            'error' => $res->error(),
        ], $res->isSuccessful() ? 200 : 400);
    }

    /**
     * Send test WhatsApp message from the dashboard.
     */
    public function testSend(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
            'content' => 'required|string',
            'type' => 'nullable|string|in:TEXT,IMAGE,DOCUMENT',
            'media_url' => 'nullable|url',
        ]);

        $to = $request->input('to');
        $content = $request->input('content');
        $type = strtoupper($request->input('type', 'TEXT'));
        $mediaUrl = $request->input('media_url');
        $sessionId = $request->input('session_id') ?? config('whatsapp.default_session_id');

        if ($type === 'TEXT' || empty($mediaUrl)) {
            $res = WhatsApp::send($to, $content, $sessionId);
        } else {
            $res = WhatsApp::sendMedia($to, $content, $type, $mediaUrl, $sessionId);
        }

        return response()->json([
            'success' => $res->isSuccessful(),
            'data' => $res->data(),
            'message_id' => $res->messageId(),
            'error' => $res->error(),
        ], $res->isSuccessful() ? 200 : 400);
    }

    /**
     * Create a new session.
     */
    public function createSession(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $res = WhatsApp::createSession($request->input('name'));

        return response()->json([
            'success' => $res->isSuccessful(),
            'data' => $res->data(),
            'error' => $res->error(),
        ], $res->isSuccessful() ? 200 : 400);
    }
}
