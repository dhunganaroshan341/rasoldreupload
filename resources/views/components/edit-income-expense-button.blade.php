<a name="" id="" class=""
    href="{{ $type === 'income' ? route('incomes.edit', ['income' => $id]) : route('expenses.edit', ['expense' => $id]) }}"
    role="button">
    {{-- Edit {{ ucfirst($type) }} --}}
    <i class="fa fa-pencil text-dark"></i>
</a>
