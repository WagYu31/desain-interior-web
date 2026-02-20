@extends('admin.layouts.app')

@section('title', 'Manajemen Tim')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Tim</h1>
        <a href="{{ route('admin.team-members.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill me-2"></i>Tambah Anggota Tim
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">Foto</th>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teamMembers as $member)
                            <tr>
                                <td>
                                    @if ($member->photo_path)
                                        <img src="{{ $member->photo_path ? asset('storage/' . $member->photo_path) : 'https://via.placeholder.com/50' }}"
                                            alt="{{ $member->name }}" class="rounded-circle"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <span class="d-inline-block p-2 rounded-circle bg-secondary text-white"
                                            style="width: 50px; height: 50px; line-height: 34px; text-align: center;">
                                            <i class="bi bi-person-fill fs-5"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->position }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.team-members.edit', $member) }}"
                                        class="btn btn-sm btn-warning me-1" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.team-members.destroy', $member) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota tim ini? Tindakan ini tidak dapat diurungkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <p class="mb-0">Belum ada anggota tim yang ditambahkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tampilkan Paginasi --}}
            @if ($teamMembers->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $teamMembers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
