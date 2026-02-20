@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Kategori Baru</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Kategori</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- Slug akan dibuat otomatis oleh model, jadi tidak perlu input di sini --}}

                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save-fill me-1" viewBox="0 0 16 16"><path d="M8.5 1.5A1.5 1.5 0 0 1 10 0h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h6c1.336 0 2.31.82 2.604 2H12A1 1 0 0 0 11 3v1a1 1 0 0 0 1 1h1V1.5H10a.5.5 0 0 0-.5.5V3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V2a.5.5 0 0 0-.5-.5H14a.5.5 0 0 0-.5.5V3a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5V2A1.5 1.5 0 0 0 13.5 0h-4A1.5 1.5 0 0 0 8 1.5v2.134L1.697 6.49A.5.5 0 0 0 1 7v7a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5V7a.5.5 0 0 0-.276-.447L8.5 2.514V1.5zM3 9.5a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm0 2a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM3 5.5a1 1 0 1 1 2 0 1 1 0 0 1-2 0z"/></svg>
                    Simpan Kategori
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection