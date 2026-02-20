@extends('admin.layouts.app')

@section('title', 'Tambah Admin Baru')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Manajemen Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4 text-gray-800">Buat Akun Admin Baru</h1>
    
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @include('admin.admins._form')
        </div>
    </div>
@endsection