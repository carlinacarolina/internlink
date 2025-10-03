@extends('layouts.app')

@section('title', 'Security Settings')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('settings.partials.nav', [
            'active' => 'security',
            'environmentAvailable' => $environmentAvailable,
        ])
    </div>
    <div class="col-lg-9">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Update Password</h2>
                    <span class="badge bg-light text-dark text-capitalize">{{ $role }}</span>
                </div>
                <p class="text-muted">Secure your account by setting a strong password. The new password replaces your current one immediately after you save changes.</p>
                <form action="{{ $schoolRoute('settings/security') }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')
                    @include('components.form-errors')

                    <div class="col-12">
                        <label for="security-old-password" class="form-label">Old Password</label>
                        <input type="password" id="security-old-password" name="old_password" class="form-control" required autocomplete="current-password">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="security-new-password" class="form-label">New Password</label>
                        <input type="password" id="security-new-password" name="new_password" class="form-control" required autocomplete="new-password">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="security-confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" id="security-confirm-password" name="new_password_confirmation" class="form-control" required autocomplete="new-password">
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <a href="{{ $schoolRoute('settings/security') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h2 class="h6 mb-3">Security Overview</h2>
                <dl class="row mb-0">
                    @foreach($securityDetails as $label => $value)
                        <dt class="col-sm-4 col-lg-3">{{ $label }}</dt>
                        <dd class="col-sm-8 col-lg-9">{{ $value }}</dd>
                    @endforeach
                </dl>
                <div class="alert alert-info mt-3 mb-0" role="alert">
                    Enable multi-factor authentication when it becomes available for additional protection.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
