<a name="" id="" class="btn btn-primary"
    href="{{ $type === 'income' ? route('incomes.edit', ['income' => $id]) : route('expenses.edit', ['expense' => $id]) }}"
    role="button">
    Edit {{ ucfirst($type) }}
</a>
