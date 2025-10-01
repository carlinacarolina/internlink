@extends('layouts.app')

@section('title', 'School Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">School Details</h1>
    <div class="d-flex gap-2">
        <a href="/schools/{{ $school->id }}/update" class="btn btn-warning">Update</a>
        <form action="/schools/{{ $school->id }}" method="POST" class="school-delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
</div>

<div class="section-card">
    <dl class="row mb-0">
        <dt class="col-sm-3">Name</dt>
        <dd class="col-sm-9">{{ $school->name }}</dd>

        <dt class="col-sm-3">Address</dt>
        <dd class="col-sm-9">{{ $school->address }}</dd>

        <dt class="col-sm-3">City</dt>
        <dd class="col-sm-9">{{ $school->city ?? '—' }}</dd>

        <dt class="col-sm-3">Postal Code</dt>
        <dd class="col-sm-9">{{ $school->postal_code ?? '—' }}</dd>

        <dt class="col-sm-3">Phone</dt>
        <dd class="col-sm-9">{{ $school->phone }}</dd>

        <dt class="col-sm-3">Email</dt>
        <dd class="col-sm-9">{{ $school->email }}</dd>

        <dt class="col-sm-3">Website</dt>
        <dd class="col-sm-9">
            @if($school->website)
                <a href="{{ $school->website }}" target="_blank" rel="noopener">{{ $school->website }}</a>
            @else
                —
            @endif
        </dd>

        <dt class="col-sm-3">Principal Name</dt>
        <dd class="col-sm-9">{{ $school->principal_name ?? '—' }}</dd>

        <dt class="col-sm-3">Principal NIP</dt>
        <dd class="col-sm-9">{{ $school->principal_nip ?? '—' }}</dd>

        <dt class="col-sm-3">Created At</dt>
        <dd class="col-sm-9">{{ $school->created_at }}</dd>

        <dt class="col-sm-3">Updated At</dt>
        <dd class="col-sm-9">{{ $school->updated_at }}</dd>
    </dl>
</div>

<div class="d-flex gap-2 mt-3">
    <a href="/schools" class="btn btn-secondary">Back to List</a>
</div>

<script>
document.querySelectorAll('.school-delete-form').forEach(form => {
    form.addEventListener('submit', event => {
        const confirmed = window.confirm('Are you sure you want to delete this school? This action cannot be undone.');
        if (!confirmed) {
            event.preventDefault();
        }
    });
});
</script>
@endsection
