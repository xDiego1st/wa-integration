<div class="card border rounded-lg shadow-sm p-4 bg-white dark:bg-gray-800" @if($status === 'SCAN_QR' || $status === 'CONNECTING') wire:poll.3000ms="refreshStatus" @endif>
    <div class="flex items-center justify-between border-b pb-3 mb-4">
        <div>
            <h5 class="text-lg font-bold text-gray-900 dark:text-white">Koneksi WhatsApp</h5>
            <p class="text-sm text-gray-500">Integrasi WhatsApp Multi-Application</p>
        </div>
        <div>
            @if($status === 'CONNECTED')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-300">
                    <span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full animate-pulse"></span>
                    TERHUBUNG
                </span>
            @elseif($status === 'SCAN_QR')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-300">
                    PINDAI QR
                </span>
            @elseif($status === 'CONNECTING')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-300">
                    MENGHUBUNGKAN...
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-300">
                    TERPUTUS
                </span>
            @endif
        </div>
    </div>

    @if($errorMessage)
        <div class="mb-4 p-3 bg-red-50 text-red-700 border border-red-200 rounded text-sm">
            {{ $errorMessage }}
        </div>
    @endif

    <div class="py-2">
        @if($status === 'CONNECTED')
            <div class="flex items-center space-x-3 p-3 bg-green-50 border border-green-200 rounded-md">
                <div class="text-green-600 font-medium">
                    Nomor Terhubung: <span class="font-bold">{{ $phone ?? 'WhatsApp Connected' }}</span>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button wire:click="disconnect" wire:loading.attr="disabled" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded font-medium text-sm transition">
                    Putuskan Koneksi (Disconnect)
                </button>
            </div>
        @elseif($status === 'SCAN_QR' && $qrCode)
            <div class="text-center py-4">
                <p class="text-sm text-gray-600 mb-3">Buka WhatsApp di ponsel Anda > Perangkat Tertaut > Tautkan Perangkat, lalu arahkan kamera ke kode di bawah ini:</p>
                <div class="inline-block p-3 bg-white border rounded shadow-md">
                    <img src="{{ $qrCode }}" alt="WhatsApp QR Code" class="w-64 h-64 mx-auto" />
                </div>
                <div class="mt-4">
                    <button wire:click="refreshStatus" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded font-medium text-sm">
                        Perbarui Status
                    </button>
                </div>
            </div>
        @else
            <div class="text-center py-6">
                <p class="text-sm text-gray-500 mb-4">Akun WhatsApp untuk aplikasi ini saat ini belum terhubung.</p>
                <button wire:click="connect" wire:loading.attr="disabled" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded font-medium text-sm transition shadow">
                    Hubungkan WhatsApp Sekarang (Connect)
                </button>
            </div>
        @endif
    </div>
</div>
