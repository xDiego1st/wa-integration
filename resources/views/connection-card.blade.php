@php
    $res = \WaIntegration\Laravel\Facades\WhatsApp::getPairing();
    $data = $res->isSuccessful() ? $res->data() : [];
    $status = $data['status'] ?? 'DISCONNECTED';
    $phone = $data['phone'] ?? null;
    $qrCode = $data['qrCode'] ?? null;
@endphp

<div class="card border rounded-lg shadow-sm p-4 bg-white">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
        <div>
            <h5 class="mb-0 font-weight-bold">Status WhatsApp</h5>
            <small class="text-muted">Multi-Application Gateway Connection</small>
        </div>
        <div>
            @if($status === 'CONNECTED')
                <span class="badge bg-success">TERHUBUNG ({{ $phone }})</span>
            @elseif($status === 'SCAN_QR')
                <span class="badge bg-warning text-dark">PINDAI QR</span>
            @else
                <span class="badge bg-secondary">TERPUTUS</span>
            @endif
        </div>
    </div>

    @if($status === 'CONNECTED')
        <div class="alert alert-success">
            Akun WhatsApp berhasil terhubung pada nomor <strong>{{ $phone }}</strong>.
        </div>
    @elseif($status === 'SCAN_QR' && $qrCode)
        <div class="text-center py-3">
            <p class="text-muted mb-2">Pindai QR Code berikut menggunakan aplikasi WhatsApp:</p>
            <img src="{{ $qrCode }}" alt="QR Code" style="width: 240px; height: 240px;" class="img-thumbnail" />
        </div>
    @else
        <div class="text-center py-3">
            <p class="text-muted">Koneksi WhatsApp belum aktif.</p>
        </div>
    @endif
</div>
