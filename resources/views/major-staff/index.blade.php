@extends('layouts.app')

@section('title', 'Major Contacts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Major Contacts</h1>
    <a href="{{ $schoolRoute('major-contacts/create') }}" class="btn btn-primary">Create Contact</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Major</th>
                        <th scope="col">Staff Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Department</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                        <tr>
                            <td>{{ $assignment->major }}</td>
                            <td>{{ $assignment->name }}</td>
                            <td>{{ $assignment->email }}</td>
                            <td>{{ $assignment->phone ?? '—' }}</td>
                            <td>{{ $assignment->department ?? '—' }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ $schoolRoute('major-contacts/' . $assignment->id . '/update') }}" class="btn btn-sm btn-outline-secondary">Update</a>
                                <form action="{{ $schoolRoute('major-contacts/' . $assignment->id) }}" method="POST" onsubmit="return confirm('Delete this contact?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No contacts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center">
        <div>
            <span class="text-muted">Total Contacts: {{ $assignments->total() }}</span>
            <span class="text-muted ms-3">Page {{ $assignments->currentPage() }} out of {{ $assignments->lastPage() }}</span>
        </div>
        <div class="d-flex gap-2">
            @if ($assignments->onFirstPage())
                <span class="btn btn-outline-secondary disabled">Back</span>
            @else
                <a href="{{ $assignments->previousPageUrl() }}" class="btn btn-outline-secondary">Back</a>
            @endif

            @if ($assignments->hasMorePages())
                <a href="{{ $assignments->nextPageUrl() }}" class="btn btn-outline-secondary">Next</a>
            @else
                <span class="btn btn-outline-secondary disabled">Next</span>
            @endif
        </div>
    </div>
</div>
@endsection
