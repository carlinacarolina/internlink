@extends('layouts.app')

@section('title', 'Update Application')

@section('content')
<h1 class="mb-4">Update Application</h1>

<form action="{{ $schoolRoute('applications/' . $application->id) }}" method="POST" class="card">
    @csrf
    @method('PUT')
    <div class="card-body d-flex flex-column gap-3">
        @include('components.form-errors')

        <div id="student-section"
             data-base-id="{{ $application->student_id }}"
             data-base-name="{{ $application->student_name }}"
             data-base-major="{{ $application->student_major }}"
             data-is-student="{{ $isStudent ? '1' : '0' }}">
            <input type="hidden" name="student_ids[]" value="{{ $application->student_id }}">
            <label class="form-label">Student Name</label>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <select class="form-select tom-select" disabled>
                    <option selected>{{ $application->student_name }}</option>
                </select>
                @unless($isStudent)
                    <button type="button" class="btn btn-outline-secondary" id="add-student-btn">+</button>
                @endunless
            </div>
            <div id="additional-students" class="mt-3 d-flex flex-column gap-2">
                @php($oldStudents = collect(old('student_ids', [$application->student_id]))->map(fn($id) => (int) $id)->filter(fn($id) => $id !== (int) $application->student_id))
                @foreach($oldStudents as $studentId)
                    <div class="d-flex gap-2 align-items-center additional-student">
                        <select name="student_ids[]" class="form-select tom-select student-select" data-selected="{{ $studentId }}"></select>
                        <button type="button" class="btn btn-outline-danger remove-student">-</button>
                    </div>
                @endforeach
            </div>
            @unless($isStudent)
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="apply-all" name="apply_all" value="1" @checked(old('apply_all'))>
                    <label class="form-check-label" for="apply-all">Apply to all applications with the same institution</label>
                </div>
            @endunless
        </div>

        <div>
            <label for="institution-id" class="form-label">Institution Name</label>
            <select name="institution_id" id="institution-id" class="form-select tom-select">
                @foreach($institutions as $institution)
                    <option value="{{ $institution->id }}" @selected((string)old('institution_id', $application->institution_id) === (string)$institution->id)>{{ $institution->name }}</option>
                @endforeach
            </select>
        </div>

        @php($selectedInstitution = old('institution_id', $application->institution_id))
        <div id="period-section" class="{{ $selectedInstitution ? '' : 'd-none' }}">
            <label for="period-id" class="form-label">Period</label>
            <select
                name="period_id"
                id="period-id"
                class="form-select tom-select"
                data-tom-allow-empty="true"
                data-selected="{{ old('period_id', $application->period_id) }}"
                data-placeholder="Select period"
            >
                <option value="">Select period</option>
            </select>
        </div>

        <div>
            <label for="status" class="form-label">Status Application</label>
            <select name="status" id="status" class="form-select">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $application->status) === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>

        @if($canSetStudentAccess)
            <div>
                <label class="form-label d-block">Student Access</label>
                @php($studentAccess = old('student_access', $application->student_access ? 'true' : 'false'))
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="student_access" id="student-access-true-update" value="true" @checked($studentAccess === 'true')>
                    <label class="form-check-label" for="student-access-true-update">True</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="student_access" id="student-access-false-update" value="false" @checked($studentAccess === 'false')>
                    <label class="form-check-label" for="student-access-false-update">False</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="student_access" id="student-access-any-update" value="any" @checked($studentAccess === 'any')>
                    <label class="form-check-label" for="student-access-any-update">Any</label>
                </div>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label for="planned-start-date" class="form-label">Planned Start Date</label>
                <input type="date" name="planned_start_date" id="planned-start-date" class="form-control" value="{{ old('planned_start_date', $application->planned_start_date) }}">
            </div>
            <div class="col-md-6">
                <label for="planned-end-date" class="form-label">Planned End Date</label>
                <input type="date" name="planned_end_date" id="planned-end-date" class="form-control" value="{{ old('planned_end_date', $application->planned_end_date) }}">
            </div>
        </div>

        <div
            class="border rounded p-3"
            id="staff-contact-wrapper"
            data-initial-major="{{ $application->student_major }}"
            data-initial-name="{{ $application->staff_name }}"
            data-initial-email="{{ $application->staff_email }}"
            data-initial-phone="{{ $application->staff_phone }}"
            data-initial-number="{{ $application->staff_supervisor_number }}"
        >
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Staff Contact</h6>
                <span class="badge bg-light text-dark" id="staff-contact-major">{{ $application->student_major ?? '—' }}</span>
            </div>
            <div id="staff-contact-empty" class="text-muted">
                Select students with the same major to view the assigned staff contact.
            </div>
            <div id="staff-contact-details" class="d-none d-flex flex-column gap-1">
                <div><span class="fw-semibold">Name:</span> <span id="staff-contact-name">—</span></div>
                <div><span class="fw-semibold">Email:</span> <span id="staff-contact-email">—</span></div>
                <div><span class="fw-semibold">Phone:</span> <span id="staff-contact-phone">—</span></div>
                <div><span class="fw-semibold">Supervisor Number:</span> <span id="staff-contact-number">—</span></div>
            </div>
            <div id="staff-contact-missing" class="text-danger d-none">No staff contact is registered for the selected major. Please create one before continuing.</div>
        </div>

        <div>
            <label for="submitted-at" class="form-label">Submitted At</label>
            <input type="date" name="submitted_at" id="submitted-at" class="form-control" value="{{ old('submitted_at', \Illuminate\Support\Carbon::parse($application->submitted_at)->format('Y-m-d')) }}">
        </div>

        <div>
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control" rows="4">{{ old('notes', $application->application_notes) }}</textarea>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end gap-2">
        <a href="{{ $schoolRoute('applications/' . $application->id . '/read') }}" class="btn btn-outline-secondary">Cancel</a>
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

    const baseStudentId = String(studentSection.dataset.baseId || '');
    const baseStudentName = studentSection.dataset.baseName || '';
    const baseStudentMajor = studentSection.dataset.baseMajor || '';
    const isStudent = studentSection.dataset.isStudent === '1';
    const allStudents = @json($allStudentsForInstitution->map(fn($student) => [
        'id' => $student->student_id,
        'name' => $student->student_name,
        'major' => $student->student_major,
    ]));
    const staffAssignments = @json($majorStaff->map(fn ($staff) => [
        'major' => $staff->major,
        'major_slug' => $staff->major_slug,
        'name' => $staff->name,
        'email' => $staff->email,
        'phone' => $staff->phone,
        'supervisor_number' => $staff->supervisor_number,
    ])->values());
    const institutionPeriods = @json($institutionPeriods);
    const addBtn = document.getElementById('add-student-btn');
    const container = document.getElementById('additional-students');
    const applyAll = document.getElementById('apply-all');

    if (baseStudentId && !allStudents.some(student => String(student.id) === baseStudentId)) {
        allStudents.push({ id: baseStudentId, name: baseStudentName, major: baseStudentMajor });
    }

    const studentLookup = new Map();
    allStudents.forEach((student) => {
        studentLookup.set(String(student.id), student);
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

    function getSelectedValues(exclude = null) {
        const values = [];
        if (baseStudentId) {
            values.push(baseStudentId);
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

    const staffMajorBadge = document.getElementById('staff-contact-major');
    const staffEmpty = document.getElementById('staff-contact-empty');
    const staffDetails = document.getElementById('staff-contact-details');
    const staffMissing = document.getElementById('staff-contact-missing');
    const staffName = document.getElementById('staff-contact-name');
    const staffEmail = document.getElementById('staff-contact-email');
    const staffPhone = document.getElementById('staff-contact-phone');
    const staffNumber = document.getElementById('staff-contact-number');
    const staffWrapper = document.getElementById('staff-contact-wrapper');

    function renderStaffDetails(staff, major) {
        if (!staffWrapper) {
            return;
        }

        if (!major) {
            staffMajorBadge.textContent = '—';
        } else {
            staffMajorBadge.textContent = major;
        }

        if (!staff) {
            staffDetails.classList.add('d-none');
            staffMissing.classList.remove('d-none');
            staffEmpty.classList.add('d-none');
            return;
        }

        staffName.textContent = staff.name;
        staffEmail.textContent = staff.email || '—';
        staffPhone.textContent = staff.phone || '—';
        staffNumber.textContent = staff.supervisor_number || '—';
        staffDetails.classList.remove('d-none');
        staffMissing.classList.add('d-none');
        staffEmpty.classList.add('d-none');
    }

    function refreshStaffContact() {
        if (!staffWrapper) {
            return;
        }

        const context = determineMajorContext();
        if (!context) {
            staffMajorBadge.textContent = staffWrapper.dataset.initialMajor || '—';
            const initialName = staffWrapper.dataset.initialName || '';
            if (initialName) {
                renderStaffDetails({
                    name: initialName,
                    email: staffWrapper.dataset.initialEmail || '—',
                    phone: staffWrapper.dataset.initialPhone || '—',
                    supervisor_number: staffWrapper.dataset.initialNumber || '—',
                }, staffWrapper.dataset.initialMajor || '—');
            } else {
                staffDetails.classList.add('d-none');
                staffMissing.classList.add('d-none');
                staffEmpty.classList.remove('d-none');
                staffEmpty.textContent = 'Select students with the same major to view the assigned staff contact.';
            }
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

        const staff = staffLookup.get(String(context.slug));
        if (staff) {
            renderStaffDetails(staff, context.major);
        } else {
            staffMajorBadge.textContent = context.major;
            staffDetails.classList.add('d-none');
            staffMissing.classList.remove('d-none');
            staffEmpty.classList.add('d-none');
        }
    }

    function buildOption(select, student, selectedIds) {
        if (selectedIds.includes(String(student.id)) && String(student.id) !== select.dataset.current) {
            return;
        }
        const option = document.createElement('option');
        option.value = student.id;
        option.textContent = student.name;
        if (String(student.id) === select.dataset.current) {
            option.selected = true;
        }
        select.appendChild(option);
    }

    function populateSelect(select) {
        destroyTomSelectInstance(select);
        const selected = getSelectedValues().filter((id) => id !== String(baseStudentId));
        select.innerHTML = '';
        allStudents.forEach((student) => buildOption(select, student, selected.filter(id => id !== select.dataset.current)));
        if (!select.value && select.options.length) {
            select.value = select.options[0].value;
        }
        select.dataset.current = select.value;
        window.initTomSelect && window.initTomSelect();
    }

    function destroyTomSelectInstance(select) {
        if (select && select.tomselect) {
            select.tomselect.destroy();
        }
    }

    function populateAllSelects() {
        container.querySelectorAll('select.student-select').forEach(populateSelect);
        updateAddButtonState();
        refreshStaffContact();
    }

    function createSelect(selectedId = null) {
        const wrapper = document.createElement('div');
        wrapper.className = 'd-flex gap-2 align-items-center additional-student';
        const select = document.createElement('select');
        select.name = 'student_ids[]';
        select.className = 'form-select tom-select student-select';
        select.dataset.current = selectedId ? String(selectedId) : '';
        if (selectedId) {
            select.dataset.selected = String(selectedId);
        }
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline-danger remove-student';
        removeBtn.textContent = '-';
        wrapper.appendChild(select);
        wrapper.appendChild(removeBtn);
        container.appendChild(wrapper);
        populateSelect(select);
    }

    function updateAddButtonState() {
        if (!addBtn) {
            return;
        }
        const selected = new Set(getSelectedValues());
        const remaining = allStudents.filter((student) => !selected.has(String(student.id)));
        addBtn.disabled = remaining.length === 0;
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

    if (addBtn) {
        addBtn.addEventListener('click', () => {
            createSelect();
            populateAllSelects();
        });
    }

    if (applyAll) {
        applyAll.addEventListener('change', () => {
            if (!applyAll.checked) {
                updateAddButtonState();
                refreshStaffContact();
                return;
            }

            const context = determineMajorContext();
            if (!context || !context.valid) {
                applyAll.checked = false;
                refreshStaffContact();
                alert('Select students with a defined major before applying this helper.');
                return;
            }

            const selected = new Set(getSelectedValues());
            allStudents.forEach((student) => {
                if (slugifyMajor(student.major || '') !== context.slug) {
                    return;
                }
                const id = String(student.id);
                if (!selected.has(id)) {
                    createSelect(student.id);
                    selected.add(id);
                }
            });
            populateAllSelects();
        });
    }

    if (!container.children.length && !isStudent && addBtn) {
        addBtn.disabled = allStudents.filter(student => String(student.id) !== baseStudentId).length === 0;
    }

    container.querySelectorAll('select.student-select').forEach((select) => {
        const preset = select.dataset.selected;
        if (preset) {
            select.dataset.current = preset;
        }
    });

    populateAllSelects();

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
