@extends('layouts.app')

@section('title', 'Kelola User - Sistem RT')

@section('content')
<div class="row">
    <div class="col-12">
        <h3><i class="bi bi-people"></i> Kelola Data User</h3>
        <p class="text-muted">Manajemen pengguna sistem (Admin & Warga).</p>
    </div>
</div>

<!-- Statistik User -->
<div class="row mt-4">
    <div class="col-md-3 mb-4">
        <div class="card h-100 border-start border-4 border-primary shadow-sm hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                     <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary-subtle me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-shield-lock text-primary fs-4"></i>
                     </div>
                     <div>
                        <h6 class="card-subtitle text-muted mb-1">Total Admin</h6>
                        <h2 class="card-title fw-bold mb-0 text-primary-custom">{{ $users->where('role', 'admin')->count() }}</h2>
                     </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card h-100 border-start border-4 border-success shadow-sm hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                     <div class="d-flex align-items-center justify-content-center rounded-circle bg-success-subtle me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-person text-success fs-4"></i>
                     </div>
                     <div>
                        <h6 class="card-subtitle text-muted mb-1">Total Warga</h6>
                        <h2 class="card-title fw-bold mb-0 text-success">{{ $users->where('role', 'warga')->count() }}</h2>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card h-100 border-start border-4 border-info shadow-sm hover-shadow">
            <div class="card-body">
                 <div class="d-flex align-items-center mb-2">
                     <div class="d-flex align-items-center justify-content-center rounded-circle bg-info-subtle me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-check-circle text-info fs-4"></i>
                     </div>
                     <div>
                        <h6 class="card-subtitle text-muted mb-1">Status Aktif</h6>
                        <h2 class="card-title fw-bold mb-0 text-info">{{ $users->where('is_active', true)->count() }}</h2>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card h-100 border-start border-4 border-secondary shadow-sm hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                     <div class="d-flex align-items-center justify-content-center rounded-circle bg-secondary-subtle me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-dash-circle text-secondary fs-4"></i>
                     </div>
                     <div>
                        <h6 class="card-subtitle text-muted mb-1">Status Non-Aktif</h6>
                        <h2 class="card-title fw-bold mb-0 text-secondary">{{ $users->where('is_active', false)->count() }}</h2>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alerts -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Tabel Users -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary-custom"><i class="bi bi-table"></i> Daftar Semua User</h5>
                    <span class="badge bg-primary rounded-pill">{{ $users->count() }} Total User</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Nama</th>
                                <th class="py-3">Email</th>
                                <th class="py-3">Role</th>
                                <th class="py-3">Status</th>
                                <th class="py-3">Tanggal Daftar</th>
                                <th class="px-4 py-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="px-4">#{{ $user->id }}</td>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        @if($user->profile_photo_path)
                                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="avatar-initial rounded-circle bg-primary-subtle text-primary fw-bold me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            @if($user->id == Auth::id())
                                                <small class="text-warning"><i class="bi bi-star-fill"></i> Akun Anda</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }} rounded-pill">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} rounded-pill">
                                        {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted"><i class="bi bi-calendar3"></i> {{ $user->created_at->format('d M Y') }}</small>
                                </td>
                                <td class="px-4 text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/users/{{ $user->id }}" class="btn btn-outline-primary" title="Detail User">
                                            <i class="bi bi-info-circle"></i>
                                        </a>
                                        
                                        @if($user->id != Auth::id())
                                        <form action="/users/{{ $user->id }}/toggle-status" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-{{ $user->is_active ? 'secondary' : 'success' }}" 
                                                    title="{{ $user->is_active ? 'Nonaktifkan User' : 'Aktifkan User' }}"
                                                    onclick="return confirm('Apakah anda yakin ingin mengubah status user ini?')">
                                                <i class="bi bi-{{ $user->is_active ? 'power' : 'lightning-fill' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="/users/{{ $user->id }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    title="Hapus User"
                                                    onclick="return confirm('PERINGATAN: Menghapus user ini akan menghapus semua data terkait (aspirasi, voting, dll). Lanjutkan?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted">Belum ada user terdaftar</h5>
                    <p class="text-muted small">Data user akan muncul di sini setelah ada pendaftaran.</p>
                </div>
                @endif
            </div>
            <div class="card-footer bg-light py-3">
                 <small class="text-muted"><i class="bi bi-info-circle"></i> Gunakan tombol aksi di sebelah kanan untuk mengelola akun user.</small>
            </div>
        </div>
    </div>
</div>
@endsection