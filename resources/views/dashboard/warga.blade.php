@extends('layouts.app')

@section('title', 'Dashboard Warga - Sistem RT')

@section('content')
<div class="row">
    <div class="col-12">
        <h3><i class="bi bi-speedometer2"></i> Dashboard Warga</h3>
        <p class="text-muted">Selamat datang, <strong>{{ Auth::user()->name }}</strong>!</p>
    </div>
</div>

<!-- Statistik -->
<div class="row mt-4">
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-4 border-primary shadow-sm hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                     <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary-subtle me-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-chat-dots text-primary fs-3"></i>
                     </div>
                     <div>
                        <h6 class="card-subtitle text-muted mb-1">Aspirasi Saya</h6>
                        <h2 class="card-title fw-bold mb-0 text-primary-custom">{{ $aspirasi_count ?? 0 }}</h2>
                     </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-4 border-success shadow-sm hover-shadow">
             <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                     <div class="d-flex align-items-center justify-content-center rounded-circle bg-success-subtle me-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-check2-square text-success fs-3"></i>
                     </div>
                     <div>
                        <h6 class="card-subtitle text-muted mb-1">Voting Aktif</h6>
                        <h2 class="card-title fw-bold mb-0 text-success">{{ $voting_active ?? 0 }}</h2>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-4 border-info shadow-sm hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                     <div class="d-flex align-items-center justify-content-center rounded-circle bg-info-subtle me-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-badge text-info fs-3"></i>
                     </div>
                     <div>
                        <h6 class="card-subtitle text-muted mb-1">Status Role</h6>
                        <h2 class="card-title fw-bold mb-0 text-info">{{ Auth::user()->role ?? 'Warga' }}</h2>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Menu Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex">
                    <!-- PERBAIKAN: route('aspirasi.create') -->
                    <a href="{{ route('aspirasi.create') }}" class="btn btn-primary me-2">
                        <i class="bi bi-plus-circle"></i> Kirim Aspirasi Baru
                    </a>
                    <!-- PERBAIKAN: route('aspirasi.my') -->
                    <a href="{{ route('aspirasi.my') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-chat-dots"></i> Lihat Aspirasi Saya
                    </a>
                    <a href="{{ route('aspirasi.public') }}" class="btn btn-outline-info me-2">
                        <i class="bi bi-collection"></i> Semua Aspirasi
                    </a>
                    <a href="{{ route('voting.index') }}" class="btn btn-outline-success">
                        <i class="bi bi-check2-square"></i> Ikuti Voting
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Aspirasi Terbaru -->
@if(isset($aspirasi_terbaru) && $aspirasi_terbaru->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Aspirasi Terbaru Anda</h5>
            </div>
            <div class="card-body">
                @foreach($aspirasi_terbaru as $aspirasi)
                <div class="border-bottom pb-2 mb-2">
                    <h6 class="mb-1">{{ $aspirasi->judul }}</h6>
                    <p class="mb-1 text-muted small">{{ Str::limit($aspirasi->isi, 100) }}</p>
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-{{ $aspirasi->status == 'terkirim' ? 'info' : ($aspirasi->status == 'ditanggapi' ? 'success' : 'secondary') }}">
                            {{ $aspirasi->status }}
                        </span>
                        <small class="text-muted">{{ $aspirasi->created_at->format('d M Y') }}</small>
                    </div>
                </div>
                @endforeach
                <div class="text-center mt-2">
                    <!-- PERBAIKAN: route('aspirasi.my') -->
                    <a href="{{ route('aspirasi.my') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Aspirasi Saya</a>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-4">
                <i class="bi bi-chat-dots text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">Belum ada aspirasi terbaru</p>
                <!-- PERBAIKAN: route('aspirasi.create') -->
                <a href="{{ route('aspirasi.create') }}" class="btn btn-primary">Kirim Aspirasi Pertama</a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection