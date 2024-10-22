@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form
    action="{{ isset($employeePayroll) ? route('employeePayroll.update', $employee->id) : route('employeePayroll.store') }}"
    method="POST">

    @csrf
    @if (isset($employeePayroll))
        @method('PUT')
    @endif

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control"
            value="{{ old('name', $employee->name ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control"
            value="{{ old('email', $employee->email ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="position">Position</label>
        <input type="text" name="position" id="position" class="form-control"
            value="{{ old('position', $employee->position ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" name="address" id="address" class="form-control"
            value="{{ old('address', $employee->address ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" name="phone" id="phone" class="form-control"
            value="{{ old('phone', $employee->phone ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="salary">Salary</label>
        <input type="number" name="salary" id="salary" class="form-control"
            value="{{ old('salary', $employee->salary ?? '') }}" required>
    </div>

    <button type="submit" class="btn btn-primary">
        {{ isset($employee) ? 'Update' : 'Create' }} Employee
    </button>
</form>
