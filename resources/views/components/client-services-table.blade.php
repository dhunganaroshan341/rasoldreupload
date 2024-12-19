<table id="data-table-client-service" width="100%" class="table table-striped table-bordered">
    <thead class="bg-light">
        <tr>
            <th>ID</th>
            <th>Service Category</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Duration</th>
            <th>Email Service</th>
            <th>Hosting Service</th>
            <th>Advance paid</th>
            <th>Actions</th>
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
                <td>{{ $service->duration ?? $service->service->duration }} -
                    {{ $service->duration_type ?? $service->service->duration_type }}</td>
                <td>{{ $service->email_service ?? $client->email_service }}</td>
                <td>{{ $service->hosting_service ?? $client->hosting_service }}</td>
                {{-- advance paid --}}
                <td>
                    {{ $service->advance_paid }}
                </td>
                <td>
                    <a title="show ledger"
                        href="{{ route('ledger-client-service.show', ['ledger_client_service' => $service->id]) }}"
                        class="btn btn-link">
                        <i class="fa fa-list"></i>
                    </a>
                    <a href="{{ route('ClientServices.edit', ['client_service_id' => $service->id]) }}"
                        class="btn btn-link">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="{{ route('ClientServices.destroy', ['client_service_id' => $service->id]) }}"
                        method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
