    <div class="dropdown">
        <a href="#" class="text-decoration-none dropdown-toggle"
            data-toggle="dropdown">{{ $client_service_name }}</a>
        <div class="dropdown-menu">
            <a title="show ledger" class="dropdown-item"
                href="{{ route('ledger-client-service.show', ['ledger_client_service' => $ledger->clientService->id]) }}">
                <i class="fas fa-book"></i> Ledger
            </a>
            <a title="edit client" class="dropdown-item"
                href="{{ route('ClientServices.edit', ['client_service_id' => $ledger->clientService->id]) }}">
                <i class="fas fa-pencil"></i> Client Service
            </a>

            @if ($ledger->income != null)
                <a title="edit income" href="{{ route('incomes.edit', ['income' => $ledger->income->id]) }}"
                    class="dropdown-item">
                    <i class="fas fa-pencil"></i> Income
                </a>
            @elseif ($ledger->expense != null)
                <a title="edit expense" href="{{ route('expenses.edit', ['expense' => $ledger->expense->id]) }}"
                    class="dropdown-item">
                    <i class="fas fa-pencil"></i> Expense
                </a>
            @else
                <a href="#" class="dropdown-item">No Action Available</a>
            @endif
        </div>
    </div>
