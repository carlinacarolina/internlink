@extends('layouts.app')

@section('title', 'Schools')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Schools</h1>
    <div class="d-flex align-items-center gap-2">
        <form method="get" action="{{ url()->current() }}" id="school-search-form" class="search-form">
            <div class="input-group">
                <input type="search" name="q" id="school-search-input" class="form-control" placeholder="Search schools" value="{{ request('q') }}" autocomplete="off">
                <button class="btn btn-primary" type="submit" id="school-search-submit">Search</button>
            </div>
            @foreach(request()->except(['q','page']) as $param => $value)
                <input type="hidden" name="{{ $param }}" value="{{ $value }}">
            @endforeach
        </form>
        <a href="/schools/create" class="btn btn-success">Create School</a>
        <button class="btn btn-outline-secondary position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#schoolFilter" aria-controls="schoolFilter">
            Filter
            @if(count($filters))
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">{{ count($filters) }}</span>
            @endif
        </button>
    </div>
</div>

@if(count($filters))
    <div class="d-flex flex-wrap gap-2 mb-3">
        @foreach($filters as $filter)
            @php($query = request()->except([$filter['param'], 'page']))
            @php($queryString = http_build_query(array_filter($query, fn($value) => $value !== null && $value !== '')))
            <a href="{{ url()->current() . ($queryString ? '?' . $queryString : '') }}" class="btn btn-sm btn-outline-primary">
                {{ $filter['label'] }}
            </a>
        @endforeach
    </div>
@endif

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Phone</th>
                <th scope="col">Email</th>
                <th scope="col">Website</th>
                <th scope="col">Updated At</th>
                <th scope="col" class="text-nowrap">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($schools as $school)
            <tr>
                <td>{{ $school->name }}</td>
                <td>{{ $school->phone }}</td>
                <td>{{ $school->email }}</td>
                <td>
                    @if($school->website)
                        <a href="{{ $school->website }}" target="_blank" rel="noopener">Visit</a>
                    @else
                        —
                    @endif
                </td>
                <td>{{ $school->updated_at }}</td>
                <td class="text-nowrap">
                    <a href="{{ url('/' . rawurlencode($school->code)) }}" class="btn btn-sm btn-info">Realm</a>
                    <a href="/schools/{{ $school->id }}/read" class="btn btn-sm btn-outline-secondary">Read</a>
                    <a href="/schools/{{ $school->id }}/update" class="btn btn-sm btn-warning">Update</a>
                    <form action="/schools/{{ $school->id }}" method="POST" class="d-inline school-delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No schools found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<p class="text-muted mb-1">Total Schools: {{ $schools->total() }}</p>
<p class="text-muted">Page {{ $schools->currentPage() }} out of {{ $schools->lastPage() }}</p>

<div class="d-flex justify-content-between align-items-center mb-4">
    @if ($schools->onFirstPage())
        <span class="btn btn-outline-secondary disabled">Back</span>
    @else
        <a href="{{ $schools->previousPageUrl() }}" class="btn btn-outline-secondary">Back</a>
    @endif

    @if ($schools->hasMorePages())
        <a href="{{ $schools->nextPageUrl() }}" class="btn btn-outline-secondary">Next</a>
    @else
        <span class="btn btn-outline-secondary disabled">Next</span>
    @endif
</div>

@php($resetBase = request('q') ? url()->current() . '?q=' . urlencode(request('q')) : url()->current())
<div class="offcanvas offcanvas-end" tabindex="-1" id="schoolFilter" aria-labelledby="schoolFilterLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="schoolFilterLabel">Filter Schools</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form method="get" id="school-filter-form" class="d-flex flex-column gap-3">
            @if(request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
            <div>
                <label class="form-label" for="filter-school-name">Name</label>
                <input type="text" class="form-control" id="filter-school-name" name="name" value="{{ request('name') }}">
            </div>
            <div>
                <label class="form-label" for="filter-school-email">Email</label>
                <input type="text" class="form-control" id="filter-school-email" name="email" value="{{ request('email') }}">
            </div>
            <div>
                <label class="form-label" for="filter-school-phone">Phone</label>
                <input type="text" class="form-control" id="filter-school-phone" name="phone" value="{{ request('phone') }}">
            </div>
            @php($hasWebsite = request('has_website'))
            <div>
                <span class="form-label d-block">Has Website?</span>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="has_website" id="filter-school-website-true" value="true" {{ $hasWebsite === 'true' ? 'checked' : '' }}>
                    <label class="form-check-label" for="filter-school-website-true">True</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="has_website" id="filter-school-website-false" value="false" {{ $hasWebsite === 'false' ? 'checked' : '' }}>
                    <label class="form-check-label" for="filter-school-website-false">False</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="has_website" id="filter-school-website-any" value="any" {{ !in_array($hasWebsite, ['true', 'false'], true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="filter-school-website-any">Any</label>
                </div>
            </div>
            <div>
                <label class="form-label" for="filter-school-sort">Sort By</label>
                <select class="form-select" id="filter-school-sort" name="sort">
                    @php($selectedSort = request('sort', 'created_at:desc'))
                    <option value="created_at:desc" {{ $selectedSort === 'created_at:desc' ? 'selected' : '' }}>Newest</option>
                    <option value="created_at:asc" {{ $selectedSort === 'created_at:asc' ? 'selected' : '' }}>Oldest</option>
                    <option value="name:asc" {{ $selectedSort === 'name:asc' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="name:desc" {{ $selectedSort === 'name:desc' ? 'selected' : '' }}>Name Z-A</option>
                    <option value="updated_at:desc" {{ $selectedSort === 'updated_at:desc' ? 'selected' : '' }}>Recently Updated</option>
                    <option value="updated_at:asc" {{ $selectedSort === 'updated_at:asc' ? 'selected' : '' }}>Least Recently Updated</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary flex-fill" id="school-filter-reset" data-reset-url="{{ $resetBase }}">Reset</button>
                <button type="submit" class="btn btn-primary flex-fill">Apply</button>
            </div>
        </form>
    </div>
</div>

<script>
const schoolSearchForm = document.getElementById('school-search-form');
const schoolFilterForm = document.getElementById('school-filter-form');
const schoolFilterReset = document.getElementById('school-filter-reset');

function submitSchoolSearch(event) {
    event.preventDefault();
    const formData = new FormData(schoolSearchForm);
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
        if (key === 'q' && value.trim() === '') {
            continue;
        }
        params.append(key, value);
    }
    params.delete('page');
    const query = params.toString();
    window.location = schoolSearchForm.getAttribute('action') + (query ? '?' + query : '');
}

if (schoolSearchForm) {
    schoolSearchForm.addEventListener('submit', submitSchoolSearch);
}

if (schoolFilterForm) {
    schoolFilterForm.addEventListener('submit', () => {
        const optionalRadios = schoolFilterForm.querySelectorAll('input[type="radio"][value="any"]');
        optionalRadios.forEach(radio => {
            if (radio.checked) {
                radio.disabled = true;
            }
        });
    });
}

if (schoolFilterReset) {
    schoolFilterReset.addEventListener('click', () => {
        window.location = schoolFilterReset.dataset.resetUrl;
    });
}

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
