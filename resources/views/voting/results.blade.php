@extends('layouts.app')

@section('title', 'Hasil Voting - Sistem RT')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary-custom mb-0"><i class="bi bi-bar-chart-line me-2"></i> Hasil Voting</h3>
            <a href="/voting/{{ $voting->id }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
        
        <!-- Info Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h4 class="card-title fw-bold text-dark">{{ $voting->judul }}</h4>
                <p class="card-text text-muted mb-4">{{ $voting->deskripsi }}</p>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-primary-subtle text-primary rounded-3 h-100">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-people fs-4 me-2"></i>
                                <span class="fw-bold">Partisipasi</span>
                            </div>
                            <h5 class="mb-0">
                                {{ $voting->votes_count }} <small class="text-muted fs-6 fw-normal">suara dari</small> 
                                {{ $voting->total_warga ?? 'Semua' }} <small class="text-muted fs-6 fw-normal">warga</small>
                            </h5>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-{{ $voting->isActive() ? 'warning-subtle text-warning-emphasis' : 'success-subtle text-success' }} rounded-3 h-100">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-clock-history fs-4 me-2"></i>
                                <span class="fw-bold">Status</span>
                            </div>
                            <h5 class="mb-0 text-capitalize">
                                {{ $voting->isActive() ? 'Sedang Berlangsung' : 'Selesai' }}
                            </h5>
                            <small class="opacity-75">Berakhir: {{ $voting->selesai->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chart/Results -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2"></i> Perolehan Suara</h5>
            </div>
            <div class="card-body p-4">
                @if($voting->options->count() > 0)
                    @php
                        $maxVotes = $voting->options->max('votes_count');
                        $totalVotes = $voting->votes->count(); // Lebih akurat hitung dari relasi votes
                    @endphp

                    <div class="vstack gap-4">
                        @foreach($voting->options->sortByDesc('votes_count') as $option)
                        @php
                            $percentage = $totalVotes > 0 ? ($option->votes_count / $totalVotes) * 100 : 0;
                            $isWinner = !$voting->isActive() && $option->votes_count == $maxVotes && $maxVotes > 0;
                        @endphp
                        <div>
                            <div class="d-flex justify-content-between align-items-end mb-1">
                                <div>
                                    <h6 class="fw-bold mb-0">
                                        {{ $option->pilihan_label }}
                                        @if($isWinner)
                                            <i class="bi bi-trophy-fill text-warning ms-1" title="Pemenang"></i>
                                        @endif
                                    </h6>
                                    @if($option->deskripsi_opsi)
                                        <small class="text-muted">{{ $option->deskripsi_opsi }}</small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold fs-5">{{ $option->votes_count }}</span> <small class="text-muted">Suara</small>
                                    <div class="small fw-bold {{ $isWinner ? 'text-success' : 'text-muted' }}">{{ round($percentage, 1) }}%</div>
                                </div>
                            </div>
                            <div class="progress" style="height: 12px; border-radius: 6px;">
                                <div class="progress-bar {{ $isWinner ? 'bg-success' : 'bg-primary' }}" 
                                     role="progressbar" 
                                     style="width: {{ $percentage }}%"
                                     aria-valuenow="{{ $percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Winner Info -->
                    @if(!$voting->isActive() && $totalVotes > 0)
                        @php
                            $winners = $voting->options->filter(function($opt) use ($maxVotes) { 
                                return $opt->votes_count == $maxVotes; 
                            });
                            $isDraw = $winners->count() > 1;
                            $winnerName = $isDraw ? $winners->pluck('pilihan_label')->join(', ') : $winners->first()->pilihan_label;
                        @endphp
                        
                        <div class="alert alert-{{ $isDraw ? 'warning' : 'success' }} mt-5 border-0 shadow-sm d-flex align-items-center">
                            <i class="bi bi-{{ $isDraw ? 'exclamation-triangle-fill' : 'trophy-fill' }} fs-1 me-3"></i>
                            <div>
                                <h5 class="alert-heading fw-bold mb-1">{{ $isDraw ? 'HASIL SERI' : 'PEMENANG VOTING' }}</h5>
                                <p class="mb-0 fs-5">
                                    {{ $winnerName }}
                                    <span class="fs-6 opacity-75">({{ $maxVotes }} suara)</span>
                                </p>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-bar-chart text-muted display-4 mb-3"></i>
                        <p class="text-muted">Belum ada data opsi untuk ditampilkan.</p>
                    </div>
                @endif
            </div>
            
             <!-- Download/Share -->
            @if(Auth::user()->role === 'admin')
            <div class="card-footer bg-light p-3">
                 <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Cetak Laporan
                    </button>
                    <!-- Spacer for future export buttons -->
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        .navbar, .btn, .no-print { display: none !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        body { background-color: white !important; }
    }
</style>
@endsection
