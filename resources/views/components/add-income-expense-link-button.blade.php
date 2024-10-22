{{-- <button type="button" class="btn bg-sidebar text-white" data-bs-toggle="modal" data-bs-target="#incomeCreateModal">
    Add Income
    <i class="fa fa-plus"></i>
</button> --}}
<button type="button" class="btn bg-sidebar text-white" data-bs-toggle="modal" data-bs-target="#incomeCreateModal">
    <a class='text-white 'href="{{ route('expenses.create') }}"> Income</a>
    <i class="fa fa-plus"></i>
</button>

&nbsp;
<button type="button" class="btn bg-sidebar " data-bs-toggle="modal" data-bs-target="#incomeCreateModal">
    <a class='text-white 'href="{{ route('expenses.create') }}">Expense</a>
    <i class="text-white fa fa-plus"></i>
</button>
