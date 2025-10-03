@extends('layouts.app')

@section('title', 'Update Application')

@section('content')
<h1 class="mb-4">Update Application</h1>

<form action="{{ $schoolRoute('applications/' . $application->id) }}" method="POST" class="card" id="application-form">
    @csrf
    @method('PUT')
    <div class="card-body d-flex flex-column gap-3">
        @include('components.form-errors')

        <div id="student-section"
             data-base-id="{{ $application->student_id }}"
             data-base-name="{{ $application->student_name }}"
             data-base-major="{{ $application->student_major }}"
             data-base-major-id="{{ $application->student_major_id }}"
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

        <div class="border rounded p-3 bg-light" id="period-helper">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Detected Period</h6>
                <span class="badge bg-secondary" id="period-helper-label">{{ $application->period_year }} • Term {{ $application->period_term }}</span>
            </div>
            <p class="mb-0 text-muted" id="period-helper-info">Select an institution and planned start date to detect the appropriate period.</p>
            <div class="alert alert-warning d-none mt-3" id="period-helper-alert" role="alert">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span id="period-helper-alert-text">Quota missing for the detected period. Create one before saving.</span>
                    <button type="button" class="btn btn-sm btn-warning" id="period-helper-modal-button">Create Quota</button>
                </div>
            </div>
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
            data-initial-major-id="{{ $application->student_major_id }}"
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
        <button type="submit" class="btn btn-primary" id="application-submit">Save</button>
    </div>
</form>

<div class="modal fade" id="quotaModal" tabindex="-1" aria-labelledby="quotaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="quota-form" action="{{ $schoolRoute('applications/resolve-period') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="quotaModalLabel">Create Period Quota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="institution_id" id="quota-institution-id" value="">
                    <input type="hidden" name="planned_start_date" id="quota-start-date" value="">
                    <p class="mb-3">Quota will be created for <span class="fw-semibold" id="quota-modal-period">—</span>.</p>
                    <div class="mb-3">
                        <label for="quota-value" class="form-label">Quota</label>
                        <input type="number" min="0" name="quota" id="quota-value" class="form-control" required>
                    </div>
                    <div class="alert alert-danger d-none" id="quota-modal-error" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    const baseStudentMajorId = Number(studentSection.dataset.baseMajorId ?? NaN);
    const isStudent = studentSection.dataset.isStudent === '1';
    const allStudents = @json($allStudentsForInstitutionJson ?? []);
    const staffAssignments = @json($majorStaffJson ?? []);
    const institutionQuotaData = Object.assign({}, @json($institutionQuotaMap ?? []));
    const addBtn = document.getElementById('add-student-btn');
    const container = document.getElementById('additional-students');
    const applyAll = document.getElementById('apply-all');

    if (baseStudentId && !allStudents.some(student => String(student.id) === baseStudentId)) {
        allStudents.push({
            id: baseStudentId,
            name: baseStudentName,
            major: baseStudentMajor,
            major_id: Number.isNaN(baseStudentMajorId) ? null : baseStudentMajorId,
        });
    }

    const studentLookup = new Map();
    allStudents.forEach((student) => {
        studentLookup.set(String(student.id), student);
    });

    const staffLookup = new Map();
    staffAssignments.forEach((staff) => {
        const majorId = Number(staff.major_id);
        if (!Number.isNaN(majorId)) {
            staffLookup.set(majorId, staff);
        }
    });

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
            const majorId = Number(student?.major_id ?? NaN);
            if (!student || Number.isNaN(majorId)) {
                return { valid: false, reason: 'missing-major' };
            }
            majors.push({ id: majorId, label: student.major });
        }

        const uniqueIds = Array.from(new Set(majors.map((item) => item.id)));
        if (uniqueIds.length !== 1) {
            return { valid: false, reason: 'mixed-major' };
        }

        return {
            valid: true,
            majorId: uniqueIds[0],
            majorLabel: majors[0].label,
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

    function renderStaffDetails(staff, majorLabel) {
        if (!staffWrapper) {
            return;
        }

        if (!majorLabel) {
            staffMajorBadge.textContent = '—';
        } else {
            staffMajorBadge.textContent = majorLabel;
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

        const staff = staffLookup.get(context.majorId);
        if (staff) {
            renderStaffDetails(staff, context.majorLabel);
        } else {
            staffMajorBadge.textContent = context.majorLabel || '—';
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
                const studentMajorId = (student.major_id ?? null) === null ? NaN : Number(student.major_id);
                if (Number.isNaN(studentMajorId) || studentMajorId !== context.majorId) {
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

    const form = document.getElementById('application-form');
    const saveButton = document.getElementById('application-submit');
    const institutionSelect = document.getElementById('institution-id');
    const plannedStartInput = document.getElementById('planned-start-date');
    const periodCard = document.getElementById('period-helper');
    const periodLabel = document.getElementById('period-helper-label');
    const periodInfo = document.getElementById('period-helper-info');
    const periodAlert = document.getElementById('period-helper-alert');
    const periodAlertText = document.getElementById('period-helper-alert-text');
    const periodModalButton = document.getElementById('period-helper-modal-button');

    const periodState = {
        ready: false,
        institutionId: null,
        year: null,
        term: null,
        startValue: null,
    };

    function setSaveDisabledByPeriod(disabled) {
        if (!saveButton) {
            return;
        }

        if (disabled) {
            saveButton.dataset.disabledByPeriod = '1';
            saveButton.disabled = true;
        } else {
            delete saveButton.dataset.disabledByPeriod;
            saveButton.disabled = false;
        }
    }

    function parsePeriodFromDate(value) {
        if (!value) {
            return null;
        }
        const parts = value.split('-');
        if (parts.length < 2) {
            return null;
        }

        const year = Number(parts[0]);
        const month = Number(parts[1]);

        if (Number.isNaN(year) || Number.isNaN(month)) {
            return null;
        }

        const term = month >= 7 ? 2 : 1;
        return { year, term };
    }

    function findQuotaRecord(institutionId, year, term) {
        const key = String(institutionId);
        const records = Array.isArray(institutionQuotaData[key]) ? institutionQuotaData[key] : [];
        return records.find((item) => Number(item.year) === year && Number(item.term) === term) || null;
    }

    function refreshPeriodCard() {
        if (!periodCard) {
            return;
        }

        const institutionId = institutionSelect?.value || '';
        const startValue = plannedStartInput?.value || '';

        periodState.ready = false;
        periodState.institutionId = institutionId || null;
        periodState.startValue = startValue || null;
        periodState.year = null;
        periodState.term = null;

        if (!institutionId && !startValue) {
            periodLabel.textContent = '{{ $application->period_year }} • Term {{ $application->period_term }}';
            periodInfo.textContent = 'Select an institution and planned start date to detect the appropriate period.';
            periodAlert.classList.add('d-none');
            setSaveDisabledByPeriod(false);
            return;
        }

        if (!institutionId) {
            periodInfo.textContent = 'Choose an institution to determine the period.';
            periodAlert.classList.add('d-none');
            setSaveDisabledByPeriod(false);
            return;
        }

        if (!startValue) {
            periodInfo.textContent = 'Set the planned start date to determine the period.';
            periodAlert.classList.add('d-none');
            setSaveDisabledByPeriod(false);
            return;
        }

        const parsed = parsePeriodFromDate(startValue);
        if (!parsed) {
            periodInfo.textContent = 'Unable to determine the period from the provided date.';
            periodAlert.classList.add('d-none');
            setSaveDisabledByPeriod(false);
            return;
        }

        const { year, term } = parsed;
        periodState.year = year;
        periodState.term = term;
        const label = `${year} • Term ${term}`;
        periodLabel.textContent = label;

        const quotaRecord = findQuotaRecord(institutionId, year, term);
        if (quotaRecord) {
            const seats = Number(quotaRecord.quota);
            const used = Number(quotaRecord.used);
            periodInfo.textContent = `Quota available: ${Number.isNaN(seats) ? '—' : seats} seat(s), used: ${Number.isNaN(used) ? '—' : used}.`;
            periodAlert.classList.add('d-none');
            periodState.ready = true;
            setSaveDisabledByPeriod(false);
        } else {
            periodInfo.textContent = 'No quota exists for the detected period.';
            periodAlertText.textContent = `Create a quota for ${label} before saving.`;
            periodAlert.classList.remove('d-none');
            periodState.ready = false;
            setSaveDisabledByPeriod(true);
        }
    }

    if (institutionSelect) {
        institutionSelect.addEventListener('change', refreshPeriodCard);
    }

    if (plannedStartInput) {
        plannedStartInput.addEventListener('change', refreshPeriodCard);
        plannedStartInput.addEventListener('input', refreshPeriodCard);
    }

    refreshPeriodCard();

    const modalConstructor = window.bootstrap && window.bootstrap.Modal ? window.bootstrap.Modal : null;
    const quotaModalEl = document.getElementById('quotaModal');
    const quotaForm = document.getElementById('quota-form');
    const quotaInstitutionInput = document.getElementById('quota-institution-id');
    const quotaStartInput = document.getElementById('quota-start-date');
    const quotaPeriodLabel = document.getElementById('quota-modal-period');
    const quotaError = document.getElementById('quota-modal-error');
    const quotaValueInput = document.getElementById('quota-value');
    const quotaModal = quotaModalEl && modalConstructor ? new modalConstructor(quotaModalEl) : null;

    function registerQuota(institutionId, payload, amount) {
        const key = String(institutionId);
        const entries = Array.isArray(institutionQuotaData[key]) ? [...institutionQuotaData[key]] : [];
        const periodPayload = payload?.period || {};
        const quotaPayload = payload?.quota || {};

        const record = {
            id: Number(periodPayload.id ?? null),
            year: Number(periodPayload.year ?? periodState.year ?? null),
            term: Number(periodPayload.term ?? periodState.term ?? null),
            quota: Number(quotaPayload.quota ?? amount ?? 0),
            used: Number(quotaPayload.used ?? 0),
        };

        const index = entries.findIndex((item) => Number(item.year) === record.year && Number(item.term) === record.term);
        if (index >= 0) {
            entries[index] = record;
        } else {
            entries.push(record);
        }

        institutionQuotaData[key] = entries;
    }

    if (periodModalButton && quotaModal && quotaForm) {
        periodModalButton.addEventListener('click', () => {
            if (!periodState.institutionId || !periodState.startValue || periodState.year === null) {
                return;
            }

            quotaForm.reset();
            if (quotaError) {
                quotaError.classList.add('d-none');
                quotaError.textContent = '';
            }

            quotaInstitutionInput.value = periodState.institutionId;
            quotaStartInput.value = periodState.startValue;
            quotaPeriodLabel.textContent = `${periodState.year} • Term ${periodState.term}`;
            quotaModal.show();
        });
    }

    if (quotaModalEl) {
        quotaModalEl.addEventListener('shown.bs.modal', () => {
            quotaValueInput?.focus();
        });
    }

    if (quotaForm && quotaModal) {
        quotaForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (quotaError) {
                quotaError.classList.add('d-none');
                quotaError.textContent = '';
            }

            const formData = new FormData(quotaForm);
            try {
                const tokenInput = quotaForm.querySelector('input[name="_token"]');
                const response = await fetch(quotaForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': tokenInput ? tokenInput.value : '',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (!response.ok) {
                    if (response.status === 422) {
                        const data = await response.json();
                        const errors = data.errors || {};
                        const messages = Object.values(errors).flat();
                        const message = messages.length ? messages.join(' ') : (data.message || 'Unable to save quota.');
                        if (quotaError) {
                            quotaError.textContent = message;
                            quotaError.classList.remove('d-none');
                        }
                    } else if (quotaError) {
                        quotaError.textContent = 'Unable to save quota.';
                        quotaError.classList.remove('d-none');
                    }
                    return;
                }

                const payload = await response.json();
                const institutionId = String(formData.get('institution_id') || '');
                const amount = Number(formData.get('quota') || 0);
                registerQuota(institutionId, payload, amount);
                quotaModal.hide();
                refreshPeriodCard();
            } catch (error) {
                if (quotaError) {
                    quotaError.textContent = 'Network error. Please try again.';
                    quotaError.classList.remove('d-none');
                }
            }
        });
    }

    if (form) {
        form.addEventListener('submit', (event) => {
            if (periodState.institutionId && periodState.startValue && !periodState.ready) {
                event.preventDefault();
                if (periodAlert) {
                    periodAlert.classList.remove('d-none');
                    periodAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
})();
</script>
@endpush
@endsection
