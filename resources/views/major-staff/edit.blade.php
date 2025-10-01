@extends('layouts.app')

@section('title', 'Update Major Contact')

@section('content')
<h1 class="mb-4">Update Major Contact</h1>

@include('major-staff.form', [
    'action' => $schoolRoute('major-contacts/' . $assignment->id),
    'method' => 'PUT',
    'assignment' => $assignment,
    'cancel' => $schoolRoute('major-contacts'),
])
@endsection
