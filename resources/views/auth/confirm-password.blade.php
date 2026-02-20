@extends('layouts.app') {{-- Ganti sesuai layout Bootstrap-mu --}}

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h4 class="mb-3 text-center">Confirm Password</h4>
        <p class="text-muted text-center mb-4">
            This is a secure area of the application. Please confirm your password before continuing.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    Confirm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
