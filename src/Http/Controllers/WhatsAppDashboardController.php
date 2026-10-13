<?php

namespace WaIntegration\Laravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use WaIntegration\Laravel\Facades\WhatsApp;

class WhatsAppDashboardController extends Controller
{
    /**
     * Authorize access to Waku Dashboard based on authentication, allowed roles, emails, or gates.
     */
    protected function authorizeAccess(Request $request)
    {
        $authRequired = config('waku.dashboard.auth_required') ?? config('whatsapp.dashboard.auth_required', true);

        if ($authRequired && !auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Authentication required'], 401);
            }
            if (Route::has('login')) {
                return redirect()->guest(route('login'));
            }
            return redirect()->guest('/login');
        }

        if (auth()->check()) {
            $user = auth()->user();
            $allowedRoles = config('waku.dashboard.allowed_roles') ?? config('whatsapp.dashboard.allowed_roles', []);
            $allowedEmails = config('waku.dashboard.allowed_emails') ?? config('whatsapp.dashboard.allowed_emails', []);
            $gate = config('waku.dashboard.gate') ?? config('whatsapp.dashboard.gate', null);

            $allowedRoles = array_values(array_filter((array) $allowedRoles));
            $allowedEmails = array_values(array_filter((array) $allowedEmails));

            if (!empty($allowedRoles) || !empty($allowedEmails) || !empty($gate)) {
                $hasAccess = false;

                // 1. Check custom Gate
                if (!empty($gate) && class_exists(Gate::class)) {
                    if (Gate::allows($gate, $user)) {
                        $hasAccess = true;
                    }
                }

                // 2. Check Allowed Emails
                if (!$hasAccess && !empty($allowedEmails)) {
                    $userEmail = strtolower(trim($user->email ?? ''));
                    $lowerAllowedEmails = array_map(fn($e) => strtolower(trim($e)), $allowedEmails);
                    if (in_array($userEmail, $lowerAllowedEmails, true)) {
                        $hasAccess = true;
                    }
                }

                // 3. Check Allowed Roles
                if (!$hasAccess && !empty($allowedRoles)) {
                    $hasAccess = $this->userMatchesAnyRole($user, $allowedRoles);
                }

                if (!$hasAccess) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'error' => 'Akses ditolak: Anda tidak memiliki peran/izin untuk membuka Waku Dashboard.'
                        ], 403);
                    }

                    $redirectUrl = config('waku.dashboard.redirect_unauthorized')
                        ?? config('whatsapp.dashboard.redirect_unauthorized', '/');

                    return redirect()->to($redirectUrl)->with('error', 'Anda tidak memiliki izin untuk mengakses halaman Waku Dashboard.');
                }
            }
        }

        return null;
    }

    /**
     * Match user roles against allowed roles across Spatie, Laratrust, or custom model attributes.
     */
    protected function userMatchesAnyRole($user, array $allowedRoles): bool
    {
        // Spatie Laravel-Permission hasAnyRole
        if (method_exists($user, 'hasAnyRole')) {
            try {
                if ($user->hasAnyRole($allowedRoles)) {
                    return true;
                }
            } catch (\Throwable $e) {
            }
        }

        // hasRole check with case insensitivity
        if (method_exists($user, 'hasRole')) {
            foreach ($allowedRoles as $role) {
                try {
                    if ($user->hasRole($role)) {
                        return true;
                    }
                } catch (\Throwable $e) {
                }
            }
        }

        // Direct 'role' string attribute on User model
        if (!empty($user->role)) {
            foreach ($allowedRoles as $role) {
                if (strcasecmp((string) $user->role, (string) $role) === 0) {
                    return true;
                }
            }
        }

        // Direct 'roles' relation on User model
        if (isset($user->roles)) {
            try {
                $userRoles = collect($user->roles)->map(function ($r) {
                    return is_object($r) ? ($r->name ?? $r->slug ?? (string) $r) : (string) $r;
                })->all();

                foreach ($userRoles as $ur) {
                    foreach ($allowedRoles as $ar) {
                        if (strcasecmp((string) $ur, (string) $ar) === 0) {
                            return true;
                        }
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        return false;
    }

    /**
     * Render the main WhatsApp Management Dashboard.
     */
    public function index(Request $request)
    {
        $authCheck = $this->authorizeAccess($request);
        if ($authCheck) {
            return $authCheck;
        }

        $appInfoRes = WhatsApp::getAppInfo();
        $sessionsRes = WhatsApp::getSessions();
        $messagesRes = WhatsApp::getMessages(1, 15);

        $appInfo = $appInfoRes->isSuccessful() ? $appInfoRes->data() : null;
        $sessions = $sessionsRes->isSuccessful() ? ($sessionsRes->data() ?? []) : [];
        $messages = $messagesRes->isSuccessful() ? ($messagesRes->data() ?? []) : [];

        // Auto-create initial default session if none exists
        if (empty($sessions) && !empty(config('waku.api_key', config('whatsapp.api_key')))) {
            $appName = config('app.name', 'Default');
            $createdRes = WhatsApp::createSession($appName);
            if ($createdRes->isSuccessful() && !empty($createdRes->data())) {
                $sessions = [$createdRes->data()];
            }
        }

        $currentUser = null;
        if (auth()->check()) {
            $u = auth()->user();
            $roleName = null;
            if (method_exists($u, 'getRoleNames')) {
                $roleName = $u->getRoleNames()->first();
            } elseif (!empty($u->role)) {
                $roleName = $u->role;
            }
            $currentUser = [
                'name' => $u->name ?? $u->username ?? 'User',
                'email' => $u->email ?? '',
                'role' => $roleName ?? 'User',
            ];
        }

        $dashboardPrefix = config('waku.dashboard.prefix') ?? config('whatsapp.dashboard.prefix', 'waku/dashboard');
        $webhookPrefix = config('waku.webhook.prefix') ?? config('whatsapp.webhook.prefix', 'waku/api/webhook');

        $config = [
            'baseUrl' => config('waku.base_url') ?? config('whatsapp.base_url'),
            'appId' => config('waku.app_id') ?? config('whatsapp.app_id'),
            'hasApiKey' => !empty(config('waku.api_key') ?? config('whatsapp.api_key')),
            'hasWebhookSecret' => !empty(config('waku.webhook_secret') ?? config('whatsapp.webhook_secret')),
            'webhookUrl' => url($webhookPrefix),
            'dashboardUrl' => url($dashboardPrefix),
            'allowedRoles' => config('waku.dashboard.allowed_roles', []),
            'allowedEmails' => config('waku.dashboard.allowed_emails', []),
        ];

        return view('whatsapp::dashboard', compact('appInfo', 'sessions', 'messages', 'config', 'currentUser'));
    }

    /**
     * Get live QR code and connection status for live scanning polling.
     */
    public function qr(Request $request)
    {
        $authCheck = $this->authorizeAccess($request);
        if ($authCheck) {
            return $authCheck;
        }

        $sessionId = $request->query('session_id') ?? config('waku.default_session_id') ?? config('whatsapp.default_session_id');
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
        $authCheck = $this->authorizeAccess($request);
        if ($authCheck) {
            return $authCheck;
        }

        $sessionId = $request->input('session_id') ?? config('waku.default_session_id') ?? config('whatsapp.default_session_id');
        $res = WhatsApp::connectSession($sessionId);

        // Also fetch pairing QR code immediately
        $pairingRes = WhatsApp::getPairing($sessionId);
        $pairingData = $pairingRes->isSuccessful() ? $pairingRes->data() : null;

        return response()->json([
            'success' => $res->isSuccessful(),
            'data' => $res->data(),
            'pairing' => $pairingData,
            'error' => $res->error(),
        ], $res->isSuccessful() ? 200 : 400);
    }

    /**
     * Disconnect an active session.
     */
    public function disconnect(Request $request)
    {
        $authCheck = $this->authorizeAccess($request);
        if ($authCheck) {
            return $authCheck;
        }

        $sessionId = $request->input('session_id') ?? config('waku.default_session_id') ?? config('whatsapp.default_session_id');
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
        $authCheck = $this->authorizeAccess($request);
        if ($authCheck) {
            return $authCheck;
        }

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
        $sessionId = $request->input('session_id') ?? config('waku.default_session_id') ?? config('whatsapp.default_session_id');

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
        $authCheck = $this->authorizeAccess($request);
        if ($authCheck) {
            return $authCheck;
        }

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

    /**
     * Delete an existing WhatsApp session.
     */
    public function deleteSession(Request $request, string $id)
    {
        $authCheck = $this->authorizeAccess($request);
        if ($authCheck) {
            return $authCheck;
        }

        $res = WhatsApp::deleteSession($id);

        return response()->json([
            'success' => $res->isSuccessful(),
            'data' => $res->data(),
            'error' => $res->error(),
        ], $res->isSuccessful() ? 200 : 400);
    }
}
