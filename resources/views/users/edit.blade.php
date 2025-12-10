@extends('layouts.app')

@section('title', 'Edit User - Sistem RT')

@section('content')
<div class="row justify-content-center">
    <!-- Header Back -->
    <div class="col-md-8 mb-3">
        <a href="/users" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Kelola User
        </a>
    </div>

    <!-- Form Card -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning-subtle text-dark border-bottom-0 py-3">
                <h4 class="mb-0 fs-5 fw-bold text-dark"><i class="bi bi-pencil-square me-2"></i> Edit Data User</h4>
            </div>
            <div class="card-body p-4">
                <form action="/users/{{ $user->id }}/update" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold small text-muted text-uppercase">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label fw-bold small text-muted text-uppercase">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="warga" {{ $user->role == 'warga' ? 'selected' : '' }}>Warga</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="is_active" class="form-label fw-bold small text-muted text-uppercase">Status Akun</label>
                            <select name="is_active" id="is_active" class="form-select" required>
                                <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Read Only Fields -->
                    @if($user->nik || $user->alamat || $user->phone)
                    <hr class="my-4">
                    <h6 class="text-muted mb-3">Informasi Tambahan (Read Only)</h6>
                    
                    @if($user->nik)
                    <div class="mb-3">
                        <label class="form-label small text-muted">NIK</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->nik }}" readonly>
                    </div>
                    @endif

                    @if($user->alamat)
                    <div class="mb-3">
                        <label class="form-label small text-muted">Alamat</label>
                        <textarea class="form-control bg-light" rows="2" readonly>{{ $user->alamat }}</textarea>
                    </div>
                    @endif

                    @if($user->phone)
                    <div class="mb-3">
                        <label class="form-label small text-muted">Telepon</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->phone }}" readonly>
                    </div>
                    @endif
                    @endif

                    <div class="alert alert-light border mt-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="bi bi-info-circle fs-4 text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">User dibuat: {{ $user->created_at->format('d M Y H:i') }}</small>
                                <small class="text-muted d-block">Terakhir update: {{ $user->updated_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="/users" class="btn btn-light border">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-check-circle me-1"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection