<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    @include('components.form-errors')

    <div class="col-12">
        <label class="form-label" for="school-name">School Name</label>
        <input type="text" name="name" id="school-name" class="form-control" value="{{ old('name', optional($school)->name) }}" maxlength="150" required>
    </div>

    <div class="col-12">
        <label class="form-label" for="school-address">Address</label>
        <textarea name="address" id="school-address" class="form-control" rows="4" maxlength="1000" required>{{ old('address', optional($school)->address) }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="school-phone">Phone</label>
        <input type="text" name="phone" id="school-phone" class="form-control" value="{{ old('phone', optional($school)->phone) }}" inputmode="tel" maxlength="30" required>
        <div class="form-text">Format example: +62 812-3456-7890</div>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="school-email">Email</label>
        <input type="email" name="email" id="school-email" class="form-control" value="{{ old('email', optional($school)->email) }}" maxlength="255" required>
    </div>

    <div class="col-12">
        <label class="form-label" for="school-website">Website</label>
        <input type="url" name="website" id="school-website" class="form-control" value="{{ old('website', optional($school)->website) }}" maxlength="255" placeholder="https://example.sch.id">
    </div>

    <div class="col-12 d-flex gap-2">
        <a href="{{ $cancel }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
