@extends('layouts.app')

@section('title', 'Create Major Contact')

@section('content')
<h1 class="mb-4">Create Major Contact</h1>

@include('major-staff.form', [
    'action' => $schoolRoute('major-contacts'),
    'method' => 'POST',
    'assignment' => null,
    'cancel' => $schoolRoute('major-contacts'),
])
@endsection
