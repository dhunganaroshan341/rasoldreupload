<table id="data-table-default" width="100%" class="table table-striped table-bordered">
    <thead class="bg-light">
        <tr>
            <th>ID</th>
            <th>Service Category</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Ledger</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($client->clientServices as $service)
            <tr>
                <td>{{ $service->id }}</td>
                <td>{{ $service->service->name . '-' . $client->name }}</td>
                <!-- Assuming there's a related `Service` model -->
                <td>{!! $service->name ?? '<small>specific-name/if required</small>' !!}</td>
                <td>{{ $service->amount ?? $service->service->price }}</td>
                <td>
                    <a title="{{ 'ledger ' . $service->name ?? $client->client->name . ' - ' . $service->service->name }}"
                        href="{{ route('ledger-client-service.show', ['ledger_client_service' => $service->id]) }}"
                        class="btn btn-link">
                        <i class="fa fa-eye"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
