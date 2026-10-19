<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Waku Dashboard v1.2.2 — Panel Kontrol WhatsApp Gateway Multi-Device &amp; BYOK AI</title>
    
    <!-- Standalone Tailwind CSS & Curated Typography -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        slate: {
                            850: '#131b2e',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
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
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

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

        @keyframes radar-sweep {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .animate-radar { animation: radar-sweep 4s linear infinite; }

        /* Premium Glassmorphism */
        .glass-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(51, 65, 85, 0.4);
        }
        html:not(.dark) .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        .glass-header {
            background: rgba(2, 6, 23, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(30, 41, 59, 0.7);
        }
        html:not(.dark) .glass-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Ambient background glow */
        .ambient-glow {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 1000px;
            height: 450px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, rgba(13, 148, 136, 0.05) 50%, transparent 80%);
            pointer-events: none;
            z-index: 0;
        }

        /* WhatsApp Chat Wallpaper Pattern */
        .wa-bg-pattern {
            background-color: #0b141a;
            background-image: radial-gradient(#1f2c34 1px, transparent 1px);
            background-size: 18px 18px;
        }
        html:not(.dark) .wa-bg-pattern {
            background-color: #efeae2;
            background-image: radial-gradient(#d1d7db 1px, transparent 1px);
            background-size: 18px 18px;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white transition-colors duration-200">

    <div class="ambient-glow"></div>

    @php
        $maxSessionsAllowed = $appInfo['maxSessions'] ?? 2;
        $activeSessionCount = count($sessions);
        $quotaPercentage = min(100, round(($activeSessionCount / max(1, $maxSessionsAllowed)) * 100));
        $connectedCount = collect($sessions)->where('status', 'CONNECTED')->count();
        $botsList = $bots ?? [];
        $activeBotCount = count($botsList);
        $defaultBotKey = $config['defaultBotKey'] ?? env('WAKU_DEFAULT_BOT_KEY');
    @endphp

    <!-- Top Navigation Header -->
    <header class="glass-header sticky top-0 z-40 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-4">
                
                <!-- Left: Branding & Tag -->
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-gradient-to-br from-emerald-400 via-teal-500 to-teal-800 text-white flex items-center justify-center shadow-lg shadow-emerald-500/25 shrink-0 animate-float border border-emerald-300/30">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.532 1.776.814 2.796.814 3.18 0 5.765-2.586 5.766-5.766 0-3.18-2.586-5.766-5.766-5.766zm9.969 5.766c0 5.485-4.464 9.948-9.969 9.948-1.709 0-3.32-.44-4.733-1.213l-5.298 1.389 1.417-5.174c-.878-1.488-1.386-3.226-1.386-5.088 0-5.486 4.464-9.95 9.969-9.95s9.969 4.464 9.969 9.95z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-base sm:text-lg font-extrabold text-slate-100 dark:text-slate-100 tracking-tight flex items-center gap-2">
                                <span>Waku Dashboard</span>
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-xs shadow-emerald-500/20">v1.2.2</span>
                            <span class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-500/10 text-teal-300 border border-teal-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> 24/7 Steady
                            </span>
                            <span class="hidden md:inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-300 border border-purple-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span> BYOK AI Ready
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 truncate hidden sm:block">Panel Kontrol WhatsApp Multi-Device, Anti-Spam Balancer &amp; Integrasi Bot BYOK</p>
                    </div>
                </div>

                <!-- Right: Tenant Info, Auth User & Theme Switcher -->
                <div class="flex items-center gap-2 sm:gap-3">
                    @if($config['appId'])
                        <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 text-xs shadow-xs">
                            <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-slate-400 font-medium">Tenant:</span>
                            <button type="button" onclick="copySnippet('{{ $config['appId'] }}', 'Tenant ID Disalin!')" class="font-mono font-bold text-slate-200 hover:text-emerald-400 transition-colors flex items-center gap-1.5" title="Klik untuk salin Tenant ID">
                                {{ Str::limit($config['appId'], 15) }}
                                <svg class="w-3.5 h-3.5 text-slate-400 hover:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                    @endif

                    @if(isset($currentUser) && $currentUser)
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-950/60 border border-emerald-800/60 text-xs">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400 text-slate-950 flex items-center justify-center font-black text-[11px] shadow-sm">
                                {{ strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="font-bold text-slate-200 leading-tight">{{ Str::limit($currentUser['name'], 14) }}</div>
                                <div class="text-[10px] text-emerald-400 font-mono">{{ $currentUser['role'] ?? 'User' }}</div>
                            </div>
                        </div>
                    @endif

                    <!-- Theme Toggle (Dark / Light) -->
                    <button type="button" onclick="toggleTheme()" class="p-2 sm:px-2.5 sm:py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:border-slate-700 transition-colors text-xs flex items-center gap-1.5 shadow-sm" title="Beralih Tema (Dark/Light)">
                        <svg id="theme-icon-dark" class="w-4 h-4 text-amber-400 hidden" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <svg id="theme-icon-light" class="w-4 h-4 text-sky-400" fill="currentColor" viewBox="0 0 24 24"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <span class="hidden lg:inline text-[11px] font-medium" id="theme-label">Mode Terang</span>
                    </button>
                </div>
            </div>

            <!-- Navigation Tabs Bar -->
            <nav class="flex space-x-1.5 sm:space-x-3 border-t border-slate-800/80 pt-2 -mb-px overflow-x-auto no-scrollbar" aria-label="Tabs">
                <button type="button" onclick="switchTab('tab-connection')" id="btn-tab-connection" class="tab-btn inline-flex items-center gap-2 py-2.5 px-3 text-xs sm:text-sm font-bold border-b-2 border-emerald-500 text-emerald-400 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Koneksi WhatsApp</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-mono bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">{{ $activeSessionCount }}/{{ $maxSessionsAllowed }}</span>
                </button>

                <button type="button" onclick="switchTab('tab-byok-bot')" id="btn-tab-byok-bot" class="tab-btn inline-flex items-center gap-2 py-2.5 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Bot &amp; BYOK AI</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30 animate-pulse">v1.2.2</span>
                </button>

                <button type="button" onclick="switchTab('tab-send')" id="btn-tab-send" class="tab-btn inline-flex items-center gap-2 py-2.5 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>Uji Kirim Pesan &amp; Mockup</span>
                </button>

                <button type="button" onclick="switchTab('tab-messages')" id="btn-tab-messages" class="tab-btn inline-flex items-center gap-2 py-2.5 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Riwayat Pesan</span>
                </button>

                <button type="button" onclick="switchTab('tab-api-guide')" id="btn-tab-api-guide" class="tab-btn inline-flex items-center gap-2 py-2.5 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    <span>Integrasi Code</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">7 Stacks</span>
                </button>

                <button type="button" onclick="switchTab('tab-lifecycle')" id="btn-tab-lifecycle" class="tab-btn inline-flex items-center gap-2 py-2.5 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Role &amp; Konfigurasi</span>
                </button>
            </nav>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 flex-1 w-full space-y-6 relative z-10">

        <!-- Banner Peringatan Kredensial -->
        @if(!$config['hasApiKey'])
            <div class="rounded-2xl p-4 sm:p-5 bg-gradient-to-r from-amber-950/80 to-amber-900/60 border border-amber-600/40 text-amber-200 flex items-start gap-3.5 shadow-lg shadow-amber-950/30">
                <svg class="w-6 h-6 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="text-xs sm:text-sm">
                    <p class="font-bold text-amber-300">Variabel Lingkungan Belum Lengkap!</p>
                    <p class="text-amber-200/90 mt-1">
                        Kunci API belum terkonfigurasi. Jalankan <code class="font-mono bg-amber-950/90 px-1.5 py-0.5 rounded text-amber-300 font-bold">php artisan waku:install</code> atau definisikan <code class="font-mono bg-amber-950/90 px-1.5 py-0.5 rounded text-amber-300 font-bold">WAKU_API_KEY</code> di berkas <code class="font-mono bg-amber-950/90 px-1.5 py-0.5 rounded text-amber-300">.env</code> Anda.
                    </p>
                </div>
            </div>
        @endif

        <!-- =============================================================== -->
        <!-- TAB 1: KONEKSI WHATSAPP, KUOTA SESI & ACTION CARDS              -->
        <!-- =============================================================== -->
        <div id="tab-connection" class="tab-content space-y-6">

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
                
                <!-- Stat 1: Kuota Sesi Card -->
                <div class="glass-card rounded-2xl p-4 sm:p-5 relative overflow-hidden group hover:border-emerald-500/50 transition-all shadow-md">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-400">Kuota Sesi Aktif</span>
                        <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-black text-slate-100 font-mono">{{ $activeSessionCount }}</span>
                        <span class="text-xs text-slate-400 font-mono">/ {{ $maxSessionsAllowed }} Sesi</span>
                    </div>
                    <div class="mt-3 w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-1.5 rounded-full transition-all duration-500" style="width: {{ $quotaPercentage }}%"></div>
                    </div>
                </div>

                <!-- Stat 2: Anti-Spam Load Balancer Status -->
                <div class="glass-card rounded-2xl p-4 sm:p-5 relative overflow-hidden group hover:border-teal-500/50 transition-all shadow-md">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-400">Anti-Spam Balancer</span>
                        <span class="p-2 rounded-xl bg-teal-500/10 text-teal-400 border border-teal-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-sm sm:text-base font-bold text-teal-300">Rotasi Otomatis</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1 truncate">
                        {{ $activeSessionCount > 1 ? 'Mendistribusikan beban ke ' . $activeSessionCount . ' nomor' : 'Standby untuk multi-nomor' }}
                    </p>
                </div>

                <!-- Stat 3: 24/7 Steady Watchdog -->
                <div class="glass-card rounded-2xl p-4 sm:p-5 relative overflow-hidden group hover:border-sky-500/50 transition-all shadow-md">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-400">24/7 Watchdog</span>
                        <span class="p-2 rounded-xl bg-sky-500/10 text-sky-400 border border-sky-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </span>
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-sm sm:text-base font-bold text-emerald-400">Aktif Terjaga</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1 truncate">Auto-reconnect tanpa putus</p>
                </div>

                <!-- Stat 4: Total Nomor Terhubung -->
                <div class="glass-card rounded-2xl p-4 sm:p-5 relative overflow-hidden group hover:border-purple-500/50 transition-all shadow-md">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-400">Nomor Terhubung</span>
                        <span class="p-2 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-black text-slate-100 font-mono">{{ $connectedCount }}</span>
                        <span class="text-xs text-slate-400 font-mono">Perangkat</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1 truncate">Siap kirim notifikasi massal</p>
                </div>
            </div>

            <!-- Two-Column Layout: Left Hero Status & Right Session Management -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left: Hero Connection Status Card (5 cols) -->
                <div class="lg:col-span-5 glass-card rounded-3xl p-6 sm:p-8 flex flex-col justify-between relative overflow-hidden shadow-xl border-emerald-500/20">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Perangkat Utama</span>
                            <span id="session-status-badge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                Memuat status...
                            </span>
                        </div>

                        <!-- Main Dynamic Connection Presentation -->
                        <div id="connection-presentation-container" class="rounded-2xl border border-slate-800 bg-slate-900/60 p-4 transition-all">
                            <div class="p-6 text-center space-y-3">
                                <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center mx-auto animate-pulse">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-xs text-slate-400">Sinkronisasi status gateway real-time...</p>
                            </div>
                        </div>

                        <!-- Action Controls -->
                        <div class="grid grid-cols-2 gap-2.5 pt-2">
                            <button type="button" onclick="openQrModalAndConnect()" class="w-full py-2.5 px-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-500/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span>Scan QR Code</span>
                            </button>
                            <button type="button" onclick="disconnectSession()" class="w-full py-2.5 px-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-rose-400 hover:text-rose-300 border border-slate-800 font-semibold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                <span>Putuskan Sesi</span>
                            </button>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-800/80 mt-4 flex items-center justify-between text-xs text-slate-400">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Protokol Baileys Multi-Device
                        </span>
                        <button type="button" onclick="refreshQrStatus()" class="hover:text-emerald-400 transition-colors flex items-center gap-1 font-mono text-[11px]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Refresh Status
                        </button>
                    </div>
                </div>

                <!-- Right: Active Sessions Management & Quota Card (7 cols) -->
                <div class="lg:col-span-7 glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-100 flex items-center gap-2">
                                <span>Daftar Sesi WhatsApp Konsumen</span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                    {{ $activeSessionCount }} / {{ $maxSessionsAllowed }}
                                </span>
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Kelola sesi mandiri. Hubungkan multi-nomor untuk load balancer anti-blokir.</p>
                        </div>

                        <button type="button" onclick="promptCreateSession()" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Tambah Sesi Baru</span>
                        </button>
                    </div>

                    <!-- Session Items List -->
                    <div class="space-y-3">
                        @forelse($sessions as $session)
                            @php
                                $isConn = ($session['status'] ?? '') === 'CONNECTED';
                                $sessPhone = $session['phone'] ?? null;
                                $sessName = $session['name'] ?? 'Sesi WhatsApp';
                                $sessId = $session['id'] ?? '';
                            @endphp
                            <div class="p-4 rounded-2xl bg-slate-900/70 border {{ $isConn ? 'border-emerald-500/30' : 'border-slate-800' }} hover:border-slate-700 transition-all flex items-center justify-between gap-3 shadow-xs">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-10 h-10 rounded-xl {{ $isConn ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }} flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-sm text-slate-200 truncate">{{ $sessName }}</h4>
                                            @if($isConn)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Terhubung
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">
                                                    Belum Terhubung
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-slate-400 mt-0.5 flex-wrap">
                                            @if($sessPhone)
                                                <span class="font-mono text-slate-300 font-semibold">{{ $sessPhone }}</span>
                                                <span>•</span>
                                            @endif
                                            <span class="font-mono text-[11px] text-slate-500">ID: {{ Str::limit($sessId, 16) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    @if($isConn)
                                        <button type="button" onclick="disconnectSessionSpecific('{{ $sessId }}', '{{ $sessName }}')" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-rose-400 border border-slate-700 text-xs font-semibold transition-colors" title="Putuskan Sesi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        </button>
                                    @else
                                        <button type="button" onclick="connectSessionSpecific('{{ $sessId }}')" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                            <span>Hubungkan</span>
                                        </button>
                                    @endif

                                    <!-- Tombol Hapus Sesi -->
                                    <button type="button" onclick="deleteSessionById('{{ $sessId }}', '{{ $sessName }}')" class="p-2 rounded-xl bg-rose-950/40 hover:bg-rose-900/60 text-rose-400 hover:text-rose-200 border border-rose-800/40 text-xs font-semibold transition-colors" title="Hapus Sesi Secara Permanen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center rounded-2xl border border-dashed border-slate-800 bg-slate-900/40">
                                <p class="text-xs text-slate-400">Belum ada sesi terdaftar. Klik <strong>Tambah Sesi Baru</strong> di atas untuk memulai.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Anti-Spam Notice -->
                    <div class="p-4 rounded-2xl bg-teal-950/40 border border-teal-800/40 text-xs text-teal-200/90 flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <span class="font-bold text-teal-300">Proteksi Anti-Spam Berbasis Load Balancer:</span>
                            <span class="text-teal-200/80"> Pesan keluar otomatis dirotasi secara seimbang antar-nomor yang aktif terhubung untuk mencegah pemblokiran nomor oleh WhatsApp.</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- =============================================================== -->
        <!-- TAB 2: BOT & BYOK AI MANAGEMENT (BARU DI v1.2.2)               -->
        <!-- =============================================================== -->
        <div id="tab-byok-bot" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl border-purple-500/20">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="p-2 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <h2 class="text-base sm:text-lg font-bold text-slate-100">Multi-Tenant Bot Key &amp; BYOK (Bring Your Own Key)</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">v1.2.2 Release</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Setiap Bot memiliki <strong>Bot Key</strong> terisolasi (<code class="font-mono text-purple-300">wbk_live_...</code>) dan kredensial AI Provider (OpenRouter, Gemini, OpenAI) terenkripsi AES-256-GCM.</p>
                    </div>

                    @if($defaultBotKey)
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-purple-950/60 border border-purple-800/60 text-xs">
                            <span class="text-slate-400 font-medium">Default Bot Key:</span>
                            <button type="button" onclick="copySnippet('{{ $defaultBotKey }}', 'Bot Key Disalin!')" class="font-mono font-bold text-purple-300 hover:text-purple-200 flex items-center gap-1.5">
                                {{ Str::limit($defaultBotKey, 18) }}
                                <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Bot List Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($botsList as $bot)
                        @php
                            $bName = $bot['name'] ?? 'AI CS Bot';
                            $bKey = $bot['botKey'] ?? 'wbk_live_...';
                            $bProv = $bot['providerType'] ?? 'OPENROUTER';
                            $bModel = $bot['modelName'] ?? 'meta-llama/llama-3.3-70b-instruct:free';
                            $bStatus = $bot['status'] ?? 'ACTIVE';
                        @endphp
                        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-purple-500/40 transition-all space-y-3.5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center font-bold text-xs border border-purple-500/20">
                                        AI
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-slate-200">{{ $bName }}</h4>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $bProv }}</span>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $bStatus === 'ACTIVE' ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }}">
                                    {{ $bStatus }}
                                </span>
                            </div>

                            <div class="space-y-1">
                                <div class="text-[11px] text-slate-400 font-medium">Model AI:</div>
                                <div class="p-2 rounded-xl bg-slate-950 border border-slate-800 font-mono text-xs text-slate-300 truncate">
                                    {{ $bModel }}
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-slate-400 font-medium">Bot Key:</span>
                                    <button type="button" onclick="copySnippet('{{ $bKey }}', 'Bot Key Disalin!')" class="text-purple-400 hover:text-purple-300 font-bold transition-colors">
                                        Salin
                                    </button>
                                </div>
                                <div class="p-2 rounded-xl bg-slate-950 border border-slate-800 font-mono text-[11px] text-purple-300 truncate flex items-center justify-between">
                                    <span>{{ Str::limit($bKey, 20) }}</span>
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-8 text-center rounded-2xl border border-dashed border-slate-800 bg-slate-900/40 space-y-2">
                            <p class="text-sm font-semibold text-slate-300">Belum Ada Bot Terdaftar Pada Tenant Ini</p>
                            <p class="text-xs text-slate-400 max-w-md mx-auto">
                                Buka Dashboard PlatformCore master di <code class="font-mono text-emerald-400">/dashboard/ai-cs/settings</code> untuk menambahkan bot baru atau mengonfigurasi BYOK provider Anda.
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Code Example: withBot usage -->
                <div class="space-y-2.5 pt-2">
                    <h3 class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        <span>Contoh Penggunaan Bot Key di Laravel (v1.2.2)</span>
                    </h3>
                    <div class="relative group">
                        <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed border border-slate-800"><code id="code-snippet-byok">// 1. Kirim pesan dengan Bot Key spesifik
use WaIntegration\Laravel\Facades\WhatsApp;

WhatsApp::withBot('{{ $defaultBotKey ?? "wbk_live_sample_key" }}')
    ->send('08123456789', 'Halo! CS Virtual siap melayani pertanyaan Anda.');

// 2. Ambil daftar Bot yang tersedia di tenant Anda
$bots = WhatsApp::getBots();</code></pre>
                        <button type="button" onclick="copySnippet('code-snippet-byok', 'Kode Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors border border-slate-700">Salin Kode</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB 3: INTERACTIVE TEST MESSAGE SANDBOX (WITH PHONE MOCKUP)     -->
        <!-- =============================================================== -->
        <div id="tab-send" class="tab-content hidden space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left: Form Controls (7 cols) -->
                <div class="lg:col-span-7 glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-100">Uji Coba Pengiriman Pesan Real-Time</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Kirim pesan WhatsApp langsung melalui sesi gateway yang terhubung secara instan.</p>
                    </div>

                    <!-- Quick Template Chips -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-400">Pilih Template Pesan Instan:</label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="applyQuickTemplate('otp')" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5">
                                <span>🔐</span>
                                <span>Verifikasi OTP</span>
                            </button>
                            <button type="button" onclick="applyQuickTemplate('doc')" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5">
                                <span>📄</span>
                                <span>Dokumen BSrE</span>
                            </button>
                            <button type="button" onclick="applyQuickTemplate('bill')" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5">
                                <span>💳</span>
                                <span>Tagihan Retribusi</span>
                            </button>
                            <button type="button" onclick="applyQuickTemplate('presensi')" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5">
                                <span>⏰</span>
                                <span>Presensi Pagi</span>
                            </button>
                        </div>
                    </div>

                    <!-- Main Send Form -->
                    <form id="form-test-send" onsubmit="handleTestSend(event)" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="send-to" class="block text-xs font-semibold text-slate-300 mb-1">Nomor WhatsApp Tujuan *</label>
                                <input type="text" id="send-to" required placeholder="08123456789 atau 628123456789" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 text-xs sm:text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all">
                            </div>
                            <div>
                                <label for="send-type" class="block text-xs font-semibold text-slate-300 mb-1">Tipe Pesan</label>
                                <select id="send-type" onchange="handleTypeChange()" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 text-xs sm:text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all">
                                    <option value="TEXT">Pesan Teks (Standard)</option>
                                    <option value="IMAGE">Gambar / Foto (Image)</option>
                                    <option value="DOCUMENT">Dokumen / PDF (Document)</option>
                                </select>
                            </div>
                        </div>

                        <div id="media-url-container" class="hidden">
                            <label for="send-media-url" class="block text-xs font-semibold text-slate-300 mb-1">URL Media (HTTPS)</label>
                            <input type="url" id="send-media-url" placeholder="https://domain.com/storage/invoice-123.pdf" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 text-xs font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all">
                        </div>

                        <div>
                            <label for="send-content" class="block text-xs font-semibold text-slate-300 mb-1">Konten Pesan WhatsApp *</label>
                            <textarea id="send-content" required rows="4" oninput="updateLiveChatPreview()" placeholder="Ketik isi pesan WhatsApp di sini..." class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 text-xs sm:text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all leading-relaxed"></textarea>
                        </div>

                        <div>
                            <label for="send-session-id" class="block text-xs font-semibold text-slate-300 mb-1">Pilih Sesi Pengirim</label>
                            <select id="send-session-id" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 text-xs sm:text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all">
                                <option value="">Otomatis (Rotasi Load Balancer Anti-Spam)</option>
                                @foreach($sessions as $sess)
                                    <option value="{{ $sess['id'] }}">
                                        {{ $sess['name'] }} ({{ $sess['phone'] ?? 'Belum Connect' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-2">
                            <button type="submit" id="btn-submit-send" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold text-sm shadow-lg shadow-emerald-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span>Kirim Pesan Sekarang</span>
                            </button>
                        </div>

                        <div id="send-result-box" class="hidden text-xs rounded-xl p-3.5 transition-all"></div>
                    </form>
                </div>

                <!-- Right: Realistic WhatsApp Mobile Mockup Preview (5 cols) -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="w-full max-w-[340px] rounded-[44px] p-3.5 bg-slate-900 border-4 border-slate-800 shadow-2xl relative overflow-hidden">
                        
                        <!-- Dynamic Island / Speaker Notch -->
                        <div class="w-24 h-4 bg-slate-950 rounded-full mx-auto mb-2 flex items-center justify-center">
                            <div class="w-2 h-2 rounded-full bg-slate-800"></div>
                        </div>

                        <!-- Screen Content -->
                        <div class="rounded-[32px] overflow-hidden border border-slate-800/80 flex flex-col h-[490px] wa-bg-pattern shadow-inner relative">
                            
                            <!-- WhatsApp Chat Header -->
                            <div class="bg-[#1f2c34] text-white px-3.5 py-3 flex items-center justify-between shadow-sm">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-400 to-teal-300 text-slate-950 flex items-center justify-center font-black text-xs shadow-sm">
                                        WA
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs leading-tight">Waku Support Bot</div>
                                        <div class="text-[10px] text-emerald-400 font-medium">Online</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-slate-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                </div>
                            </div>

                            <!-- Chat Messages Area -->
                            <div class="p-3 flex-1 flex flex-col justify-end space-y-3 overflow-y-auto no-scrollbar">
                                
                                <div class="self-center px-2.5 py-1 rounded-lg bg-[#182229] text-[10px] text-slate-400 font-mono shadow-xs border border-slate-800">
                                    HARI INI
                                </div>

                                <!-- Dynamic Outgoing Bubble -->
                                <div class="self-end max-w-[85%] rounded-2xl rounded-tr-none bg-[#005c4b] text-white p-3 shadow-md text-xs relative space-y-1">
                                    <div id="chat-preview-text" class="leading-relaxed whitespace-pre-line break-words">
                                        Halo! Ini adalah pratinjau pesan real-time WhatsApp Gateway.
                                    </div>
                                    <div class="flex items-center justify-end gap-1 text-[10px] text-emerald-200/80">
                                        <span>{{ date('H:i') }}</span>
                                        <svg class="w-3.5 h-3.5 text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 13l2 2L21 7"/></svg>
                                    </div>
                                </div>

                            </div>

                            <!-- Mock Input Bar -->
                            <div class="bg-[#1f2c34] p-2 flex items-center gap-2 border-t border-slate-800">
                                <div class="flex-1 px-3 py-1.5 rounded-full bg-[#2a3942] text-[11px] text-slate-400 flex items-center justify-between">
                                    <span>Ketik pesan...</span>
                                    <span>😊</span>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB 4: RIWAYAT PESAN (MESSAGE LOGS)                             -->
        <!-- =============================================================== -->
        <div id="tab-messages" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-100">Riwayat Pesan Keluar (Recent Logs)</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Memantau status pengiriman pesan WhatsApp secara transparan.</p>
                    </div>

                    <!-- Search Input -->
                    <div class="w-full sm:w-64">
                        <input type="text" id="log-search-input" oninput="filterLogTable()" placeholder="Cari nomor / pesan..." class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 text-xs focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <!-- Messages Table -->
                <div class="overflow-x-auto rounded-2xl border border-slate-800">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-900/90 text-slate-400 font-semibold border-b border-slate-800">
                            <tr>
                                <th class="p-3.5">Waktu</th>
                                <th class="p-3.5">Tujuan</th>
                                <th class="p-3.5">Tipe</th>
                                <th class="p-3.5">Pesan</th>
                                <th class="p-3.5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80 text-slate-300">
                            @forelse($messages as $msg)
                                @php
                                    $status = strtoupper($msg['status'] ?? 'SENT');
                                    $statusClass = match($status) {
                                        'DELIVERED', 'READ' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                        'SENT' => 'bg-sky-500/15 text-sky-400 border-sky-500/30',
                                        'FAILED' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                                        default => 'bg-slate-800 text-slate-400 border-slate-700',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-900/50 transition-colors">
                                    <td class="p-3.5 font-mono text-[11px] text-slate-400 whitespace-nowrap">
                                        {{ isset($msg['createdAt']) ? date('d M Y H:i', strtotime($msg['createdAt'])) : '-' }}
                                    </td>
                                    <td class="p-3.5 font-mono font-semibold text-slate-200 whitespace-nowrap">
                                        {{ $msg['to'] ?? '-' }}
                                    </td>
                                    <td class="p-3.5 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-slate-800 text-slate-300 border border-slate-700">
                                            {{ $msg['type'] ?? 'TEXT' }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 max-w-xs truncate text-slate-300">
                                        {{ $msg['content'] ?? '-' }}
                                    </td>
                                    <td class="p-3.5 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $statusClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500">
                                        Belum ada riwayat pesan keluar. Lakukan uji kirim pesan untuk memulai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB 5: INTEGRASI MULTI-FRAMEWORK HUB (7 STACKS)                 -->
        <!-- =============================================================== -->
        <div id="tab-api-guide" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-100">Panduan Integrasi Kode Multi-Stack (7 Frameworks)</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Integrasikan pengiriman pesan WhatsApp ke dalam stack teknologi apapun secara instan.</p>
                </div>

                <!-- Framework Tabs -->
                <div class="flex space-x-2 border-b border-slate-800 pb-2 overflow-x-auto no-scrollbar">
                    <button type="button" onclick="switchStack('laravel')" id="btn-stack-laravel" class="stack-btn px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold transition-all">Laravel (PHP)</button>
                    <button type="button" onclick="switchStack('nodejs')" id="btn-stack-nodejs" class="stack-btn px-3 py-1.5 rounded-xl bg-slate-900 text-slate-400 hover:text-white text-xs font-semibold transition-all">Node.js / Express</button>
                    <button type="button" onclick="switchStack('nextjs')" id="btn-stack-nextjs" class="stack-btn px-3 py-1.5 rounded-xl bg-slate-900 text-slate-400 hover:text-white text-xs font-semibold transition-all">Next.js 14 / React</button>
                    <button type="button" onclick="switchStack('php')" id="btn-stack-php" class="stack-btn px-3 py-1.5 rounded-xl bg-slate-900 text-slate-400 hover:text-white text-xs font-semibold transition-all">PHP Native (cURL)</button>
                    <button type="button" onclick="switchStack('python')" id="btn-stack-python" class="stack-btn px-3 py-1.5 rounded-xl bg-slate-900 text-slate-400 hover:text-white text-xs font-semibold transition-all">Python</button>
                    <button type="button" onclick="switchStack('golang')" id="btn-stack-golang" class="stack-btn px-3 py-1.5 rounded-xl bg-slate-900 text-slate-400 hover:text-white text-xs font-semibold transition-all">Golang</button>
                    <button type="button" onclick="switchStack('curl')" id="btn-stack-curl" class="stack-btn px-3 py-1.5 rounded-xl bg-slate-900 text-slate-400 hover:text-white text-xs font-semibold transition-all">cURL / CLI</button>
                </div>

                <!-- Code Container -->
                <div class="relative group">
                    <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-5 rounded-2xl overflow-x-auto leading-relaxed border border-slate-800"><code id="code-stack-display">// Memuat snippet kode...</code></pre>
                    <button type="button" onclick="copyCurrentSnippet()" class="absolute top-4 right-4 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-mono transition-colors border border-slate-700">
                        Salin Kode
                    </button>
                </div>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB 6: KONFIGURASI, HAK AKSES & LIFE CYCLE                      -->
        <!-- =============================================================== -->
        <div id="tab-lifecycle" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-8 shadow-xl">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-100">Hak Akses, Batasan Sesi &amp; Manajemen Paket</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Pengaturan keamanan, kuota dari Super Admin, dan pemeliharaan paket <code class="font-mono text-xs text-emerald-400">config/waku.php</code></p>
                </div>

                <!-- Section 1: Quota Rule -->
                <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 space-y-2 text-xs">
                    <h4 class="font-bold text-slate-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Kebijakan Kuota Sesi (Default: 2)</span>
                    </h4>
                    <p class="text-slate-400 leading-relaxed">
                        Setiap aplikasi konsumen memiliki batas kuota sesi maksimum yang dikontrol penuh oleh <strong>Super Admin</strong>. Jika kuota telah penuh, Anda dapat menghapus sesi yang sudah tidak digunakan menggunakan tombol <strong>"Hapus Sesi"</strong> pada tab Koneksi atau meminta Super Admin untuk meningkatkan batas kuota.
                    </p>
                </div>

                <!-- Section 2: Automated Install & Config -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-slate-300 flex items-center gap-1.5 uppercase tracking-wider">
                        <span class="w-5 h-5 rounded-full bg-sky-500/20 text-sky-400 flex items-center justify-center text-[10px] font-bold border border-sky-500/30">1</span>
                        Pengaturan Hak Akses Role (config/waku.php)
                    </h3>
                    <div class="relative group">
                        <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed border border-slate-800"><code id="code-config-roles">'dashboard' => [
    'enabled' => env('WAKU_DASHBOARD_ENABLED', true),
    'prefix' => env('WAKU_DASHBOARD_PREFIX', 'waku/dashboard'),
    'auth_required' => true,
    'allowed_roles' => ['SUPER-ADMIN', 'ADMIN', 'MANAGER'],
],</code></pre>
                        <button type="button" onclick="copySnippet('code-config-roles', 'Konfigurasi Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors border border-slate-700">Salin</button>
                    </div>
                </div>

                <!-- Section 3: Clean Uninstall -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-slate-300 flex items-center gap-1.5 uppercase tracking-wider">
                        <span class="w-5 h-5 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center text-[10px] font-bold border border-rose-500/30">2</span>
                        Pencopotan Bersih (Zero-Residue Clean Uninstall)
                    </h3>
                    <div class="relative group">
                        <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed border border-slate-800"><code id="code-uninstall-waku"># 1. Bersihkan seluruh file konfigurasi & variabel .env secara otomatis
php artisan waku:uninstall --force

# 2. Hapus pustaka dependensi Composer
composer remove xdiego1st/wa-integration</code></pre>
                        <button type="button" onclick="copySnippet('code-uninstall-waku', 'Perintah Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors border border-slate-700">Salin</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="glass-header py-5 text-center text-xs text-slate-500 mt-auto border-t border-slate-800">
        &copy; {{ date('Y') }} PlatformCore WhatsApp Gateway (Waku Dashboard v1.2.2). Dilindungi Hak Cipta.
    </footer>

    <!-- =============================================================== -->
    <!-- DEDICATED LIVE QR CODE MODAL POPUP                             -->
    <!-- =============================================================== -->
    <div id="modal-qr" class="fixed inset-0 z-50 hidden bg-slate-950/85 backdrop-blur-md flex items-center justify-center p-4 transition-all">
        <div class="glass-card rounded-3xl border border-slate-800 shadow-2xl max-w-md w-full overflow-hidden flex flex-col transform transition-all scale-95 opacity-0 duration-200" id="modal-qr-card">
            
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <h3 class="font-bold text-sm text-slate-100">Pindai QR Code WhatsApp</h3>
                </div>
                <button type="button" onclick="closeQrModal()" class="text-slate-400 hover:text-slate-200 p-1 rounded-lg hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 text-center space-y-4">
                <div class="p-4 bg-white rounded-2xl inline-block shadow-inner mx-auto">
                    <img id="modal-qr-img" src="" alt="WhatsApp QR Code" class="w-64 h-64 object-contain rounded-xl hidden" />
                    <div id="modal-qr-spinner" class="w-64 h-64 flex flex-col items-center justify-center space-y-3">
                        <svg class="animate-spin h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <p class="text-xs text-slate-600 font-medium">Menghubungkan ke Gateway Baileys...</p>
                    </div>
                </div>

                <div id="modal-qr-badge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">
                    Menunggu QR Code...
                </div>

                <div class="text-xs text-slate-400 space-y-1">
                    <p>1. Buka aplikasi <strong>WhatsApp</strong> di ponsel Anda</p>
                    <p>2. Ketuk <strong>Perangkat Tertaut (Linked Devices)</strong> &gt; <strong>Tautkan Perangkat</strong></p>
                    <p>3. Arahkan kamera ponsel ke QR code di atas</p>
                </div>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-900/60 flex justify-end">
                <button type="button" onclick="closeQrModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Frontend Scripting Logic -->
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const CURRENT_SESSION_COUNT = {{ $activeSessionCount }};
        const MAX_SESSIONS = {{ $maxSessionsAllowed }};
        let activeTargetSessionId = null;
        let pollingTimer = null;
        let isModalOpen = false;
        let activeStack = 'laravel';

        // Code Snippets for Multi-Stack
        const STACK_SNIPPETS = {
            laravel: `// 1. Kirim Pesan Teks Sederhana
use WaIntegration\\Laravel\\Facades\\WhatsApp;

WhatsApp::send('08123456789', 'Halo, tagihan retribusi Anda telah terbit.');

// 2. Kirim Pesan Media (PDF / Faktur)
WhatsApp::sendDocument('08123456789', 'Faktur Resmi #123', 'https://domain.com/faktur.pdf');

// 3. Routing ke Bot Key Spesifik (v1.2.2)
WhatsApp::withBot('{{ $defaultBotKey ?? "wbk_live_sample_key" }}')
    ->send('08123456789', 'Pesan via Bot AI CS');`,

            nodejs: `// Node.js (axios / fetch)
import axios from 'axios';

const res = await axios.post('{{ $config["baseUrl"] }}/messages', {
  to: '628123456789',
  type: 'TEXT',
  content: 'Halo dari Node.js / Express'
}, {
  headers: {
    'X-API-Key': 'YOUR_API_KEY',
    'X-Application-Id': '{{ $config["appId"] }}'
  }
});
console.log(res.data);`,

            nextjs: `// Next.js 14 App Router (Server Action / Route Handler)
import { WakuDashboard } from '@platform-core/whatsapp-sdk/react';

export default function Page() {
  return (
    <WakuDashboard
      appId="{{ $config["appId"] }}"
      apiKey={process.env.WAKU_API_KEY}
    />
  );
}`,

            php: `// PHP Native (cURL)
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => '{{ $config["baseUrl"] }}/messages',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => json_encode([
    'to' => '628123456789',
    'type' => 'TEXT',
    'content' => 'Halo dari PHP Native'
  ]),
  CURLOPT_HTTPHEADER => [
    'Content-Type: application/json',
    'X-API-Key: YOUR_API_KEY',
    'X-Application-Id: {{ $config["appId"] }}'
  ]
]);
$response = curl_exec($curl);
curl_close($curl);`,

            python: `# Python (requests)
import requests

url = "{{ $config["baseUrl"] }}/messages"
headers = {
    "Content-Type": "application/json",
    "X-API-Key": "YOUR_API_KEY",
    "X-Application-Id": "{{ $config["appId"] }}"
}
payload = {
    "to": "628123456789",
    "type": "TEXT",
    "content": "Halo dari Python script"
}
res = requests.post(url, json=payload, headers=headers)
print(res.json())`,

            golang: `// Golang (net/http)
package main

import (
    "bytes"
    "net/http"
)

func main() {
    url := "{{ $config["baseUrl"] }}/messages"
    payload := []byte(\`{"to":"628123456789","type":"TEXT","content":"Halo dari Golang"}\`)
    req, _ := http.NewRequest("POST", url, bytes.NewBuffer(payload))
    req.Header.Set("Content-Type", "application/json")
    req.Header.Set("X-API-Key", "YOUR_API_KEY")
    req.Header.Set("X-Application-Id", "{{ $config["appId"] }}")
    
    client := &http.Client{}
    client.Do(req)
}`,

            curl: `# cURL Command Line
curl -X POST "{{ $config["baseUrl"] }}/messages" \\
  -H "Content-Type: application/json" \\
  -H "X-API-Key: YOUR_API_KEY" \\
  -H "X-Application-Id: {{ $config["appId"] }}" \\
  -d '{
    "to": "628123456789",
    "type": "TEXT",
    "content": "Pesan via cURL CLI"
  }'`
        };

        // --- Theme Switcher (Dark / Light) ---
        function initTheme() {
            const saved = localStorage.getItem('waku_theme');
            if (saved === 'light') {
                document.documentElement.classList.remove('dark');
                updateThemeUi('light');
            } else {
                document.documentElement.classList.add('dark');
                updateThemeUi('dark');
            }
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('waku_theme', 'light');
                updateThemeUi('light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('waku_theme', 'dark');
                updateThemeUi('dark');
            }
        }

        function updateThemeUi(theme) {
            const darkIcon = document.getElementById('theme-icon-dark');
            const lightIcon = document.getElementById('theme-icon-light');
            const label = document.getElementById('theme-label');
            if (theme === 'dark') {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
                label.innerText = 'Mode Terang';
            } else {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
                label.innerText = 'Mode Gelap';
            }
        }

        // --- Tab Navigation Switcher ---
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-emerald-500', 'text-emerald-400');
                btn.classList.add('border-transparent', 'text-slate-400');
            });

            const targetTab = document.getElementById(tabId);
            const targetBtn = document.getElementById('btn-' + tabId);

            if (targetTab) targetTab.classList.remove('hidden');
            if (targetBtn) {
                targetBtn.classList.remove('border-transparent', 'text-slate-400');
                targetBtn.classList.add('border-emerald-500', 'text-emerald-400');
            }
        }

        // --- Stack Switcher ---
        function switchStack(stackName) {
            activeStack = stackName;
            document.querySelectorAll('.stack-btn').forEach(btn => {
                btn.classList.remove('bg-emerald-600', 'text-white');
                btn.classList.add('bg-slate-900', 'text-slate-400');
            });

            const activeBtn = document.getElementById('btn-stack-' + stackName);
            if (activeBtn) {
                activeBtn.classList.remove('bg-slate-900', 'text-slate-400');
                activeBtn.classList.add('bg-emerald-600', 'text-white');
            }

            const codeEl = document.getElementById('code-stack-display');
            if (codeEl && STACK_SNIPPETS[stackName]) {
                codeEl.innerText = STACK_SNIPPETS[stackName];
            }
        }

        function copyCurrentSnippet() {
            const code = STACK_SNIPPETS[activeStack] || '';
            navigator.clipboard.writeText(code);
            showToast('Kode snippet disalin!');
        }

        // --- Real-time Polling QR Code & Connection Status ---
        function startPolling() {
            if (pollingTimer) clearInterval(pollingTimer);
            refreshQrStatus();
            pollingTimer = setInterval(refreshQrStatus, 3000);
        }

        async function refreshQrStatus() {
            try {
                const url = activeTargetSessionId 
                    ? `{{ route('waku.qr') }}?session_id=${encodeURIComponent(activeTargetSessionId)}`
                    : `{{ route('waku.qr') }}`;
                
                const res = await fetch(url);
                const json = await res.json();
                renderConnectionStatus(json);
            } catch (err) {
                console.error('Polling error:', err);
            }
        }

        function renderConnectionStatus(res) {
            const badge = document.getElementById('session-status-badge');
            const container = document.getElementById('connection-presentation-container');

            if (!res.success) {
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-950/60 text-rose-400 border border-rose-800/60";
                badge.innerText = "Gagal Mengambil Status";
                container.innerHTML = `
                    <div class="p-6 text-center space-y-2">
                        <div class="w-12 h-12 rounded-2xl bg-rose-950/80 text-rose-400 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h4 class="font-bold text-slate-200 text-sm">Gagal Mengambil Status</h4>
                        <p class="text-xs text-slate-400 max-w-sm mx-auto">${res.error || 'Server tidak merespon'}</p>
                    </div>
                `;
                return;
            }

            const data = res.data || {};
            const status = data.status || 'DISCONNECTED';
            const phone = data.phone || '';
            const qrCode = data.qrCode || '';

            if (isModalOpen) {
                updateModalQrDisplay(status, qrCode, phone);
            }

            if (status === 'CONNECTED') {
                badge.className = "inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-950/70 text-emerald-300 border border-emerald-700/60";
                badge.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span> TERHUBUNG 24/7 (${phone || 'Aktif'})`;
                
                container.innerHTML = `
                    <div class="p-6 text-center space-y-4">
                        <div class="w-16 h-16 rounded-3xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/15 animate-float">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-100">Perangkat WhatsApp Terhubung Stabil</h3>
                            <p class="text-xs text-slate-400 mt-1">Nomor Aktif: <strong class="font-mono text-emerald-400 text-sm">${phone}</strong></p>
                            <p class="text-[11px] text-emerald-400 font-semibold mt-1 flex items-center justify-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> 24/7 Steady Mode Siap
                            </p>
                        </div>
                        <div class="pt-1">
                            <button type="button" onclick="switchTab('tab-send')" class="px-4 py-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30 transition-colors cursor-pointer">
                                ➔ Uji Kirim Pesan Sekarang
                            </button>
                        </div>
                    </div>
                `;

                if (isModalOpen) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Terhubung!',
                        text: `Nomor WhatsApp ${phone} telah aktif.`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    closeQrModal();
                }

            } else if (status === 'SCAN_QR' || qrCode) {
                badge.className = "inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-amber-950/70 text-amber-300 border border-amber-700/60";
                badge.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span> Menunggu Scan QR`;

                container.innerHTML = `
                    <div class="p-4 text-center flex flex-col items-center space-y-3">
                        <div class="p-3 bg-white rounded-2xl shadow-sm">
                            <img src="${qrCode}" alt="WhatsApp QR Code" class="w-44 h-44 object-contain rounded-xl" />
                        </div>
                        <p class="text-xs font-semibold text-slate-300">Pindai QR Code di atas dengan ponsel Anda:</p>
                        <button type="button" onclick="openQrModal()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow transition-colors cursor-pointer">
                            Perbesar Tampilan QR Code (Modal)
                        </button>
                    </div>
                `;
            } else {
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-400 border border-slate-700";
                badge.innerText = "Belum Terhubung";

                container.innerHTML = `
                    <div class="p-6 text-center space-y-3">
                        <div class="w-14 h-14 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center mx-auto">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-200 text-sm">Sesi WhatsApp Belum Terhubung</h4>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto mt-0.5">Klik tombol <strong>Scan QR Code</strong> di bawah untuk memunculkan QR Code pemindaian.</p>
                        </div>
                    </div>
                `;
            }
        }

        // --- Modal QR Code Controls ---
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
            document.getElementById('modal-qr-badge').innerHTML = `<span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span> Menginisialisasi Sesi Baileys...`;
            document.getElementById('modal-qr-badge').className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-950/70 text-sky-300 border border-sky-700/60";

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
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-950/70 text-amber-300 border border-amber-700/60";
                badge.innerHTML = `<span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span> Menunggu Scan WhatsApp`;
            } else if (status === 'CONNECTED') {
                img.classList.add('hidden');
                spinner.classList.remove('hidden');
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-950/70 text-emerald-300 border border-emerald-700/60";
                badge.innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-400"></span> Berhasil Terhubung!`;
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

        // --- Delete Session with SweetAlert2 ---
        async function deleteSessionById(sessionId, sessionName) {
            const result = await Swal.fire({
                title: 'Hapus Sesi WhatsApp?',
                html: `Apakah Anda yakin ingin menghapus sesi <strong>${sessionName}</strong>?<br><span class="text-xs text-rose-400 font-medium">Seluruh berkas autentikasi pada server gateway akan dihapus permanen.</span>`,
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

        // --- Create New Session Prompt ---
        async function promptCreateSession() {
            if (CURRENT_SESSION_COUNT >= MAX_SESSIONS) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Kuota Sesi Tercapai',
                    html: `Aplikasi Anda telah menggunakan <strong>${CURRENT_SESSION_COUNT} dari ${MAX_SESSIONS}</strong> sesi maksimum.<br><br><span class="text-xs text-slate-400">Silakan hapus sesi yang tidak aktif terlebih dahulu, atau hubungi <strong>Super Admin</strong>.</span>`,
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
            bubble.innerText = content || 'Halo! Ini adalah pratinjau pesan real-time WhatsApp Gateway.';
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

                    resultBox.className = "p-4 rounded-2xl bg-emerald-950/60 border border-emerald-800/60 text-emerald-300";
                    resultBox.innerHTML = `<strong>SUKSES:</strong> Pesan berhasil dikirim! ID Pesan: <code>${json.message_id}</code>`;
                    resultBox.classList.remove('hidden');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengirim',
                        text: json.error || 'Terjadi kesalahan saat pengiriman.',
                        confirmButtonColor: '#e11d48'
                    });

                    resultBox.className = "p-4 rounded-2xl bg-rose-950/60 border border-rose-800/60 text-rose-300";
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
            showToast(successText);
        }

        function showToast(message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: message
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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            initTheme();
            switchStack('laravel');
            startPolling();
        });
    </script>
</body>
</html>
