<form method="POST" action="{{ $employee ? route('admin.employees.update', $employee) : route('admin.employees.store') }}">
    @csrf
    @if ($employee)
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label" for="name">Name</label>
            <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $employee?->name) }}" required maxlength="255" autocomplete="name">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="position">Position</label>
            <input class="form-control" id="position" name="position" type="text" value="{{ old('position', $employee?->position) }}" required maxlength="255">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="office">Office</label>
            <input class="form-control" id="office" name="office" type="text" value="{{ old('office', $employee?->office) }}" required maxlength="255">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="age">Age</label>
            <input class="form-control" id="age" name="age" type="number" value="{{ old('age', $employee?->age) }}" min="0" max="120">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="start_date">Start Date</label>
            <input class="form-control" id="start_date" name="start_date" type="date" value="{{ old('start_date', $employee?->start_date?->format('Y-m-d')) }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="salary">Salary</label>
            <input class="form-control" id="salary" name="salary" type="number" value="{{ old('salary', $employee?->salary) }}" min="0" max="9999999999.99" step="0.01" required>
        </div>
    </div>

    <button class="btn btn-primary" type="submit">{{ $employee ? 'Save Changes' : 'Create Employee' }}</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.employees.index') }}">Cancel</a>
</form>
