@extends('layouts.app')

@section('title', 'Detail Aspirasi - Sistem RT')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="bi bi-chat-text text-primary"></i> Detail Aspirasi
                </h2>
                <div>
                    <a href="{{ route('aspirasi.my') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('aspirasi.index') }}" class="btn btn-outline-primary ms-2">
                            <i class="bi bi-list"></i> Kelola
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Card Detail -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $aspirasi->judul }}</h4>
                    <div>
                        {!! $aspirasi->status_badge !!}
                        {!! $aspirasi->prioritas_badge !!}
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Info Aspirasi -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Dibuat oleh:</strong></td>
                                    <td>{{ $aspirasi->user->name ?? 'User' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal:</strong></td>
                                    <td>{{ $aspirasi->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kategori:</strong></td>
                                    <td>{{ ucfirst($aspirasi->kategori) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                @if($aspirasi->admin)
                                <tr>
                                    <td><strong>Ditangani oleh:</strong></td>
                                    <td>{{ $aspirasi->admin->name }}</td>
                                </tr>
                                @endif
                                @if($aspirasi->approved_at)
                                <tr>
                                    <td><strong>Disetujui:</strong></td>
                                    <td>{{ $aspirasi->approved_at->format('d M Y H:i') }}</td>
                                </tr>
                                @endif
                                @if($aspirasi->rejected_at)
                                <tr>
                                    <td><strong>Ditolak:</strong></td>
                                    <td>{{ $aspirasi->rejected_at->format('d M Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    <!-- Isi Aspirasi -->
                    <div class="mb-4">
                        <h5><i class="bi bi-chat-left-text"></i> Isi Aspirasi</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $aspirasi->isi }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tanggapan Admin -->
                    @if($aspirasi->admin_response)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="text-success mb-0">
                                <i class="bi bi-person-check"></i> Tanggapan Admin
                            </h5>
                            @if(Auth::user()->role === 'admin' && ($aspirasi->isApproved() || $aspirasi->isCompleted()))
                            <button type="button" class="btn btn-sm btn-outline-warning"
                                    data-bs-toggle="modal" data-bs-target="#responseModal{{ $aspirasi->id }}">
                                <i class="bi bi-pencil"></i> Edit Tanggapan
                            </button>
                            @endif
                        </div>
                        <div class="card border-success">
                            <div class="card-body">
                                <p class="mb-0">
                                    {{ $aspirasi->admin_response }}
                                    @if($aspirasi->is_response_edited)
                                        <small class="text-muted fst-italic ms-1">(Diedit)</small>
                                    @endif
                                </p>
                                @if($aspirasi->admin)
                                    <hr>
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> {{ $aspirasi->admin->name }} • 
                                        <i class="bi bi-clock"></i> {{ $aspirasi->responded_at ? $aspirasi->responded_at->format('d M Y H:i') : $aspirasi->updated_at->format('d M Y H:i') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Alasan Penolakan -->
                    @if($aspirasi->alasan_penolakan)
                    <div class="mb-4">
                        <h5 class="text-danger">
                            <i class="bi bi-x-circle"></i> Alasan Penolakan
                        </h5>
                        <div class="card border-danger">
                            <div class="card-body">
                                <p class="mb-0">{{ $aspirasi->alasan_penolakan }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Form Tanggapan (Admin Only) -->
                    @if(Auth::user()->role === 'admin' && !$aspirasi->admin_response && $aspirasi->isApproved())
                    <div class="mt-4">
                        <h5><i class="bi bi-reply"></i> Beri Tanggapan</h5>
                        <form action="{{ route('aspirasi.respond', $aspirasi->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control" name="admin_response" rows="4" 
                                          placeholder="Tulis tanggapan Anda..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Kirim Tanggapan
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                
                <!-- Footer Card -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            ID: {{ $aspirasi->id }} • 
                            Status: {{ $aspirasi->status }}
                        </small>
                        @if(Auth::user()->role === 'admin')
                            <!-- Tombol Admin Actions -->
                            <div>
                                <!-- TC-06: Setujui -->
                                @if($aspirasi->isSubmitted())
                                <form action="{{ route('aspirasi.approve', $aspirasi->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" 
                                            onclick="return confirm('Setujui aspirasi ini?')">
                                        <i class="bi bi-check"></i> Setujui
                                    </button>
                                </form>
                                @endif
                                
                                <!-- TC-07: Tolak -->
                                @if($aspirasi->isSubmitted())
                                <button type="button" class="btn btn-danger btn-sm ms-2" 
                                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $aspirasi->id }}">
                                    <i class="bi bi-x"></i> Tolak
                                </button>
                                @endif
                                
                                <!-- TC-08: Hapus -->
                                <form action="{{ route('aspirasi.destroy', $aspirasi->id) }}" 
                                      method="POST" class="d-inline ms-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('Hapus aspirasi ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                                
                                <!-- Tombol Status -->
                                @if($aspirasi->isApproved())
                                <form action="{{ route('aspirasi.markInProgress', $aspirasi->id) }}" 
                                      method="POST" class="d-inline ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-sm">
                                        <i class="bi bi-arrow-right"></i> Mark In Progress
                                    </button>
                                </form>
                                @endif
                                
                                @if($aspirasi->status === 'in_progress')
                                <form action="{{ route('aspirasi.markCompleted', $aspirasi->id) }}" 
                                      method="POST" class="d-inline ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-check-all"></i> Mark Completed
                                    </button>
                                </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tolak Aspirasi -->
@if(Auth::user()->role === 'admin' && $aspirasi->isSubmitted())
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
                                  rows="4" placeholder="Berikan alasan penolakan..." required></textarea>
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

<!-- Modal Tanggapi / Edit (Untuk Admin) -->
@if(Auth::user()->role === 'admin' && (($aspirasi->isApproved() && !$aspirasi->admin_response) || $aspirasi->admin_response))
<div class="modal fade" id="responseModal{{ $aspirasi->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $aspirasi->admin_response ? 'Edit Tanggapan' : 'Tanggapi Aspirasi' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('aspirasi.respond', $aspirasi->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p><strong>{{ $aspirasi->judul }}</strong></p>
                    <div class="mb-3">
                        <label class="form-label">Tanggapan Anda</label>
                        <textarea class="form-control" name="admin_response" 
                                  rows="4" placeholder="Tulis tanggapan Anda..." required>{{ $aspirasi->admin_response }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">{{ $aspirasi->admin_response ? 'Update Tanggapan' : 'Kirim Tanggapan' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection