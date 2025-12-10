@extends('layouts.app')

@section('title', 'Aspirasi Saya - Sistem RT')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-chat-left-text text-primary"></i> Aspirasi Saya
        </h2>
        <a href="{{ route('aspirasi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Aspirasi Baru
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <!-- Filter Status -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="btn-group" role="group">
                <a href="?status=all" class="btn {{ request('status') == 'all' || !request('status') ? 'btn-secondary' : 'btn-outline-secondary' }}">Semua</a>
                <a href="?status=submitted" class="btn {{ request('status') == 'submitted' ? 'btn-warning' : 'btn-outline-warning' }}">Menunggu</a>
                <a href="?status=approved" class="btn {{ request('status') == 'approved' ? 'btn-success' : 'btn-outline-success' }}">Diterima</a>
                <a href="?status=rejected" class="btn {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">Ditolak</a>
                <a href="?status=completed" class="btn {{ request('status') == 'completed' ? 'btn-primary' : 'btn-outline-primary' }}">Selesai</a>
            </div>
        </div>
    </div>

    @if($aspirasis->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-chat-text display-1 text-muted"></i>
                <h4 class="mt-3">Belum Ada Aspirasi</h4>
                <p class="text-muted">Tidak ada data aspirasi pada kategori ini.</p>
                <a href="{{ route('aspirasi.create') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle"></i> Buat Aspirasi Baru
                </a>
            </div>
        </div>
    @else
        
        <!-- Daftar Aspirasi -->
        <div class="row">
            @foreach($aspirasis as $aspirasi)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <!-- Header dengan Status -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">{{ $aspirasi->judul }}</h5>
                                <span class="badge bg-secondary">{{ $aspirasi->kategori }}</span>
                                {!! $aspirasi->status_badge !!}
                                {!! $aspirasi->prioritas_badge !!}
                            </div>
                            <small class="text-muted">
                                {{ $aspirasi->created_at->format('d M Y') }}
                            </small>
                        </div>
                        
                        <!-- Preview Isi -->
                        <p class="card-text text-muted">
                            {{ Str::limit($aspirasi->isi, 150) }}
                        </p>
                        
                        <!-- Tanggapan Admin -->
                        @if($aspirasi->admin_response)
                        <div class="alert alert-info mt-3">
                            <strong><i class="bi bi-person-check"></i> Tanggapan Admin:</strong>
                            <p class="mb-0 mt-1">{{ $aspirasi->admin_response }}</p>
                            @if($aspirasi->admin)
                                <small class="text-muted">
                                    Oleh: {{ $aspirasi->admin->name }} • 
                                    {{ $aspirasi->updated_at->format('d M Y H:i') }}
                                </small>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Alasan Penolakan -->
                        @if($aspirasi->alasan_penolakan)
                        <div class="alert alert-danger mt-3">
                            <strong><i class="bi bi-x-circle"></i> Alasan Penolakan:</strong>
                            <p class="mb-0 mt-1">{{ $aspirasi->alasan_penolakan }}</p>
                        </div>
                        @endif
                        
                        <!-- Tombol Aksi -->
                        <div class="mt-3">
                            <a href="{{ route('aspirasi.show', $aspirasi->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            
                            @if($aspirasi->isSubmitted())
                                <span class="badge bg-warning ms-2">
                                    <i class="bi bi-clock"></i> Menunggu persetujuan admin
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection