@extends('layouts.app')

@section('title', 'Ikuti Voting - Sistem RT')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-lg mb-4">
            <div class="card-header bg-primary text-white p-4">
                <h4 class="mb-0 fw-bold"><i class="bi bi-check2-square me-2"></i> Berikan Suara Anda</h4>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark mb-2">{{ $voting->judul }}</h2>
                    <p class="text-muted fs-5">{{ $voting->deskripsi }}</p>
                    <div class="d-inline-flex gap-3 mt-3">
                        <span class="badge bg-light text-dark border p-2"><i class="bi bi-clock me-1"></i> Berakhir: {{ $voting->selesai->format('d M Y H:i') }}</span>
                        <span class="badge bg-light text-dark border p-2"><i class="bi bi-people me-1"></i> {{ $voting->votes()->count() }} Partisipan</span>
                    </div>
                </div>
                
                <form action="{{ route('voting.submit', $voting->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Pilih Opsi (Bisa lebih dari satu):</h5>
                        
                        <div class="vstack gap-3">
                        @if($voting->allow_multiple_choice)
                            <!-- Multiple choice (checkbox) -->
                            @foreach($voting->options as $option)
                            <div class="position-relative">
                                <input class="form-check-input position-absolute top-50 start-0 translate-middle-y ms-3" type="checkbox" 
                                       name="options[]" value="{{ $option->id }}" 
                                       id="option{{ $option->id }}"
                                       style="width: 1.5em; height: 1.5em;">
                                <label class="list-group-item d-block p-4 ps-5 border rounded-3 shadow-sm hover-shadow cursor-pointer" for="option{{ $option->id }}">
                                    <span class="fw-bold fs-5 text-primary-custom">{{ $option->pilihan_label }}</span>
                                    @if($option->deskripsi_opsi)
                                        <div class="text-muted mt-1">{{ $option->deskripsi_opsi }}</div>
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        @else
                            <!-- Single choice (radio) - Fallback design just in case -->
                            @foreach($voting->options as $option)
                            <div class="position-relative">
                                <input class="form-check-input position-absolute top-50 start-0 translate-middle-y ms-3" type="radio" 
                                       name="option_id" value="{{ $option->id }}" 
                                       id="option{{ $option->id }}" required
                                       style="width: 1.5em; height: 1.5em;">
                                <label class="list-group-item d-block p-4 ps-5 border rounded-3 shadow-sm hover-shadow cursor-pointer" for="option{{ $option->id }}">
                                    <span class="fw-bold fs-5 text-primary-custom">{{ $option->pilihan_label }}</span>
                                    @if($option->deskripsi_opsi)
                                        <div class="text-muted mt-1">{{ $option->deskripsi_opsi }}</div>
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        @endif
                        </div>
                    </div>
                    
                    @error('options')
                        <div class="alert alert-danger rounded-3"><i class="bi bi-exclamation-triangle me-2"></i> {{ $message }}</div>
                    @enderror
                    @error('option_id')
                        <div class="alert alert-danger rounded-3"><i class="bi bi-exclamation-triangle me-2"></i> {{ $message }}</div>
                    @enderror
                    
                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <a href="/voting/{{ $voting->id }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success px-5 py-3 rounded-pill fw-bold shadow-sm fs-5">
                            <i class="bi bi-send me-2"></i> Kirim Suara
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.cursor-pointer { cursor: pointer; }
.hover-shadow:hover { background-color: #f8f9fa; border-color: var(--primary-color) !important; }
.form-check-input:checked + label {
    background-color: #effcf6;
    border-color: #198754 !important;
}
</style>
@endsection