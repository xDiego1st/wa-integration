<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Waku Dashboard — Panel Kontrol WhatsApp Gateway</title>
    
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
        code, pre, .font-mono { font-family: 'JetBrains Mono', monospace; }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 0.3; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .animate-pulse-ring { animation: pulse-ring 2.5s infinite ease-in-out; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Top Navigation Header -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-40 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-4">
                
                <!-- Left: Branding & Tag -->
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white flex items-center justify-center shadow-md shadow-emerald-500/20 shrink-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.532 1.776.814 2.796.814 3.18 0 5.765-2.586 5.766-5.766 0-3.18-2.586-5.766-5.766-5.766zm9.969 5.766c0 5.485-4.464 9.948-9.969 9.948-1.709 0-3.32-.44-4.733-1.213l-5.298 1.389 1.417-5.174c-.878-1.488-1.386-3.226-1.386-5.088 0-5.486 4.464-9.95 9.969-9.95s9.969 4.464 9.969 9.95z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Waku Dashboard</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">v1.0.5</span>
                        </div>
                        <p class="text-xs text-slate-500 truncate hidden sm:block">Panel Kontrol WhatsApp Multi-Device & Integrasi Gateway Mandiri</p>
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
                    <span>1. Koneksi & Live Scan QR</span>
                </button>
                <button type="button" onclick="switchTab('tab-send')" id="btn-tab-send" class="tab-btn inline-flex items-center gap-2 py-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 whitespace-nowrap transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>2. Kirim Pesan Uji Coba</span>
                </button>
                <button type="button" onclick="switchTab('tab-messages')" id="btn-tab-messages" class="tab-btn inline-flex items-center gap-2 py-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 whitespace-nowrap transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>3. Riwayat Pesan</span>
                </button>
                <button type="button" onclick="switchTab('tab-api-guide')" id="btn-tab-api-guide" class="tab-btn inline-flex items-center gap-2 py-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 whitespace-nowrap transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    <span>4. Petunjuk Teknis API</span>
                </button>
                <button type="button" onclick="switchTab('tab-lifecycle')" id="btn-tab-lifecycle" class="tab-btn inline-flex items-center gap-2 py-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 whitespace-nowrap transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>5. Panduan Akses & Uninstall</span>
                </button>
            </nav>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(!$config['hasApiKey'])
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3 shadow-sm">
                <svg class="w-5 h-5 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="text-xs sm:text-sm text-amber-900 leading-relaxed">
                    <strong class="font-bold">Kredensial Dibutuhkan:</strong> Variabel <code class="font-mono text-xs bg-amber-100 px-1.5 py-0.5 rounded font-bold">WAKU_APP_ID</code> dan <code class="font-mono text-xs bg-amber-100 px-1.5 py-0.5 rounded font-bold">WAKU_API_KEY</code> belum ditemukan di berkas <code class="font-mono text-xs">.env</code> Anda. Silakan isi konfigurasi terlebih dahulu untuk mulai menghubungkan WhatsApp.
                </div>
            </div>
        @endif

        <!-- =============================================================== -->
        <!-- TAB 1: KONEKSI WHATSAPP & LIVE SCAN QR (RE-DESIGNED HERO)      -->
        <!-- =============================================================== -->
        <div id="tab-connection" class="tab-content space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left: Hero Connection Status Card -->
                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-md p-6 sm:p-8 space-y-6 relative overflow-hidden">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-5">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Status Koneksi Perangkat</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Pantau dan kelola sesi Baileys WhatsApp Multi-Device secara real-time</p>
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
                                Segarkan Status
                            </button>
                            
                            <button type="button" onclick="openQrModalAndConnect()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-2 shadow-lg shadow-emerald-600/25 transition-all cursor-pointer hover:scale-[1.02]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                Hubungkan / Request QR
                            </button>
                        </div>

                        <button type="button" onclick="disconnectSession()" class="px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold flex items-center gap-2 border border-rose-200/80 transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            Putuskan Sesi
                        </button>
                    </div>
                </div>

                <!-- Right: Active Sessions & Quick Specs -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md p-6 space-y-5 flex flex-col">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900">Daftar Sesi Aplikasi</h3>
                        <button type="button" onclick="promptCreateSession()" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 cursor-pointer">
                            + Tambah Sesi
                        </button>
                    </div>

                    <div id="session-list-container" class="space-y-2 flex-1">
                        @if(empty($sessions))
                            <div class="py-8 text-center text-xs text-slate-500 space-y-2">
                                <p>Belum ada sesi aktif.</p>
                                <p class="text-[11px] text-slate-400">Klik tombol <strong>"Hubungkan / Request QR"</strong> untuk inisialisasi sesi pertama.</p>
                            </div>
                        @else
                            @foreach($sessions as $s)
                                <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-slate-100/70 transition-colors flex items-center justify-between text-xs">
                                    <div class="space-y-0.5 min-w-0">
                                        <div class="font-bold text-slate-900 truncate">{{ $s['name'] ?? 'Default' }}</div>
                                        <div class="font-mono text-slate-500 text-[11px] truncate">{{ $s['phone'] ?? 'Belum Terhubung' }}</div>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold shrink-0 {{ ($s['status'] ?? '') === 'CONNECTED' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                                        {{ $s['status'] ?? 'DISCONNECTED' }}
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Scan Instructions Box -->
                    <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-100 text-xs text-emerald-950 space-y-2">
                        <div class="font-bold flex items-center gap-1.5 text-emerald-900">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Panduan Cepat Scan QR:
                        </div>
                        <ol class="list-decimal list-inside space-y-1 text-slate-600 text-[11px] leading-relaxed">
                            <li>Buka aplikasi WhatsApp di smartphone Anda.</li>
                            <li>Buka menu <strong>Setelan</strong> &gt; <strong>Perangkat Tertaut</strong>.</li>
                            <li>Ketuk tombol <strong>Tautkan Perangkat</strong>.</li>
                            <li>Arahkan kamera ponsel Anda ke QR Code modal.</li>
                        </ol>
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
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 shadow-md p-6 sm:p-8 space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Uji Coba Pengiriman Pesan Real-Time</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Kirim pesan WhatsApp langsung untuk menguji reliabilitas gateway Anda</p>
                    </div>

                    <form onsubmit="handleTestSend(event)" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Tujuan (WhatsApp):</label>
                            <input type="text" id="send-to" required placeholder="Contoh: 08123456789 atau 628123456789" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 font-mono">
                            <span class="text-[11px] text-slate-400 mt-1 block">Format otomatis disesuaikan ke standar internasional E.164.</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe Pesan:</label>
                                <select id="send-type" onchange="handleTypeChange()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                                    <option value="TEXT">Pesan Teks (Standard)</option>
                                    <option value="IMAGE">Pesan Gambar (Foto / Bukti)</option>
                                    <option value="DOCUMENT">Dokumen (PDF / Faktur)</option>
                                </select>
                            </div>
                            <div id="media-url-container" class="hidden">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">URL Berkas Media:</label>
                                <input type="url" id="send-media-url" placeholder="https://domain.com/faktur.pdf" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 font-mono">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konten Pesan / Caption:</label>
                            <textarea id="send-content" rows="4" required placeholder="Halo! Ini adalah pesan uji coba dari Waku WhatsApp Gateway..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed" oninput="updateLiveChatPreview()"></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" id="btn-submit-send" class="w-full py-3 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-lg shadow-emerald-600/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span>Kirim Pesan Sekarang</span>
                            </button>
                        </div>
                    </form>

                    <div id="send-result-box" class="hidden p-4 rounded-2xl text-xs font-mono"></div>
                </div>

                <!-- Right: Live WhatsApp Chat Bubble Preview (5 cols) -->
                <div class="lg:col-span-5 bg-slate-900 rounded-3xl p-6 shadow-xl border border-slate-800 text-white space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-xs font-bold font-mono uppercase tracking-wider text-emerald-400">Live Bubble Preview</span>
                        </div>
                        <span class="text-[11px] text-slate-400 font-mono">WhatsApp Web Mockup</span>
                    </div>

                    <!-- Chat Mockup Window -->
                    <div class="bg-[#0b141a] rounded-2xl p-4 sm:p-5 min-h-[300px] flex flex-col justify-end border border-slate-800 relative overflow-hidden" style="background-image: radial-gradient(#1f2c34 1px, transparent 1px); background-size: 16px 16px;">
                        
                        <!-- System Date Stamp -->
                        <div class="text-center my-2">
                            <span class="px-3 py-1 rounded-lg bg-[#182229] text-[10px] text-slate-400 font-medium uppercase tracking-wider shadow-sm">Hari Ini</span>
                        </div>

                        <!-- Chat Bubble (Sent) -->
                        <div class="self-end max-w-[85%] bg-[#005c4b] text-[#e9edef] rounded-2xl rounded-tr-none p-3.5 shadow-md space-y-1 relative group transition-all">
                            <p id="chat-preview-text" class="text-xs sm:text-[13px] leading-relaxed whitespace-pre-wrap">Halo! Ini adalah pesan uji coba dari Waku WhatsApp Gateway...</p>
                            <div class="flex items-center justify-end gap-1 pt-1 text-[10px] text-emerald-200/80 font-mono">
                                <span id="chat-preview-time">{{ date('H:i') }}</span>
                                <span class="text-sky-300 font-bold">✓✓</span>
                            </div>
                        </div>

                    </div>
                    <p class="text-[11px] text-slate-400 text-center">Tampilan simulasi pesan yang akan diterima oleh nomor tujuan di aplikasi WhatsApp.</p>
                </div>

            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB 3: RIWAYAT PESAN (LOGS VIEWER)                             -->
        <!-- =============================================================== -->
        <div id="tab-messages" class="tab-content hidden space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md p-6 sm:p-8 space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Riwayat Pesan Keluar & Masuk</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar transaksi pesan WhatsApp dengan status delivery dari Baileys</p>
                    </div>
                    <button type="button" onclick="location.reload()" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Segarkan Data
                    </button>
                </div>

                @if(empty($messages))
                    <div class="py-12 text-center text-xs text-slate-500 space-y-2">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <p class="font-bold text-slate-700">Belum Ada Riwayat Pesan</p>
                        <p class="text-slate-400">Pesan yang dikirim melalui aplikasi atau Tab "Kirim Pesan Uji Coba" akan tercatat di sini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto rounded-2xl border border-slate-100">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50/80 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3">Tujuan / Kontak</th>
                                    <th class="px-4 py-3">Isi Pesan</th>
                                    <th class="px-4 py-3">Tipe</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @foreach($messages as $m)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $m['to'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-slate-700 max-w-xs truncate">{{ $m['content'] ?? '-' }}</td>
                                        <td class="px-4 py-3 font-mono text-[11px] text-slate-500">{{ $m['type'] ?? 'TEXT' }}</td>
                                        <td class="px-4 py-3">
                                            @php
                                                $st = $m['status'] ?? 'PENDING';
                                                $color = match($st) {
                                                    'DELIVERED', 'READ' => 'bg-emerald-100 text-emerald-800',
                                                    'SENT' => 'bg-sky-100 text-sky-800',
                                                    'FAILED' => 'bg-rose-100 text-rose-800',
                                                    default => 'bg-amber-100 text-amber-800',
                                                };
                                            @endphp
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $color }}">
                                                {{ $st }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-mono text-slate-400 text-[11px]">{{ isset($m['createdAt']) ? date('d M Y H:i', strtotime($m['createdAt'])) : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB 4: PETUNJUK TEKNIS API & LARAVEL FACADE SDK                -->
        <!-- =============================================================== -->
        <div id="tab-api-guide" class="tab-content hidden space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Petunjuk Teknis Pemanggilan API & SDK</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Contoh integrasi siap pakai di kode Controller, Service, atau Notifikasi Laravel</p>
                </div>

                <div class="space-y-6">
                    <!-- Example 1: Laravel Facade Send Text -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] font-bold">1</span>
                            Kirim Pesan Teks via Laravel Facade (WhatsApp::send)
                        </h3>
                        <div class="relative group">
                            <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-send-text">use WaIntegration\Laravel\Facades\WhatsApp;

// Kirim pesan teks biasa
$response = WhatsApp::send('08123456789', 'Halo! Akun Anda telah berhasil diaktivasi.');

if ($response->isSuccessful()) {
    $messageId = $response->messageId();
    // Berhasil dikirim ke queue gateway
} else {
    $error = $response->error();
}</code></pre>
                            <button type="button" onclick="copySnippet('code-send-text', 'Kode Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                        </div>
                    </div>

                    <!-- Example 2: Send Image & Document -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="w-5 h-5 rounded-full bg-sky-100 text-sky-800 flex items-center justify-center text-[10px] font-bold">2</span>
                            Kirim Media (Gambar &amp; Dokumen Faktur PDF)
                        </h3>
                        <div class="relative group">
                            <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-send-media">use WaIntegration\Laravel\Facades\WhatsApp;

// Kirim Gambar dengan Keterangan (Caption)
WhatsApp::sendImage(
    to: '08123456789',
    imageUrl: 'https://aplikasi-anda.com/storage/bukti-transfer.jpg',
    caption: 'Bukti transaksi pembayaran #INV-2026-001'
);

// Kirim Dokumen PDF
WhatsApp::sendDocument(
    to: '08123456789',
    documentUrl: 'https://aplikasi-anda.com/invoices/INV-2026-001.pdf',
    caption: 'Berikut adalah berkas tanda terima resmi Anda.'
);</code></pre>
                            <button type="button" onclick="copySnippet('code-send-media', 'Kode Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                        </div>
                    </div>

                    <!-- Example 3: Webhook Verification -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-800 flex items-center justify-center text-[10px] font-bold">3</span>
                            Inbound Webhook Status Delivery (HMAC-SHA256)
                        </h3>
                        <p class="text-xs text-slate-600">Endpoint otomatis tersedia di <code class="font-mono text-xs bg-slate-100 px-1 py-0.5 rounded">{{ $config['webhookUrl'] }}</code> untuk menerima status pesan dan pesan masuk.</p>
                        <div class="relative group">
                            <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-webhook">// Event ditangkap otomatis oleh paket. Anda dapat me-listen event Laravel bawaan:
// Event: WaIntegration\Laravel\Events\WhatsAppMessageReceived
// Event: WaIntegration\Laravel\Events\WhatsAppStatusUpdated

Event::listen(\WaIntegration\Laravel\Events\WhatsAppStatusUpdated::class, function ($event) {
    Log::info("Pesan {$event->messageId} status berubah menjadi: {$event->status}");
});</code></pre>
                            <button type="button" onclick="copySnippet('code-webhook', 'Kode Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- TAB 5: PANDUAN HAK AKSES, ROLE, & UNINSTALL (CONFIG/WAKU.PHP)   -->
        <!-- =============================================================== -->
        <div id="tab-lifecycle" class="tab-content hidden space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md p-6 sm:p-8 space-y-8">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Panduan Konfigurasi Hak Akses & Lifecycle Paket</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Sesuaikan otorisasi role, instalasi otomatis, update versi, dan uninstaller bersih</p>
                </div>

                <!-- Section 1: Role-Based Authorization in config/waku.php -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] font-bold">1</span>
                        Pengaturan Hak Akses Dashboard (Role &amp; Email di config/waku.php)
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Secara default, dashboard ini dilindungi middleware <code class="font-mono bg-slate-100 px-1 py-0.5 rounded font-bold">['web', 'auth']</code>. Anda dapat membatasi akses hanya untuk pengguna atau role tertentu dengan mengedit berkas <code class="font-mono bg-slate-100 px-1 py-0.5 rounded font-bold">config/waku.php</code>:
                    </p>
                    <div class="relative group">
                        <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-config-roles">// config/waku.php
'dashboard' => [
    'enabled' => env('WAKU_DASHBOARD_ENABLED', true),
    'prefix' => env('WAKU_DASHBOARD_PREFIX', 'waku/dashboard'),
    'middleware' => ['web', 'auth'],
    'auth_required' => true,

    // Batasi akses berdasarkan Role (mendukung Spatie Permission / User role)
    'allowed_roles' => [
        'SUPER-ADMIN',
        'ADMIN',
    ],

    // Atau batasi akses berdasarkan Email tertentu
    'allowed_emails' => [
        'admin@pekanbaru.go.id',
    ],
],</code></pre>
                        <button type="button" onclick="copySnippet('code-config-roles', 'Konfigurasi Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                    </div>
                </div>

                <!-- Section 2: Automated Install -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                        <span class="w-5 h-5 rounded-full bg-sky-100 text-sky-800 flex items-center justify-center text-[10px] font-bold">2</span>
                        Instalasi Satu Langkah via Artisan
                    </h3>
                    <div class="relative group">
                        <pre class="bg-slate-950 text-slate-100 font-mono text-xs p-4 rounded-2xl overflow-x-auto leading-relaxed"><code id="code-install-waku"># 1. Pasang paket melalui Composer
composer require xdiego1st/wa-integration

# 2. Jalankan inisialisasi otomatis satu langkah
php artisan waku:install</code></pre>
                        <button type="button" onclick="copySnippet('code-install-waku', 'Perintah Disalin!')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                    </div>
                </div>

                <!-- Section 3: Clean Uninstall -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 uppercase tracking-wider">
                        <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-800 flex items-center justify-center text-[10px] font-bold">3</span>
                        Pencopotan Bersih Tanpa Residu (Zero-Residue Clean Uninstall)
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Gunakan perintah otomatis uninstaller untuk membersihkan seluruh berkas konfigurasi <code class="font-mono text-xs">config/waku.php</code>, views, cache, dan variabel lingkungan <code class="font-mono text-xs">.env</code>:
                    </p>
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
                        <h3 class="text-sm font-bold text-slate-900">Pindai QR Code WhatsApp</h3>
                        <p class="text-[11px] text-slate-500">Tautkan sesi Baileys Multi-Device</p>
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
                    Perbarui QR Code
                </button>
                <button type="button" onclick="closeQrModal()" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- =============================================================== -->
    <!-- JAVASCRIPT LOGIC, POLLING, MODAL CONTROLS & SWEETALERT          -->
    <!-- =============================================================== -->
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let pollingInterval = null;
        let isModalOpen = false;

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
                const res = await fetch("{{ route('waku.qr') }}");
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
                badge.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> TERHUBUNG (${phone || 'Aktif'})`;
                
                container.innerHTML = `
                    <div class="p-6 text-center space-y-4">
                        <div class="w-20 h-20 rounded-3xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-inner shadow-emerald-500/10">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Perangkat WhatsApp Terhubung!</h3>
                            <p class="text-xs text-slate-500 mt-1">Nomor Aktif: <strong class="font-mono text-slate-900 text-sm font-bold">${phone}</strong></p>
                            <p class="text-[11px] text-emerald-600 font-medium mt-0.5">Sesi Baileys aktif dan siap mengirim serta menerima pesan.</p>
                        </div>
                        <div class="pt-2">
                            <button type="button" onclick="switchTab('tab-send')" class="px-4 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200 transition-colors cursor-pointer">
                                ➔ Buka Sandbox Kirim Pesan
                            </button>
                        </div>
                    </div>
                `;

                // If modal was open, celebrate and close!
                if (isModalOpen) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Terhubung!',
                        text: `Nomor WhatsApp ${phone} berhasil dipindai dan siap digunakan.`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    closeQrModal();
                }

            } else if (status === 'SCAN_QR' || qrCode) {
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300";
                badge.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span> Menunggu Scan QR`;

                container.innerHTML = `
                    <div class="p-4 text-center flex flex-col items-center space-y-3">
                        <div class="p-3 bg-white border border-slate-200 rounded-2xl shadow-sm">
                            <img src="${qrCode}" alt="WhatsApp QR Code" class="w-48 h-48 object-contain rounded-xl" />
                        </div>
                        <p class="text-xs font-semibold text-slate-700">Pindai QR Code di atas dengan ponsel Anda, atau buka modal layar penuh:</p>
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
            // Show loading inside modal
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
                const res = await fetch("{{ route('waku.connect') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
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
                await fetch("{{ route('waku.disconnect') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
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

        // --- Prompt Create Session ---
        async function promptCreateSession() {
            const { value: sessionName } = await Swal.fire({
                title: 'Buat Sesi WhatsApp Baru',
                input: 'text',
                inputLabel: 'Nama Sesi (Contoh: Customer Service / CS 2)',
                inputPlaceholder: 'Masukkan nama sesi...',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Buat Sesi',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nama sesi tidak boleh kosong!';
                    }
                }
            });

            if (sessionName) {
                try {
                    const res = await fetch("{{ route('waku.create-session') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                        body: JSON.stringify({ name: sessionName })
                    });
                    const json = await res.json();
                    if (json.success) {
                        Swal.fire('Berhasil', `Sesi "${sessionName}" berhasil dibuat.`, 'success');
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        Swal.fire('Gagal', json.error || 'Terjadi kesalahan', 'error');
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
                        text: `Pesan berhasil dimasukkan ke antrean gateway. ID: ${json.message_id || 'OK'}`,
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

        // --- Helper: Copy Snippet to Clipboard ---
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

        // Initialize polling on page load
        document.addEventListener('DOMContentLoaded', () => {
            startPolling();
        });
    </script>
</body>
</html>
