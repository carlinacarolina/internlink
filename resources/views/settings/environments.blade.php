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
                
                <!-- Major/Department Management -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="h6 mb-0">Major/Department Management</h3>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addMajorModal">
                            <i class="bi bi-plus-circle"></i> Add Major/Department
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Manage the list of majors and departments available for students and supervisors in this school.</p>
                        
                        @if($majors->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Students</th>
                                            <th>Supervisors</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($majors as $major)
                                            <tr>
                                                <td>{{ $major->name }}</td>
                                                <td>
                                                    <span class="badge {{ $major->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $major->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>{{ $major->students_count ?? 0 }}</td>
                                                <td>{{ $major->supervisors_count ?? 0 }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-primary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editMajorModal"
                                                                data-major-id="{{ $major->id }}"
                                                                data-major-name="{{ $major->name }}"
                                                                data-major-active="{{ $major->is_active }}">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        @if(($major->students_count ?? 0) == 0 && ($major->supervisors_count ?? 0) == 0)
                                                            <button type="button" class="btn btn-outline-danger" 
                                                                    onclick="deleteMajor({{ $major->id }}, '{{ $major->name }}')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-bookmark text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">No majors/departments configured yet.</p>
                                <p class="text-muted">Add your first major or department to get started.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="border rounded p-4 bg-light text-muted">
                    Placeholder for upcoming environment configuration.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Major Modal -->
<div class="modal fade" id="addMajorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ $schoolRoute('settings/environments/majors') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Major/Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="major-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="major-name" name="name" required>
                        <div class="form-text">Enter the full name of the major or department (e.g., "Teknik Informatika")</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="major-active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="major-active">
                                Active
                            </label>
                        </div>
                        <div class="form-text">Active majors/departments will be available in forms</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Major/Department</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Major Modal -->
<div class="modal fade" id="editMajorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editMajorForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Major/Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-major-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit-major-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit-major-active" name="is_active" value="1">
                            <label class="form-check-label" for="edit-major-active">
                                Active
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Major/Department</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit modal
    const editModal = document.getElementById('editMajorModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const majorId = button.getAttribute('data-major-id');
        const majorName = button.getAttribute('data-major-name');
        const majorActive = button.getAttribute('data-major-active') === '1';
        
        const form = document.getElementById('editMajorForm');
        form.action = '{{ $schoolRoute("settings/environments/majors") }}/' + majorId;
        
        document.getElementById('edit-major-name').value = majorName;
        document.getElementById('edit-major-active').checked = majorActive;
    });
});

function deleteMajor(majorId, majorName) {
    if (confirm('Are you sure you want to delete "' + majorName + '"? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ $schoolRoute("settings/environments/majors") }}/' + majorId;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
