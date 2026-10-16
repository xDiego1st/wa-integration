<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Waku Dashboard — Panel Kontrol WhatsApp Gateway Multi-Device</title>
    
    <!-- Styling: Standalone Tailwind CSS & Curated Typography -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
        code, pre, .font-mono { font-family: 'JetBrains Mono', monospace; }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.2); opacity: 0.2; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .animate-pulse-ring { animation: pulse-ring 2.5s infinite ease-in-out; }
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-4px); }
        }
        .animate-float { animation: float-slow 4s ease-in-out infinite; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white">

    @php
        $maxSessionsAllowed = $appInfo['maxSessions'] ?? 2;
        $activeSessionCount = count($sessions);
        $quotaPercentage = min(100, round(($activeSessionCount / max(1, $maxSessionsAllowed)) * 100));
        $connectedCount = collect($sessions)->where('status', 'CONNECTED')->count();
    @endphp

    <!-- Top Navigation Header -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-40 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-4">
                
                <!-- Left: Branding & Tag -->
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-br from-emerald-500 via-teal-600 to-teal-800 text-white flex items-center justify-center shadow-lg shadow-emerald-500/25 shrink-0 animate-float">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.532 1.776.814 2.796.814 3.18 0 5.765-2.586 5.766-5.766 0-3.18-2.586-5.766-5.766-5.766zm9.969 5.766c0 5.485-4.464 9.948-9.969 9.948-1.709 0-3.32-.44-4.733-1.213l-5.298 1.389 1.417-5.174c-.878-1.488-1.386-3.226-1.386-5.088 0-5.486 4.464-9.95 9.969-9.95s9.969 4.464 9.969 9.95z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Waku Dashboard</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">v1.2.0</span>
                            <span class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span> 24/7 Steady
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 truncate hidden sm:block">Panel Kontrol WhatsApp Multi-Device & Gateway Anti-Spam Mandiri</p>
                    </div>
                </div>

                <!-- Right: Tenant Info, Auth User & Return Link -->
                <div class="flex items-center gap-2.5 sm:gap-3">
                    @if($config['appId'])
                        <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100/90 border border-slate-200/80 text-xs">
                            <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-slate-500 font-medium">Tenant:</span>
                            <button type="button" onclick="copySnippet('{{ $config['appId'] }}', 'Tenant ID Disalin!')" class="font-mono font-bold text-slate-800 hover:text-emerald-600 transition-colors flex items-center gap-1" title="Klik untuk salin Tenant ID">
                                {{ Str::limit($config['appId'], 14) }}
                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                    @endif

                    @if(isset($currentUser) && $currentUser)
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs">
                            <div class="w-6 h-6 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-[10px] uppercase">
                                {{ substr($currentUser['name'] ?? 'U', 0, 1) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="font-bold text-emerald-950 truncate max-w-[120px]">{{ $currentUser['name'] }}</div>
                                <div class="text-[10px] text-emerald-700 font-mono">{{ $currentUser['role'] ?? 'User' }}</div>
                            </div>
                        </div>
                    @endif

                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span class="hidden sm:inline">Kembali ke Aplikasi</span>
                    </a>
                </div>
            </div>

            <!-- Navigation Tabs Bar -->
            <nav class="flex space-x-2 sm:space-x-4 border-t border-slate-100 pt-1 -mb-px overflow-x-auto no-scrollbar" aria-label="Tabs">
                <button type="button" onclick="switchTab('tab-connection')" id="btn-tab-connection" class="tab-btn inline-flex items-center gap-2 py-3 px-3 text-xs sm:text-sm font-bold border-b-2 border-emerald-600 text-emerald-700 whitespace-nowrap transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Koneksi & Sesi WhatsApp</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-mono bg-emerald-100 text-emerald-800">{{ $activeSessionCount }}/{{ $maxSessionsAllowed }}</span>
                </button>

                <button type="button" onclick="switchTab('tab-send')" id="btn-tab-send" class="tab-btn inline-flex items-center gap-2 py-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 whitespace-nowrap transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>Uji Kirim Pesan</span>
                </button>

                <button type="button" onclick="switchTab('tab-messages')" id="btn-tab-messages" class="tab-btn inline-flex items-center gap-2 py-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 whitespace-nowrap transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Riwayat Pesan</span>
                </button>

                <button type="button" onclick="switchTab('tab-api-guide')" id="btn-tab-api-guide" class="tab-btn inline-flex items-center gap-2 py-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 whitespace-nowrap transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    <span>Integrasi Multi-Framework</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">7 Stacks</span>
                </button>

                <button type="button" onclick="switchTab('tab-lifecycle')" id="btn-tab-lifecycle" class="tab-btn inline-flex items-center gap-2 py-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 whitespace-nowrap transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Konfigurasi & Role</span>
                </button>
            </nav>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 flex-1 w-full space-y-6">

        <!-- Banner Peringatan Cepat Kredensial -->
        @if(!$config['hasApiKey'])
            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 sm:p-5 flex items-start gap-3.5 shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="space-y-1">
                    <h3 class="text-sm font-bold text-amber-900">Kunci API Belum Dikonfigurasi!</h3>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Variabel lingkungan <code class="font-mono bg-amber-100 px-1 py-0.5 rounded text-amber-950 font-bold">WAKU_API_KEY</code> belum diisi di file <code class="font-mono">.env</code> Anda. Hubungi Super Admin atau buka panel konsol gateway untuk membuat API Key live.
                    </p>
                </div>
            </div>
        @endif

        <!-- =============================================================== -->
        <!-- TAB 1: KONEKSI WHATSAPP, KUOTA SESI & ACTION CARDS              -->
        <!-- =============================================================== -->
        <div id="tab-connection" class="tab-content space-y-6">
            
            <!-- Quick Feature Overview Stats Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Stat 1: Kuota Sesi Card -->
                <div class="glass-card rounded-2xl p-4 sm:p-5 space-y-2 shadow-sm">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span class="font-semibold">Kuota Sesi Aplikasi</span>
                        <span class="font-mono font-bold text-slate-700">{{ $activeSessionCount }} / {{ $maxSessionsAllowed }} Sesi</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-2 rounded-full transition-all duration-500" style="width: {{ $quotaPercentage }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                        <span>Diatur oleh Super Admin</span>
                        <span class="font-bold text-slate-600">{{ $quotaPercentage }}% Terpakai</span>
                    </div>
                </div>

                <!-- Stat 2: Anti-Spam Load Balancer Status -->
                <div class="glass-card rounded-2xl p-4 sm:p-5 space-y-1 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Distribusi Beban</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $connectedCount > 1 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                            {{ $connectedCount > 1 ? 'Round-Robin Aktif' : 'Single Session' }}
                        </span>
                    </div>
                    <div class="text-base font-bold text-slate-900 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Rotasi Anti-Spam</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Otomatis bergantian di {{ $connectedCount }} nomor aktif</p>
                </div>

                <!-- Stat 3: 24/7 Steady Watchdog -->
                <div class="glass-card rounded-2xl p-4 sm:p-5 space-y-1 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Status Gateway</span>
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    </div>
                    <div class="text-base font-bold text-slate-900 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>24/7 Steady Mode</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Watchdog auto-heal aktif setiap 30 detik</p>
                </div>

                <!-- Stat 4: Total Nomor Terhubung -->
                <div class="glass-card rounded-2xl p-4 sm:p-5 space-y-1 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Nomor Terhubung</span>
                        <span class="font-mono text-xs font-bold text-emerald-700">{{ $connectedCount }} Aktif</span>
                    </div>
                    <div class="text-xl font-black text-slate-900">
                        {{ $connectedCount }} <span class="text-xs font-normal text-slate-400">/ {{ $activeSessionCount }} Sesi Terdaftar</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Siap menerima &amp; mengirim trafik</p>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left: Hero Connection Status Card -->
                <div class="lg:col-span-2 glass-card rounded-3xl p-6 sm:p-8 space-y-6 relative overflow-hidden">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-5">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                <span>Status Koneksi Utama</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Live Socket</span>
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Sesi aktif langsung tersinkronisasi dengan WhatsApp Baileys Gateway</p>
                        </div>
                        <div id="status-badge-container">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200" id="badge-status">
                                Memuat status...
                            </span>
                        </div>
                    </div>

                    <!-- Main Dynamic Connection Presentation -->
                    <div id="connection-display" class="py-8 flex flex-col items-center justify-center text-center">
                        <div class="animate-pulse flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-slate-200 mb-4"></div>
                            <div class="h-4 bg-slate-200 rounded w-48 mb-2"></div>
                            <div class="h-3 bg-slate-200 rounded w-32"></div>
                        </div>
                    </div>

                    <!-- Action Controls -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-5 border-t border-slate-100">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <button type="button" onclick="refreshQrStatus()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold flex items-center gap-2 transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Segarkan Status</span>
                            </button>
                            
                            <button type="button" onclick="openQrModalAndConnect()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-2 shadow-lg shadow-emerald-600/25 transition-all cursor-pointer hover:scale-[1.02]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span>Hubungkan / Request QR</span>
                            </button>
                        </div>

                        <button type="button" onclick="disconnectSession()" class="px-4 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold flex items-center gap-2 border border-amber-200/80 transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            <span>Putuskan Sesi Aktif</span>
                        </button>
                    </div>
                </div>

                <!-- Right: Active Sessions Management & Quota Card -->
                <div class="glass-card rounded-3xl p-6 space-y-5 flex flex-col">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Daftar Sesi Nomor WA</h3>
                            <p class="text-[11px] text-slate-400">Kuota: {{ $activeSessionCount }}/{{ $maxSessionsAllowed }} sesi dibuat</p>
                        </div>
                        <button type="button" onclick="promptCreateSession()" class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center gap-1.5 border border-emerald-200 transition-colors cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Tambah Sesi</span>
                        </button>
                    </div>

                    <!-- Session Items List -->
                    <div id="session-list-container" class="space-y-3 flex-1 overflow-y-auto max-h-[380px] pr-1">
                        @if(empty($sessions))
                            <div class="py-8 text-center text-xs text-slate-500 space-y-2">
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="font-semibold text-slate-700">Belum ada sesi nomor WhatsApp</p>
                                <p class="text-[11px] text-slate-400">Klik tombol <strong>"+ Tambah Sesi"</strong> di atas untuk membuat sesi baru.</p>
                            </div>
                        @else
                            @foreach($sessions as $s)
                                @php
                                    $isConn = ($s['status'] ?? '') === 'CONNECTED';
                                    $sId = $s['id'] ?? '';
                                    $sName = $s['name'] ?? 'Sesi WhatsApp';
                                @endphp
                                <div class="p-3.5 rounded-2xl border {{ $isConn ? 'border-emerald-200/80 bg-emerald-50/40' : 'border-slate-200/80 bg-slate-50/70' }} hover:shadow-sm transition-all space-y-2.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="space-y-0.5 min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-slate-900 text-xs truncate">{{ $sName }}</span>
                                                @if(!empty($s['isDefault']))
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-slate-200 text-slate-700">Utama</span>
                                                @endif
                                            </div>
                                            <div class="font-mono text-slate-600 text-[11px] font-semibold flex items-center gap-1">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                <span>{{ $s['phone'] ?? 'Belum dipindai' }}</span>
                                            </div>
                                        </div>
                                        <div class="shrink-0">
                                            @if($isConn)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> 24/7 Live
                                                </span>
                                            @elseif(($s['status'] ?? '') === 'SCAN_QR')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> Scan QR
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700">
                                                    {{ $s['status'] ?? 'DISCONNECTED' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Session Action Buttons (Hubungkan, Putuskan, HAPUS SESI) -->
                                    <div class="flex items-center justify-between pt-2 border-t border-slate-200/60 text-xs">
                                        <div class="flex items-center gap-1.5">
                                            @if(!$isConn)
                                                <button type="button" onclick="connectSessionSpecific('{{ $sId }}')" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition-colors cursor-pointer flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                                    <span>Scan</span>
                                                </button>
                                            @else
                                                <button type="button" onclick="disconnectSessionSpecific('{{ $sId }}', '{{ $sName }}')" class="px-2.5 py-1 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-[11px] transition-colors cursor-pointer">
                                                    <span>Putuskan</span>
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Tombol Hapus Sesi Konsumen dengan SweetAlert2 -->
                                        <button type="button" onclick="deleteSessionById('{{ $sId }}', '{{ $sName }}')" class="px-2.5 py-1 rounded-lg text-rose-600 hover:bg-rose-50 hover:text-rose-700 font-bold text-[11px] transition-colors cursor-pointer flex items-center gap-1 border border-transparent hover:border-rose-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus Sesi</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Anti-Spam Notice -->
                    <div class="p-3.5 rounded-2xl bg-teal-50/70 border border-teal-100 text-xs text-teal-950 space-y-1">
                        <div class="font-bold flex items-center gap-1.5 text-teal-900 text-[11px] uppercase tracking-wider">
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Anti-Spam Load Balancer:</span>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-relaxed">
                            Jika Anda menghubungkan lebih dari 1 nomor WhatsApp, service gateway secara otomatis merotasi pengiriman pesan antar nomor secara bergantian agar terhindar dari pemblokiran atau limit spam.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB 2: INTERACTIVE TEST MESSAGE SANDBOX (WITH PHONE MOCKUP)     -->
        <!-- =============================================================== -->
        <div id="tab-send" class="tab-content hidden space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Form Controls (7 cols) -->
                <div class="lg:col-span-7 glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-md">
                    <div class="border-b border-slate-100 pb-4">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Uji Coba Pengiriman Pesan Langsung</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Uji performa pengiriman pesan instan teks, gambar, atau dokumen dari gateway</p>
                    </div>

                    <form onsubmit="handleTestSend(event)" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Tujuan WhatsApp (Penerima)</label>
                            <div class="relative">
                                <input type="text" id="send-to" required placeholder="Contoh: 08123456789 atau 628123456789" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all" />
                                <div class="absolute right-3 top-2.5 text-xs text-slate-400 font-mono">Format Indonesia</div>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1">Otomatis dinormalisasi ke format internasional (misal 628123456789).</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe Pesan</label>
                                <select id="send-type" onchange="handleTypeChange()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white transition-all">
                                    <option value="TEXT">Pesan Teks (Standard)</option>
                                    <option value="IMAGE">Gambar (Image URL)</option>
                                    <option value="DOCUMENT">Dokumen (PDF / Faktur)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mode Rotasi Sesi</label>
                                <select id="send-session-id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white transition-all">
                                    <option value="">Otomatis Bergantian (Anti-Spam Round-Robin)</option>
                                    @foreach($sessions as $s)
                                        <option value="{{ $s['id'] }}">{{ $s['name'] }} ({{ $s['phone'] ?? 'Offline' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="media-url-container" class="hidden">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tautan Berkas Media (URL Publik)</label>
                            <input type="url" id="send-media-url" placeholder="https://domain-anda.com/assets/invoice.pdf" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all" />
                            <p class="text-[11px] text-slate-500 mt-1">Berkas harus dapat diunduh langsung secara publik via HTTPS.</p>
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center justify-between gap-1 mb-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Isi Pesan / Keterangan (Caption)</label>
                                <div class="flex flex-wrap items-center gap-1">
                                    <span class="text-[10px] text-slate-400 font-semibold mr-0.5">Template Cepat:</span>
                                    <button type="button" onclick="applyQuickTemplate('otp')" class="px-2 py-0.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-[10px] font-bold border border-emerald-200 transition-colors cursor-pointer">🔐 OTP</button>
                                    <button type="button" onclick="applyQuickTemplate('doc')" class="px-2 py-0.5 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-800 text-[10px] font-bold border border-sky-200 transition-colors cursor-pointer">📄 Dokumen</button>
                                    <button type="button" onclick="applyQuickTemplate('bill')" class="px-2 py-0.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 text-[10px] font-bold border border-amber-200 transition-colors cursor-pointer">💳 Tagihan</button>
                                    <button type="button" onclick="applyQuickTemplate('presensi')" class="px-2 py-0.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-800 text-[10px] font-bold border border-indigo-200 transition-colors cursor-pointer">⏰ Presensi</button>
                                </div>
                            </div>
                            <textarea id="send-content" rows="4" required oninput="updateLiveChatPreview()" placeholder="Ketik isi pesan WhatsApp di sini..." class="w-full px-4 py-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all leading-relaxed"></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" id="btn-submit-send" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2 transition-all cursor-pointer hover:scale-[1.01]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span>Kirim Pesan Sekarang</span>
                            </button>
                        </div>
                    </form>

                    <div id="send-result-box" class="hidden text-xs rounded-2xl p-4 transition-all"></div>
                </div>

                <!-- Right: Realistic WhatsApp Mobile Mockup Preview (5 cols) -->
                <div class="lg:col-span-5 flex flex-col items-center">
                    <div class="w-full max-w-[340px] bg-slate-900 rounded-[44px] p-4 shadow-2xl border-4 border-slate-800 relative">
                        <div class="w-28 h-4 bg-slate-950 rounded-b-xl mx-auto mb-2 flex items-center justify-center">
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-800 mr-2"></div>
                            <div class="w-8 h-1 bg-slate-800 rounded-full"></div>
                        </div>

                        <!-- Screen Content -->
                        <div class="bg-[#efeae2] rounded-[32px] overflow-hidden flex flex-col h-[460px] relative">
                            <!-- WhatsApp Chat Header -->
                            <div class="bg-[#075e54] text-white px-3.5 py-2.5 flex items-center gap-2.5 shadow-sm">
                                <div class="w-7 h-7 rounded-full bg-slate-300 text-slate-700 flex items-center justify-center text-xs font-bold shrink-0">
                                    WA
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold truncate">Pelanggan / Consumer</div>
                                    <div class="text-[9px] text-emerald-200">Online</div>
                                </div>
                            </div>

                            <!-- Chat Messages Area -->
                            <div class="flex-1 p-3.5 space-y-2 overflow-y-auto flex flex-col justify-end">
                                <div class="self-end max-w-[85%] bg-[#dcf8c6] rounded-2xl rounded-tr-none p-3 text-xs shadow-sm space-y-1">
                                    <p class="text-slate-800 leading-relaxed break-words" id="chat-preview-text">
                                        Halo! Ini adalah pesan uji coba dari panel Waku WhatsApp Gateway...
                                    </p>
                                    <div class="text-[9px] text-slate-400 text-right font-mono flex items-center justify-end gap-1">
                                        <span>{{ date('H:i') }}</span>
                                        <svg class="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7m-5-4l4 4L19 7"/></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Mock Input Bar -->
                            <div class="bg-slate-100 px-3 py-2 flex items-center gap-2 border-t border-slate-200/60">
                                <div class="flex-1 bg-white rounded-full px-3 py-1.5 text-[11px] text-slate-400">Ketik pesan...</div>
                                <div class="w-7 h-7 rounded-full bg-[#128c7e] text-white flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB 3: RIWAYAT PESAN (MESSAGE LOGS)                             -->
        <!-- =============================================================== -->
        <div id="tab-messages" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-5 shadow-md">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Riwayat Pesan Terakhir</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Log 15 pesan terakhir yang dikirim melalui aplikasi tenant ini</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="relative w-full sm:w-56">
                            <input type="text" id="log-search-input" onkeyup="filterLogTable()" placeholder="Cari nomor/isi pesan..." class="w-full pl-8 pr-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all" />
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <button type="button" onclick="location.reload()" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors cursor-pointer flex items-center gap-1.5 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Refresh</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-3">Waktu</th>
                                <th class="py-3 px-3">Tujuan</th>
                                <th class="py-3 px-3">Isi Pesan</th>
                                <th class="py-3 px-3">Tipe</th>
                                <th class="py-3 px-3">Status</th>
                                <th class="py-3 px-3">ID Pesan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @if(empty($messages))
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">Belum ada riwayat pesan yang tercatat.</td>
                                </tr>
                            @else
                                @foreach($messages as $m)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-3 px-3 text-slate-500 font-mono text-[11px] whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($m['createdAt'] ?? now())->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td class="py-3 px-3 font-mono font-bold text-slate-800">{{ $m['to'] ?? '-' }}</td>
                                        <td class="py-3 px-3 text-slate-600 max-w-xs truncate">{{ $m['content'] ?? '-' }}</td>
                                        <td class="py-3 px-3 font-mono text-[11px] text-slate-500">{{ $m['type'] ?? 'TEXT' }}</td>
                                        <td class="py-3 px-3">
                                            @php $st = $m['status'] ?? 'PENDING'; @endphp
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold font-mono {{ $st === 'SENT' ? 'bg-emerald-100 text-emerald-800' : ($st === 'FAILED' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700') }}">
                                                {{ $st }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 font-mono text-[11px] text-slate-400 truncate max-w-[120px]" title="{{ $m['id'] ?? '' }}">
                                            {{ $m['id'] ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB 4: INTEGRASI MULTI-FRAMEWORK HUB (7 STACKS)                 -->
        <!-- =============================================================== -->
        <div id="tab-api-guide" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-md">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-5">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span>Pusat Integrasi Multi-Framework</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">SDK &amp; REST API</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Pilih bahasa atau framework favorit Anda untuk mendapatkan kode siap salin (Copy-Paste Ready)</p>
                    </div>

                    <!-- Anti-Spam Banner in Multi-Framework -->
                    <div class="px-3.5 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs flex items-center gap-2 font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Auto-Rotasi Anti-Spam aktif tanpa perlu konfigurasi tambahan</span>
                    </div>
                </div>

                <!-- Framework Selection Tabs -->
                <div class="flex flex-wrap gap-2 border-b border-slate-100 pb-3" id="framework-nav">
                    <button type="button" onclick="switchFramework('laravel')" id="btn-fw-laravel" class="fw-btn px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-600 text-white shadow-sm transition-all cursor-pointer">
                        Laravel (PHP)
                    </button>
                    <button type="button" onclick="switchFramework('nodejs')" id="btn-fw-nodejs" class="fw-btn px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all cursor-pointer">
                        Node.js / Express
                    </button>
                    <button type="button" onclick="switchFramework('nextjs')" id="btn-fw-nextjs" class="fw-btn px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all cursor-pointer">
                        Next.js 14 / React
                    </button>
                    <button type="button" onclick="switchFramework('php-native')" id="btn-fw-php-native" class="fw-btn px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all cursor-pointer">
                        PHP Native / cURL
                    </button>
                    <button type="button" onclick="switchFramework('python')" id="btn-fw-python" class="fw-btn px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all cursor-pointer">
                        Python (FastAPI / Requests)
                    </button>
                    <button type="button" onclick="switchFramework('golang')" id="btn-fw-golang" class="fw-btn px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all cursor-pointer">
                        Go (Golang)
                    </button>
                    <button type="button" onclick="switchFramework('curl')" id="btn-fw-curl" class="fw-btn px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all cursor-pointer">
                        cURL / HTTP CLI
                    </button>
                </div>

                <!-- 1. Framework: LARAVEL -->
                <div id="fw-laravel" class="fw-content space-y-5">
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] font-bold">1</span>
                            Kirim Pesan via Laravel Facade (WhatsApp::send)
                        </h3>
                        <p class="text-xs text-slate-600">Jika <code class="font-mono text-xs">$sessionId</code> dikosongkan, gateway secara otomatis merotasi nomor yang terhubung secara bergantian (*anti-spam*).</p>
                        <div class="relative group">
                            <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-fw-laravel">use WaIntegration\Laravel\Facades\WhatsApp;

// 1. Kirim Pesan Teks (Auto Round-Robin Anti-Spam jika sessionId null)
$response = WhatsApp::send('08123456789', 'Halo! Pesanan #INV-2026-001 Anda sedang diproses.');

if ($response->isSuccessful()) {
    $messageId = $response->messageId();
    // Berhasil masuk antrean gateway
} else {
    $error = $response->error();
}

// 2. Kirim Media Dokumen PDF
WhatsApp::sendDocument(
    to: '08123456789',
    documentUrl: 'https://domain-anda.com/invoices/INV-001.pdf',
    caption: 'Berikut faktur transaksi pembayaran Anda.'
);

// 3. AI Bot Customer Service & Live Chat Inbox (v1.2.0)
WhatsApp::updateBotConfig($sessionId, [
    'botEnabled' => true,
    'mode' => 'HYBRID', // HYBRID | AI_ONLY | MENU_ONLY | DISABLED
    'providerType' => 'GOOGLE_GEMINI', // GOOGLE_GEMINI | OPENAI
    'modelName' => 'gemini-1.5-flash',
    'handoverKeywords' => ['admin', 'human', 'cs', 'bantuan'],
]);

// Ambil alih percakapan (Human Takeover)
WhatsApp::takeoverConversation($conversationId, 'Ditangani tim support');
// Balas percakapan pelanggan dari backend
WhatsApp::sendConversationReply($conversationId, 'Halo, kami siap membantu.');</code></pre>
                            <button type="button" onclick="copySnippet('code-fw-laravel', 'Kode Laravel Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                        </div>
                    </div>
                </div>

                <!-- 2. Framework: NODE.JS / EXPRESS -->
                <div id="fw-nodejs" class="fw-content hidden space-y-5">
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] font-bold">1</span>
                            Kirim Pesan via Node.js / Express (Fetch API / Axios)
                        </h3>
                        <div class="relative group">
                            <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-fw-nodejs">import axios from 'axios';

async function sendWhatsAppMessage(to, content) {
  const response = await axios.post('{{ $config['baseUrl'] }}/app/whatsapp/send', {
    to: to,
    content: content,
    type: 'TEXT'
    // Biarkan sessionId kosong agar gateway otomatis merotasi sesi (Anti-Spam)
  }, {
    headers: {
      'X-App-Id': '{{ $config['appId'] }}',
      'X-App-Key': process.env.WAKU_API_KEY, // Kunci API Live Anda
      'Content-Type': 'application/json'
    }
  });

  return response.data;
}

// Contoh Pemanggilan:
sendWhatsAppMessage('08123456789', 'Kode OTP aktivasi akun Anda adalah 492019')
  .then(res => console.log('Terkirim:', res))
  .catch(err => console.error('Gagal:', err.response?.data || err.message));</code></pre>
                            <button type="button" onclick="copySnippet('code-fw-nodejs', 'Kode Node.js Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                        </div>
                    </div>
                </div>

                <!-- 3. Framework: NEXT.JS 14 / REACT -->
                <div id="fw-nextjs" class="fw-content hidden space-y-5">
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] font-bold">1</span>
                            Next.js 14 App Router (Server Action / Route Handler)
                        </h3>
                        <div class="relative group">
                            <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-fw-nextjs">// app/actions/send-whatsapp.ts
'use server';

export async function sendNotificationAction(phone: string, text: string) {
  const res = await fetch('{{ $config['baseUrl'] }}/app/whatsapp/send', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-App-Id': '{{ $config['appId'] }}',
      'X-App-Key': process.env.WAKU_API_KEY!,
    },
    body: JSON.stringify({
      to: phone,
      content: text,
      type: 'TEXT',
    }),
  });

  const data = await res.json();
  if (!res.ok) {
    throw new Error(data.message || 'Gagal mengirim pesan WhatsApp');
  }
  return data;
}</code></pre>
                            <button type="button" onclick="copySnippet('code-fw-nextjs', 'Kode Next.js Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                        </div>
                    </div>
                </div>

                <!-- 4. Framework: PHP NATIVE / CURL -->
                <div id="fw-php-native" class="fw-content hidden space-y-5">
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] font-bold">1</span>
                            PHP Native (cURL Tanpa Framework)
                        </h3>
                        <div class="relative group">
                            <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-fw-php-native">&lt;?php

$payload = [
    'to' => '08123456789',
    'content' => 'Halo! Ini pesan notifikasi dari integrasi PHP native.',
    'type' => 'TEXT'
];

$ch = curl_init('{{ $config['baseUrl'] }}/app/whatsapp/send');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-App-Id: {{ $config['appId'] }}',
    'X-App-Key: ' . getenv('WAKU_API_KEY')
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);
print_r($result);
?&gt;</code></pre>
                            <button type="button" onclick="copySnippet('code-fw-php-native', 'Kode PHP Native Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                        </div>
                    </div>
                </div>

                <!-- 5. Framework: PYTHON -->
                <div id="fw-python" class="fw-content hidden space-y-5">
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] font-bold">1</span>
                            Python (FastAPI / Django / Requests)
                        </h3>
                        <div class="relative group">
                            <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-fw-python">import os
import requests

def send_whatsapp(to_number: str, message_text: str):
    url = "{{ $config['baseUrl'] }}/app/whatsapp/send"
    headers = {
        "Content-Type": "application/json",
        "X-App-Id": "{{ $config['appId'] }}",
        "X-App-Key": os.getenv("WAKU_API_KEY", "wai_live_your_secret_key")
    }
    payload = {
        "to": to_number,
        "content": message_text,
        "type": "TEXT"
    }

    response = requests.post(url, json=payload, headers=headers)
    return response.json()

# Contoh:
result = send_whatsapp("08123456789", "Verifikasi transaksi berhasil!")
print(result)</code></pre>
                            <button type="button" onclick="copySnippet('code-fw-python', 'Kode Python Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                        </div>
                    </div>
                </div>

                <!-- 6. Framework: GOLANG -->
                <div id="fw-golang" class="fw-content hidden space-y-5">
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] font-bold">1</span>
                            Go (Golang net/http)
                        </h3>
                        <div class="relative group">
                            <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-fw-golang">package main

import (
	"bytes"
	"encoding/json"
	"fmt"
	"net/http"
	"os"
)

type WAPayload struct {
	To      string `json:"to"`
	Content string `json:"content"`
	Type    string `json:"type"`
}

func SendWhatsApp(to, content string) error {
	payload, _ := json.Marshal(WAPayload{To: to, Content: content, Type: "TEXT"})
	req, _ := http.NewRequest("POST", "{{ $config['baseUrl'] }}/app/whatsapp/send", bytes.NewBuffer(payload))
	req.Header.Set("Content-Type", "application/json")
	req.Header.Set("X-App-Id", "{{ $config['appId'] }}")
	req.Header.Set("X-App-Key", os.Getenv("WAKU_API_KEY"))

	client := &http.Client{}
	resp, err := client.Do(req)
	if err != nil {
		return err
	}
	defer resp.Body.Close()
	fmt.Println("Status:", resp.Status)
	return nil
}</code></pre>
                            <button type="button" onclick="copySnippet('code-fw-golang', 'Kode Go Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                        </div>
                    </div>
                </div>

                <!-- 7. Framework: CURL / CLI -->
                <div id="fw-curl" class="fw-content hidden space-y-5">
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] font-bold">1</span>
                            cURL / Postman Terminal CLI
                        </h3>
                        <div class="relative group">
                            <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-fw-curl">curl -X POST "{{ $config['baseUrl'] }}/app/whatsapp/send" \
  -H "Content-Type: application/json" \
  -H "X-App-Id: {{ $config['appId'] }}" \
  -H "X-App-Key: wai_live_your_secret_api_key" \
  -d '{
    "to": "08123456789",
    "content": "Halo! Pesan uji coba gateway WhatsApp PlatformCore.",
    "type": "TEXT"
  }'</code></pre>
                            <button type="button" onclick="copySnippet('code-fw-curl', 'Perintah cURL Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB 5: KONFIGURASI, HAK AKSES & LIFE CYCLE                      -->
        <!-- =============================================================== -->
        <div id="tab-lifecycle" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-8 shadow-md">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Hak Akses, Batasan Sesi &amp; Manajemen Paket</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Pengaturan keamanan, kuota dari Super Admin, dan pemeliharaan paket <code class="font-mono text-xs">config/waku.php</code></p>
                </div>

                <!-- Section 1: Quota Rule -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
                    <h4 class="font-bold text-slate-900 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Kebijakan Kuota Sesi (Default: 2)</span>
                    </h4>
                    <p class="text-slate-600 leading-relaxed">
                        Setiap aplikasi konsumen memiliki batas kuota sesi maksimum yang dikontrol penuh oleh <strong>Super Admin</strong> melalui panel manajemen aplikasi. Jika kuota telah penuh, Anda dapat menghapus sesi yang sudah tidak digunakan menggunakan tombol <strong>"Hapus Sesi"</strong> pada tab Koneksi atau meminta Super Admin untuk meningkatkan batas kuota.
                    </p>
                </div>

                <!-- Section 2: Automated Install & Config -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                        <span class="w-5 h-5 rounded-full bg-sky-100 text-sky-800 flex items-center justify-center text-[10px] font-bold">1</span>
                        Pengaturan Hak Akses Role (config/waku.php)
                    </h3>
                    <div class="relative group">
                        <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-config-roles">'dashboard' => [
    'enabled' => env('WAKU_DASHBOARD_ENABLED', true),
    'prefix' => env('WAKU_DASHBOARD_PREFIX', 'waku/dashboard'),
    'auth_required' => true,
    'allowed_roles' => ['SUPER-ADMIN', 'ADMIN', 'MANAGER'],
],</code></pre>
                        <button type="button" onclick="copySnippet('code-config-roles', 'Konfigurasi Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                    </div>
                </div>

                <!-- Section 3: Clean Uninstall -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                        <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-800 flex items-center justify-center text-[10px] font-bold">2</span>
                        Pencopotan Bersih (Zero-Residue Clean Uninstall)
                    </h3>
                    <div class="relative group">
                        <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-uninstall-waku"># 1. Bersihkan seluruh file konfigurasi & variabel .env secara otomatis
php artisan waku:uninstall --force

# 2. Hapus pustaka dependensi Composer
composer remove xdiego1st/wa-integration</code></pre>
                        <button type="button" onclick="copySnippet('code-uninstall-waku', 'Perintah Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-5 text-center text-xs text-slate-500 mt-auto">
        &copy; {{ date('Y') }} PlatformCore WhatsApp Gateway (Waku Dashboard). Dilindungi Hak Cipta.
    </footer>

    <!-- =============================================================== -->
    <!-- DEDICATED LIVE QR CODE MODAL POPUP                             -->
    <!-- =============================================================== -->
    <div id="modal-qr" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 transition-all">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xl max-w-md w-full overflow-hidden flex flex-col transform transition-all scale-95 opacity-0 duration-200" id="modal-qr-card">
            
            <!-- Modal Header -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-md shadow-emerald-600/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900" id="modal-qr-title">Pindai QR Code WhatsApp</h3>
                        <p class="text-[11px] text-slate-500">Tautkan sesi Baileys Multi-Device (24/7 Steady)</p>
                    </div>
                </div>
                <button type="button" onclick="closeQrModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 text-center space-y-5">
                
                <!-- Status Badge Indicator inside modal -->
                <div id="modal-qr-badge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    <span>Menunggu Pindaian QR Ponsel</span>
                </div>

                <!-- QR Container Area -->
                <div class="flex flex-col items-center justify-center min-h-[260px] relative">
                    <div id="modal-qr-image-wrapper" class="p-3 bg-white border border-slate-200 rounded-2xl shadow-inner relative">
                        <img id="modal-qr-img" src="" alt="WhatsApp QR Code" class="w-64 h-64 object-contain rounded-xl hidden" />
                        
                        <!-- Spinner for loading / initializing -->
                        <div id="modal-qr-spinner" class="w-64 h-64 flex flex-col items-center justify-center space-y-3 bg-slate-50/80 rounded-xl">
                            <div class="w-10 h-10 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin"></div>
                            <span class="text-xs text-slate-500 font-medium">Menghubungkan ke Baileys Gateway...</span>
                        </div>
                    </div>
                </div>

                <!-- Step-by-Step Instructions -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-left text-xs space-y-1 text-slate-600">
                    <div class="font-bold text-slate-800 text-[11px] uppercase tracking-wider">Langkah Pemindaian:</div>
                    <ol class="list-decimal list-inside space-y-0.5 text-[11px] text-slate-600 leading-relaxed">
                        <li>Buka WhatsApp &gt; Menu (⋮) atau Pengaturan</li>
                        <li>Pilih <strong>Perangkat Tertaut</strong> &gt; <strong>Tautkan Perangkat</strong></li>
                        <li>Arahkan kamera ponsel Anda ke kotak QR di atas</li>
                    </ol>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 bg-slate-50/60 border-t border-slate-100 flex items-center justify-between gap-3">
                <button type="button" onclick="connectSession(true)" class="px-3.5 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold flex items-center gap-1.5 transition-colors cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Perbarui QR</span>
                </button>
                <button type="button" onclick="closeQrModal()" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition-colors cursor-pointer">
                    <span>Tutup</span>
                </button>
            </div>
        </div>
    </div>

    <!-- =============================================================== -->
    <!-- JAVASCRIPT LOGIC, POLLING, MULTI-FRAMEWORK & SWEETALERT         -->
    <!-- =============================================================== -->
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let pollingInterval = null;
        let isModalOpen = false;
        let activeTargetSessionId = null;
        const MAX_SESSIONS = {{ $maxSessionsAllowed }};
        const CURRENT_SESSION_COUNT = {{ $activeSessionCount }};

        // --- Tab Switching Logic ---
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-emerald-600', 'text-emerald-700', 'font-bold');
                btn.classList.add('border-transparent', 'text-slate-500', 'font-semibold');
            });

            document.getElementById(tabId).classList.remove('hidden');
            const activeBtn = document.getElementById('btn-' + tabId);
            if (activeBtn) {
                activeBtn.classList.remove('border-transparent', 'text-slate-500', 'font-semibold');
                activeBtn.classList.add('border-emerald-600', 'text-emerald-700', 'font-bold');
            }

            if (tabId === 'tab-connection') {
                startPolling();
            } else if (!isModalOpen) {
                stopPolling();
            }
        }

        // --- Framework Tab Switching Logic ---
        function switchFramework(fwKey) {
            document.querySelectorAll('.fw-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.fw-btn').forEach(btn => {
                btn.classList.remove('bg-emerald-600', 'text-white', 'shadow-sm', 'font-bold');
                btn.classList.add('bg-slate-100', 'text-slate-700', 'font-semibold');
            });

            const targetContent = document.getElementById('fw-' + fwKey);
            const targetBtn = document.getElementById('btn-fw-' + fwKey);
            if (targetContent) targetContent.classList.remove('hidden');
            if (targetBtn) {
                targetBtn.classList.remove('bg-slate-100', 'text-slate-700', 'font-semibold');
                targetBtn.classList.add('bg-emerald-600', 'text-white', 'shadow-sm', 'font-bold');
            }
        }

        // --- Polling Lifecycle ---
        function startPolling() {
            if (pollingInterval) return;
            refreshQrStatus();
            pollingInterval = setInterval(refreshQrStatus, 3000);
        }

        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        // --- Fetch QR & Connection Status ---
        async function refreshQrStatus() {
            try {
                const url = activeTargetSessionId
                    ? `{{ route('waku.qr') }}?session_id=${encodeURIComponent(activeTargetSessionId)}`
                    : `{{ route('waku.qr') }}`;
                const res = await fetch(url);
                const json = await res.json();
                renderConnectionStatus(json);
            } catch (err) {
                console.error("Gagal memeriksa status koneksi:", err);
            }
        }

        // --- Render UI Status ---
        function renderConnectionStatus(res) {
            const container = document.getElementById('connection-display');
            const badge = document.getElementById('badge-status');

            if (!res.success && res.error) {
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200";
                badge.innerText = "Error / Terputus";
                container.innerHTML = `
                    <div class="p-6 text-center space-y-3">
                        <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-2 shadow-inner">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h4 class="font-bold text-slate-800 text-base">Gagal Mengambil Status</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">${res.error}</p>
                    </div>
                `;
                return;
            }

            const data = res.data || {};
            const status = data.status || 'DISCONNECTED';
            const phone = data.phone || '';
            const qrCode = data.qrCode || '';

            // Update Modal if open
            if (isModalOpen) {
                updateModalQrDisplay(status, qrCode, phone);
            }

            if (status === 'CONNECTED') {
                badge.className = "inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300";
                badge.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> TERHUBUNG 24/7 (${phone || 'Aktif'})`;
                
                container.innerHTML = `
                    <div class="p-6 text-center space-y-4">
                        <div class="w-20 h-20 rounded-3xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-inner shadow-emerald-500/10 animate-float">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Perangkat WhatsApp Terhubung Stabil!</h3>
                            <p class="text-xs text-slate-500 mt-1">Nomor Aktif: <strong class="font-mono text-slate-900 text-sm font-bold">${phone}</strong></p>
                            <p class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center justify-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> 24/7 Steady Mode &amp; Anti-Spam Load Balancer Siap
                            </p>
                        </div>
                        <div class="pt-2">
                            <button type="button" onclick="switchTab('tab-send')" class="px-4 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200 transition-colors cursor-pointer">
                                ➔ Uji Kirim Pesan Sekarang
                            </button>
                        </div>
                    </div>
                `;

                if (isModalOpen) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Terhubung!',
                        text: `Nomor WhatsApp ${phone} telah aktif dan dijaga tetap online 24 jam.`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    closeQrModal();
                }

            } else if (status === 'SCAN_QR' || qrCode) {
                badge.className = "inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300";
                badge.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span> Menunggu Scan QR`;

                container.innerHTML = `
                    <div class="p-4 text-center flex flex-col items-center space-y-3">
                        <div class="p-3 bg-white border border-slate-200 rounded-2xl shadow-sm">
                            <img src="${qrCode}" alt="WhatsApp QR Code" class="w-48 h-48 object-contain rounded-xl" />
                        </div>
                        <p class="text-xs font-semibold text-slate-700">Pindai QR Code di atas dengan ponsel Anda, atau perbesar tampilan modal:</p>
                        <button type="button" onclick="openQrModal()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow transition-colors cursor-pointer">
                            Perbesar Tampilan QR Code (Modal)
                        </button>
                    </div>
                `;
            } else {
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200";
                badge.innerText = "Belum Terhubung";

                container.innerHTML = `
                    <div class="p-8 text-center space-y-3">
                        <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-base">Sesi WhatsApp Belum Terhubung</h4>
                            <p class="text-xs text-slate-500 max-w-sm mx-auto mt-0.5">Klik tombol <strong>Hubungkan / Request QR</strong> di bawah untuk memunculkan QR Code pemindaian.</p>
                        </div>
                    </div>
                `;
            }
        }

        // --- Dedicated QR Modal Controls ---
        function openQrModal() {
            isModalOpen = true;
            startPolling();
            const modal = document.getElementById('modal-qr');
            const card = document.getElementById('modal-qr-card');
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeQrModal() {
            isModalOpen = false;
            const modal = document.getElementById('modal-qr');
            const card = document.getElementById('modal-qr-card');
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        async function openQrModalAndConnect() {
            openQrModal();
            document.getElementById('modal-qr-spinner').classList.remove('hidden');
            document.getElementById('modal-qr-img').classList.add('hidden');
            document.getElementById('modal-qr-badge').innerHTML = `<span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span> Menginisialisasi Sesi Baileys...`;
            document.getElementById('modal-qr-badge').className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800 border border-sky-200";

            await connectSession(false);
        }

        function updateModalQrDisplay(status, qrCode, phone) {
            const img = document.getElementById('modal-qr-img');
            const spinner = document.getElementById('modal-qr-spinner');
            const badge = document.getElementById('modal-qr-badge');

            if (qrCode) {
                img.src = qrCode;
                img.classList.remove('hidden');
                spinner.classList.add('hidden');
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200";
                badge.innerHTML = `<span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span> Menunggu Scan WhatsApp`;
            } else if (status === 'CONNECTED') {
                img.classList.add('hidden');
                spinner.classList.remove('hidden');
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200";
                badge.innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-500"></span> Berhasil Terhubung!`;
            }
        }

        // --- Initiate Connect Session ---
        async function connectSession(silent = false) {
            try {
                const payload = activeTargetSessionId ? { session_id: activeTargetSessionId } : {};
                const res = await fetch("{{ route('waku.connect') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify(payload)
                });
                const json = await res.json();
                
                if (json.pairing && json.pairing.qrCode) {
                    updateModalQrDisplay(json.pairing.status, json.pairing.qrCode, json.pairing.phone);
                }

                await refreshQrStatus();
            } catch (err) {
                if (!silent) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menghubungkan',
                        text: err.message
                    });
                }
            }
        }

        async function connectSessionSpecific(sessionId) {
            activeTargetSessionId = sessionId;
            await openQrModalAndConnect();
        }

        // --- Disconnect Session ---
        async function disconnectSession() {
            const result = await Swal.fire({
                title: 'Putuskan Sesi WhatsApp?',
                text: 'Perangkat ponsel akan terputus dari gateway. Anda perlu memindai QR Code kembali untuk menghubungkan ulang.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Putuskan Sesi',
                cancelButtonText: 'Batal'
            });

            if (!result.isConfirmed) return;

            try {
                const payload = activeTargetSessionId ? { session_id: activeTargetSessionId } : {};
                await fetch("{{ route('waku.disconnect') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify(payload)
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Sesi Diputuskan',
                    timer: 1500,
                    showConfirmButton: false
                });

                await refreshQrStatus();
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        async function disconnectSessionSpecific(sessionId, sessionName) {
            activeTargetSessionId = sessionId;
            await disconnectSession();
        }

        // --- ★ HAPUS SESI KONSUMEN (DELETE SESSION WITH SWEETALERT2) ---
        async function deleteSessionById(sessionId, sessionName) {
            const result = await Swal.fire({
                title: 'Hapus Sesi WhatsApp?',
                html: `Apakah Anda yakin ingin menghapus sesi <strong>${sessionName}</strong>?<br><span class="text-xs text-rose-500 font-medium">Seluruh berkas autentikasi pada server gateway akan dihapus permanen.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Sesi',
                cancelButtonText: 'Batal',
                focusCancel: true
            });

            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Menghapus Sesi...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const res = await fetch("{{ url($config['dashboardUrl'] ?? 'waku/dashboard') }}/sessions/" + encodeURIComponent(sessionId) + "/delete", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });
                const json = await res.json();

                if (json.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Sesi Berhasil Dihapus',
                        text: `Sesi "${sessionName}" telah dihapus secara permanen.`,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menghapus Sesi',
                        text: json.error || 'Terjadi kesalahan saat menghapus sesi.'
                    });
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        // --- Prompt Create Session with Quota Validation ---
        async function promptCreateSession() {
            if (CURRENT_SESSION_COUNT >= MAX_SESSIONS) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Kuota Sesi Tercapai',
                    html: `Aplikasi Anda telah menggunakan <strong>${CURRENT_SESSION_COUNT} dari ${MAX_SESSIONS}</strong> sesi maksimum yang diizinkan.<br><br><span class="text-xs text-slate-500">Silakan hapus sesi yang tidak aktif terlebih dahulu, atau hubungi <strong>Super Admin</strong> untuk menambah kuota sesi aplikasi ini.</span>`,
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'Mengerti'
                });
                return;
            }

            const { value: sessionName } = await Swal.fire({
                title: 'Buat Sesi WhatsApp Baru',
                input: 'text',
                inputLabel: `Nama Sesi (Kuota Tersedia: ${MAX_SESSIONS - CURRENT_SESSION_COUNT} Sesi)`,
                inputPlaceholder: 'Contoh: CS Pemasaran / Operasional...',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Buat Sesi',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value || !value.trim()) {
                        return 'Nama sesi tidak boleh kosong!';
                    }
                }
            });

            if (sessionName) {
                try {
                    const res = await fetch("{{ route('waku.create-session') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                        body: JSON.stringify({ name: sessionName.trim() })
                    });
                    const json = await res.json();
                    if (json.success) {
                        Swal.fire('Berhasil', `Sesi "${sessionName}" berhasil dibuat.`, 'success');
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        Swal.fire('Gagal Membuat Sesi', json.error || 'Terjadi kesalahan pada gateway.', 'error');
                    }
                } catch (err) {
                    Swal.fire('Error', err.message, 'error');
                }
            }
        }

        // --- Live WhatsApp Chat Bubble Preview Updater ---
        function updateLiveChatPreview() {
            const content = document.getElementById('send-content').value;
            const bubble = document.getElementById('chat-preview-text');
            bubble.innerText = content || 'Halo! Ini adalah pesan uji coba dari Waku WhatsApp Gateway...';
        }

        function handleTypeChange() {
            const type = document.getElementById('send-type').value;
            const mediaUrlCont = document.getElementById('media-url-container');
            if (type === 'IMAGE' || type === 'DOCUMENT') {
                mediaUrlCont.classList.remove('hidden');
            } else {
                mediaUrlCont.classList.add('hidden');
            }
        }

        // --- Handle Test Send Message ---
        async function handleTestSend(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-send');
            const resultBox = document.getElementById('send-result-box');

            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin h-4 w-4 text-white inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim Pesan...`;

            const payload = {
                to: document.getElementById('send-to').value,
                type: document.getElementById('send-type').value,
                content: document.getElementById('send-content').value,
                session_id: document.getElementById('send-session-id').value || null,
                media_url: document.getElementById('send-media-url') ? document.getElementById('send-media-url').value : null,
            };

            try {
                const res = await fetch("{{ route('waku.send') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify(payload)
                });
                const json = await res.json();

                if (json.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pesan Terkirim!',
                        text: `Pesan berhasil dikirim via gateway. ID: ${json.message_id || 'OK'}`,
                        confirmButtonColor: '#10b981'
                    });

                    resultBox.className = "p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900";
                    resultBox.innerHTML = `<strong>SUKSES:</strong> Pesan berhasil dikirim! ID Pesan: <code>${json.message_id}</code>`;
                    resultBox.classList.remove('hidden');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengirim',
                        text: json.error || 'Terjadi kesalahan saat pengiriman.',
                        confirmButtonColor: '#e11d48'
                    });

                    resultBox.className = "p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900";
                    resultBox.innerHTML = `<strong>GAGAL:</strong> ${json.error}`;
                    resultBox.classList.remove('hidden');
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg> Kirim Pesan Sekarang`;
            }
        }

        // --- Helper: Copy Snippet to Clipboard with Toast ---
        function copySnippet(elementIdOrText, successText = 'Teks Disalin!') {
            const el = document.getElementById(elementIdOrText);
            const textToCopy = el ? el.innerText : elementIdOrText;
            navigator.clipboard.writeText(textToCopy);

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: successText
            });
        }

        // --- Quick Templates Handler ---
        function applyQuickTemplate(type) {
            const el = document.getElementById('send-content');
            if (!el) return;
            if (type === 'otp') {
                el.value = '*[DISKOMINFOTIKSAN]*\nKode verifikasi keamanan OTP Anda adalah *' + Math.floor(100000 + Math.random() * 900000) + '*.\n\nJangan berikan kode ini kepada siapapun demi keamanan akun Anda.';
            } else if (type === 'doc') {
                el.value = '*[NOTIFIKASI SISTEM]*\nDokumen permohonan #DOC-' + Math.floor(1000 + Math.random() * 9000) + ' telah berhasil ditandatangani secara elektronik (BSrE) dan siap diunduh.';
            } else if (type === 'bill') {
                el.value = '*[PENGINGAT RESMI]*\nTagihan retribusi daerah periode ini telah diterbitkan. Silakan selesaikan pembayaran sebelum tanggal jatuh tempo.';
            } else if (type === 'presensi') {
                el.value = '*[PRESENSI HARIAN]*\nPengingat presensi pagi: Silakan lakukan rekam kehadiran pada aplikasi sebelum pukul 08:00 WIB. Terima kasih.';
            }
            updateLiveChatPreview();
        }

        // --- Filter Log Table Real-Time ---
        function filterLogTable() {
            const query = (document.getElementById('log-search-input')?.value || '').toLowerCase();
            const rows = document.querySelectorAll('#tab-messages tbody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        }

        // Initialize polling on page load
        document.addEventListener('DOMContentLoaded', () => {
            startPolling();
        });
    </script>
</body>
</html>
