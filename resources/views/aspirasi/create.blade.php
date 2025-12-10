@extends('layouts.app')

@section('title', 'Buat Aspirasi Baru - Sistem RT')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-md border-0">
                <div class="card-header bg-white border-bottom p-4">
                    <h4 class="mb-0 text-primary-custom fw-bold">
                        <i class="bi bi-pencil-square me-2"></i> Buat Aspirasi Baru
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('aspirasi.store') }}" method="POST">
                        @csrf
                        
                        <!-- Judul -->
                        <div class="mb-3">
                            <label for="judul" class="form-label">
                                <strong>Judul Aspirasi</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                   id="judul" name="judul" 
                                   value="{{ old('judul') }}" 
                                   placeholder="Contoh: Perbaikan Jalan Gang Bunga Mawar" 
                                   required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Judul yang jelas dan deskriptif</div>
                        </div>
                        
                        <!-- Isi Aspirasi -->
                        <div class="mb-4">
                            <label for="isi" class="form-label">
                                <strong>Isi Aspirasi</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('isi') is-invalid @enderror" 
                                      id="isi" name="isi" rows="6" 
                                      placeholder="Jelaskan aspirasi Anda secara detail..." 
                                      required>{{ old('isi') }}</textarea>
                            @error('isi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <span id="charCount">0</span> karakter (minimal 10 karakter)
                            </div>
                        </div>
                        
                        <!-- Tombol -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ url('/dashboard/warga') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Kirim Aspirasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Panduan -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Panduan Pengisian</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Pastikan judul jelas dan mewakili isi aspirasi</li>
                        <li>Jelaskan aspirasi secara detail untuk memudahkan penanganan</li>
                        <li>Aspirasi akan ditinjau oleh admin RT sebelum diproses</li>
                        <li>Anda dapat melacak status aspirasi di menu "Aspirasi Saya"</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Hitung karakter
document.getElementById('isi').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

// Validasi form sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
    const judul = document.getElementById('judul').value.trim();
    const isi = document.getElementById('isi').value.trim();
    
    let errors = [];
    
    if (!judul) errors.push('Judul harus diisi');
    if (judul.length < 5) errors.push('Judul minimal 5 karakter');
    if (!isi) errors.push('Isi aspirasi harus diisi');
    if (isi.length < 10) errors.push('Isi minimal 10 karakter');
    
    if (errors.length > 0) {
        e.preventDefault();
        alert('Validasi gagal:\n\n' + errors.join('\n'));
    }
});
</script>
@endsection