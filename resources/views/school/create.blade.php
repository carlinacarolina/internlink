@extends('layouts.app')

@section('title', 'Create School')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Create School</h1>
</div>

@include('school.form', [
    'action' => url('/schools'),
    'method' => 'POST',
    'school' => null,
    'cancel' => url('/schools'),
])
@endsection
