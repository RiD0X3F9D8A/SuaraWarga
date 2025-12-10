@extends('layouts.app')

@section('title', 'Semua Aspirasi Warga - Sistem RT')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-collection text-primary"></i> Semua Aspirasi Warga
            </h2>
            <p class="text-muted mb-0">Aspirasi yang telah selesai atau disetujui dari seluruh warga.</p>
        </div>
        <a href="{{ route('dashboard.warga') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
    
    @if($aspirasis->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h4 class="mt-3">Belum Ada Aspirasi Publik</h4>
                <p class="text-muted">Belum ada aspirasi yang disetujui atau diselesaikan untuk ditampilkan.</p>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($aspirasis as $aspirasi)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-start border-4 border-{{ $aspirasi->status == 'completed' ? 'primary' : 'success' }}">
                    <div class="card-body">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-1 fw-bold">{{ $aspirasi->judul }}</h5>
                            <span class="badge bg-{{ $aspirasi->status == 'completed' ? 'primary' : 'success' }}">
                                {{ $aspirasi->status == 'completed' ? 'Selesai' : 'Diterima' }}
                            </span>
                        </div>
                        
                        <!-- Info Penulis -->
                        <div class="d-flex align-items-center mb-3 text-muted small">
                            <i class="bi bi-person-circle me-1"></i> 
                            <span class="me-3">{{ $aspirasi->user->name ?? 'Warga' }}</span>
                            <i class="bi bi-calendar3 me-1"></i>
                            <span>{{ $aspirasi->created_at->format('d M Y') }}</span>
                        </div>
                        
                        <!-- Isi -->
                        <p class="card-text text-secondary bg-light p-3 rounded">
                            {{ Str::limit($aspirasi->isi, 200) }}
                        </p>
                        
                        <!-- Tanggapan Admin -->
                        @if($aspirasi->admin_response)
                        <div class="alert alert-info mt-3 mb-0">
                            <strong><i class="bi bi-chat-quote-fill"></i> Tanggapan Admin:</strong>
                            <p class="mb-0 mt-1 small">{{ $aspirasi->admin_response }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
