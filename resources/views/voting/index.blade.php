@extends('layouts.app')

@section('title', 'Daftar Voting - Sistem RT')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="bi bi-check2-square text-success"></i> Daftar Voting Aktif</h3>
            @if(Auth::check() && Auth::user()->role === 'admin')
                <a href="/voting/create-session" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Buat Voting Baru
                </a>
            @endif
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if($votings->count() > 0)
            <div class="row">
                @foreach($votings as $voting)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title fw-bold text-primary-custom mb-0">{{ $voting->judul }}</h5>
                                <span class="badge {{ $voting->isActive() ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} px-3 py-2 rounded-pill">
                                    {{ $voting->isActive() ? 'Aktif' : 'Selesai' }}
                                </span>
                            </div>
                            
                            <p class="card-text text-muted mb-4">{{ Str::limit($voting->deskripsi, 120) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i> 
                                    Berakhir: {{ $voting->selesai->format('d M Y') }}
                                </small>
                                
                                <div>
                                    @php
                                        $userHasVoted = Auth::check() ? $voting->hasVoted(Auth::id()) : false;
                                    @endphp
                                    
                                    @if($voting->isActive() && !$userHasVoted && Auth::check())
                                        <a href="/voting/{{ $voting->id }}" class="btn btn-primary btn-sm px-3">
                                            <i class="bi bi-box-arrow-in-right me-1"></i> Buka Voting
                                        </a>
                                    @elseif($userHasVoted)
                                        <span class="badge bg-info-subtle text-info border border-info rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i> Sudah Voting
                                        </span>
                                    @else
                                        <a href="/voting/{{ $voting->id }}" class="btn btn-outline-primary btn-sm px-3">
                                            Lihat Hasil
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-4">
                {{-- Pagination jika ada --}}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #dee2e6;"></i>
                <h4 class="mt-3 text-muted">Tidak ada voting aktif</h4>
                <p class="text-muted">Tunggu admin membuat sesi voting baru</p>
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <a href="/voting/create-session" class="btn btn-success mt-2">
                        <i class="bi bi-plus-circle"></i> Buat Voting Pertama
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection