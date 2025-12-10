@extends('layouts.app')

@section('title', 'Buat Voting Baru - Sistem RT')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-plus-circle text-success"></i> Buat Voting Baru</h5>
            </div>
            <div class="card-body">
                <form action="/voting" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Voting *</label>
                        <input type="text" class="form-control" id="judul" name="judul" 
                               value="{{ old('judul') }}" required>
                        @error('judul')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" 
                                  rows="3">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mulai" class="form-label">Mulai *</label>
                            <input type="datetime-local" class="form-control" id="mulai" 
                                   name="mulai" value="{{ old('mulai') }}" required>
                            @error('mulai')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="selesai" class="form-label">Selesai *</label>
                            <input type="datetime-local" class="form-control" id="selesai" 
                                   name="selesai" value="{{ old('selesai') }}" required>
                            @error('selesai')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Opsi Voting -->
                    <div class="mb-4">
                        <label class="form-label">Opsi Voting * (Minimal 2)</label>
                        <div id="options-container">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="options[]" 
                                       placeholder="Opsi 1" required>
                                <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">
                                    <i class="bi bi-dash"></i>
                                </button>
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="options[]" 
                                       placeholder="Opsi 2" required>
                                <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">
                                    <i class="bi bi-dash"></i>
                                </button>
                            </div>
                        </div>
                        @error('options')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addOption()">
                            <i class="bi bi-plus"></i> Tambah Opsi
                        </button>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="allow_multiple" 
                               name="allow_multiple" value="1">
                        <label class="form-check-label" for="allow_multiple">
                            Izinkan memilih lebih dari satu opsi
                        </label>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/voting" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Buat Voting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let optionCount = 2;
    
    function addOption() {
        optionCount++;
        const container = document.getElementById('options-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" class="form-control" name="options[]" 
                   placeholder="Opsi ${optionCount}" required>
            <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">
                <i class="bi bi-dash"></i>
            </button>
        `;
        container.appendChild(div);
    }
    
    function removeOption(button) {
        const container = document.getElementById('options-container');
        if (container.children.length > 2) {
            button.parentElement.remove();
            optionCount--;
        }
    }
</script>
@endsection