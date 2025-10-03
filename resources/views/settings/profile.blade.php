@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        @include('settings.partials.nav', [
            'active' => 'profile',
            'environmentAvailable' => $environmentAvailable,
        ])
    </div>
    <div class="col-lg-9">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Edit Profile</h2>
                    <span class="badge bg-light text-dark text-capitalize">{{ $role }}</span>
                </div>
                <p class="text-muted">Update your personal information. Changes are saved immediately after you submit the form.</p>
                <form action="{{ $schoolRoute('settings/profile') }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')
                    @include('components.form-errors')

                    <div class="col-12 col-md-6">
                        <label for="profile-name" class="form-label">Name</label>
                        <input type="text" id="profile-name" name="name" class="form-control" value="{{ old('name', $formData['name']) }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="profile-email" class="form-label">Email</label>
                        <input type="email" id="profile-email" name="email" class="form-control" value="{{ old('email', $formData['email']) }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="profile-phone" class="form-label">Phone</label>
                        <input type="text" id="profile-phone" name="phone" class="form-control" value="{{ old('phone', $formData['phone']) }}" inputmode="tel">
                    </div>

                    @if($role === 'student')
                        <div class="col-12 col-md-6">
                            <label for="profile-student-number" class="form-label">Student Number</label>
                            <input type="text" id="profile-student-number" name="student_number" class="form-control" value="{{ old('student_number', $formData['student_number']) }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="profile-national-sn" class="form-label">National Student Number</label>
                            <input type="text" id="profile-national-sn" name="national_sn" class="form-control" value="{{ old('national_sn', $formData['national_sn']) }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="profile-major" class="form-label">Major</label>
                            <select id="profile-major" name="major_id" class="form-select tom-select" required>
                                <option value="">Select major</option>
                                @foreach($majors as $major)
                                    <option value="{{ $major->id }}" @selected(old('major_id', $formData['major_id']) == $major->id)>
                                        {{ $major->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="profile-class" class="form-label">Class</label>
                            <input type="text" id="profile-class" name="class" class="form-control" value="{{ old('class', $formData['class']) }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="profile-batch" class="form-label">Batch</label>
                            <input type="number" id="profile-batch" name="batch" class="form-control" value="{{ old('batch', $formData['batch']) }}" min="1900" max="2100" step="1" required>
                        </div>
                        <div class="col-12">
                            <label for="profile-notes" class="form-label">Notes</label>
                            <textarea id="profile-notes" name="notes" class="form-control" rows="4">{{ old('notes', $formData['notes']) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label for="profile-photo" class="form-label">Photo URL</label>
                            <input type="text" id="profile-photo" name="photo" class="form-control" value="{{ old('photo', $formData['photo']) }}">
                        </div>
                    @elseif($role === 'supervisor')
                        <div class="col-12 col-md-6">
                            <label for="profile-supervisor-number" class="form-label">Supervisor Number</label>
                            <input type="text" id="profile-supervisor-number" name="supervisor_number" class="form-control" value="{{ old('supervisor_number', $formData['supervisor_number']) }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="profile-department" class="form-label">Department</label>
                            <select id="profile-department" name="department_id" class="form-select tom-select" required>
                                <option value="">Select department</option>
                                @foreach($majors as $major)
                                    <option value="{{ $major->id }}" @selected(old('department_id', $formData['department_id']) == $major->id)>
                                        {{ $major->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="profile-notes" class="form-label">Notes</label>
                            <textarea id="profile-notes" name="notes" class="form-control" rows="4">{{ old('notes', $formData['notes']) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label for="profile-photo" class="form-label">Photo URL</label>
                            <input type="text" id="profile-photo" name="photo" class="form-control" value="{{ old('photo', $formData['photo']) }}">
                        </div>
                    @endif

                    <div class="col-12 d-flex gap-2">
                        <a href="{{ $schoolRoute('settings/profile') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        @if(!empty($profileDetails))
            <div class="card">
                <div class="card-body">
                    <h2 class="h6 mb-3">Profile Overview</h2>
                    <dl class="row mb-0">
                        @foreach($profileDetails as $label => $value)
                            <dt class="col-sm-4 col-lg-3">{{ $label }}</dt>
                            <dd class="col-sm-8 col-lg-9">{{ $value }}</dd>
                        @endforeach
                    </dl>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
