<div class="dropdown">
    <a href="#" class="text-decoration-none dropdown-toggle" data-toggle="dropdown">{{ $client_service_name }}
    </a>

    <div class="dropdown-menu">
        <a title = "show ledger" class="dropdown-item"
            href="{{ route('ledger-client-service.show', ['ledger_client_service' => $ledger->clientService->id]) }}">
            <i class="fas fa-book"></i>
            Ledger</a>
        <a title = "edi client" class="dropdown-item"
            href="{{ route('ClientServices.edit', ['client_service_id' => $ledger->clientService->id]) }}">
            <i class="fas fa-pencil"></i> client-service
        </a>

        @if ($ledger->income != null)
            <!-- Show Edit Income link if income exists -->
            <a title = "edit income" href="{{ route('incomes.edit', ['income' => $ledger->income->id]) }}"
                class="dropdown-item">
                <i class="fas fa-pencil"></i> Income
            </a>
        @elseif ($ledger->expense != null)
            <!-- Show Edit Expense link if expense exists and income doesn't exist -->
            <a title = "edit expense" href="{{ route('expenses.edit', ['expense' => $ledger->expense->id]) }}"
                class="dropdown-item">
                <i class="fas fa-pencil"></i> Expense
            </a>
        @else
            <!-- Fallback link if neither income nor expense exists -->
            <a href="#" class="dropdown-item">
                No Action Available
            </a>
        @endif



    </div>
