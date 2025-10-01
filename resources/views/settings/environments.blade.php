@extends('layouts.app')

@section('title', 'Environment Settings')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('settings.partials.nav', [
            'active' => 'environments',
            'environmentAvailable' => true,
        ])
    </div>
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Environments</h2>
                    <span class="badge bg-light text-dark text-capitalize">{{ $role }}</span>
                </div>
                <p class="text-muted">Environment-specific tools will appear here. Use this area to configure staging, production, or future integrations aligned with your school.</p>
                <div class="border rounded p-4 bg-light text-muted">
                    Placeholder for upcoming environment configuration.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
