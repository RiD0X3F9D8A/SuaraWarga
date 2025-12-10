@extends('layouts.app')

@section('title', 'Kelola Aspirasi - Admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-chat-text text-primary"></i> Kelola Aspirasi</h2>
        <a href="{{ url('/dashboard/admin') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-2 mb-3">
             <div class="card h-100 bg-primary text-white shadow-sm border-0">
                <div class="card-body text-center p-3">
                    <h3 class="fw-bold mb-0">{{ $stats['total'] ?? 0 }}</h3>
                    <small class="opacity-75">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
             <div class="card h-100 bg-warning text-white shadow-sm border-0">
                <div class="card-body text-center p-3">
                    <h3 class="fw-bold mb-0">{{ $stats['submitted'] ?? 0 }}</h3>
                    <small class="opacity-75">Menunggu</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card h-100 bg-success text-white shadow-sm border-0">
                <div class="card-body text-center p-3">
                    <h3 class="fw-bold mb-0">{{ $stats['approved'] ?? 0 }}</h3>
                    <small class="opacity-75">Disetujui</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
             <div class="card h-100 bg-danger text-white shadow-sm border-0">
                <div class="card-body text-center p-3">
                    <h3 class="fw-bold mb-0">{{ $stats['rejected'] ?? 0 }}</h3>
                     <small class="opacity-75">Ditolak</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card h-100 bg-info text-white shadow-sm border-0">
                <div class="card-body text-center p-3">
                    <h3 class="fw-bold mb-0">{{ $stats['completed'] ?? 0 }}</h3>
                    <small class="opacity-75">Selesai</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabel Aspirasi -->
    <div class="card">
        <div class="card-body">
            @if($aspirasis->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-chat-text display-1 text-muted"></i>
                    <h5 class="mt-3">Belum Ada Aspirasi</h5>
                    <p class="text-muted">Tidak ada aspirasi yang diajukan warga.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Pembuat</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aspirasis as $aspirasi)
                            <tr>
                                <td class="fw-bold">#{{ $aspirasi->id }}</td>
                                <td>
                                    <strong>{{ $aspirasi->judul }}</strong>
                                    <div class="text-muted small">
                                        {{ Str::limit($aspirasi->isi, 50) }}
                                    </div>
                                </td>
                                <td>{{ $aspirasi->user->name ?? '-' }}</td>
                                <td>
                                    {!! $aspirasi->status_badge !!}
                                    @if($aspirasi->admin_response)
                                        <br><small class="text-success">
                                            Sudah ditanggapi
                                            @if($aspirasi->is_response_edited)
                                                <span class="text-muted fst-italic ms-1" style="font-size: 0.85em">(Diedit)</span>
                                            @endif
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $aspirasi->created_at->format('d/m/Y') }}<br>
                                        {{ $aspirasi->created_at->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- Detail -->
                                        <a href="{{ route('aspirasi.show', $aspirasi->id) }}" 
                                           class="btn btn-outline-primary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <!-- Setujui -->
                                        @if($aspirasi->isSubmitted())
                                        <form action="{{ route('aspirasi.approve', $aspirasi->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" 
                                                    title="Setujui" onclick="return confirm('Setujui?')">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <!-- Tolak -->
                                        @if($aspirasi->isSubmitted())
                                        <button type="button" class="btn btn-outline-danger" 
                                                title="Tolak"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $aspirasi->id }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                        @endif
                                        
                                        <!-- Tanggapi -->
                                        @if($aspirasi->isApproved() && !$aspirasi->admin_response)
                                        <button type="button" class="btn btn-outline-info" 
                                                title="Tanggapi"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#responseModal{{ $aspirasi->id }}">
                                            <i class="bi bi-reply"></i>
                                        </button>
                                        @endif
                                        
                                        <!-- Hapus -->
                                        <form action="{{ route('aspirasi.destroy', $aspirasi->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    title="Hapus" onclick="return confirm('Hapus aspirasi ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Modal Tolak -->
                            @if($aspirasi->isSubmitted())
                            <div class="modal fade" id="rejectModal{{ $aspirasi->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tolak Aspirasi #{{ $aspirasi->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('aspirasi.reject', $aspirasi->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p><strong>{{ $aspirasi->judul }}</strong></p>
                                                <div class="mb-3">
                                                    <label class="form-label">Alasan Penolakan</label>
                                                    <textarea class="form-control" name="alasan_penolakan" 
                                                              rows="3" placeholder="Wajib diisi" required></textarea>
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
                            
                            <!-- Modal Tanggapi -->
                            @if($aspirasi->isApproved() && !$aspirasi->admin_response)
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
                                                <div class="mb-3">
                                                    <label class="form-label">Tanggapan Anda</label>
                                                    <textarea class="form-control" name="admin_response" 
                                                              rows="4" placeholder="Tulis tanggapan..." required></textarea>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

<style>
.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>