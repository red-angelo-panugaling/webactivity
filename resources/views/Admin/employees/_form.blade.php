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
            <select class="form-select" id="position" name="position" data-add-option-value="{{ $addNewOption }}" required>
                <option value="">Select a position</option>
                @foreach ($positions as $positionOption)
                    <option value="{{ $positionOption }}" @selected(old('position', $employee?->position) === $positionOption)>{{ $positionOption }}</option>
                @endforeach
                <option value="{{ $addNewOption }}" @selected(old('position') === $addNewOption)>+ Add another position</option>
            </select>
            <div class="mt-2 {{ old('position') === $addNewOption ? '' : 'd-none' }}" data-add-option-field="position">
                <label class="form-label" for="position_new">New Position</label>
                <input class="form-control" id="position_new" name="position_new" type="text" value="{{ old('position_new') }}" maxlength="255" data-add-option-input>
                <div class="form-text">This position will be available in the dropdown after saving.</div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="office">Office</label>
            <select class="form-select" id="office" name="office" data-add-option-value="{{ $addNewOption }}" required>
                <option value="">Select an office</option>
                @foreach ($offices as $officeOption)
                    <option value="{{ $officeOption }}" @selected(old('office', $employee?->office) === $officeOption)>{{ $officeOption }}</option>
                @endforeach
                <option value="{{ $addNewOption }}" @selected(old('office') === $addNewOption)>+ Add another office</option>
            </select>
            <div class="mt-2 {{ old('office') === $addNewOption ? '' : 'd-none' }}" data-add-option-field="office">
                <label class="form-label" for="office_new">New Office</label>
                <input class="form-control" id="office_new" name="office_new" type="text" value="{{ old('office_new') }}" maxlength="255" data-add-option-input>
                <div class="form-text">This office will be available in the dropdown after saving.</div>
            </div>
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

<script>
    document.querySelectorAll('[data-add-option-value]').forEach((select) => {
        const field = document.querySelector(`[data-add-option-field="${select.name}"]`);
        const input = field?.querySelector('[data-add-option-input]');
        const updateField = () => {
            const showField = select.value === select.dataset.addOptionValue;
            field?.classList.toggle('d-none', !showField);

            if (input) {
                input.required = showField;
            }
        };

        select.addEventListener('change', updateField);
        updateField();
    });
</script>
