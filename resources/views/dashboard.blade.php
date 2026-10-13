<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WhatsApp Management & Panduan Teknis — {{ config('app.name', 'Laravel') }}</title>
    <!-- Tailwind CSS CDN for zero-dependency standalone dashboard -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        wa: {
                            50: '#eefcf5',
                            100: '#d7f7e6',
                            500: '#128c7e',
                            600: '#075e54',
                            700: '#25d366',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen">

    <!-- Top Navigation -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.23 8.23 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.44 0-2.85-.38-4.09-1.11l-.29-.17-3.04.8 1.07-2.97-.19-.3a8.21 8.21 0 0 1-1.25-4.49c0-4.54 3.7-8.24 8.24-8.24m4.52 11.64c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.24-.75-.67-1.25-1.49-1.4-1.74-.14-.25-.02-.39.11-.51.11-.11.25-.29.38-.44.12-.14.17-.25.25-.42.08-.17.04-.32-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.78 2.71 4.3 3.8 2.53 1.09 2.53.73 2.98.69.46-.04 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.11-.23-.17-.48-.29z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-lg font-bold text-slate-900 tracking-tight">WhatsApp Gateway Manager</h1>
                            <span class="px-2 py-0.5 text-[11px] font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Laravel Package</span>
                        </div>
                        <p class="text-xs text-slate-500">Koneksi multi-device, uji kirim pesan & dokumentasi teknis API</p>
                    </div>
                </div>

                <!-- Environment Credentials Badge -->
                <div class="hidden sm:flex items-center gap-2.5">
                    @if($config['hasApiKey'])
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            App: <code class="font-mono font-bold text-slate-900">{{ $config['appId'] ?? 'Auto' }}</code>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            Kredensial Belum Terkonfigurasi
                        </span>
                    @endif
                    <a href="{{ url('/') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800 transition-colors">
                        &larr; Kembali ke Aplikasi
                    </a>
                </div>
            </div>

            <!-- Tab Navigation Header -->
            <div class="flex space-x-1 border-t border-slate-100 pt-1 -mb-px overflow-x-auto" id="dashboard-tabs">
                <button type="button" onclick="switchTab('tab-connection')" id="btn-tab-connection" class="tab-btn px-4 py-3 text-xs font-bold border-b-2 border-emerald-600 text-emerald-700 flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Koneksi & Scan QR
                </button>
                <button type="button" onclick="switchTab('tab-sandbox')" id="btn-tab-sandbox" class="tab-btn px-4 py-3 text-xs font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Pesan Tes
                </button>
                <button type="button" onclick="switchTab('tab-logs')" id="btn-tab-logs" class="tab-btn px-4 py-3 text-xs font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Riwayat Pesan
                </button>
                <button type="button" onclick="switchTab('tab-docs')" id="btn-tab-docs" class="tab-btn px-4 py-3 text-xs font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    Petunjuk Teknis API & SDK
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(!$config['hasApiKey'])
            <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="text-sm text-amber-900">
                    <strong class="font-bold">Konfigurasi Dibutuhkan:</strong> Variabel <code class="font-mono text-xs bg-amber-100 px-1 py-0.5 rounded">WHATSAPP_APP_ID</code> dan <code class="font-mono text-xs bg-amber-100 px-1 py-0.5 rounded">WHATSAPP_API_KEY</code> belum ditemukan di file <code class="font-mono text-xs">.env</code> aplikasi Anda. Silakan lihat tab <strong>Petunjuk Teknis API & SDK</strong> untuk petunjuk konfigurasi.
                </div>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 1: KONEKSI WHATSAPP & LIVE SCAN QR    -->
        <!-- ========================================== -->
        <div id="tab-connection" class="tab-content space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Session Status & QR Code -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Status Koneksi Perangkat</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Pantau sesi Baileys WhatsApp Multi-Device secara real-time</p>
                        </div>
                        <div id="status-badge-container">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200" id="badge-status">
                                Memuat status...
                            </span>
                        </div>
                    </div>

                    <!-- Live Connection Area -->
                    <div id="connection-display" class="py-6 flex flex-col items-center justify-center text-center">
                        <div class="animate-pulse flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full bg-slate-200 mb-3"></div>
                            <div class="h-4 bg-slate-200 rounded w-48 mb-2"></div>
                            <div class="h-3 bg-slate-200 rounded w-32"></div>
                        </div>
                    </div>

                    <!-- Action Controls -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="refreshQrStatus()" class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Segarkan Status
                            </button>
                            <button type="button" onclick="connectSession()" class="px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold flex items-center gap-1.5 shadow-sm transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                Hubungkan / Request QR
                            </button>
                        </div>
                        <button type="button" onclick="disconnectSession()" class="px-3 py-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold flex items-center gap-1.5 border border-rose-200 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            Putuskan Sesi
                        </button>
                    </div>
                </div>

                <!-- Right: Session List & Specs -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Daftar Sesi Aplikasi</h3>

                    @if(empty($sessions))
                        <div class="py-6 text-center text-xs text-slate-500">
                            Belum ada sesi aktif. Klik "Hubungkan / Request QR" untuk memulai sesi baru.
                        </div>
                    @else
                        <div class="divide-y divide-slate-100">
                            @foreach($sessions as $s)
                                <div class="py-3 flex items-center justify-between text-xs">
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $s['name'] ?? 'Default' }}</div>
                                        <div class="font-mono text-slate-500 text-[11px]">{{ $s['phone'] ?? 'Belum Terhubung' }}</div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ ($s['status'] ?? '') === 'CONNECTED' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $s['status'] ?? 'DISCONNECTED' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-2 text-xs">
                        <div class="font-bold text-slate-700">Petunjuk Pindai QR:</div>
                        <ol class="list-decimal list-inside space-y-1 text-slate-600 text-[11px] leading-relaxed">
                            <li>Buka WhatsApp di ponsel Anda</li>
                            <li>Ketuk <strong>Menu (⋮)</strong> atau <strong>Pengaturan</strong></li>
                            <li>Pilih <strong>Perangkat Tertaut</strong> &rarr; <strong>Tautkan Perangkat</strong></li>
                            <li>Arahkan kamera ke QR Code yang muncul di layar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: KIRIM PESAN TES (SANDBOX)           -->
        <!-- ========================================== -->
        <div id="tab-sandbox" class="tab-content hidden space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left: Send Form -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                    <div class="border-b border-slate-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900">Uji Coba Pengiriman Pesan Langsung</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Uji coba kirim pesan teks, dokumen, atau gambar melalui nomor WhatsApp yang terhubung.</p>
                    </div>

                    <form id="form-test-send" onsubmit="handleTestSend(event)" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp Tujuan *</label>
                            <input type="text" id="send-to" required placeholder="Contoh: 08123456789 atau 628123456789" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                            <p class="text-[11px] text-slate-400 mt-1">Dapat diawali dengan 08 atau 628 (format otomatis dinormalisasi).</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Pesan</label>
                            <select id="send-type" onchange="handleTypeChange()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="TEXT">Pesan Teks Biasa (Standard Text)</option>
                                <option value="IMAGE">Gambar (Image with Caption)</option>
                                <option value="DOCUMENT">Dokumen / PDF (Document)</option>
                            </select>
                        </div>

                        <div id="media-url-container" class="hidden">
                            <label class="block text-xs font-bold text-slate-700 mb-1">URL Media (Publik) *</label>
                            <input type="url" id="send-media-url" placeholder="https://example.com/invoice.pdf" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                            <p class="text-[11px] text-slate-400 mt-1">Harus berupa tautan publik yang dapat diakses langsung oleh gateway.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Konten Pesan / Keterangan *</label>
                            <textarea id="send-content" required rows="4" placeholder="Ketik pesan Anda di sini..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                        </div>

                        <button type="submit" id="btn-submit-send" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Pesan Sekarang
                        </button>
                    </form>
                </div>

                <!-- Right: Result Preview -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4 flex flex-col">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Respons & Diagnostik Pengiriman</h3>
                    <div id="send-result-box" class="flex-1 p-4 rounded-xl bg-slate-900 text-emerald-400 font-mono text-xs overflow-auto min-h-[260px] flex items-center justify-center text-slate-500">
                        Belum ada pesan yang dikirim. Kirim pesan dari form di sebelah kiri untuk melihat respons gateway.
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: RIWAYAT PESAN (LOGS)                -->
        <!-- ========================================== -->
        <div id="tab-logs" class="tab-content hidden space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Riwayat Pesan Terkini</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Audit log pesan yang dikirimkan atau diproses melalui gateway</p>
                    </div>
                    <button type="button" onclick="location.reload()" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold hover:bg-slate-50 transition-colors">
                        Muat Ulang
                    </button>
                </div>

                @if(empty($messages))
                    <div class="py-12 flex flex-col items-center justify-center text-center text-slate-400 space-y-2">
                        <svg class="w-10 h-10 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <h6 class="font-bold text-slate-600 text-sm">Belum Ada Riwayat Pesan</h6>
                        <p class="text-xs">Gunakan tab "Kirim Pesan Tes" untuk mencoba mengirim pesan pertama Anda.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                                <tr>
                                    <th class="py-3 px-4">Waktu</th>
                                    <th class="py-3 px-4">Penerima</th>
                                    <th class="py-3 px-4">Tipe</th>
                                    <th class="py-3 px-4">Isi Pesan</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($messages as $m)
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="py-3 px-4 font-mono text-[11px] text-slate-500 whitespace-nowrap">
                                            {{ isset($m['createdAt']) ? \Carbon\Carbon::parse($m['createdAt'])->format('d M Y, H:i') : '-' }}
                                        </td>
                                        <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                            {{ $m['to'] ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold bg-slate-100 text-slate-700">
                                                {{ $m['type'] ?? 'TEXT' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-700 max-w-xs truncate">
                                            {{ $m['content'] ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @php
                                                $st = $m['status'] ?? 'PENDING';
                                                $color = match($st) {
                                                    'SENT', 'DELIVERED', 'READ' => 'bg-emerald-100 text-emerald-800',
                                                    'FAILED' => 'bg-rose-100 text-rose-800',
                                                    default => 'bg-amber-100 text-amber-800',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $color }}">
                                                {{ $st }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 4: PETUNJUK TEKNIS API & LARAVEL SDK   -->
        <!-- ========================================== -->
        <div id="tab-docs" class="tab-content hidden space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-8">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Petunjuk Teknis Integrasi API & Laravel SDK</h2>
                    <p class="text-xs text-slate-500 mt-1">Panduan lengkap mengintegrasikan pengiriman pesan WhatsApp ke dalam logika aplikasi Laravel Anda.</p>
                </div>

                <!-- 1. Environment Config -->
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">1</span>
                        Konfigurasi Environment (<code class="text-xs font-mono bg-slate-100 px-1 py-0.5 rounded">.env</code>)
                    </h3>
                    <p class="text-xs text-slate-600">Pastikan nilai variabel berikut telah ditambahkan ke dalam file <code class="font-mono">.env</code> Laravel Anda:</p>
                    <div class="relative group">
                        <pre class="bg-slate-900 text-slate-100 font-mono text-xs p-4 rounded-xl overflow-x-auto"><code id="code-env">WHATSAPP_BASE_URL={{ $config['baseUrl'] }}
WHATSAPP_APP_ID={{ $config['appId'] ?? 'wai_app_your_application_id' }}
WHATSAPP_API_KEY={{ $config['hasApiKey'] ? 'wai_live_your_secret_api_key' : 'wai_live_paste_api_key_here' }}
WHATSAPP_WEBHOOK_SECRET=wai_sec_your_webhook_secret</code></pre>
                        <button type="button" onclick="copySnippet('code-env')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                    </div>
                </div>

                <!-- 2. Send Text Message -->
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">2</span>
                        Mengirim Pesan Teks via Facade
                    </h3>
                    <p class="text-xs text-slate-600">Gunakan Facade <code class="font-mono text-xs font-bold text-emerald-700">WhatsApp::send()</code> di controller, service, atau job Anda:</p>
                    <div class="relative group">
                        <pre class="bg-slate-900 text-slate-100 font-mono text-xs p-4 rounded-xl overflow-x-auto"><code id="code-send-text">use WaIntegration\Laravel\Facades\WhatsApp;

// Kirim pesan teks langsung
$response = WhatsApp::send('08123456789', 'Halo! Pesanan #1042 sedang diproses.');

if ($response->isSuccessful()) {
    $messageId = $response->messageId();
    // Berhasil terkirim ke antrean gateway
} else {
    logger()->error('Gagal kirim WhatsApp: ' . $response->error());
}</code></pre>
                        <button type="button" onclick="copySnippet('code-send-text')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                    </div>
                </div>

                <!-- 3. Send Media & Document -->
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">3</span>
                        Mengirim Media & PDF Invoice
                    </h3>
                    <p class="text-xs text-slate-600">Gunakan method khusus untuk dokumen dan media gambar:</p>
                    <div class="relative group">
                        <pre class="bg-slate-900 text-slate-100 font-mono text-xs p-4 rounded-xl overflow-x-auto"><code id="code-send-media">use WaIntegration\Laravel\Facades\WhatsApp;

// Kirim PDF invoice
WhatsApp::sendDocument(
    to: '08123456789',
    documentUrl: 'https://domain-anda.com/invoices/INV-2026.pdf',
    caption: 'Berikut adalah dokumen faktur tagihan Anda.'
);

// Kirim Gambar / Foto bukti transaksi
WhatsApp::sendImage(
    to: '08123456789',
    imageUrl: 'https://domain-anda.com/storage/resi.jpg',
    caption: 'Paket pesanan Anda telah dikirimkan via kurir.'
);</code></pre>
                        <button type="button" onclick="copySnippet('code-send-media')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                    </div>
                </div>

                <!-- 4. Webhook Event Handling -->
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">4</span>
                        Menerima Webhook Masuk (Pesan Masuk & Status Delivery)
                    </h3>
                    <p class="text-xs text-slate-600">Route webhook otomatis terdaftar di <code class="font-mono text-xs bg-slate-100 px-1 py-0.5 rounded">{{ $config['webhookUrl'] }}</code> lengkap dengan validasi tanda tangan HMAC-SHA256. Anda cukup mendengarkan event Laravel:</p>
                    <div class="relative group">
                        <pre class="bg-slate-900 text-slate-100 font-mono text-xs p-4 rounded-xl overflow-x-auto"><code id="code-webhook">// Di app/Providers/EventServiceProvider.php atau boot provider
use Illuminate\Support\Facades\Event;

Event::listen('whatsapp.message.received', function ($data, $appId) {
    $senderPhone = $data['from'];     // e.g. 628123456789
    $messageText = $data['content'];  // Isi pesan chat pengguna

    // Respon otomatis atau simpan ke tiket chat support
});

Event::listen('whatsapp.message.delivered', function ($data) {
    // Status pesan diperbarui menjadi DELIVERED / READ
});</code></pre>
                        <button type="button" onclick="copySnippet('code-webhook')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                    </div>
                </div>

                <!-- 5. cURL REST API direct -->
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">5</span>
                        Contoh cURL REST API Langsung
                    </h3>
                    <p class="text-xs text-slate-600">Selain menggunakan Laravel Facade, gateway juga dapat dipanggil dari bahasa apa pun menggunakan REST API:</p>
                    <div class="relative group">
                        <pre class="bg-slate-900 text-slate-100 font-mono text-xs p-4 rounded-xl overflow-x-auto"><code id="code-curl">curl -X POST {{ $config['baseUrl'] }}/app/whatsapp/send \
  -H "Content-Type: application/json" \
  -H "X-App-Id: {{ $config['appId'] ?? 'YOUR_APP_ID' }}" \
  -H "X-App-Key: wai_live_PASTE_YOUR_API_KEY" \
  -d '{
    "to": "08123456789",
    "content": "Pesan tes via REST API cURL",
    "type": "TEXT"
  }'</code></pre>
                        <button type="button" onclick="copySnippet('code-curl')" class="absolute top-3 right-3 px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-mono transition-colors">Salin</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500 mt-auto">
        &copy; {{ date('Y') }} PlatformCore WhatsApp Gateway Package for Laravel. All rights reserved.
    </footer>

    <!-- JavaScript Interactions & Live Polling -->
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let pollingInterval = null;

        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-emerald-600', 'text-emerald-700');
                btn.classList.add('border-transparent', 'text-slate-500');
            });

            document.getElementById(tabId).classList.remove('hidden');
            const activeBtn = document.getElementById('btn-' + tabId);
            if (activeBtn) {
                activeBtn.classList.remove('border-transparent', 'text-slate-500');
                activeBtn.classList.add('border-emerald-600', 'text-emerald-700');
            }

            if (tabId === 'tab-connection') {
                startPolling();
            } else {
                stopPolling();
            }
        }

        async function refreshQrStatus() {
            try {
                const res = await fetch("{{ route('whatsapp.qr') }}");
                const json = await res.json();
                renderConnectionStatus(json);
            } catch (err) {
                console.error("Gagal memuat status QR", err);
            }
        }

        function renderConnectionStatus(res) {
            const container = document.getElementById('connection-display');
            const badge = document.getElementById('badge-status');

            if (!res.success && res.error) {
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200";
                badge.innerText = "Error / Terputus";
                container.innerHTML = `
                    <div class="p-6 text-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm">Gagal Mengambil Status</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">${res.error}</p>
                    </div>
                `;
                return;
            }

            const data = res.data || {};
            const status = data.status || 'DISCONNECTED';
            const phone = data.phone || '';
            const qrCode = data.qrCode || '';

            if (status === 'CONNECTED') {
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300";
                badge.innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> TERHUBUNG (${phone || 'Aktif'})`;
                container.innerHTML = `
                    <div class="p-6 text-center space-y-3">
                        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Perangkat WhatsApp Terhubung!</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">Nomor: <strong class="font-mono text-slate-900">${phone}</strong>. Sesi Baileys aktif dan siap mengirim serta menerima pesan.</p>
                    </div>
                `;
            } else if (status === 'SCAN_QR' || qrCode) {
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300";
                badge.innerHTML = `<span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span> Menunggu Scan QR`;
                container.innerHTML = `
                    <div class="p-4 text-center flex flex-col items-center space-y-3">
                        <p class="text-xs font-semibold text-slate-700">Pindai QR Code di bawah dengan aplikasi WhatsApp ponsel Anda:</p>
                        <div class="p-3 bg-white border border-slate-200 rounded-2xl shadow-sm">
                            <img src="${qrCode}" alt="WhatsApp QR Code" class="w-56 h-56 object-contain" />
                        </div>
                        <p class="text-[11px] text-slate-400">QR Code otomatis diperbarui setiap beberapa detik.</p>
                    </div>
                `;
            } else {
                badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200";
                badge.innerText = "Belum Terhubung";
                container.innerHTML = `
                    <div class="p-6 text-center space-y-3">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm">Sesi WhatsApp Terputus</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">Klik tombol <strong>Hubungkan / Request QR</strong> di bawah untuk memulai sesi koneksi.</p>
                    </div>
                `;
            }
        }

        async function connectSession() {
            try {
                const res = await fetch("{{ route('whatsapp.connect') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                await refreshQrStatus();
            } catch (err) {
                alert("Gagal menginisiasi koneksi: " + err.message);
            }
        }

        async function disconnectSession() {
            if (!confirm("Apakah Anda yakin ingin memutuskan sesi WhatsApp ini?")) return;
            try {
                await fetch("{{ route('whatsapp.disconnect') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                await refreshQrStatus();
            } catch (err) {
                alert("Gagal memutuskan sesi: " + err.message);
            }
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

        async function handleTestSend(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-send');
            const resultBox = document.getElementById('send-result-box');

            btn.disabled = true;
            btn.innerHTML = `<span class="inline-block animate-spin mr-1">⏳</span> Mengirim pesan...`;

            const payload = {
                to: document.getElementById('send-to').value,
                type: document.getElementById('send-type').value,
                content: document.getElementById('send-content').value,
                media_url: document.getElementById('send-media-url').value || null,
            };

            try {
                const res = await fetch("{{ route('whatsapp.send') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify(payload)
                });
                const json = await res.json();
                resultBox.className = "flex-1 p-4 rounded-xl bg-slate-900 font-mono text-xs overflow-auto text-emerald-400";
                resultBox.innerHTML = `<pre>${JSON.stringify(json, null, 2)}</pre>`;
            } catch (err) {
                resultBox.className = "flex-1 p-4 rounded-xl bg-slate-900 font-mono text-xs overflow-auto text-rose-400";
                resultBox.innerHTML = `<pre>Error: ${err.message}</pre>`;
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg> Kirim Pesan Sekarang`;
            }
        }

        function copySnippet(id) {
            const text = document.getElementById(id).innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert("Cuplikan kode berhasil disalin ke clipboard!");
            });
        }

        function startPolling() {
            refreshQrStatus();
            if (!pollingInterval) {
                pollingInterval = setInterval(refreshQrStatus, 4000);
            }
        }

        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        // Start polling on initial load
        document.addEventListener('DOMContentLoaded', () => {
            startPolling();
        });
    </script>
</body>
</html>
