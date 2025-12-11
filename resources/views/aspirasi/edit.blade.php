@extends('layouts.app')

@section('title', 'Edit Aspirasi - Sistem RT')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-md border-0">
                <div class="card-header bg-white border-bottom p-4">
                    <h4 class="mb-0 text-primary-custom fw-bold">
                        <i class="bi bi-pencil-square me-2"></i> Edit Aspirasi
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('aspirasi.update', $aspirasi->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Judul -->
                        <div class="mb-3">
                            <label for="judul" class="form-label">
                                <strong>Judul Aspirasi</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                   id="judul" name="judul" 
                                   value="{{ old('judul', $aspirasi->judul) }}" 
                                   placeholder="Contoh: Perbaikan Jalan Gang Bunga Mawar" 
                                   required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                      required>{{ old('isi', $aspirasi->isi) }}</textarea>
                            @error('isi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <span id="charCount">0</span> karakter (minimal 10 karakter)
                            </div>
                        </div>
                        
                        <!-- Tombol -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('aspirasi.my') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Hitung karakter saat load dan input
function updateCharCount() {
    document.getElementById('charCount').textContent = document.getElementById('isi').value.length;
}

document.getElementById('isi').addEventListener('input', updateCharCount);
// Run on load
document.addEventListener('DOMContentLoaded', updateCharCount);

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
