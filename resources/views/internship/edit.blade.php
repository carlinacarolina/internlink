@extends('layouts.app')

@section('title', 'Update Application')

@section('content')
<h1>Update Application</h1>
@include('internship.form', [
    'action' => $schoolRoute('internships/' . $internship->id),
    'method' => 'PUT',
    'internship' => $internship,
    'applications' => $applications,
    'statuses' => $statuses,
    'selected' => [$internship->application_id],
    'lockedFirst' => true,
    'cancelUrl' => $schoolRoute('internships'),
])
@endsection
