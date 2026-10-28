<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Waku Dashboard v1.2.4 — Panel Kontrol WhatsApp Gateway Multi-Device &amp; BYOK AI</title>
    
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

        /* Unified Light Theme System Overrides */
        html:not(.dark) body {
            background-color: #f8fafc !important;
            color: #0f172a !important;
        }
        html:not(.dark) .text-slate-100,
        html:not(.dark) .text-slate-200 {
            color: #0f172a !important;
        }
        html:not(.dark) .text-slate-300 {
            color: #1e293b !important;
        }
        html:not(.dark) .text-slate-400 {
            color: #475569 !important;
        }
        html:not(.dark) .text-slate-500 {
            color: #64748b !important;
        }
        html:not(.dark) [class*="bg-slate-950"] {
            background-color: #ffffff !important;
        }
        html:not(.dark) [class*="bg-slate-900"] {
            background-color: #ffffff !important;
        }
        html:not(.dark) [class*="bg-slate-850"] {
            background-color: #f8fafc !important;
        }
        html:not(.dark) [class*="bg-slate-800"] {
            background-color: #f1f5f9 !important;
        }
        html:not(.dark) [class*="divide-slate-800"] > :not([hidden]) ~ :not([hidden]) {
            border-color: #e2e8f0 !important;
        }
        html:not(.dark) [class*="border-slate-800"],
        html:not(.dark) [class*="border-slate-700"] {
            border-color: #e2e8f0 !important;
        }
        /* Colored Pills and Badges in Light Mode */
        html:not(.dark) [class*="bg-emerald-950"] {
            background-color: #ecfdf5 !important;
            border-color: #a7f3d0 !important;
        }
        html:not(.dark) [class*="bg-teal-950"] {
            background-color: #f0fdfa !important;
            border-color: #99f6e4 !important;
        }
        html:not(.dark) [class*="bg-rose-950"] {
            background-color: #fff1f2 !important;
            border-color: #fecdd3 !important;
        }
        html:not(.dark) [class*="bg-amber-950"] {
            background-color: #fffbeb !important;
            border-color: #fde68a !important;
        }
        html:not(.dark) [class*="bg-purple-950"] {
            background-color: #faf5ff !important;
            border-color: #e9d5ff !important;
        }
        html:not(.dark) [class*="bg-sky-950"] {
            background-color: #f0f9ff !important;
            border-color: #bae6fd !important;
        }
        html:not(.dark) [class*="border-emerald-800"],
        html:not(.dark) [class*="border-emerald-700"] {
            border-color: #a7f3d0 !important;
        }
        html:not(.dark) [class*="border-teal-800"],
        html:not(.dark) [class*="border-teal-700"] {
            border-color: #99f6e4 !important;
        }
        html:not(.dark) [class*="border-rose-800"],
        html:not(.dark) [class*="border-rose-700"] {
            border-color: #fecdd3 !important;
        }
        html:not(.dark) [class*="border-amber-800"],
        html:not(.dark) [class*="border-amber-700"] {
            border-color: #fde68a !important;
        }
        html:not(.dark) [class*="border-purple-800"],
        html:not(.dark) [class*="border-purple-700"] {
            border-color: #e9d5ff !important;
        }
        html:not(.dark) [class*="border-sky-800"],
        html:not(.dark) [class*="border-sky-700"] {
            border-color: #bae6fd !important;
        }
        html:not(.dark) .text-teal-200,
        html:not(.dark) .text-teal-300 {
            color: #0f766e !important;
        }
        html:not(.dark) .text-emerald-300,
        html:not(.dark) .text-emerald-400 {
            color: #065f46 !important;
        }
        html:not(.dark) .text-rose-200,
        html:not(.dark) .text-rose-300,
        html:not(.dark) .text-rose-400 {
            color: #be123c !important;
        }
        html:not(.dark) .text-amber-200,
        html:not(.dark) .text-amber-300 {
            color: #b45309 !important;
        }
        html:not(.dark) .text-purple-300,
        html:not(.dark) .text-purple-400 {
            color: #7e22ce !important;
        }
        html:not(.dark) input,
        html:not(.dark) select,
        html:not(.dark) textarea {
            background-color: #ffffff !important;
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
        }
        html:not(.dark) input::placeholder,
        html:not(.dark) textarea::placeholder {
            color: #94a3b8 !important;
        }
        html:not(.dark) .ambient-glow {
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, rgba(13, 148, 136, 0.03) 50%, transparent 80%) !important;
        }
    </style>
    <script>
        (function() {
            try {
                var theme = localStorage.getItem('waku_theme');
                if (theme === 'light') {
                    document.documentElement.classList.remove('dark');
                } else if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            } catch(e) {}
        })();
    </script>
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
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-xs shadow-emerald-500/20">v1.2.4</span>
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
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-xs shadow-xs">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400 text-slate-950 flex items-center justify-center font-black text-[11px] shadow-sm">
                                {{ strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ Str::limit($currentUser['name'], 14) }}</div>
                                <div class="text-[10px] text-emerald-700 dark:text-emerald-400 font-mono font-semibold">{{ $currentUser['role'] ?? 'User' }}</div>
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

            <!-- Navigation Tabs Bar (Full Master PRD Customer Service Suite) -->
            <nav class="flex space-x-1 sm:space-x-2 border-t border-slate-800/80 pt-2 -mb-px overflow-x-auto no-scrollbar" aria-label="Tabs">
                <button type="button" onclick="switchTab('tab-connection')" id="btn-tab-connection" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-bold border-b-2 border-emerald-500 text-emerald-400 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Koneksi WhatsApp</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-mono bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">{{ $activeSessionCount }}/{{ $maxSessionsAllowed }}</span>
                </button>

                <button type="button" onclick="switchTab('tab-inbox')" id="btn-tab-inbox" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    <span>Live Chat Inbox</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                </button>

                <button type="button" onclick="switchTab('tab-byok-bot')" id="btn-tab-byok-bot" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>AI Bot &amp; Router</span>
                    <span id="nav-pill-bot-status" class="px-1.5 py-0.5 rounded-full text-[9px] font-bold {{ ($botConfig['botMode'] ?? 'HYBRID') !== 'DISABLED' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">{{ ($botConfig['botMode'] ?? 'HYBRID') !== 'DISABLED' ? 'Aktif' : 'Off' }}</span>
                </button>

                <button type="button" onclick="switchTab('tab-knowledge')" id="btn-tab-knowledge" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Knowledge Base</span>
                </button>

                <button type="button" onclick="switchTab('tab-flows')" id="btn-tab-flows" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    <span>Chatbot Flows</span>
                </button>

                <button type="button" onclick="switchTab('tab-playground')" id="btn-tab-playground" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>AI Playground</span>
                </button>

                <button type="button" onclick="switchTab('tab-broadcast')" id="btn-tab-broadcast" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <span>Broadcast</span>
                </button>

                <button type="button" onclick="switchTab('tab-teams')" id="btn-tab-teams" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Tim &amp; Beban</span>
                </button>

                <button type="button" onclick="switchTab('tab-automations')" id="btn-tab-automations" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Jam Kerja &amp; Otomasi</span>
                </button>

                <button type="button" onclick="switchTab('tab-send')" id="btn-tab-send" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>Uji Kirim</span>
                </button>

                <button type="button" onclick="switchTab('tab-messages')" id="btn-tab-messages" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Riwayat Pesan</span>
                </button>

                <button type="button" onclick="switchTab('tab-usage')" id="btn-tab-usage" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Biaya &amp; Token</span>
                </button>

                <button type="button" onclick="switchTab('tab-lifecycle')" id="btn-tab-lifecycle" class="tab-btn inline-flex items-center gap-1.5 py-2 px-2.5 text-xs font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-200 whitespace-nowrap transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Integrasi</span>
                </button>
            </nav>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 flex-1 w-full space-y-6 relative z-10">

        <!-- Banner Peringatan Kredensial (Danger / Red Theme) -->
        @if(!$config['hasApiKey'])
            <div class="rounded-2xl p-4 sm:p-5 bg-gradient-to-r from-red-50 to-rose-50 dark:from-rose-950/80 dark:to-red-950/60 border border-red-300 dark:border-rose-600/40 text-red-900 dark:text-rose-200 flex items-start gap-3.5 shadow-lg shadow-red-500/10 dark:shadow-rose-950/30">
                <div class="p-2 rounded-xl bg-red-100 dark:bg-rose-900/60 text-red-600 dark:text-rose-400 shrink-0 mt-0.5 border border-red-200 dark:border-rose-700/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="text-xs sm:text-sm">
                    <p class="font-bold text-red-900 dark:text-rose-300 text-sm sm:text-base">Variabel Lingkungan Belum Lengkap!</p>
                    <p class="text-red-800 dark:text-rose-200/90 mt-1 leading-relaxed">
                        Kunci API belum terkonfigurasi. Jalankan <code class="font-mono bg-red-100 dark:bg-rose-950/90 border border-red-200 dark:border-rose-800/60 px-1.5 py-0.5 rounded text-red-900 dark:text-rose-300 font-bold">php artisan waku:install</code> atau definisikan <code class="font-mono bg-red-100 dark:bg-rose-950/90 border border-red-200 dark:border-rose-800/60 px-1.5 py-0.5 rounded text-red-900 dark:text-rose-300 font-bold">WAKU_API_KEY</code> di berkas <code class="font-mono bg-red-100 dark:bg-rose-950/90 border border-red-200 dark:border-rose-800/60 px-1.5 py-0.5 rounded text-red-900 dark:text-rose-300">.env</code> Anda.
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
                                    <button type="button" onclick="deleteSessionById('{{ $sessId }}', '{{ $sessName }}')" class="p-2 rounded-xl bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-200 border border-rose-200 dark:border-rose-800/40 text-xs font-semibold transition-colors shadow-xs" title="Hapus Sesi Secara Permanen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40">
                                <p class="text-xs text-slate-500 dark:text-slate-400">Belum ada sesi terdaftar. Klik <strong class="text-slate-800 dark:text-slate-200">Tambah Sesi Baru</strong> di atas untuk memulai.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Anti-Spam Notice -->
                    <div class="p-4 rounded-2xl bg-teal-50 dark:bg-teal-950/40 border border-teal-200 dark:border-teal-800/40 text-xs text-teal-900 dark:text-teal-200/90 flex items-start gap-3 shadow-sm">
                        <svg class="w-5 h-5 text-teal-600 dark:text-teal-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <span class="font-bold text-teal-900 dark:text-teal-300">Proteksi Anti-Spam Berbasis Load Balancer:</span>
                            <span class="text-teal-800 dark:text-teal-200/80"> Pesan keluar otomatis dirotasi secara seimbang antar-nomor yang aktif terhubung untuk mencegah pemblokiran nomor oleh WhatsApp.</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- =============================================================== -->
        <!-- TAB: LIVE CHAT INBOX (MASTER-DETAIL WORKSPACE)                  -->
        <!-- =============================================================== -->
        <div id="tab-inbox" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl overflow-hidden shadow-xl border-emerald-500/20">
                
                <!-- Inbox Layout: Split Panes -->
                <div class="grid grid-cols-1 md:grid-cols-12 min-h-[640px] max-h-[720px] divide-y md:divide-y-0 md:divide-x divide-slate-800">
                    
                    <!-- Left Column: Conversation List -->
                    <div class="md:col-span-4 lg:col-span-4 flex flex-col bg-slate-900/40">
                        
                        <!-- Search & Quick Filters -->
                        <div class="p-4 border-b border-slate-800 space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-bold text-slate-100 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Percakapan Pelanggan</span>
                                </h3>
                                <span class="text-[11px] font-mono text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20" id="inbox-count-badge">{{ count($conversations['items'] ?? $conversations) }} Sesi</span>
                            </div>

                            <div class="relative">
                                <input type="text" id="inbox-search" placeholder="Cari nomor atau nama..." onkeyup="filterInboxConversations()" class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-slate-950/80 border border-slate-700/60 text-slate-200 placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition-colors">
                                <svg class="w-4 h-4 text-slate-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>

                            <!-- Filter Pills -->
                            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar text-[11px]">
                                <button type="button" onclick="setInboxFilter('all')" class="inbox-filter-btn px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30 whitespace-nowrap">Semua</button>
                                <button type="button" onclick="setInboxFilter('AI')" class="inbox-filter-btn px-2.5 py-1 rounded-lg bg-slate-800/80 text-slate-400 font-medium hover:text-slate-200 whitespace-nowrap">AI Aktif</button>
                                <button type="button" onclick="setInboxFilter('AGENT')" class="inbox-filter-btn px-2.5 py-1 rounded-lg bg-slate-800/80 text-slate-400 font-medium hover:text-slate-200 whitespace-nowrap">Handover</button>
                                <button type="button" onclick="setInboxFilter('RESOLVED')" class="inbox-filter-btn px-2.5 py-1 rounded-lg bg-slate-800/80 text-slate-400 font-medium hover:text-slate-200 whitespace-nowrap">Selesai</button>
                            </div>
                        </div>

                        <!-- Conversation List Items Container -->
                        <div class="flex-1 overflow-y-auto divide-y divide-slate-800/50 p-2 space-y-1" id="inbox-conversation-list">
                            @forelse($conversations['items'] ?? $conversations as $c)
                                @php
                                    $contact = $c['contact'] ?? null;
                                    $contactName = $contact['name'] ?? $c['customerNumber'] ?? $c['senderPhone'] ?? 'Pelanggan';
                                    $contactPhone = $contact['phone'] ?? $c['customerNumber'] ?? $c['senderPhone'] ?? '-';
                                    $mode = $c['mode'] ?? 'BOT';
                                    $status = $c['status'] ?? 'OPEN';
                                    $lastMsg = $c['lastMessagePreview'] ?? 'Tidak ada pesan terbaru';
                                @endphp
                                <div onclick="selectConversation('{{ $c['id'] }}', '{{ $contactName }}', '{{ $contactPhone }}', '{{ $mode }}', '{{ $status }}')" class="inbox-item p-3 rounded-2xl cursor-pointer hover:bg-slate-800/60 transition-all border border-transparent hover:border-slate-700/50" data-id="{{ $c['id'] }}" data-mode="{{ $mode }}" data-status="{{ $status }}" data-name="{{ strtolower($contactName) }}" data-phone="{{ $contactPhone }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-slate-800 to-slate-700 text-slate-200 flex items-center justify-center font-bold text-sm shrink-0 border border-slate-600/40">
                                            {{ strtoupper(substr($contactName, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-1">
                                                <h4 class="text-xs font-bold text-slate-100 truncate">{{ $contactName }}</h4>
                                                <span class="text-[10px] text-slate-500 font-mono">{{ isset($c['updatedAt']) ? \Carbon\Carbon::parse($c['updatedAt'])->diffForHumans(null, true) : 'Baru' }}</span>
                                            </div>
                                            <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $lastMsg }}</p>
                                            <div class="flex items-center gap-1.5 mt-1.5">
                                                @if($mode === 'AGENT' || $mode === 'HUMAN')
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">Human Handover</span>
                                                @else
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">AI Bot</span>
                                                @endif
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-mono text-slate-400 bg-slate-800">{{ $contactPhone }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 px-4 text-slate-500 space-y-2">
                                    <svg class="w-10 h-10 mx-auto text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    <p class="text-xs font-medium">Belum ada percakapan masuk</p>
                                    <p class="text-[11px] text-slate-600">Pesan WhatsApp dari pelanggan akan muncul di sini secara otomatis.</p>
                                </div>
                            @endforelse
                        </div>

                    </div>

                    <!-- Center & Right Columns: Chat Conversation Timeline -->
                    <div class="md:col-span-8 lg:col-span-8 flex flex-col justify-between bg-slate-950/60 relative" id="inbox-timeline-pane">
                        
                        <!-- Empty Active State -->
                        <div id="inbox-empty-view" class="flex-1 flex flex-col items-center justify-center p-8 text-center space-y-3">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-200">Pilih Percakapan untuk Memulai</h3>
                            <p class="text-xs text-slate-400 max-w-sm">Pilih salah satu nomor pelanggan di panel kiri untuk melihat riwayat percakapan, mengambil alih ke agen manusia, atau mengirim balasan.</p>
                        </div>

                        <!-- Active Conversation Content (Hidden until conversation is selected) -->
                        <div id="inbox-active-view" class="hidden flex-1 flex flex-col h-full">
                            
                            <!-- Header Toolbar -->
                            <div class="p-3.5 px-5 border-b border-slate-800 flex items-center justify-between flex-wrap gap-2 bg-slate-900/70">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm border border-emerald-500/30 shrink-0" id="active-chat-avatar">
                                        P
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-bold text-slate-100" id="active-chat-name">Pelanggan</h4>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30" id="active-chat-mode-badge">AI Bot Aktif</span>
                                        </div>
                                        <div class="text-[11px] font-mono text-slate-400" id="active-chat-phone">-</div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="takeoverActiveChat()" id="btn-takeover" class="px-3 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 text-xs font-bold border border-amber-500/30 transition-all flex items-center gap-1.5 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span>Ambil Alih (Human Handover)</span>
                                    </button>

                                    <button type="button" onclick="returnActiveChatToBot()" id="btn-return-bot" class="hidden px-3 py-1.5 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 text-purple-300 text-xs font-bold border border-purple-500/30 transition-all flex items-center gap-1.5 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span>Kembalikan ke Bot</span>
                                    </button>

                                    <button type="button" onclick="resolveActiveChat()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold border border-slate-700 transition-all flex items-center gap-1.5 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Selesai</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Timeline Messages Area -->
                            <div class="flex-1 p-4 overflow-y-auto space-y-3 wa-bg-pattern min-h-[380px] max-h-[440px]" id="inbox-messages-timeline">
                                <div class="text-center py-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-slate-900/80 text-slate-400 border border-slate-800 shadow-sm">Hari Ini</span>
                                </div>
                                <div id="timeline-bubbles-container" class="space-y-3">
                                    <!-- Dynamic bubbles injected here -->
                                </div>
                            </div>

                            <!-- Quick Reply Shortcuts Bar -->
                            <div class="px-4 py-2 border-t border-slate-800/80 bg-slate-900/60 flex items-center gap-2 overflow-x-auto no-scrollbar text-xs">
                                <span class="text-[10px] text-slate-500 font-bold shrink-0">Balasan Cepat:</span>
                                <button type="button" onclick="insertQuickMessage('Halo! Terima kasih telah menghubungi kami. Ada yang bisa kami bantu?')" class="px-2.5 py-1 rounded-lg bg-slate-800/80 hover:bg-slate-700 text-slate-300 text-[11px] whitespace-nowrap transition-colors">Halo &amp; Sapa</button>
                                <button type="button" onclick="insertQuickMessage('Mohon tunggu sebentar, kami sedang memeriksa informasi Anda.')" class="px-2.5 py-1 rounded-lg bg-slate-800/80 hover:bg-slate-700 text-slate-300 text-[11px] whitespace-nowrap transition-colors">Mohon Tunggu</button>
                                <button type="button" onclick="insertQuickMessage('Permintaan Anda telah selesai kami proses. Terima kasih.')" class="px-2.5 py-1 rounded-lg bg-slate-800/80 hover:bg-slate-700 text-slate-300 text-[11px] whitespace-nowrap transition-colors">Selesai Proses</button>
                            </div>

                            <!-- Reply Composer Box -->
                            <form onsubmit="handleSendInboxReply(event)" class="p-3 border-t border-slate-800 bg-slate-900/90 flex items-end gap-2">
                                <div class="flex-1 relative">
                                    <textarea id="inbox-reply-input" rows="2" placeholder="Ketik balasan Anda ke pelanggan..." required class="w-full px-3 py-2 text-xs rounded-xl bg-slate-950 border border-slate-700 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition-colors resize-none"></textarea>
                                </div>
                                <button type="submit" id="btn-inbox-submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-1.5 shrink-0 cursor-pointer">
                                    <span>Kirim</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </button>
                            </form>

                        </div>

                    </div>

                </div>

            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB: AI BOT & AI ROUTER TOKEN (BYOK v1.2.5)                     -->
        <!-- =============================================================== -->
        <div id="tab-byok-bot" class="tab-content hidden space-y-6">
            @php
                $activeBotMode = $botConfig['botMode'] ?? 'HYBRID';
                $isBotActive = $activeBotMode !== 'DISABLED';
                $botProvider = $botConfig['providerType'] ?? 'OPENROUTER';
                $botModel = $botConfig['modelName'] ?? 'openrouter/free';
                $botMaskedKey = $botConfig['apiKeyMasked'] ?? null;
                $botFallbackMsg = $botConfig['fallbackMessage'] ?? 'Mohon maaf, asisten AI kami sedang mengalami kendala. Admin manusia kami akan segera membalas pesan Anda.';
            @endphp

            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl border-purple-500/20">
                <!-- Header Title & Status -->
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="p-2 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <h2 class="text-base sm:text-lg font-bold text-slate-100">Kontrol Bot AI &amp; Token AI Router (BYOK)</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">v1.2.5</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">
                            Setiap konsumen memiliki kontrol penuh untuk mengaktifkan/menonaktifkan bot dan menghubungkan <strong>Token AI Router</strong> (OpenRouter / Provider) masing-masing. Kredensial tersimpan aman dengan enkripsi AES-256-GCM.
                        </p>
                    </div>

                    <!-- Bot Global Status Badge -->
                    <div id="bot-status-container" class="flex items-center gap-3">
                        <span id="badge-bot-global" class="px-3 py-1 rounded-full text-xs font-bold border flex items-center gap-2 {{ $isBotActive ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-slate-800 text-slate-400 border-slate-700' }}">
                            <span class="w-2 h-2 rounded-full {{ $isBotActive ? 'bg-emerald-400 animate-pulse' : 'bg-slate-500' }}" id="dot-bot-global"></span>
                            <span id="text-bot-global">{{ $isBotActive ? 'Bot AI Aktif' : 'Bot AI Dinonaktifkan' }}</span>
                        </span>
                    </div>
                </div>

                <!-- 1. Quick Master Switch Card (Aktifkan / Nonaktifkan Bot AI) -->
                <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-r {{ $isBotActive ? 'from-emerald-950/40 via-slate-900 to-slate-900 border-emerald-500/30' : 'from-slate-900 via-slate-900 to-slate-950 border-slate-800' }} border transition-all shadow-md flex items-center justify-between flex-wrap gap-4" id="bot-toggle-card">
                    <div class="space-y-1 max-w-xl">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-slate-200">Sakelar Utama Bot AI Pelanggan</span>
                            <span id="label-bot-mode-pill" class="text-[10px] font-mono px-2 py-0.5 rounded-md font-bold {{ $isBotActive ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                                Mode: {{ $activeBotMode }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Aktifkan sakelar ini agar asisten virtual langsung menjawab pesan WhatsApp masuk secara cerdas dan otomatis. Nonaktifkan jika Anda ingin menangguhkan bot dan melayani seluruh pesan secara manual melalui tab Live Chat Inbox.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" id="btn-toggle-bot-action" onclick="toggleAiBotState()" class="px-5 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2.5 shadow-lg transition-all cursor-pointer {{ $isBotActive ? 'bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/40' : 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-emerald-600/30' }}">
                            <svg class="w-4 h-4 {{ $isBotActive ? 'text-rose-400' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span id="btn-toggle-bot-text">{{ $isBotActive ? 'Nonaktifkan Bot AI' : 'Aktifkan Bot AI Sekarang' }}</span>
                        </button>
                    </div>
                </div>

                @if(empty($botMaskedKey))
                    <!-- Precondition Helper Alert: Bot is disabled by default until OpenRouter token is provided -->
                    <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/25 text-amber-300 text-xs flex items-start gap-3 shadow-sm">
                        <svg class="w-5 h-5 shrink-0 text-amber-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div class="space-y-1">
                            <span class="font-bold text-amber-200">Bot AI Berstatus Default Nonaktif:</span>
                            <p class="text-amber-300/90 leading-relaxed">
                                Ketika nomor WhatsApp Anda terhubung, bot secara otomatis diset <strong>NONAKTIF (DISABLED)</strong>. Sebagai syarat untuk mengaktifkan bot, Anda wajib memasukkan <strong>Token OpenRouter</strong> Anda masing-masing pada formulir kredensial di bawah ini.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- 2. AI Router Configuration Form (Input Token & Provider) -->
                <div class="rounded-2xl p-5 sm:p-6 bg-slate-950/70 border border-slate-800/80 space-y-5">
                    <div class="flex items-center justify-between flex-wrap gap-2 pb-3 border-b border-slate-800">
                        <div>
                            <h3 class="text-sm font-bold text-slate-200 flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                <span>Kredensial Token AI Router &amp; Konfigurasi Model</span>
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Masukkan token router AI Anda sendiri untuk kontrol biaya mandiri dan kebebasan memilih ratusan model LLM.</p>
                        </div>

                        @if(!empty($botMaskedKey))
                            <div class="flex items-center gap-2 px-3 py-1 rounded-xl bg-purple-500/10 border border-purple-500/20 text-xs">
                                <span class="text-slate-400">Token Aktif:</span>
                                <span class="font-mono font-bold text-purple-300">{{ $botMaskedKey }}</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            </div>
                        @endif
                    </div>

                    <form onsubmit="handleSaveAiRouterConfig(event)" class="space-y-4">
                        <input type="hidden" id="ai-session-id" value="{{ $sessions[0]['id'] ?? ($botConfig['whatsappSessionId'] ?? '') }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Provider Selection -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                                    <span>Penyedia AI / Gateway <span class="text-rose-400">*</span></span>
                                    <span class="text-[10px] text-purple-400 font-mono">Multi-Provider Router</span>
                                </label>
                                <select id="ai-provider-type" onchange="handleAiProviderChange()" class="w-full px-3 py-2.5 text-xs rounded-xl bg-slate-900 border border-slate-700 text-slate-200 focus:outline-none focus:border-purple-500 transition-colors">
                                    <option value="OPENROUTER" {{ $botProvider === 'OPENROUTER' ? 'selected' : '' }}>OpenRouter (Direkomendasikan - Semua Model LLM)</option>
                                    <option value="GEMINI" {{ $botProvider === 'GEMINI' ? 'selected' : '' }}>Google Gemini (Gemini 2.0 Flash / Pro)</option>
                                    <option value="OPENAI" {{ $botProvider === 'OPENAI' ? 'selected' : '' }}>OpenAI (GPT-4o / GPT-4o-mini)</option>
                                </select>
                            </div>

                            <!-- Token Input with Eye Toggle -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                                    <span>Token AI Router / API Key <span class="text-rose-400">*</span></span>
                                    <a href="https://openrouter.ai/keys" target="_blank" rel="noopener" class="text-[10px] text-purple-400 hover:text-purple-300 underline flex items-center gap-1">
                                        <span>Dapatkan Token OpenRouter</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </label>
                                <div class="relative">
                                    <input type="password" id="ai-router-token" placeholder="{{ !empty($botMaskedKey) ? '•••••••••••••••• (Biarkan kosong jika tidak diubah)' : 'sk-or-v1-xxxxxxxxxxxxxxxxxxxx' }}" class="w-full pl-3 pr-10 py-2.5 text-xs rounded-xl bg-slate-900 border border-slate-700 text-slate-200 placeholder-slate-500 focus:outline-none focus:border-purple-500 font-mono transition-colors">
                                    <button type="button" onclick="toggleTokenVisibility('ai-router-token')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-200 transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" id="icon-eye-ai-router-token" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                                <span class="text-[10px] text-slate-500 mt-1 block">Terenkripsi AES-256-GCM. Kunci rahasia tidak pernah ditampilkan secara utuh demi keamanan.</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                            <!-- Model Name -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                                    <span>Model Identifier <span class="text-rose-400">*</span></span>
                                    <span class="text-[10px] text-slate-400">Contoh: deepseek/deepseek-chat</span>
                                </label>
                                <input type="text" id="ai-model-name" value="{{ $botModel }}" placeholder="openrouter/free" class="w-full px-3 py-2.5 text-xs rounded-xl bg-slate-900 border border-slate-700 text-slate-200 focus:outline-none focus:border-purple-500 font-mono transition-colors">
                                
                                <!-- Preset Pills -->
                                <div class="flex items-center gap-1.5 flex-wrap mt-2">
                                    <span class="text-[10px] text-slate-500">Rekomendasi:</span>
                                    <button type="button" onclick="selectModelPreset('openrouter/free')" class="px-2 py-0.5 rounded-md text-[10px] font-mono bg-slate-800 hover:bg-purple-900/40 text-purple-300 border border-slate-700 transition-colors">openrouter/free</button>
                                    <button type="button" onclick="selectModelPreset('deepseek/deepseek-chat')" class="px-2 py-0.5 rounded-md text-[10px] font-mono bg-slate-800 hover:bg-purple-900/40 text-purple-300 border border-slate-700 transition-colors">deepseek-chat</button>
                                    <button type="button" onclick="selectModelPreset('meta-llama/llama-3.3-70b-instruct')" class="px-2 py-0.5 rounded-md text-[10px] font-mono bg-slate-800 hover:bg-purple-900/40 text-purple-300 border border-slate-700 transition-colors">llama-3.3-70b</button>
                                    <button type="button" onclick="selectModelPreset('google/gemini-2.5-flash')" class="px-2 py-0.5 rounded-md text-[10px] font-mono bg-slate-800 hover:bg-purple-900/40 text-purple-300 border border-slate-700 transition-colors">gemini-flash</button>
                                </div>
                            </div>

                            <!-- Bot Response Mode -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                                    <span>Strategi Respon Bot</span>
                                </label>
                                <select id="ai-bot-mode" class="w-full px-3 py-2.5 text-xs rounded-xl bg-slate-900 border border-slate-700 text-slate-200 focus:outline-none focus:border-purple-500 transition-colors">
                                    <option value="HYBRID" {{ $activeBotMode === 'HYBRID' ? 'selected' : '' }}>HYBRID — Alur Menu Chatbot + AI Grounded (Direkomendasikan)</option>
                                    <option value="AI_ONLY" {{ $activeBotMode === 'AI_ONLY' ? 'selected' : '' }}>AI_ONLY — Respons Full AI Berdasarkan Dokumen Knowledge</option>
                                    <option value="MENU_ONLY" {{ $activeBotMode === 'MENU_ONLY' ? 'selected' : '' }}>MENU_ONLY — Alur Menu Interaktif Saja (Tanpa AI)</option>
                                    <option value="DISABLED" {{ $activeBotMode === 'DISABLED' ? 'selected' : '' }}>DISABLED — Nonaktifkan Semua Respon Otomatis</option>
                                </select>
                                <span class="text-[10px] text-slate-500 mt-1 block">Mode HYBRID akan mendahulukan menu cepat dan otomatis menggunakan AI jika kata kunci tidak cocok.</span>
                            </div>
                        </div>

                        <!-- Fallback Message -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                                <span>Pesan Fallback / Penanganan Kendala</span>
                            </label>
                            <textarea id="ai-fallback-msg" rows="2" class="w-full p-3 text-xs rounded-xl bg-slate-900 border border-slate-700 text-slate-200 placeholder-slate-500 focus:outline-none focus:border-purple-500 transition-colors">{{ $botFallbackMsg }}</textarea>
                            <span class="text-[10px] text-slate-500 mt-1 block">Pesan yang otomatis dikirim ke pelanggan jika terjadi gangguan koneksi ke AI provider atau kuota token habis.</span>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="submit" id="btn-save-ai-config" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs shadow-lg shadow-purple-600/30 transition-all flex items-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Simpan Konfigurasi AI Router</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 3. WhatsApp Sessions & Bot Active Status Table -->
                <div class="space-y-3 pt-2">
                    <h3 class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <span>Status Bot per Sesi WhatsApp Konsumen</span>
                    </h3>
                    <div class="rounded-2xl border border-slate-800 bg-slate-950 overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-900/80 text-slate-400 font-semibold border-b border-slate-800">
                                <tr>
                                    <th class="py-3 px-4">Nama Sesi</th>
                                    <th class="py-3 px-4">Nomor WhatsApp</th>
                                    <th class="py-3 px-4">Status Koneksi</th>
                                    <th class="py-3 px-4">Status Bot AI</th>
                                    <th class="py-3 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @forelse($sessions as $sess)
                                    @php
                                        $sId = $sess['id'] ?? '';
                                        $sName = $sess['name'] ?? 'Default Session';
                                        $sPhone = $sess['phone'] ?? '-';
                                        $sStatus = $sess['status'] ?? 'DISCONNECTED';
                                    @endphp
                                    <tr class="hover:bg-slate-900/40 transition-colors">
                                        <td class="py-3 px-4 font-semibold text-slate-200">{{ $sName }}</td>
                                        <td class="py-3 px-4 font-mono text-slate-300">{{ $sPhone }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $sStatus === 'CONNECTED' ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }}">
                                                {{ $sStatus }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $isBotActive ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-400 border border-rose-500/30' }}">
                                                {{ $isBotActive ? 'Aktif (' . $activeBotMode . ')' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <button type="button" onclick="toggleAiBotState('{{ $sId }}')" class="px-2.5 py-1 rounded-lg text-[11px] font-bold border transition-colors cursor-pointer {{ $isBotActive ? 'bg-rose-950/40 border-rose-800/40 text-rose-300 hover:bg-rose-900/60' : 'bg-emerald-950/40 border-emerald-800/40 text-emerald-300 hover:bg-emerald-900/60' }}">
                                                {{ $isBotActive ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-slate-500 text-xs">
                                            Belum ada sesi WhatsApp terhubung. Silakan scan QR code pada tab Koneksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 4. Developer SDK Code Snippet -->
                <div class="space-y-2.5 pt-2">
                    <h3 class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        <span>Kode Penggunaan Bot &amp; BYOK di Laravel (v1.2.5)</span>
                    </h3>
                    <div class="relative group">
                        <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed border border-slate-800"><code id="code-snippet-byok">// 1. Aktifkan atau Nonaktifkan Bot AI secara programatik
use WaIntegration\Laravel\Facades\WhatsApp;

WhatsApp::toggleBot(true); // Aktifkan bot AI
WhatsApp::toggleBot(false); // Nonaktifkan bot AI

// 2. Perbarui Kredensial AI Router Token & Model Sesi
WhatsApp::updateBotConfig([
    'providerType' => 'OPENROUTER',
    'modelName' => 'openrouter/free',
    'apiKey' => 'sk-or-v1-...', // Token OpenRouter Anda
    'botMode' => 'HYBRID',
]);

// 3. Dapatkan Konfigurasi & Status Bot Aktif
$botConfig = WhatsApp::getBotConfig();</code></pre>
                        <button type="button" onclick="copySnippet('code-snippet-byok', 'Kode Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors border border-slate-700 cursor-pointer">Salin Kode</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB: KNOWLEDGE BASE MANAGEMENT                                  -->
        <!-- =============================================================== -->
        <div id="tab-knowledge" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl border-emerald-500/20">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </span>
                            <h2 class="text-base sm:text-lg font-bold text-slate-100">Knowledge Base &amp; Sumber Pengetahuan AI</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">RAG Ready</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Unggah dokumen SOP, FAQ, dan panduan layanan agar Bot AI dapat menjawab pertanyaan pelanggan secara akurat dan tergrounding.</p>
                    </div>

                    <button type="button" onclick="toggleKnowledgeUploadForm()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Dokumen SOP / FAQ</span>
                    </button>
                </div>

                <!-- Add Document Form (Collapsible) -->
                @php
                    $allKbs = $knowledgeBases['items'] ?? $knowledgeBases ?? [];
                    $firstKbId = !empty($allKbs) ? ($allKbs[0]['id'] ?? 'default') : 'default';
                @endphp
                <div id="knowledge-upload-box" class="hidden rounded-2xl p-5 bg-slate-900/80 border border-slate-700/60 space-y-4">
                    <h3 class="text-xs font-bold text-slate-200 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Formulir Unggah Dokumen Baru</span>
                    </h3>
                    <form onsubmit="handleUploadKnowledgeDoc(event)" class="space-y-4">
                        <input type="hidden" id="kb-id" value="{{ $firstKbId }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1">Judul Dokumen / SOP <span class="text-emerald-400">*</span></label>
                                <input type="text" id="doc-title" placeholder="Contoh: SOP Pelayanan Perizinan &amp; Layanan Publik" required class="w-full px-3 py-2 text-xs rounded-xl bg-slate-950 border border-slate-700 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1">URL Rujukan / Sumber (Opsional)</label>
                                <input type="url" id="doc-source" placeholder="https://portal.instansi.go.id/sop" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-950 border border-slate-700 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Isi Dokumen, Tanya Jawab FAQ, atau Panduan Lengkap <span class="text-emerald-400">*</span></label>
                            <textarea id="doc-content" rows="6" placeholder="Tempelkan isi dokumen teks, daftar pertanyaan umum (FAQ), jadwal pelayanan, tarif, syarat pendaftaran, atau prosedur layanan..." required class="w-full px-3 py-2.5 text-xs rounded-xl bg-slate-950 border border-slate-700 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500"></textarea>
                            <p class="text-[11px] text-slate-500 mt-1">Dokumen teks akan dipartisi (chunking) secara otomatis dan disimpan terisolasi untuk bot aplikasi Anda.</p>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" onclick="toggleKnowledgeUploadForm()" class="px-3.5 py-1.5 rounded-xl bg-slate-800 text-slate-400 hover:text-slate-200 text-xs font-semibold">Batal</button>
                            <button type="submit" id="btn-save-doc" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-600/30 flex items-center gap-2">
                                <span>Simpan &amp; Indeks Sekarang</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Knowledge Documents Table -->
                <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-950/40">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="bg-slate-900/80 text-[11px] uppercase tracking-wider text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="py-3 px-4">Judul Dokumen</th>
                                <th class="py-3 px-4">Sumber / Tipe</th>
                                <th class="py-3 px-4">Karakter / Chunks</th>
                                <th class="py-3 px-4">Status Indeks</th>
                                <th class="py-3 px-4">Tanggal Diperbarui</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60" id="knowledge-docs-tbody">
                            @php
                                $kbList = $knowledgeBases['items'] ?? $knowledgeBases ?? [];
                                $firstKb = !empty($kbList) ? $kbList[0] : null;
                                $docs = $firstKb['documents'] ?? [];
                            @endphp
                            @forelse($docs as $doc)
                                <tr class="hover:bg-slate-900/40 transition-colors">
                                    <td class="py-3.5 px-4 font-bold text-slate-100 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>{{ $doc['title'] ?? 'Dokumen Tanpa Judul' }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-400 font-mono text-[11px]">{{ Str::limit($doc['sourceUrl'] ?? 'Teks Dokumen', 30) }}</td>
                                    <td class="py-3.5 px-4 font-mono text-emerald-400">{{ number_format($doc['characterCount'] ?? strlen($doc['content'] ?? '')) }} Karakter</td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            Terindeks
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px]">{{ isset($doc['updatedAt']) ? \Carbon\Carbon::parse($doc['updatedAt'])->format('d M Y, H:i') : '-' }}</td>
                                    <td class="py-3.5 px-4 text-right">
                                        <button type="button" onclick="deleteKnowledgeDoc('{{ $doc['id'] }}')" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-colors" title="Hapus Dokumen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-500">
                                        <p class="text-xs font-semibold">Belum ada dokumen yang diunggah ke Knowledge Base.</p>
                                        <p class="text-[11px] text-slate-600 mt-1">Klik tombol "Tambah Dokumen SOP / FAQ" di atas untuk menambahkan pengetahuan bot pertama Anda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB: CHATBOT AUTOMATION FLOWS                                   -->
        <!-- =============================================================== -->
        <div id="tab-flows" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl border-purple-500/20">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="p-2 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                            </span>
                            <h2 class="text-base sm:text-lg font-bold text-slate-100">Alur Percakapan Otomatis (Chatbot Flows)</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">Visual Routing</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Atur alur otomatisasi ketika pelanggan mengirimkan kata kunci spesifik (menu layanan, pendaftaran, eskalasi tiket).</p>
                    </div>

                    <button type="button" onclick="showCreateFlowModal()" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold shadow-lg shadow-purple-600/30 transition-all flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Buat Flow Percakapan Baru</span>
                    </button>
                </div>

                <!-- Flows Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="flows-container">
                    @forelse($flows['items'] ?? $flows ?? [] as $flow)
                        <div class="rounded-2xl p-5 bg-slate-900/80 border border-slate-700/60 hover:border-purple-500/50 transition-all space-y-4">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-100">{{ $flow['name'] ?? 'Flow Percakapan' }}</h3>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $flow['description'] ?? 'Alur otomatisasi respons pelanggan.' }}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ ($flow['isActive'] ?? true) ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-400' }}">
                                    {{ ($flow['isActive'] ?? true) ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>

                            <!-- Flow Steps Diagram Pills -->
                            <div class="space-y-2 pt-2 border-t border-slate-800 text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 rounded-lg bg-purple-500/10 text-purple-300 font-mono font-bold text-[10px] border border-purple-500/20">Trigger: {{ $flow['triggerValue'] ?? 'Kata Kunci' }}</span>
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    <span class="px-2 py-1 rounded-lg bg-emerald-500/10 text-emerald-300 font-bold text-[10px] border border-emerald-500/20">Respons AI / Menu</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Default Built-In Flows Cards -->
                        <div class="rounded-2xl p-5 bg-slate-900/80 border border-slate-700/60 hover:border-purple-500/50 transition-all space-y-4">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-100">Menu Informasi &amp; Layanan Publik</h3>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Merespons saat pelanggan mengetik "info", "menu", atau "layanan".</p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Aktif</span>
                            </div>
                            <div class="flex items-center gap-1.5 flex-wrap text-[11px] pt-2 border-t border-slate-800">
                                <span class="px-2 py-1 rounded-lg bg-purple-500/10 text-purple-300 font-mono text-[10px]">Keyword: "info"</span>
                                <span class="text-slate-500">→</span>
                                <span class="px-2 py-1 rounded-lg bg-emerald-500/10 text-emerald-300 text-[10px]">Kirim Menu Interaktif</span>
                            </div>
                        </div>

                        <div class="rounded-2xl p-5 bg-slate-900/80 border border-slate-700/60 hover:border-amber-500/50 transition-all space-y-4">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-100">Eskalasi ke Customer Service (Handover)</h3>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Deteksi kata "admin", "cs", "operator", atau "bantuan".</p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Aktif</span>
                            </div>
                            <div class="flex items-center gap-1.5 flex-wrap text-[11px] pt-2 border-t border-slate-800">
                                <span class="px-2 py-1 rounded-lg bg-amber-500/10 text-amber-300 font-mono text-[10px]">Keyword: "admin"</span>
                                <span class="text-slate-500">→</span>
                                <span class="px-2 py-1 rounded-lg bg-amber-500/10 text-amber-300 text-[10px]">Alihkan ke Agen CS</span>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB: AI CS PLAYGROUND (TESTING SANDBOX)                         -->
        <!-- =============================================================== -->
        <div id="tab-playground" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl border-emerald-500/20">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <h2 class="text-base sm:text-lg font-bold text-slate-100">AI Customer Service Playground (Sandbox)</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Sandbox Simulasi</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Uji coba simulasi respons bot AI, inspeksi kutipan Knowledge Base (grounding), dan latensi sebelum diaktifkan secara live ke pelanggan.</p>
                    </div>

                    <button type="button" onclick="resetPlaygroundChat()" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset Simulasi</span>
                    </button>
                </div>

                <!-- Playground Interface: 2 Columns -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    <!-- Left: Playground Sandbox Chat (7 cols) -->
                    <div class="lg:col-span-7 flex flex-col rounded-2xl border border-slate-800 bg-slate-950/80 overflow-hidden shadow-md">
                        <!-- Chat Header -->
                        <div class="p-3.5 px-4 border-b border-slate-800 bg-slate-900/60 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs border border-emerald-500/30">
                                    AI
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-100">Simulasi Bot WhatsApp</div>
                                    <div class="text-[10px] text-emerald-400 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Siap merespons pertanyaan
                                    </div>
                                </div>
                            </div>
                            <span class="text-[10px] font-mono text-slate-500">Isolasi Sandbox</span>
                        </div>

                        <!-- Chat Messages Scroll Area -->
                        <div class="p-4 space-y-3 min-h-[320px] max-h-[380px] overflow-y-auto wa-bg-pattern" id="playground-chat-bubbles">
                            <!-- Bot Welcome Bubble -->
                            <div class="flex items-start gap-2 max-w-[85%]">
                                <div class="p-3 rounded-2xl bg-slate-800 text-slate-200 text-xs shadow-sm space-y-1">
                                    <p>Halo! Saya adalah Customer Service Virtual Anda. Silakan ajukan pertanyaan simulasi untuk menguji pemahaman bot dan rujukan Knowledge Base.</p>
                                    <span class="text-[9px] text-slate-500 font-mono block text-right">Otomatis</span>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Composer Input -->
                        <form onsubmit="handlePlaygroundSend(event)" class="p-3 border-t border-slate-800 bg-slate-900/90 flex items-center gap-2">
                            <input type="text" id="playground-prompt-input" placeholder="Ketik pertanyaan pengujian (contoh: Bagaimana cara mengurus surat izin?)..." required class="flex-1 px-3 py-2 text-xs rounded-xl bg-slate-950 border border-slate-700 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                            <button type="submit" id="btn-playground-submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-600/30 flex items-center gap-1.5 cursor-pointer">
                                <span>Kirim</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                        </form>
                    </div>

                    <!-- Right: Grounding Inspector & Diagnostics (5 cols) -->
                    <div class="lg:col-span-5 rounded-2xl p-5 bg-slate-900/80 border border-slate-700/60 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold text-slate-200 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                                <span>Inspektur Grounding &amp; Latensi</span>
                            </h3>
                            <span class="text-[10px] font-mono text-purple-400 bg-purple-500/10 px-2 py-0.5 rounded-full border border-purple-500/20">RAG Diagnostic</span>
                        </div>

                        <!-- Metrics Grid -->
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-800">
                                <div class="text-[10px] text-slate-400">Latensi Respons</div>
                                <div class="text-base font-bold text-emerald-400 font-mono mt-0.5" id="inspect-latency">- ms</div>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-800">
                                <div class="text-[10px] text-slate-400">Token Digunakan</div>
                                <div class="text-base font-bold text-slate-100 font-mono mt-0.5" id="inspect-tokens">-</div>
                            </div>
                        </div>

                        <!-- Retrieved Chunks Box -->
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-slate-300">Potongan Referensi Knowledge Base Terpilih:</label>
                            <div class="p-3 rounded-xl bg-slate-950/80 border border-slate-800 text-[11px] text-slate-400 font-mono min-h-[140px] max-h-[180px] overflow-y-auto leading-relaxed" id="inspect-chunks">
                                Belum ada interaksi pengujian. Ajukan pertanyaan di panel kiri untuk melihat kutipan dokumen rujukan.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB: WHATSAPP BROADCAST & CAMPAIGNS                             -->
        <!-- =============================================================== -->
        <div id="tab-broadcast" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl border-emerald-500/20">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            </span>
                            <h2 class="text-base sm:text-lg font-bold text-slate-100">WhatsApp Broadcast &amp; Pengiriman Massal</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Anti-Spam Throttled</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Kirimkan pesan pengumuman resmi, sosialisasi program, atau notifikasi ke daftar nomor WhatsApp secara teratur dan terjadwal.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    <!-- Wizard Form (5 cols) -->
                    <div class="lg:col-span-5 rounded-2xl p-5 bg-slate-900/80 border border-slate-700/60 space-y-4">
                        <h3 class="text-xs font-bold text-slate-200 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>Formulir Buat Kampanye Baru</span>
                        </h3>
                        <form onsubmit="handleCreateBroadcast(event)" class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Kampanye <span class="text-emerald-400">*</span></label>
                                <input type="text" id="bc-name" placeholder="Contoh: Sosialisasi Layanan Digital 2026" required class="w-full px-3 py-2 text-xs rounded-xl bg-slate-950 border border-slate-700 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1">Target Nomor Penerima <span class="text-emerald-400">*</span></label>
                                <textarea id="bc-recipients" rows="4" placeholder="081234567890&#10;085298765432&#10;628112233445" required class="w-full px-3 py-2 text-xs font-mono rounded-xl bg-slate-950 border border-slate-700 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500"></textarea>
                                <p class="text-[10px] text-slate-500 mt-1">Satu nomor WhatsApp per baris atau dipisahkan tanda koma.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1">Pesan Broadcast <span class="text-emerald-400">*</span></label>
                                <textarea id="bc-content" rows="4" placeholder="Yth. Bapak/Ibu {nama}, kami menginformasikan bahwa..." required class="w-full px-3 py-2 text-xs rounded-xl bg-slate-950 border border-slate-700 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1">Jeda Pengiriman (Throttle Delay)</label>
                                <select id="bc-delay" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-950 border border-slate-700 text-slate-100 focus:outline-none focus:border-emerald-500">
                                    <option value="2000">2 Detik / Pesan (Standar)</option>
                                    <option value="4000">4 Detik / Pesan (Aman untuk daftar > 100)</option>
                                    <option value="1000">1 Detik / Pesan (Cepat)</option>
                                </select>
                            </div>

                            <button type="submit" id="btn-submit-bc" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span>Mulai Kirim Kampanye</span>
                            </button>
                        </form>
                    </div>

                    <!-- Campaigns Table (7 cols) -->
                    <div class="lg:col-span-7 rounded-2xl border border-slate-800 bg-slate-950/60 overflow-hidden shadow-md">
                        <div class="p-3.5 px-4 border-b border-slate-800 bg-slate-900/60 flex items-center justify-between">
                            <h3 class="text-xs font-bold text-slate-200">Riwayat Kampanye Broadcast</h3>
                            <span class="text-[10px] font-mono text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20">{{ count($broadcasts['items'] ?? $broadcasts ?? []) }} Kampanye</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-300">
                                <thead class="bg-slate-900/40 text-[10px] uppercase text-slate-400 border-b border-slate-800">
                                    <tr>
                                        <th class="py-2.5 px-3">Nama Kampanye</th>
                                        <th class="py-2.5 px-3">Penerima</th>
                                        <th class="py-2.5 px-3">Status</th>
                                        <th class="py-2.5 px-3">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/60">
                                    @forelse($broadcasts['items'] ?? $broadcasts ?? [] as $bc)
                                        <tr>
                                            <td class="py-3 px-3 font-bold text-slate-100">{{ $bc['name'] ?? 'Broadcast' }}</td>
                                            <td class="py-3 px-3 font-mono text-emerald-400">{{ $bc['totalRecipients'] ?? 0 }} Nomor</td>
                                            <td class="py-3 px-3">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                    {{ $bc['status'] ?? 'COMPLETED' }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-3 text-slate-500 font-mono text-[10px]">{{ isset($bc['createdAt']) ? \Carbon\Carbon::parse($bc['createdAt'])->format('d/m/Y H:i') : '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-8 text-center text-slate-500">
                                                <p class="text-xs">Belum ada riwayat broadcast yang dikirim.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB: TIM & BEBAN OPERATOR                                       -->
        <!-- =============================================================== -->
        <div id="tab-teams" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl border-emerald-500/20">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </span>
                            <h2 class="text-base sm:text-lg font-bold text-slate-100">Tim &amp; Beban Kapasitas Operator</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Workload SLA</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Pantau ketersediaan agen CS, beban antrean aktif, dan SLA kecepatan balasan pertama (First Response Time).</p>
                    </div>
                </div>

                <!-- Operator Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="rounded-2xl p-5 bg-slate-900/80 border border-slate-700/60 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400 text-slate-950 flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($currentUser['name'] ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-100">{{ $currentUser['name'] ?? 'Petugas CS' }}</h4>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $currentUser['email'] ?? 'admin@domain.id' }}</span>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Online
                            </span>
                        </div>

                        <div class="space-y-1.5 pt-2 border-t border-slate-800">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-400">Beban Chat Aktif</span>
                                <span class="font-mono font-bold text-emerald-400">2 / 10 Chat</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 20%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-slate-400 pt-1">
                            <span>SLA Response: <strong class="text-slate-200">1.2 Menit</strong></span>
                            <span class="text-emerald-400 font-bold">Kapasitas Aman</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB: ATURAN OTOMASI & JAM KERJA                                 -->
        <!-- =============================================================== -->
        <div id="tab-automations" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl border-emerald-500/20">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <h2 class="text-base sm:text-lg font-bold text-slate-100">Aturan Jam Kerja &amp; Penjawab Otomatis</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Auto Responder</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Konfigurasikan jam operasional kantor dan pesan balasan instan saat pesan masuk di luar jam layanan.</p>
                    </div>
                </div>

                <form onsubmit="handleSaveBusinessHours(event)" class="rounded-2xl p-5 bg-slate-900/80 border border-slate-700/60 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Jam Buka Layanan (Senin - Jumat)</label>
                            <input type="time" id="bh-open" value="08:00" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-950 border border-slate-700 text-slate-100 focus:outline-none focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Jam Tutup Layanan (Senin - Jumat)</label>
                            <input type="time" id="bh-close" value="16:00" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-950 border border-slate-700 text-slate-100 focus:outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Pesan Balasan Di Luar Jam Kerja (After-Hours Auto-Responder)</label>
                        <textarea id="bh-message" rows="3" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-950 border border-slate-700 text-slate-100 focus:outline-none focus:border-emerald-500">Terima kasih telah menghubungi kami. Saat ini layanan customer service sedang berada di luar jam operasional (08:00 - 16:00 WIB). Pesan Anda telah kami catat dan akan kami respons pada jam kerja berikutnya.</textarea>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-600/30 flex items-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Aturan Jam Kerja</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB: BIAYA & TOKEN AI                                           -->
        <!-- =============================================================== -->
        <div id="tab-usage" class="tab-content hidden space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl border-emerald-500/20">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </span>
                            <h2 class="text-base sm:text-lg font-bold text-slate-100">Biaya &amp; Penggunaan Token AI</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">BYOK Transparency</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Transparansi penuh konsumsi token dan estimasi biaya penyedia AI (OpenRouter / OpenAI / Gemini).</p>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="rounded-2xl p-4 bg-slate-900/80 border border-slate-700/60 space-y-1">
                        <span class="text-xs text-slate-400">Total Permintaan AI</span>
                        <div class="text-2xl font-black text-slate-100 font-mono">{{ number_format($usageSummary['totalCalls'] ?? 0) }}</div>
                        <span class="text-[10px] text-emerald-400">Panggilan AI Sukses</span>
                    </div>

                    <div class="rounded-2xl p-4 bg-slate-900/80 border border-slate-700/60 space-y-1">
                        <span class="text-xs text-slate-400">Total Token Digunakan</span>
                        <div class="text-2xl font-black text-emerald-400 font-mono">{{ number_format($usageSummary['totalTokens'] ?? 0) }}</div>
                        <span class="text-[10px] text-slate-500 font-mono">Prompt + Completion</span>
                    </div>

                    <div class="rounded-2xl p-4 bg-slate-900/80 border border-slate-700/60 space-y-1">
                        <span class="text-xs text-slate-400">Estimasi Biaya USD</span>
                        <div class="text-2xl font-black text-amber-400 font-mono">${{ number_format($usageSummary['estimatedCostUsd'] ?? 0, 4) }}</div>
                        <span class="text-[10px] text-slate-500">Berdasarkan tarif token</span>
                    </div>

                    <div class="rounded-2xl p-4 bg-slate-900/80 border border-slate-700/60 space-y-1">
                        <span class="text-xs text-slate-400">Estimasi Rupiah</span>
                        <div class="text-2xl font-black text-slate-100 font-mono">Rp {{ number_format(($usageSummary['estimatedCostUsd'] ?? 0) * 16300) }}</div>
                        <span class="text-[10px] text-emerald-400">Kurs Rp 16.300/USD</span>
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
        <!-- TAB 6: INTEGRASI MULTI-FRAMEWORK & LIFE CYCLE                   -->
        <!-- =============================================================== -->
        <div id="tab-lifecycle" class="tab-content hidden space-y-6">
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
        &copy; {{ date('Y') }} PlatformCore WhatsApp Gateway (Waku Dashboard v1.2.4). Dilindungi Hak Cipta.
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

        // ===============================================================
        // LIVE CHAT INBOX & HUMAN HANDOVER LOGIC
        // ===============================================================
        let activeConversationId = null;
        let activeConversationMode = 'AI';
        let activeConversationStatus = 'OPEN';

        function filterInboxConversations() {
            const query = (document.getElementById('inbox-search')?.value || '').toLowerCase();
            const items = document.querySelectorAll('.inbox-item');
            items.forEach(item => {
                const name = (item.getAttribute('data-name') || '').toLowerCase();
                const phone = (item.getAttribute('data-phone') || '').toLowerCase();
                item.style.display = (name.includes(query) || phone.includes(query)) ? '' : 'none';
            });
        }

        function setInboxFilter(filter) {
            document.querySelectorAll('.inbox-filter-btn').forEach(btn => {
                btn.className = 'inbox-filter-btn px-2.5 py-1 rounded-lg bg-slate-800/80 text-slate-400 font-medium hover:text-slate-200 whitespace-nowrap';
            });
            if (window.event?.currentTarget) {
                window.event.currentTarget.className = 'inbox-filter-btn px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30 whitespace-nowrap';
            }

            const items = document.querySelectorAll('.inbox-item');
            items.forEach(item => {
                const mode = item.getAttribute('data-mode');
                const status = item.getAttribute('data-status');
                if (filter === 'all') {
                    item.style.display = '';
                } else if (filter === 'AI') {
                    item.style.display = (mode !== 'AGENT' && mode !== 'HUMAN') ? '' : 'none';
                } else if (filter === 'AGENT') {
                    item.style.display = (mode === 'AGENT' || mode === 'HUMAN') ? '' : 'none';
                } else if (filter === 'RESOLVED') {
                    item.style.display = (status === 'RESOLVED') ? '' : 'none';
                }
            });
        }

        async function selectConversation(id, name, phone, mode, status) {
            activeConversationId = id;
            activeConversationMode = mode;
            activeConversationStatus = status;

            // Visual highlight active item
            document.querySelectorAll('.inbox-item').forEach(el => {
                el.classList.remove('bg-slate-800/90', 'border-emerald-500/50');
            });
            const activeEl = document.querySelector(`.inbox-item[data-id="${id}"]`);
            if (activeEl) {
                activeEl.classList.add('bg-slate-800/90', 'border-emerald-500/50');
            }

            // Show active pane, hide placeholder
            document.getElementById('inbox-empty-view')?.classList.add('hidden');
            document.getElementById('inbox-active-view')?.classList.remove('hidden');

            // Update chat header info
            const avatarEl = document.getElementById('active-chat-avatar');
            if (avatarEl) avatarEl.innerText = (name || 'P').charAt(0).toUpperCase();

            const nameEl = document.getElementById('active-chat-name');
            if (nameEl) nameEl.innerText = name || 'Pelanggan';

            const phoneEl = document.getElementById('active-chat-phone');
            if (phoneEl) phoneEl.innerText = phone || '-';

            const badgeEl = document.getElementById('active-chat-mode-badge');
            const btnTakeover = document.getElementById('btn-takeover');
            const btnReturnBot = document.getElementById('btn-return-bot');

            if (mode === 'AGENT' || mode === 'HUMAN') {
                if (badgeEl) {
                    badgeEl.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30';
                    badgeEl.innerText = 'Human Handover';
                }
                btnTakeover?.classList.add('hidden');
                btnReturnBot?.classList.remove('hidden');
            } else {
                if (badgeEl) {
                    badgeEl.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
                    badgeEl.innerText = 'AI Bot Aktif';
                }
                btnTakeover?.classList.remove('hidden');
                btnReturnBot?.classList.add('hidden');
            }

            // Fetch conversation messages
            const container = document.getElementById('timeline-bubbles-container');
            if (container) {
                container.innerHTML = `
                    <div class="py-8 text-center text-xs text-slate-500 flex flex-col items-center gap-2">
                        <svg class="animate-spin w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Memuat linimasa obrolan...</span>
                    </div>
                `;
            }

            try {
                const res = await fetch("{{ url($config['dashboardUrl']) }}/conversations/" + encodeURIComponent(id), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!res.ok) {
                    let errMsg = `Gagal memuat (HTTP ${res.status})`;
                    try {
                        const errJson = await res.json();
                        errMsg = errJson.error || errJson.message || errMsg;
                    } catch (_) {
                        const raw = await res.text();
                        if (raw.includes('<!DOCTYPE') || raw.includes('<html')) {
                            errMsg = `Server mengembalikan respon HTML (HTTP ${res.status}). Pastikan endpoint dan autentikasi sesi valid.`;
                        }
                    }
                    throw new Error(errMsg);
                }

                const json = await res.json();
                const messages = json.data?.messages || json.data?.conversation?.messages || json.messages || [];
                renderTimelineMessages(messages);
            } catch (err) {
                if (container) {
                    container.innerHTML = `
                        <div class="p-4 rounded-xl bg-rose-950/40 border border-rose-800/40 text-center text-xs text-rose-300 space-y-2">
                            <p class="font-semibold">Gagal memuat pesan obrolan: ${escapeHtml(err.message)}</p>
                            <button type="button" onclick="loadConversationDetail('${escapeHtml(id)}')" class="px-3 py-1 rounded-lg bg-rose-800/60 hover:bg-rose-700 text-[11px] text-white transition-colors cursor-pointer">
                                Coba Muat Ulang
                            </button>
                        </div>
                    `;
                }
            }
        }

        function renderTimelineMessages(messages) {
            const container = document.getElementById('timeline-bubbles-container');
            if (!container) return;
            container.innerHTML = '';

            if (!messages || messages.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-6 text-slate-500 text-xs">
                        Belum ada riwayat pesan dalam percakapan ini.
                    </div>
                `;
                return;
            }

            messages.forEach(msg => {
                const isIncoming = msg.direction === 'INBOUND' || msg.senderType === 'CUSTOMER' || msg.fromMe === false;
                const time = msg.createdAt ? new Date(msg.createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
                const bubble = document.createElement('div');

                if (isIncoming) {
                    bubble.className = 'flex items-start gap-2 max-w-[85%]';
                    bubble.innerHTML = `
                        <div class="p-3 rounded-2xl rounded-tl-none bg-slate-800 text-slate-100 text-xs shadow-sm space-y-1">
                            <p class="leading-relaxed whitespace-pre-line">${escapeHtml(msg.content || '')}</p>
                            <span class="text-[9px] text-slate-400 font-mono block text-right">${time}</span>
                        </div>
                    `;
                } else {
                    bubble.className = 'flex items-start justify-end gap-2 max-w-[85%] ml-auto';
                    bubble.innerHTML = `
                        <div class="p-3 rounded-2xl rounded-tr-none bg-emerald-700/80 text-white text-xs shadow-sm space-y-1">
                            <p class="leading-relaxed whitespace-pre-line">${escapeHtml(msg.content || '')}</p>
                            <div class="flex items-center justify-end gap-1 text-[9px] text-emerald-200">
                                <span>${time}</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                    `;
                }
                container.appendChild(bubble);
            });

            const scrollArea = document.getElementById('inbox-messages-timeline');
            if (scrollArea) scrollArea.scrollTop = scrollArea.scrollHeight;
        }

        function insertQuickMessage(text) {
            const input = document.getElementById('inbox-reply-input');
            if (input) {
                input.value = text;
                input.focus();
            }
        }

        async function handleSendInboxReply(e) {
            e.preventDefault();
            if (!activeConversationId) {
                showToast('Pilih percakapan terlebih dahulu!');
                return;
            }

            const input = document.getElementById('inbox-reply-input');
            const content = (input?.value || '').trim();
            if (!content) return;

            // Optimistic bubble append
            const container = document.getElementById('timeline-bubbles-container');
            const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const bubble = document.createElement('div');
            bubble.className = 'flex items-start justify-end gap-2 max-w-[85%] ml-auto';
            bubble.innerHTML = `
                <div class="p-3 rounded-2xl rounded-tr-none bg-emerald-700/80 text-white text-xs shadow-sm space-y-1">
                    <p class="leading-relaxed whitespace-pre-line">${escapeHtml(content)}</p>
                    <div class="flex items-center justify-end gap-1 text-[9px] text-emerald-200">
                        <span>${now}</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>
            `;
            container?.appendChild(bubble);
            input.value = '';

            const scrollArea = document.getElementById('inbox-messages-timeline');
            if (scrollArea) scrollArea.scrollTop = scrollArea.scrollHeight;

            try {
                const res = await fetch("{{ url($config['dashboardUrl']) }}/conversations/" + encodeURIComponent(activeConversationId) + "/reply", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ content })
                });
                const json = await res.json();
                if (json.success === false) {
                    showToast('Gagal mengirim: ' + (json.error || 'Terjadi kesalahan'));
                }
            } catch (err) {
                showToast('Error: ' + err.message);
            }
        }

        async function takeoverActiveChat() {
            if (!activeConversationId) return;
            try {
                const res = await fetch("{{ url($config['dashboardUrl']) }}/conversations/" + encodeURIComponent(activeConversationId) + "/takeover", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const json = await res.json();
                if (json.success !== false) {
                    showToast('Percakapan diambil alih ke Agen Manusia');
                    document.getElementById('btn-takeover')?.classList.add('hidden');
                    document.getElementById('btn-return-bot')?.classList.remove('hidden');
                    const badge = document.getElementById('active-chat-mode-badge');
                    if (badge) {
                        badge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30';
                        badge.innerText = 'Human Handover';
                    }
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        async function returnActiveChatToBot() {
            if (!activeConversationId) return;
            try {
                const res = await fetch("{{ url($config['dashboardUrl']) }}/conversations/" + encodeURIComponent(activeConversationId) + "/return-to-bot", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const json = await res.json();
                if (json.success !== false) {
                    showToast('Percakapan dikembalikan ke AI Bot');
                    document.getElementById('btn-return-bot')?.classList.add('hidden');
                    document.getElementById('btn-takeover')?.classList.remove('hidden');
                    const badge = document.getElementById('active-chat-mode-badge');
                    if (badge) {
                        badge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
                        badge.innerText = 'AI Bot Aktif';
                    }
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        async function resolveActiveChat() {
            if (!activeConversationId) return;
            const result = await Swal.fire({
                title: 'Tandai Selesai?',
                text: 'Percakapan akan diselesaikan dan diarsipkan statusnya.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Tandai Selesai',
                cancelButtonText: 'Batal'
            });
            if (!result.isConfirmed) return;

            try {
                const res = await fetch("{{ url($config['dashboardUrl']) }}/conversations/" + encodeURIComponent(activeConversationId) + "/resolve", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const json = await res.json();
                if (json.success !== false) {
                    showToast('Percakapan berhasil diselesaikan');
                    const badge = document.getElementById('active-chat-mode-badge');
                    if (badge) {
                        badge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-700 text-slate-300 border border-slate-600';
                        badge.innerText = 'Selesai (Resolved)';
                    }
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        // ===============================================================
        // KNOWLEDGE BASE LOGIC
        // ===============================================================
        function toggleKnowledgeUploadForm() {
            const el = document.getElementById('knowledge-upload-box');
            if (el) el.classList.toggle('hidden');
        }

        async function handleUploadKnowledgeDoc(e) {
            e.preventDefault();
            const kbId = document.getElementById('kb-id')?.value || 'default';
            const title = document.getElementById('doc-title')?.value;
            const content = document.getElementById('doc-content')?.value;
            const sourceUrl = document.getElementById('doc-source')?.value || null;

            if (!title || !content) {
                Swal.fire('Form Belum Lengkap', 'Judul dan isi materi SOP wajib diisi.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Mengindeks Dokumen...',
                text: 'Memproses teks ke dalam basis pengetahuan RAG AI...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const res = await fetch("{{ url($config['dashboardUrl']) }}/knowledge/" + encodeURIComponent(kbId) + "/documents", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ title, content, contentType: 'text/plain', sourceUrl })
                });
                const json = await res.json();
                if (json.success !== false) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Dokumen Terindeks!',
                        text: `Dokumen "${title}" telah berhasil diindeks ke Knowledge Base AI.`,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    location.reload();
                } else {
                    Swal.fire('Gagal Mengindeks', json.error || 'Terjadi kesalahan.', 'error');
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        async function deleteKnowledgeDoc(docId) {
            const result = await Swal.fire({
                title: 'Hapus Dokumen SOP?',
                text: 'Dokumen ini akan dihapus dari indeks AI Customer Service.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Dokumen',
                cancelButtonText: 'Batal'
            });
            if (!result.isConfirmed) return;

            try {
                const res = await fetch("{{ url($config['dashboardUrl']) }}/knowledge/documents/" + encodeURIComponent(docId) + "/delete", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const json = await res.json();
                if (json.success !== false) {
                    showToast('Dokumen berhasil dihapus');
                    location.reload();
                } else {
                    Swal.fire('Gagal', json.error || 'Gagal menghapus dokumen', 'error');
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        // ===============================================================
        // CHATBOT FLOWS LOGIC
        // ===============================================================
        async function showCreateFlowModal() {
            const { value: formValues } = await Swal.fire({
                title: 'Buat Alur Percakapan Baru',
                html: `
                    <div class="space-y-3 text-left">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Nama Flow</label>
                            <input id="swal-flow-name" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-900 border border-slate-700 text-slate-100" placeholder="Contoh: Pendaftaran Layanan">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Kata Kunci Pemicu (Koma dipisah)</label>
                            <input id="swal-flow-trigger" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-900 border border-slate-700 text-slate-100" placeholder="daftar, registrasi, formulir">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Pesan Balasan Otomatis</label>
                            <textarea id="swal-flow-reply" rows="3" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-900 border border-slate-700 text-slate-100" placeholder="Ketik isi balasan WhatsApp..."></textarea>
                        </div>
                    </div>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Simpan Alur',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#9333ea',
                preConfirm: () => {
                    const name = document.getElementById('swal-flow-name').value;
                    const trigger = document.getElementById('swal-flow-trigger').value;
                    const reply = document.getElementById('swal-flow-reply').value;
                    if (!name || !trigger || !reply) {
                        Swal.showValidationMessage('Semua bidang wajib diisi!');
                    }
                    return { name, trigger, reply };
                }
            });

            if (formValues) {
                try {
                    const res = await fetch("{{ url($config['dashboardUrl']) }}/flows", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                        body: JSON.stringify({
                            name: formValues.name,
                            triggerValue: formValues.trigger,
                            responseMessage: formValues.reply,
                            isActive: true
                        })
                    });
                    const json = await res.json();
                    if (json.success !== false) {
                        await Swal.fire('Tersimpan', 'Alur percakapan baru berhasil dibuat.', 'success');
                        location.reload();
                    } else {
                        Swal.fire('Gagal', json.error || 'Gagal menyimpan flow.', 'error');
                    }
                } catch (err) {
                    Swal.fire('Error', err.message, 'error');
                }
            }
        }

        // ===============================================================
        // AI CS PLAYGROUND SIMULATION LOGIC
        // ===============================================================
        function resetPlaygroundChat() {
            const container = document.getElementById('playground-chat-bubbles');
            if (container) {
                container.innerHTML = `
                    <div class="flex items-start gap-2 max-w-[85%]">
                        <div class="p-3 rounded-2xl bg-slate-800 text-slate-200 text-xs shadow-sm space-y-1">
                            <p>Halo! Saya adalah Customer Service Virtual Anda. Silakan ajukan pertanyaan simulasi untuk menguji pemahaman bot dan rujukan Knowledge Base.</p>
                            <span class="text-[9px] text-slate-500 font-mono block text-right">Otomatis</span>
                        </div>
                    </div>
                `;
            }
            const latencyEl = document.getElementById('inspect-latency');
            if (latencyEl) latencyEl.innerText = '- ms';
            const tokensEl = document.getElementById('inspect-tokens');
            if (tokensEl) tokensEl.innerText = '-';
            const chunksEl = document.getElementById('inspect-chunks');
            if (chunksEl) chunksEl.innerText = 'Belum ada interaksi pengujian. Ajukan pertanyaan di panel kiri untuk melihat kutipan dokumen rujukan.';
        }

        async function handlePlaygroundSend(e) {
            e.preventDefault();
            const input = document.getElementById('playground-prompt-input');
            const prompt = (input?.value || '').trim();
            if (!prompt) return;

            const container = document.getElementById('playground-chat-bubbles');

            // Append User Bubble
            const userBubble = document.createElement('div');
            userBubble.className = 'flex items-start justify-end gap-2 max-w-[85%] ml-auto';
            userBubble.innerHTML = `
                <div class="p-3 rounded-2xl rounded-tr-none bg-emerald-700/80 text-white text-xs shadow-sm space-y-1">
                    <p class="leading-relaxed whitespace-pre-line">${escapeHtml(prompt)}</p>
                    <span class="text-[9px] text-emerald-200 font-mono block text-right">Anda (Penguji)</span>
                </div>
            `;
            container?.appendChild(userBubble);
            input.value = '';

            // Append Typing Indicator
            const typingBubble = document.createElement('div');
            typingBubble.id = 'pg-typing-indicator';
            typingBubble.className = 'flex items-start gap-2 max-w-[85%]';
            typingBubble.innerHTML = `
                <div class="p-3 rounded-2xl bg-slate-800 text-slate-400 text-xs shadow-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-bounce"></span>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-bounce" style="animation-delay: 0.15s"></span>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-bounce" style="animation-delay: 0.3s"></span>
                    <span class="text-[11px] font-medium text-slate-300">AI CS sedang memproses &amp; mencari Knowledge Base...</span>
                </div>
            `;
            container?.appendChild(typingBubble);
            if (container) container.scrollTop = container.scrollHeight;

            const startTime = performance.now();

            try {
                const res = await fetch("{{ url($config['dashboardUrl']) }}/ai/playground/chat", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ message: prompt })
                });
                const elapsed = Math.round(performance.now() - startTime);
                const json = await res.json();
                typingBubble.remove();

                const botReply = json.data?.reply || json.reply || json.data?.content || json.content || 'Terima kasih atas pertanyaan Anda.';
                const tokensUsed = json.data?.tokensUsed || json.tokensUsed || Math.round((prompt.length + botReply.length) / 4);

                // Update Grounding Inspector
                const latencyEl = document.getElementById('inspect-latency');
                if (latencyEl) latencyEl.innerText = elapsed + ' ms';
                const tokensEl = document.getElementById('inspect-tokens');
                if (tokensEl) tokensEl.innerText = tokensUsed + ' Token';
                const chunksEl = document.getElementById('inspect-chunks');
                if (chunksEl) {
                    const citations = json.data?.citations || json.citations || [];
                    if (citations.length > 0) {
                        chunksEl.innerHTML = citations.map((c, i) => `<div class="p-1.5 rounded bg-slate-900 border border-slate-800 mb-1"><span class="text-emerald-400 font-bold">[Rujukan ${i+1}]</span> ${escapeHtml(c.text || c)}</div>`).join('');
                    } else {
                        chunksEl.innerText = 'Jawaban disintesis langsung berdasarkan Persona AI Bot & SOP Terindeks.';
                    }
                }

                // Append Bot Bubble
                const botBubble = document.createElement('div');
                botBubble.className = 'flex items-start gap-2 max-w-[85%]';
                botBubble.innerHTML = `
                    <div class="p-3 rounded-2xl rounded-tl-none bg-slate-800 text-slate-100 text-xs shadow-sm space-y-1.5">
                        <p class="leading-relaxed whitespace-pre-line">${escapeHtml(botReply)}</p>
                        <div class="flex items-center justify-between text-[9px] text-slate-400 font-mono pt-1 border-t border-slate-700/60">
                            <span class="text-emerald-400">⚡ ${elapsed}ms</span>
                            <span>🪙 ${tokensUsed} tokens</span>
                        </div>
                    </div>
                `;
                container?.appendChild(botBubble);
                if (container) container.scrollTop = container.scrollHeight;
            } catch (err) {
                typingBubble.remove();
                const errBubble = document.createElement('div');
                errBubble.className = 'flex items-start gap-2 max-w-[85%]';
                errBubble.innerHTML = `
                    <div class="p-3 rounded-2xl bg-rose-950/60 border border-rose-800/60 text-rose-300 text-xs">
                        Gagal mendapatkan respons AI: ${escapeHtml(err.message)}
                    </div>
                `;
                container?.appendChild(errBubble);
                if (container) container.scrollTop = container.scrollHeight;
            }
        }

        // ===============================================================
        // BROADCAST & AUTOMATIONS LOGIC
        // ===============================================================
        async function handleCreateBroadcast(e) {
            e.preventDefault();
            const name = document.getElementById('bc-name')?.value;
            const recipientsRaw = document.getElementById('bc-recipients')?.value;
            const content = document.getElementById('bc-content')?.value;
            const delay = document.getElementById('bc-delay')?.value || 2000;

            if (!name || !recipientsRaw || !content) {
                Swal.fire('Form Belum Lengkap', 'Nama kampanye, daftar nomor penerima, dan pesan wajib diisi.', 'warning');
                return;
            }

            const recipients = recipientsRaw.split(/[\n,]+/).map(s => s.trim()).filter(Boolean);

            Swal.fire({
                title: 'Mempersiapkan Kampanye Broadcast...',
                text: `Memvalidasi ${recipients.length} nomor tujuan dan antrean pengiriman...`,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const res = await fetch("{{ url($config['dashboardUrl']) }}/broadcasts", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({
                        name,
                        recipients,
                        content,
                        throttleDelayMs: parseInt(delay, 10),
                        status: 'QUEUED'
                    })
                });
                const json = await res.json();
                if (json.success !== false) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Broadcast Diterbitkan!',
                        text: `Kampanye "${name}" berhasil dibuat dan masuk antrean pengiriman aman.`,
                        confirmButtonColor: '#10b981'
                    });
                    location.reload();
                } else {
                    Swal.fire('Gagal Membuat Broadcast', json.error || 'Terjadi kesalahan gateway.', 'error');
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        async function handleSaveBusinessHours(e) {
            e.preventDefault();
            const openTime = document.getElementById('bh-open')?.value;
            const closeTime = document.getElementById('bh-close')?.value;
            const message = document.getElementById('bh-message')?.value;

            Swal.fire({
                title: 'Menyimpan Aturan Jam Kerja...',
                didOpen: () => Swal.showLoading()
            });

            try {
                const res = await fetch("{{ url($config['dashboardUrl']) }}/automations/business-hours", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ openTime, closeTime, message, enabled: true })
                });
                const json = await res.json();
                if (json.success !== false) {
                    Swal.fire('Tersimpan', 'Aturan operasional dan pesan penjawab otomatis berhasil diperbarui.', 'success');
                } else {
                    Swal.fire('Gagal', json.error || 'Gagal menyimpan.', 'error');
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        let isAiBotCurrentlyActive = {{ ($botConfig['botMode'] ?? 'HYBRID') !== 'DISABLED' ? 'true' : 'false' }};

        function toggleTokenVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('icon-eye-' + inputId);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
                }
            }
        }

        function selectModelPreset(modelName) {
            const input = document.getElementById('ai-model-name');
            if (input) {
                input.value = modelName;
                input.focus();
            }
        }

        function handleAiProviderChange() {
            const prov = document.getElementById('ai-provider-type')?.value;
            const modelInput = document.getElementById('ai-model-name');
            if (!modelInput) return;
            if (prov === 'GEMINI') {
                modelInput.value = 'google/gemini-2.5-flash';
            } else if (prov === 'OPENAI') {
                modelInput.value = 'gpt-4o-mini';
            } else {
                modelInput.value = 'openrouter/free';
            }
        }

        const hasActiveAiToken = {{ !empty($botMaskedKey) ? 'true' : 'false' }};

        async function toggleAiBotState(targetSessionId = null) {
            const newActive = !isAiBotCurrentlyActive;
            const actionText = newActive ? 'mengaktifkan' : 'menonaktifkan';

            // Syarat aktif: konsumen wajib memasukkan token OpenRouter terlebih dahulu
            if (newActive && !hasActiveAiToken) {
                await Swal.fire({
                    icon: 'warning',
                    title: 'Token OpenRouter Wajib Diisi',
                    html: '<div class="text-left text-xs text-slate-300 space-y-2 leading-relaxed">' +
                          '<p>Untuk mengaktifkan Bot AI, Anda <b>wajib memasukkan Token OpenRouter</b> terlebih dahulu.</p>' +
                          '<p>Silakan isi token pada formulir <b>Kredensial Token AI Router</b> di bawah ini lalu klik <b>Simpan Konfigurasi</b>.</p>' +
                          '</div>',
                    confirmButtonText: 'Isi Token Sekarang',
                    confirmButtonColor: '#9333ea',
                });
                const tokenInput = document.getElementById('ai-router-token');
                if (tokenInput) {
                    tokenInput.focus();
                    tokenInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }

            const confirm = await Swal.fire({
                title: `${newActive ? 'Aktifkan' : 'Nonaktifkan'} Bot AI?`,
                text: `Apakah Anda yakin ingin ${actionText} bot AI untuk merespons pesan WhatsApp masuk?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: newActive ? '#10b981' : '#f43f5e',
                cancelButtonColor: '#64748b',
                confirmButtonText: `Ya, ${newActive ? 'Aktifkan' : 'Nonaktifkan'}`,
                cancelButtonText: 'Batal'
            });

            if (!confirm.isConfirmed) return;

            Swal.fire({
                title: 'Memproses Perubahan Status Bot...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const res = await fetch("{{ url($config['dashboardUrl']) }}/ai/toggle", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        enabled: newActive,
                        sessionId: targetSessionId || document.getElementById('ai-session-id')?.value || null
                    })
                });

                if (!res.ok) {
                    const errJson = await res.json().catch(() => ({}));
                    throw new Error(errJson.error || errJson.message || `HTTP ${res.status}`);
                }

                const json = await res.json();
                if (json.success !== false) {
                    isAiBotCurrentlyActive = newActive;
                    await Swal.fire({
                        icon: 'success',
                        title: `Bot AI Berhasil ${newActive ? 'Diaktifkan' : 'Dinonaktifkan'}!`,
                        text: newActive
                            ? 'Asisten AI sekarang aktif dan akan membalas pertanyaan pelanggan secara otomatis.'
                            : 'Bot AI dinonaktifkan. Seluruh pesan masuk dialihkan ke Inbox CS.',
                        confirmButtonColor: '#10b981'
                    });
                    location.reload();
                } else {
                    Swal.fire('Gagal Mengubah Status Bot', json.error || 'Terjadi kesalahan.', 'error');
                }
            } catch (err) {
                Swal.fire('Gagal Mengubah Status Bot', err.message, 'error');
            }
        }

        async function handleSaveAiRouterConfig(e) {
            e.preventDefault();
            const providerType = document.getElementById('ai-provider-type')?.value;
            const apiKey = document.getElementById('ai-router-token')?.value?.trim();
            const modelName = document.getElementById('ai-model-name')?.value?.trim();
            const botMode = document.getElementById('ai-bot-mode')?.value;
            const fallbackMessage = document.getElementById('ai-fallback-msg')?.value;
            const sessionId = document.getElementById('ai-session-id')?.value || null;

            // Syarat aktif: jika mode bukan DISABLED, token wajib diisi (jika belum ada token tersimpan)
            if (botMode !== 'DISABLED' && !apiKey && !hasActiveAiToken) {
                await Swal.fire({
                    icon: 'warning',
                    title: 'Token OpenRouter Wajib Diisi',
                    html: '<div class="text-left text-xs text-slate-300 space-y-2 leading-relaxed">' +
                          '<p>Mode bot yang dipilih aktif (<b>' + botMode + '</b>), namun Anda belum memasukkan <b>Token OpenRouter</b>.</p>' +
                          '<p>Silakan isi token OpenRouter Anda terlebih dahulu, atau pilih strategi respon <b>DISABLED</b> jika belum ingin mengaktifkan bot.</p>' +
                          '</div>',
                    confirmButtonText: 'Isi Token Sekarang',
                    confirmButtonColor: '#9333ea',
                });
                document.getElementById('ai-router-token')?.focus();
                return;
            }

            Swal.fire({
                title: 'Menyimpan Kredensial AI Router...',
                text: 'Mengamankan token dengan enkripsi AES-256-GCM...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const payload = {
                providerType,
                modelName,
                botMode,
                fallbackMessage,
                sessionId,
            };

            if (apiKey && apiKey.length > 0) {
                payload.apiKey = apiKey;
            }

            try {
                const res = await fetch("{{ url($config['dashboardUrl']) }}/ai/config", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(payload)
                });

                if (!res.ok) {
                    const errJson = await res.json().catch(() => ({}));
                    throw new Error(errJson.error || errJson.message || `HTTP ${res.status}`);
                }

                const json = await res.json();
                if (json.success !== false) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Konfigurasi AI Router Disimpan!',
                        text: 'Token dan parameter model AI berhasil diperbarui dan aktif digunakan.',
                        confirmButtonColor: '#10b981'
                    });
                    location.reload();
                } else {
                    Swal.fire('Gagal Menyimpan', json.error || 'Terjadi kesalahan gateway.', 'error');
                }
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
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
