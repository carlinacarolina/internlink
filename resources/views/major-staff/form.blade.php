<form action="{{ $action }}" method="POST" class="card">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="card-body d-flex flex-column gap-3">
        @include('components.form-errors')

        <div>
            <label for="major" class="form-label">Major</label>
            <select name="major_id" id="major" class="form-select tom-select" required>
                <option value="">Select major</option>
                @foreach($majors as $major)
                    <option value="{{ $major->id }}" @selected(old('major_id', optional($assignment)->major_id) == $major->id)>
                        {{ $major->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="supervisor-id" class="form-label">Staff (Supervisor)</label>
            <select name="supervisor_id" id="supervisor-id" class="form-select tom-select" required>
                <option value="">Select supervisor</option>
                @foreach($supervisors as $supervisor)
                    <option value="{{ $supervisor->id }}" @selected((string)old('supervisor_id', optional($assignment)->supervisor_id) === (string)$supervisor->id)>
                        {{ $supervisor->name }}
                        @if($supervisor->department)
                            ({{ $supervisor->department }})
                        @endif
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="card-footer d-flex gap-2 justify-content-end">
        <a href="{{ $cancel }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
