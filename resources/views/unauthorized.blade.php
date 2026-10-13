<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak — Waku Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        code, pre { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl border border-slate-200/80 shadow-2xl p-8 text-center space-y-6 relative overflow-hidden">
        <div class="absolute -top-16 -right-16 w-36 h-36 bg-rose-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-36 h-36 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="w-16 h-16 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mx-auto shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3.5a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>

        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                403 — Hak Akses Terbatas
            </span>
            <h1 class="text-xl font-bold text-slate-900">Akses Waku Dashboard Ditolak</h1>
            <p class="text-xs text-slate-600 leading-relaxed">
                Akun Anda terautentikasi sebagai <strong class="text-slate-800 font-bold">{{ $user->email ?? $user->name ?? 'Pengguna' }}</strong>, namun tidak memiliki izin peran (<code class="text-rose-600 font-mono text-[11px]">Role</code>) untuk membuka panel kontrol WhatsApp ini.
            </p>
        </div>

        @if(!empty($allowedRoles))
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-left space-y-1.5">
                <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Peran (Role) yang Diizinkan:</div>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($allowedRoles as $role)
                        <span class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700 font-mono text-[11px] font-semibold">{{ $role }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="pt-2 flex flex-col sm:flex-row gap-2.5">
            <a href="{{ url('/') }}" class="flex-1 py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Beranda
            </a>
            @if(Route::has('logout'))
                <form action="{{ route('logout') }}" method="POST" class="flex-1 inline">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-sm transition-colors flex items-center justify-center gap-1.5">
                        Ganti Akun
                    </button>
                </form>
            @endif
        </div>
    </div>
</body>
</html>
