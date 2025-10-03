@extends('layouts.app')

@section('title', 'Update School')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Update School</h1>
</div>

@include('school.form', [
    'action' => url('/schools/' . $school->id),
    'method' => 'PUT',
    'school' => $school,
    'cancel' => url('/schools/' . $school->id . '/read'),
])
@endsection
