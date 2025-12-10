@extends('layouts.app')

@section('title', 'Detail User - Sistem RT')

@section('content')
<div class="row justify-content-center">
    <!-- Header Back -->
    <div class="col-md-8 mb-3">
        <a href="/users" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Kelola User
        </a>
    </div>

    <!-- Detail Card -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fs-5 fw-bold"><i class="bi bi-person-badge me-2"></i> Detail Data User</h4>
                @if($user->is_active)
                    <span class="badge bg-light text-primary">Aktif</span>
                @else
                    <span class="badge bg-danger">Nonaktif</span>
                @endif
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    @if($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile" class="rounded-circle mb-3 border" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-3" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'success' }} mt-2">{{ ucfirst($user->role) }}</span>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted text-uppercase fw-bold">NIK</label>
                        <p class="fs-5">{{ $user->nik ?? '-' }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted text-uppercase fw-bold">No. Telepon</label>
                        <p class="fs-5">{{ $user->phone ?? '-' }}</p>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="small text-muted text-uppercase fw-bold">Alamat Lengkap</label>
                        <p class="fs-5">{{ $user->alamat ?? '-' }}</p>
                    </div>
                </div>

                <div class="alert alert-light border mt-3">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="bi bi-calendar3 fs-4 text-muted"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Terdaftar pada: {{ $user->created_at->format('d M Y H:i') }}</small>
                            <small class="text-muted d-block">Terakhir update: {{ $user->updated_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection
