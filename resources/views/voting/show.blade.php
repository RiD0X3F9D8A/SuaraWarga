@extends('layouts.app')

@section('title', 'Detail Voting - Sistem RT')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">🗳️ Detail Voting</h1>
                <a href="/voting" class="btn btn-outline-secondary">
                    ← Kembali
                </a>
            </div>

            <!-- Voting Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">{{ $voting->judul }}</h2>
                    <p class="card-text">{{ $voting->deskripsi }}</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($voting->mulai <= now() && $voting->selesai >= now())
                                            <span class="badge bg-success">AKTIF</span>
                                        @else
                                            <span class="badge bg-secondary">SELESAI</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Mulai:</strong></td>
                                    <td>{{ $voting->mulai->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Selesai:</strong></td>
                                    <td>{{ $voting->selesai->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Partisipan:</strong></td>
                                    <td><span class="badge bg-info">{{ $voting->votes()->count() }} orang</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat oleh:</strong></td>
                                    <td>{{ $voting->creator->name ?? 'Admin' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $voting->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Opsi Voting -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">📋 PILIHAN KANDIDAT</h4>
                </div>
                <div class="card-body">
                    <!-- Debug Info -->
                    <div class="alert alert-warning mb-4">
                        <h5>🔍 INFO DEBUG:</h5>
                        <p class="mb-1"><strong>Waktu Server:</strong> {{ now()->format('d M Y H:i:s') }}</p>
                        <p class="mb-1"><strong>Waktu Voting Mulai:</strong> {{ $voting->mulai->format('d M Y H:i:s') }}</p>
                        <p class="mb-1"><strong>Waktu Voting Selesai:</strong> {{ $voting->selesai->format('d M Y H:i:s') }}</p>
                        <p class="mb-1"><strong>Status Voting:</strong> 
                            @if($voting->mulai <= now() && $voting->selesai >= now())
                                <span class="text-success">AKTIF (bisa vote)</span>
                            @else
                                <span class="text-danger">TIDAK AKTIF</span>
                            @endif
                        </p>
                        <p class="mb-1"><strong>User Role:</strong> {{ Auth::user()->role ?? 'guest' }}</p>
                        <p class="mb-0"><strong>Sudah Vote?:</strong> {{ $user_has_voted ? 'YA' : 'BELUM' }}</p>
                    </div>

                    @if($user_has_voted)
                    <div class="alert alert-success mb-4">
                        <h5>✅ ANDA SUDAH VOTE!</h5>
                        <p class="mb-0">Terima kasih telah berpartisipasi dalam voting ini.</p>
                    </div>
                    @endif

                    <!-- Pesan untuk Voting Aktif -->
                    @if($voting->mulai <= now() && $voting->selesai >= now() && !$user_has_voted && Auth::user()->role === 'warga')
                    <div class="alert alert-info mb-4">
                        <h5>🎯 SILAKAN PILIH KANDIDAT:</h5>
                        <p class="mb-0">Klik tombol <strong>"VOTE"</strong> di bawah nama kandidat pilihan Anda.</p>
                    </div>
                    @endif

                    <div class="row">
                        @foreach($voting->options as $option)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <!-- Nama Kandidat -->
                                    <h3 class="text-primary mb-3">{{ $option->pilihan_label }}</h3>
                                    
                                    <!-- Deskripsi -->
                                    @if($option->deskripsi_opsi)
                                        <p class="text-muted mb-4">{{ $option->deskripsi_opsi }}</p>
                                    @endif

                                    <!-- ====== TOMBOL VOTE ====== -->
                                    <!-- LOGIC: Tampilkan tombol jika:
                                        1. Voting aktif (waktu sekarang antara mulai-selesai)
                                        2. User belum vote
                                        3. User role = warga
                                    -->
                                    @if($voting->mulai <= now() && $voting->selesai >= now() && !$user_has_voted && Auth::check() && Auth::user()->role === 'warga')
                                    <form action="{{ url('/voting/' . $voting->id . '/submit') }}" method="POST" class="mb-3">
                                        @csrf
                                        <input type="hidden" name="option_id" value="{{ $option->id }}">
                                        
                                        <button type="submit" class="btn btn-success btn-lg w-100 py-3" 
                                                onclick="return confirm('Yakin memilih {{ $option->pilihan_label }}?')">
                                            <i class="bi bi-check-circle-fill"></i> 
                                            <strong>VOTE {{ $option->pilihan_label }}</strong>
                                        </button>
                                    </form>
                                    @endif
                                    <!-- ========================= -->

                                    <!-- Status Sudah Vote -->
                                    @if($user_has_voted)
                                        @php
                                            $user_vote = $voting->votes()
                                                ->where('user_id', auth()->id())
                                                ->where('option_id', $option->id)
                                                ->first();
                                        @endphp
                                        
                                        @if($user_vote)
                                        <div class="alert alert-success">
                                            <i class="bi bi-star-fill"></i> 
                                            <strong>INI PILIHAN ANDA</strong>
                                            <p class="mb-0 small">
                                                Dipilih pada {{ $user_vote->created_at->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                        @endif
                                    @endif

                                    <!-- Untuk Admin: Tampilkan jumlah suara -->
                                    @if(Auth::check() && Auth::user()->role === 'admin')
                                    <div class="mt-3 p-2 bg-light rounded">
                                        <p class="mb-0">
                                            <i class="bi bi-eye"></i> 
                                            <strong>Total Suara:</strong> 
                                            <span class="badge bg-primary">{{ $option->votes()->count() }}</span>
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pesan jika tidak bisa vote -->
                    @if(!($voting->mulai <= now() && $voting->selesai >= now()) && Auth::user()->role === 'warga' && !$user_has_voted)
                    <div class="alert alert-warning text-center mt-4">
                        <h5>⏰ VOTING TELAH BERAKHIR</h5>
                        <p class="mb-0">Waktu voting sudah habis. Anda tidak dapat memberikan suara.</p>
                    </div>
                    @endif

                    @if($user_has_voted && Auth::user()->role === 'warga')
                    <div class="text-center mt-4">
                        <a href="/voting/{{ $voting->id }}/results" class="btn btn-info">
                            <i class="bi bi-graph-up"></i> Lihat Hasil Voting
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    transition: transform 0.3s;
}
.card:hover {
    transform: translateY(-5px);
}
.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    font-size: 1.1rem;
}
.btn-success:hover {
    transform: scale(1.02);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
}
</style>

<script>
// Simple confirmation
function confirmVote(name) {
    return confirm(`Apakah Anda yakin memilih:\n\n"${name}"\n\nPilihan tidak dapat diubah!`);
}
</script>
@endsection