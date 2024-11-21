<table id="data-table-default" width="100%" class="table table-striped table-bordered align-middle text-nowrap">
    <thead class="bg-light">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Salary</th>
            <th>Phone</th>
            <th class="description-column">Address</th>
            <th>Email</th>
            <th>Position</th>
            <th data-orderable="false">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            <tr class="hover-item position-relative">
                <td>{{ $employee->id }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->salary }}</td>
                <td>{{ $employee->phone }}</td>
                <td class="description-column">{{ $employee->address }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->position }}</td>
                <td class="position-relative">
                    <button type="button" class="btn btn-sm btn-info" onclick="showPayroll({{ $employee->id }})">
                        <i class="fa fa-money-bill-wave"></i> salary
                    </button>
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>

                    <form id="delete-employee-{{ $employee->id }}"
                        action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger"
                            onclick="confirmDelete({{ $employee->id }})">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
