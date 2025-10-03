@extends('layouts.app')

@section('title', 'Update Supervisor')

@section('content')
<h1>Update Supervisor</h1>
@include('supervisor.form', ['action' => $schoolRoute('supervisors/' . $supervisor->id), 'method' => 'PUT', 'supervisor' => $supervisor])
@endsection
