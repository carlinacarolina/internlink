@extends('layouts.app')

@section('title', 'Create Application')

@section('content')
<h1 class="mb-4">Create Application</h1>

<form action="{{ $schoolRoute('applications') }}" method="POST" class="card">
    @csrf
    <div class="card-body d-flex flex-column gap-3">
        @include('components.form-errors')

        @php
            $defaultStudentIds = collect(old('student_ids'));
            if ($defaultStudentIds->isEmpty() && $students->count() === 1) {
                $defaultStudentIds = collect([(int) $students->first()->id]);
            }
            $defaultStudentIds = $defaultStudentIds
                ->filter(fn ($value) => $value !== null && $value !== '')
                ->map(fn ($value) => (int) $value)
                ->values();
            $baseStudentId = $defaultStudentIds->first();
        @endphp

        <div id="student-section" data-is-student="{{ $isStudent ? '1' : '0' }}">
            <label for="student-id" class="form-label">Student Name</label>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <select name="student_ids[]" id="student-id" class="form-select tom-select">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected((string)($baseStudentId ?? '') === (string)$student->id)>{{ $student->name }}</option>
                    @endforeach
                </select>
                @unless($isStudent)
                    <button type="button" class="btn btn-outline-secondary" id="add-student-btn">+</button>
                @endunless
            </div>
            <div id="additional-students" class="mt-3 d-flex flex-column gap-2">
                @foreach($defaultStudentIds->slice(1) as $studentId)
                    <div class="d-flex gap-2 align-items-center additional-student">
                        <select name="student_ids[]" class="form-select tom-select student-select" data-selected="{{ $studentId }}"></select>
                        <button type="button" class="btn btn-outline-danger remove-student">-</button>
                    </div>
                @endforeach
            </div>
            @unless($isStudent)
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="apply-missing" name="apply_missing" value="1" @checked(old('apply_missing'))>
                    <label class="form-check-label" for="apply-missing">Apply to all students who do not yet have the application</label>
                </div>
            @endunless
        </div>

        <div>
            <label for="institution-id" class="form-label">Institution Name</label>
            <select name="institution_id" id="institution-id" class="form-select tom-select">
                <option value="">Select institution</option>
                @foreach($institutions as $institution)
                    <option value="{{ $institution->id }}" @selected((string)old('institution_id') === (string)$institution->id)>{{ $institution->name }}</option>
                @endforeach
            </select>
        </div>

        @php($showPeriodInput = old('institution_id') !== null && old('institution_id') !== '')
        <div id="period-section" class="{{ $showPeriodInput ? '' : 'd-none' }}">
            <label for="period-id" class="form-label">Period</label>
            <select
                name="period_id"
                id="period-id"
                class="form-select tom-select"
                data-tom-allow-empty="true"
                data-selected="{{ old('period_id', '') }}"
                data-placeholder="Select period"
            >
                <option value="">Select period</option>
            </select>
        </div>

        <div>
            <label for="status" class="form-label">Status Application</label>
            <select name="status" id="status" class="form-select">
                <option value="">Select status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status') === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>

        @if($canSetStudentAccess)
            <div>
                <label class="form-label d-block">Student Access</label>
                @php($studentAccess = old('student_access', 'any'))
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="student_access" id="student-access-true-create" value="true" @checked($studentAccess === 'true')>
                    <label class="form-check-label" for="student-access-true-create">True</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="student_access" id="student-access-false-create" value="false" @checked($studentAccess === 'false')>
                    <label class="form-check-label" for="student-access-false-create">False</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="student_access" id="student-access-any-create" value="any" @checked($studentAccess === 'any')>
                    <label class="form-check-label" for="student-access-any-create">Any</label>
                </div>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label for="planned-start-date" class="form-label">Planned Start Date</label>
                <input type="date" name="planned_start_date" id="planned-start-date" class="form-control" value="{{ old('planned_start_date') }}">
            </div>
            <div class="col-md-6">
                <label for="planned-end-date" class="form-label">Planned End Date</label>
                <input type="date" name="planned_end_date" id="planned-end-date" class="form-control" value="{{ old('planned_end_date') }}">
            </div>
        </div>

        <div class="border rounded p-3" id="staff-contact-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Staff Contact</h6>
                <span class="badge bg-light text-dark" id="staff-contact-major">—</span>
            </div>
            <div id="staff-contact-empty" class="text-muted">Select students with the same major to view the assigned staff contact.</div>
            <div id="staff-contact-details" class="d-none d-flex flex-column gap-1">
                <div><span class="fw-semibold">Name:</span> <span id="staff-contact-name"></span></div>
                <div><span class="fw-semibold">Email:</span> <span id="staff-contact-email"></span></div>
                <div><span class="fw-semibold">Phone:</span> <span id="staff-contact-phone"></span></div>
                <div><span class="fw-semibold">Supervisor Number:</span> <span id="staff-contact-number"></span></div>
            </div>
            <div id="staff-contact-missing" class="text-danger d-none">No staff contact is registered for the selected major. Please create one before continuing.</div>
        </div>

        <div>
            <label for="submitted-at" class="form-label">Submitted At</label>
            <input type="date" name="submitted_at" id="submitted-at" class="form-control" value="{{ old('submitted_at', now()->format('Y-m-d')) }}">
        </div>

        <div>
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end gap-2">
        <a href="{{ $schoolRoute('applications') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
@push('scripts')
<script>
(() => {
    const studentSection = document.getElementById('student-section');
    if (!studentSection) {
        return;
    }

    const baseSelect = document.getElementById('student-id');
    const addBtn = document.getElementById('add-student-btn');
    const container = document.getElementById('additional-students');
    const applyMissing = document.getElementById('apply-missing');
    const allStudents = @json($students->map(fn ($student) => [
        'id' => $student->id,
        'name' => $student->name,
        'major' => $student->major,
    ])->values());
    const missingStudents = @json($studentsWithoutApplication->map(fn ($student) => [
        'id' => $student->id,
        'name' => $student->name,
        'major' => $student->major,
    ])->values());
    const staffAssignments = @json($majorStaff->map(fn ($staff) => [
        'major' => $staff->major,
        'major_slug' => $staff->major_slug,
        'name' => $staff->name,
        'email' => $staff->email,
        'phone' => $staff->phone,
        'supervisor_number' => $staff->supervisor_number,
    ])->values());
    const institutionPeriods = @json($institutionPeriods);

    const studentLookup = new Map();
    allStudents.forEach((student) => {
        studentLookup.set(String(student.id), student);
    });
    missingStudents.forEach((student) => {
        if (!studentLookup.has(String(student.id))) {
            studentLookup.set(String(student.id), student);
        }
    });

    const staffLookup = new Map();
    staffAssignments.forEach((staff) => {
        staffLookup.set(String(staff.major_slug), staff);
    });

    function slugifyMajor(value) {
        if (!value) {
            return '';
        }
        const normalized = value
            .toString()
            .normalize('NFKD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');

        return normalized || 'major';
    }

    function determineMajorContext() {
        const selectedIds = getSelectedValues();
        if (!selectedIds.length) {
            return null;
        }

        const majors = [];
        for (const id of selectedIds) {
            const student = studentLookup.get(String(id));
            if (!student || !student.major || !student.major.trim()) {
                return { valid: false, reason: 'missing-major' };
            }
            majors.push(student.major);
        }

        const slugs = new Set(majors.map((major) => slugifyMajor(major)));
        if (slugs.size !== 1) {
            return { valid: false, reason: 'mixed-major' };
        }

        return {
            valid: true,
            major: majors[0],
            slug: Array.from(slugs)[0],
        };
    }

    const staffWrapper = document.getElementById('staff-contact-wrapper');
    const staffMajorBadge = document.getElementById('staff-contact-major');
    const staffEmpty = document.getElementById('staff-contact-empty');
    const staffDetails = document.getElementById('staff-contact-details');
    const staffMissing = document.getElementById('staff-contact-missing');
    const staffName = document.getElementById('staff-contact-name');
    const staffEmail = document.getElementById('staff-contact-email');
    const staffPhone = document.getElementById('staff-contact-phone');
    const staffNumber = document.getElementById('staff-contact-number');

    function refreshStaffContact() {
        if (!staffWrapper) {
            return;
        }

        const context = determineMajorContext();
        if (!context) {
            staffMajorBadge.textContent = '—';
            staffEmpty.classList.remove('d-none');
            staffEmpty.textContent = 'Select students with the same major to view the assigned staff contact.';
            staffDetails.classList.add('d-none');
            staffMissing.classList.add('d-none');
            return;
        }

        if (!context.valid) {
            staffMajorBadge.textContent = '—';
            staffDetails.classList.add('d-none');
            staffMissing.classList.add('d-none');
            staffEmpty.classList.remove('d-none');
            staffEmpty.textContent = context.reason === 'missing-major'
                ? 'One or more selected students do not have a major yet.'
                : 'Selected students belong to different majors.';
            return;
        }

        staffMajorBadge.textContent = context.major;
        const staff = staffLookup.get(String(context.slug));
        if (staff) {
            staffName.textContent = staff.name;
            staffEmail.textContent = staff.email || '—';
            staffPhone.textContent = staff.phone || '—';
            staffNumber.textContent = staff.supervisor_number || '—';
            staffDetails.classList.remove('d-none');
            staffMissing.classList.add('d-none');
            staffEmpty.classList.add('d-none');
        } else {
            staffDetails.classList.add('d-none');
            staffMissing.classList.remove('d-none');
            staffEmpty.classList.add('d-none');
        }
    }

    function destroyTomSelectInstance(select) {
        if (select && select.tomselect) {
            select.tomselect.destroy();
        }
    }

    function getSelectedValues(exclude = null) {
        const values = [];
        if (baseSelect && baseSelect.value && (!exclude || exclude !== baseSelect)) {
            values.push(String(baseSelect.value));
        }
        container.querySelectorAll('select.student-select').forEach((select) => {
            if (exclude && exclude === select) {
                return;
            }
            if (select.value) {
                values.push(String(select.value));
            }
        });
        return values;
    }

    function populateSelectOptions(select) {
        if (!select) {
            return;
        }

        const previous = select.dataset.current || select.value || '';
        const selectedValues = new Set(getSelectedValues(select).map(String));
        let desired = previous;
        if (desired && selectedValues.has(desired)) {
            desired = '';
        }

        destroyTomSelectInstance(select);
        select.innerHTML = '';

        allStudents.forEach((student) => {
            const value = String(student.id);
            if (selectedValues.has(value)) {
                return;
            }
            const option = document.createElement('option');
            option.value = student.id;
            option.textContent = student.name;
            select.appendChild(option);
        });

        if (desired && Array.from(select.options).some((option) => option.value === desired)) {
            select.value = desired;
        } else if (!select.value && select.options.length) {
            select.value = select.options[0].value;
        } else if (!select.options.length) {
            select.value = '';
        }

        select.dataset.current = select.value || '';
        window.initTomSelect && window.initTomSelect();
    }

    function updateAddButtonState() {
        if (!addBtn) {
            return;
        }
        const selected = new Set(getSelectedValues().map(String));
        const hasRemaining = allStudents.some((student) => !selected.has(String(student.id)));
        addBtn.disabled = !hasRemaining;
    }

    function populateAllSelects() {
        if (baseSelect) {
            populateSelectOptions(baseSelect);
        }
        container.querySelectorAll('select.student-select').forEach(populateSelectOptions);
        updateAddButtonState();
        refreshStaffContact();
    }

    function createSelect(preselected = null) {
        const wrapper = document.createElement('div');
        wrapper.className = 'd-flex gap-2 align-items-center additional-student';
        const select = document.createElement('select');
        select.name = 'student_ids[]';
        select.className = 'form-select tom-select student-select';
        select.dataset.current = preselected ? String(preselected) : '';
        if (preselected) {
            select.dataset.selected = String(preselected);
        }
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline-danger remove-student';
        removeBtn.textContent = '-';
        wrapper.appendChild(select);
        wrapper.appendChild(removeBtn);
        container.appendChild(wrapper);
        populateAllSelects();
        return select;
    }

    container.addEventListener('click', (event) => {
        if (!event.target.classList.contains('remove-student')) {
            return;
        }
        event.target.closest('.additional-student').remove();
        populateAllSelects();
    });

    container.addEventListener('change', (event) => {
        const target = event.target;
        if (!target.classList.contains('student-select')) {
            return;
        }
        target.dataset.current = target.value;
        populateAllSelects();
    });

    if (baseSelect) {
        baseSelect.dataset.current = baseSelect.value || '';
        baseSelect.addEventListener('change', () => {
            baseSelect.dataset.current = baseSelect.value || '';
            populateAllSelects();
        });
    }

    if (addBtn) {
        addBtn.addEventListener('click', () => {
            createSelect();
        });
    }

    if (applyMissing) {
        applyMissing.addEventListener('change', () => {
            if (!applyMissing.checked) {
                updateAddButtonState();
                refreshStaffContact();
                return;
            }
            const context = determineMajorContext();
            if (!context || !context.valid) {
                applyMissing.checked = false;
                refreshStaffContact();
                alert('Select at least one student with a defined major before applying this helper.');
                return;
            }
            const selectedValues = new Set(getSelectedValues().map(String));
            missingStudents.forEach((student) => {
                const id = String(student.id);
                if (selectedValues.has(id)) {
                    return;
                }
                if (slugifyMajor(student.major || '') !== context.slug) {
                    return;
                }
                createSelect(student.id);
                selectedValues.add(id);
            });
            populateAllSelects();
        });
    }

    container.querySelectorAll('select.student-select').forEach((select) => {
        if (select.dataset.selected) {
            select.dataset.current = select.dataset.selected;
        }
    });

    populateAllSelects();
    refreshStaffContact();

    const institutionSelect = document.getElementById('institution-id');
    const periodSection = document.getElementById('period-section');
    const periodSelect = document.getElementById('period-id');
    const initialPeriodId = periodSelect ? (periodSelect.dataset.selected || '') : '';

    function rebuildPeriodOptions(options, selectedValue) {
        if (!periodSelect) {
            return;
        }

        const normalizedSelected = options.some((item) => String(item.id) === String(selectedValue))
            ? String(selectedValue)
            : '';

        if (periodSelect.tomselect) {
            periodSelect.tomselect.destroy();
        }

        periodSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Select period';
        periodSelect.appendChild(placeholder);

        options.forEach((item) => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.label;
            periodSelect.appendChild(option);
        });

        if (normalizedSelected) {
            periodSelect.value = normalizedSelected;
        }

        window.initTomSelect && window.initTomSelect();

        if (periodSelect.tomselect) {
            if (normalizedSelected) {
                periodSelect.tomselect.setValue(normalizedSelected, true);
            } else {
                periodSelect.tomselect.clear(true);
            }
        }

        periodSelect.dataset.selected = normalizedSelected;
    }

    function updatePeriodSection(useInitial = false) {
        if (!institutionSelect || !periodSection || !periodSelect) {
            return;
        }

        const institutionId = institutionSelect.value;
        if (!institutionId) {
            periodSection.classList.add('d-none');
            rebuildPeriodOptions([], '');
            return;
        }

        const options = institutionPeriods[String(institutionId)] || [];
        periodSection.classList.remove('d-none');
        rebuildPeriodOptions(options, useInitial ? initialPeriodId : '');
    }

    if (institutionSelect && periodSection && periodSelect) {
        updatePeriodSection(true);
        institutionSelect.addEventListener('change', () => {
            periodSelect.dataset.selected = '';
            updatePeriodSection(false);
        });
        periodSelect.addEventListener('change', () => {
            periodSelect.dataset.selected = periodSelect.value || '';
        });
    }
})();
</script>
@endpush
@endsection
