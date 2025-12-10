@extends('layouts.app')

@section('title', 'Kelola Voting - Sistem RT')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"><i class="bi bi-gear text-primary"></i> Kelola Voting</h2>
            <p class="text-muted mb-0">Kelola semua sesi voting di sistem RT</p>
        </div>
        <a href="{{ route('voting.createSession') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Buat Voting Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(isset($votings) && $votings->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Judul Voting</th>
                                <th>Status</th>
                                <th>Opsi</th>
                                <th>Vote</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($votings as $voting)
                            @php
                                $now = now();
                                $is_active = $voting->mulai <= $now && $voting->selesai >= $now;
                                $options_count = $voting->options->count();
                                $votes_count = $voting->votes_count ?? 0;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div>
                                        <strong class="d-block">{{ $voting->judul }}</strong>
                                        @if(!empty($voting->deskripsi))
                                            <small class="text-muted d-block mt-1">
                                                {{ Str::limit($voting->deskripsi, 60) }}
                                            </small>
                                        @endif
                                        <small class="text-muted d-block mt-1">
                                            <i class="bi bi-person"></i> {{ $voting->creator->name ?? 'Admin' }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    @if($is_active)
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="bi bi-play-circle me-1"></i> Aktif
                                        </span>
                                    @elseif($voting->selesai < $now)
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i> Selesai
                                        </span>
                                    @else
                                        <span class="badge bg-warning rounded-pill px-3 py-2">
                                            <i class="bi bi-clock me-1"></i> Belum Mulai
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $options_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $votes_count }}</span>
                                </td>
                                <td>
                                    @if($voting->mulai)
                                        <div class="text-nowrap">
                                            {{ $voting->mulai->format('d/m/Y') }}
                                            <br>
                                            <small class="text-muted">{{ $voting->mulai->format('H:i') }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($voting->selesai)
                                        <div class="text-nowrap">
                                            {{ $voting->selesai->format('d/m/Y') }}
                                            <br>
                                            <small class="text-muted">{{ $voting->selesai->format('H:i') }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Lihat Detail -->
                                        <a href="{{ route('voting.show', $voting->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <!-- Edit -->
                                        <a href="{{ route('voting.edit', $voting->id) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit Voting">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <!-- Hasil Voting -->
                                        <a href="{{ route('voting.results', $voting->id) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Lihat Hasil">
                                            <i class="bi bi-bar-chart"></i>
                                        </a>
                                        
                                        <!-- Tutup Voting (jika aktif) -->
                                        @if($is_active)
                                        <form action="{{ route('voting.close', $voting->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Tutup Voting Sekarang"
                                                    onclick="return confirm('Tutup voting ini sekarang?')">
                                                <i class="bi bi-lock"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <!-- Hapus -->
                                        <form action="{{ route('voting.destroy', $voting->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-dark" 
                                                    title="Hapus Voting"
                                                    onclick="return confirm('Hapus voting ini? Semua data akan hilang.')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Summary -->
                <div class="mt-4 pt-3 border-top">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h4 class="mb-0">{{ $votings->where('selesai', '>', now())->where('mulai', '<=', now())->count() }}</h4>
                                    <p class="text-muted mb-0">Voting Aktif</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h4 class="mb-0">{{ $votings->sum('votes_count') }}</h4>
                                    <p class="text-muted mb-0">Total Suara</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h4 class="mb-0">{{ $votings->count() }}</h4>
                                    <p class="text-muted mb-0">Total Voting</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-bar-chart display-1 text-muted"></i>
                </div>
                <h3 class="mb-3">Belum Ada Voting</h3>
                <p class="text-muted mb-4">Anda belum membuat sesi voting. Buat voting pertama untuk memulai.</p>
                <a href="{{ route('voting.createSession') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i> Buat Voting Pertama
                </a>
            </div>
        </div>
    @endif
</div>
@endsection