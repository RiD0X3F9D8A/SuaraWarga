@extends('layouts.app')

@section('title', 'Dashboard Admin - Sistem RT')

@section('content')
<div class="row">
    <div class="col-12">
        <h3><i class="bi bi-speedometer2"></i> Dashboard Admin RT</h3>
        <p class="text-muted">Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Anda login sebagai Administrator.</p>
    </div>
</div>

<!-- Statistik Admin -->
<div class="row mt-4">
    <div class="col-md-3 mb-4">
        <div class="card h-100 border-start border-4 border-primary shadow-sm hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                     <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary-subtle me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-people text-primary fs-4"></i>
                     </div>
                     <div>
                        <h6 class="card-subtitle text-muted mb-1">Total Warga</h6>
                        <h2 class="card-title fw-bold mb-0 text-primary-custom">{{ $total_warga }}</h2>
                     </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card h-100 border-start border-4 border-warning shadow-sm hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                     <div class="d-flex align-items-center justify-content-center rounded-circle bg-warning-subtle me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-chat-dots text-warning fs-4"></i>
                     </div>
                     <div>
                        <h6 class="card-subtitle text-muted mb-1">Aspirasi Baru</h6>
                        <h2 class="card-title fw-bold mb-0 text-accent">{{ $aspirasi_baru }}</h2>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card h-100 border-start border-4 border-info shadow-sm hover-shadow">
            <div class="card-body">
                 <div class="d-flex align-items-center mb-2">
                     <div class="d-flex align-items-center justify-content-center rounded-circle bg-info-subtle me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-check2-square text-info fs-4"></i>
                     </div>
                     <div>
                        <h6 class="card-subtitle text-muted mb-1">Voting Aktif</h6>
                        <h2 class="card-title fw-bold mb-0 text-info">{{ $voting_aktif }}</h2>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card h-100 border-start border-4 border-success shadow-sm hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                     <div class="d-flex align-items-center justify-content-center rounded-circle bg-success-subtle me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-shield-check text-success fs-4"></i>
                     </div>
                     <div>
                        <h6 class="card-subtitle text-muted mb-1">Role Anda</h6>
                        <h2 class="card-title fw-bold mb-0 text-success">Admin</h2>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Admin -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Menu Admin</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex flex-wrap">
                    <!-- PERBAIKAN LINK: Pakai route() -->
                    <a href="{{ route('voting.createSession') }}" class="btn btn-success me-2 mb-2">
                        <i class="bi bi-plus-circle"></i> Buat Voting Baru
                    </a>
                    
                    <!-- PERBAIKAN LINK INI: Kelola Voting -->
                    <a href="{{ route('voting.manage') }}" class="btn btn-outline-success me-2 mb-2">
                        <i class="bi bi-gear"></i> Kelola Voting
                    </a>
                    
                    <!-- PERBAIKAN LINK: Kelola Aspirasi -->
                    <a href="{{ route('aspirasi.index') }}" class="btn btn-outline-primary me-2 mb-2">
                        <i class="bi bi-chat-dots"></i> Kelola Aspirasi
                    </a>
                    
                    <!-- PERBAIKAN LINK: Kelola User -->
                    <a href="{{ route('users.index') }}" class="btn btn-outline-info me-2 mb-2">
                        <i class="bi bi-person-gear"></i> Kelola User
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
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Aspirasi Terbaru</h5>
            </div>
            <div class="card-body">
                @foreach($aspirasi_terbaru as $aspirasi)
                <div class="border-bottom pb-2 mb-2">
                    <div class="d-flex justify-content-between align-items-start">
                        <h6 class="mb-1">{{ $aspirasi->judul }}</h6>
                        @if($aspirasi->status === 'submitted')
                            <span class="badge bg-warning">Menunggu</span>
                        @elseif($aspirasi->status === 'approved')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($aspirasi->status === 'rejected')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">{{ $aspirasi->status }}</span>
                        @endif
                    </div>
                    <p class="mb-1 text-muted small">{{ Str::limit($aspirasi->isi, 100) }}</p>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            <i class="bi bi-person"></i> {{ $aspirasi->user->name ?? 'Anonim' }}
                        </small>
                        <small class="text-muted">{{ $aspirasi->created_at->format('d M Y H:i') }}</small>
                    </div>
                    <div class="mt-1">
                        <!-- TOMBOL TANGGAPI DENGAN MODAL -->
                        @if(empty($aspirasi->admin_response) && $aspirasi->status === 'submitted')
                        <button type="button" class="btn btn-sm btn-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#responseModal{{ $aspirasi->id }}">
                            Tanggapi
                        </button>
                        @elseif($aspirasi->admin_response)
                        <span class="badge bg-success ms-1">
                            Disetujui
                            @if($aspirasi->is_response_edited)
                                <span class="fw-normal fst-italic ms-1" style="font-size: 0.7em">(Diedit)</span>
                            @endif
                        </span>
                        @endif
                        
                        <a href="{{ route('aspirasi.show', $aspirasi->id) }}" 
                           class="btn btn-sm btn-outline-secondary">Detail</a>
                        
                        <!-- Tombol Aksi Admin -->
                        @if($aspirasi->status === 'submitted')
                        <div class="btn-group btn-group-sm" role="group">
                            <form action="{{ route('aspirasi.approve', $aspirasi->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" 
                                        onclick="return confirm('Setujui aspirasi ini?')">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                            <button type="button" class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#rejectModal{{ $aspirasi->id }}">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Modal Tanggapi Aspirasi -->
                @if(empty($aspirasi->admin_response) && $aspirasi->status === 'submitted')
                <div class="modal fade" id="responseModal{{ $aspirasi->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tanggapi Aspirasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('aspirasi.respond', $aspirasi->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p><strong>{{ $aspirasi->judul }}</strong></p>
                                    <p class="text-muted">{{ $aspirasi->isi }}</p>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Tanggapan Anda:</label>
                                        <textarea name="admin_response" class="form-control" rows="4" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Kirim Tanggapan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Modal Tolak Aspirasi -->
                @if($aspirasi->status === 'submitted')
                <div class="modal fade" id="rejectModal{{ $aspirasi->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tolak Aspirasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('aspirasi.reject', $aspirasi->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p>Anda akan menolak aspirasi: <strong>"{{ $aspirasi->judul }}"</strong></p>
                                    <div class="mb-3">
                                        <label for="alasan" class="form-label">Alasan Penolakan</label>
                                        <textarea class="form-control" id="alasan" name="alasan_penolakan" 
                                                  rows="3" placeholder="Berikan alasan penolakan..." required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Tolak Aspirasi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
                
                <div class="text-center mt-2">
                    <!-- PERBAIKAN LINK: Ke Kelola Aspirasi -->
                    <a href="{{ route('aspirasi.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua Aspirasi
                    </a>
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
                <i class="bi bi-chat-text display-1 text-muted"></i>
                <h5 class="mt-3">Belum Ada Aspirasi</h5>
                <p class="text-muted">Tidak ada aspirasi baru yang menunggu tanggapan.</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection