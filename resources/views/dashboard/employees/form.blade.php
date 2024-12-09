@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ isset($employee) ? route('employees.update', $employee->id) : route('employees.store') }}" method="POST"
    class="p-4 shadow-sm rounded bg-light">
    @csrf
    @if (isset($employee))
        @method('PUT')
    @endif

    <h4 class="text-sidebar mb-4 text-center">Employee Details</h4>

    <!-- Row 1 -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name" class="font-weight-bold">Name</label>
                <input type="text" name="name" id="name" class="form-control rounded-pill"
                    value="{{ old('name', $employee->name ?? '') }}" placeholder="Enter full name" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email" class="font-weight-bold">Email</label>
                <input type="email" name="email" id="email" class="form-control rounded-pill"
                    value="{{ old('email', $employee->email ?? '') }}" placeholder="Enter email address" required>
            </div>
        </div>
    </div>

    <!-- Row 2 -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label for="position" class="font-weight-bold">Position</label>
                <input type="text" name="position" id="position" class="form-control rounded-pill"
                    value="{{ old('position', $employee->position ?? '') }}" placeholder="Enter position" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="address" class="font-weight-bold">Address</label>
                <input type="text" name="address" id="address" class="form-control rounded-pill"
                    value="{{ old('address', $employee->address ?? '') }}" placeholder="Enter address" required>
            </div>
        </div>
    </div>

    <!-- Row 3 -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone" class="font-weight-bold">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control rounded-pill"
                    value="{{ old('phone', $employee->phone ?? '') }}" placeholder="Enter phone number" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="salary" class="font-weight-bold">Salary</label>
                <input type="number" name="salary" id="salary" class="form-control rounded-pill"
                    value="{{ old('salary', $employee->salary ?? '') }}" placeholder="Enter salary amount" required>
            </div>
        </div>
    </div>

    <!-- Section Divider -->
    <hr class="my-4">

    <!-- Submit Button -->
    <div class="text-center">
        <button type="submit" class="btn btn-success btn-lg rounded-pill px-4">
            {{ isset($employee) ? 'Update' : 'Create' }} Employee
        </button>
    </div>
</form>
