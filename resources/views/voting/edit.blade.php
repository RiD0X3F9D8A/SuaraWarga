@extends('layouts.app')

@section('title', 'Edit Voting - Sistem RT')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pencil text-warning"></i> Edit Voting</h5>
            </div>
            <div class="card-body">
                <form action="/voting/{{ $voting->id }}/update" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Voting *</label>
                        <input type="text" class="form-control" id="judul" name="judul" 
                               value="{{ $voting->judul }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ $voting->deskripsi }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mulai" class="form-label">Mulai *</label>
                            <input type="datetime-local" class="form-control" id="mulai" 
                                   name="mulai" value="{{ $voting->mulai->format('Y-m-d\TH:i') }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="selesai" class="form-label">Selesai *</label>
                            <input type="datetime-local" class="form-control" id="selesai" 
                                   name="selesai" value="{{ $voting->selesai->format('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/voting/manage" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection