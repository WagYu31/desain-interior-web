@extends('admin.layouts.app')

@section('title', 'Tambah Anggota Tim')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.team-members.index') }}">Manajemen Tim</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4 text-gray-800">Tambah Anggota Tim</h1>
    
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @include('admin.team._form')
        </div>
    </div>
@endsection