@extends('admin.layouts.app')

@section('title', 'Manajemen Admin')

@push('styles')
<style>
    .admin-page-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 16px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .admin-page-header::after {
        content: '';
        position: absolute;
        right: -30px; top: -30px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .admin-page-header h1 {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
    }
    .admin-page-header p {
        color: rgba(255,255,255,.6);
        font-size: .85rem;
        margin: .25rem 0 0;
    }
    .btn-add-admin {
        background: #fff;
        color: #1e293b;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        padding: .6rem 1.25rem;
        font-size: .85rem;
        transition: all .2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,.08);
    }
    .btn-add-admin:hover {
        background: #f1f5f9;
        color: #1e293b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,.12);
    }

    /* Stats strip */
    .stats-strip {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .stat-chip {
        background: #fff;
        border-radius: 12px;
        padding: .75rem 1.25rem;
        display: flex;
        align-items: center;
        gap: .75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        flex: 1;
        max-width: 220px;
    }
    .stat-chip .stat-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
    }
    .stat-chip .stat-value {
        font-size: 1.3rem;
        font-weight: 800;
        line-height: 1;
    }
    .stat-chip .stat-label {
        font-size: .7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #94a3b8;
    }

    /* Table card */
    .admin-table-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }
    .admin-table-card .card-header {
        background: #fff;
        border-bottom: 2px solid #f1f5f9;
        padding: 1rem 1.5rem;
    }
    .admin-table thead th {
        background: #f8fafc;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        color: #64748b;
        padding: .85rem 1rem;
        border: none;
    }
    .admin-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .admin-table tbody tr {
        transition: background .15s ease;
    }
    .admin-table tbody tr:hover {
        background: #f8faff;
    }
    .admin-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Admin avatar */
    .admin-avatar {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        font-size: .85rem;
        color: #fff;
        flex-shrink: 0;
    }

    /* Role badge */
    .badge-role {
        padding: .3em .7em;
        font-size: .7rem;
        font-weight: 600;
        border-radius: 8px;
    }

    /* Action buttons */
    .btn-action {
        width: 34px; height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-size: .8rem;
        transition: all .2s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
    .btn-action.btn-edit {
        background: #e0f2fe;
        color: #0284c7;
    }
    .btn-action.btn-edit:hover {
        background: #bae6fd;
        color: #0369a1;
    }
    .btn-action.btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }
    .btn-action.btn-delete:hover {
        background: #fecaca;
        color: #b91c1c;
    }
    .btn-action.btn-disabled {
        background: #f1f5f9;
        color: #cbd5e1;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
    {{-- Page Header --}}
    <div class="admin-page-header d-flex align-items-center justify-content-between">
        <div>
            <h1><i class="bi bi-shield-lock-fill me-2" style="opacity:.7;"></i>Manajemen Admin</h1>
            <p>Kelola akun administrator yang memiliki akses ke panel admin</p>
        </div>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-add-admin">
            <i class="bi bi-person-plus-fill me-2"></i>Tambah Admin
        </a>
    </div>

    {{-- Stats --}}
    <div class="stats-strip">
        <div class="stat-chip">
            <div class="stat-icon" style="background:#e0f2fe; color:#0284c7;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#0284c7;">{{ $admins->total() }}</div>
                <div class="stat-label">Total Admin</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-icon" style="background:#dcfce7; color:#16a34a;">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#16a34a;">{{ $admins->total() }}</div>
                <div class="stat-label">Aktif</div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card admin-table-card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0" style="color:#1e293b;">
                <i class="bi bi-list-ul me-2" style="color:#4361ee;"></i>Daftar Admin
            </h6>
            <span class="text-muted small">{{ $admins->total() }} admin terdaftar</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th style="min-width:200px;">Admin</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Bergabung</th>
                            <th class="text-center" style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $avatarColors = ['#4361ee','#0ea770','#e04f5f','#7c3aed','#0891b2','#e8890c','#c026d3','#16a34a'];
                        @endphp
                        @forelse ($admins as $index => $admin)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="admin-avatar me-3" style="background:{{ $avatarColors[$index % count($avatarColors)] }};">
                                            {{ strtoupper(substr($admin->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="color:#1e293b;">{{ $admin->name }}</div>
                                            @if(auth()->id() === $admin->id)
                                                <small class="text-success fw-semibold"><i class="bi bi-dot"></i>Anda</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="color:#475569;">{{ $admin->email }}</span>
                                </td>
                                <td>
                                    @if($admin->hasRole('owner'))
                                        <span class="badge-role" style="background:#fef3c7; color:#b45309;">
                                            <i class="bi bi-crown-fill me-1" style="font-size:.65rem;"></i>Owner
                                        </span>
                                    @elseif($admin->hasRole('arsitek'))
                                        <span class="badge-role" style="background:#e0f2fe; color:#0369a1;">
                                            <i class="bi bi-vector-pen me-1" style="font-size:.65rem;"></i>Arsitek
                                        </span>
                                    @else
                                        <span class="badge-role" style="background:#f0fdf4; color:#16a34a;">
                                            <i class="bi bi-shield-fill-check me-1" style="font-size:.65rem;"></i>Admin
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div style="color:#475569; font-size:.85rem;">{{ $admin->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $admin->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        @if (auth()->id() !== $admin->id)
                                            <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" title="Hapus">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn-action btn-disabled" disabled
                                                title="Tidak dapat menghapus akun sendiri">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div style="color:#94a3b8;">
                                        <i class="bi bi-people" style="font-size:2.5rem;"></i>
                                        <p class="mt-2 mb-0 fw-medium">Belum ada data admin</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($admins->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $admins->links() }}
        </div>
    @endif
@endsection
