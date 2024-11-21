<table id="data-table-default" width="100%" class="table table-striped table-bordered align-middle text-nowrap">
    <thead class="bg-light">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>type</th>
            <th data-orderable="false">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($chartsOfAccount as $coa)
            <tr class="hover-item position-relative">
                <td>{{ $coa->id }}</td>
                <td>{{ $coa->name }}</td>
                <td>{{ $coa->type }}</td>

                <td class="position-relative">
                    {{-- <button type="button" class="btn btn-sm btn-info" onclick="showPayroll({{ $coa->id }})">
                        <i class="fa fa-money-bill-wave"></i> salary
                    </button> --}}
                    <!-- Edit Button with onclick event to open the modal and load data -->
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary"
                        onclick="editAccount({{ $coa->id }})">
                        <i class="fa fa-pencil"></i> Edit
                    </a>

                    <form id="delete-employee-{{ $coa->id }}" action="{{ route('employees.destroy', $coa->id) }}"
                        method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger"
                            onclick="confirmDelete({{ $coa->id }})">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
