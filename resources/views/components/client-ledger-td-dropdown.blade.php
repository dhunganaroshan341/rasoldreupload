<div class="dropdown">
    <a href="#" class="text-decoration-none dropdown-toggle" data-toggle="dropdown">{{ $client_service_name }}
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item"
            href="{{ route('ClientServices.edit', ['client_service_id' => $ledger->clientService->id]) }}">
            Edit Client
        </a>
        <a class="dropdown-item"
            href="{{ route('ledger-client-service.show', ['ledger_client_service' => $ledger->clientService->id]) }}">Show
            Ledger</a>
    </div>
