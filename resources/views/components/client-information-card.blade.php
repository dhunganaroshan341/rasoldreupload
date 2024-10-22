<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg border-light rounded-lg">
            <div class="card-header bg-sidebar text-white border-bottom-0 rounded-top">
                <h3 class="mb-0 text-golden">Client Information</h3>
            </div>
            <div class="card-body">
                <!-- Client Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-2"><strong class="text-sidebar-bg">Name:</strong> {{ $client->name }}</p>
                        <p class="mb-2"><strong class="text-sidebar-bg">P.A.N Number:</strong>
                            {{ $client->pan_no }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong class="text-sidebar-bg">Address:</strong> {{ $client->address }}
                        </p>
                        <p class="mb-2"><strong class="text-sidebar-bg">Email:</strong> {{ $client->email }}</p>
                        <p class="mb-2"><strong class="text-sidebar-bg">Phone:</strong> {{ $client->phone }}</p>
                    </div>
                </div>

                <!-- Associated Services -->
                <div class="mt-4">
                    <h5 class="mb-3 text-secondary">Associated Services:</h5>
                    @if ($clientServices->isEmpty())
                        <p>No services associated with this client.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($clientServices as $service)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Service Name:</strong> {{ $service->service->name }}<br>
                                        <strong>Duration:</strong> {{ $service->duration }}<br>
                                        <strong>Duration Type:</strong> {{ $service->duration_type }}<br>
                                        <strong>Hosting Service:</strong> {{ $service->hosting_service }}<br>
                                        <strong>Email Service:</strong> {{ $service->email_service }}
                                    </div>
                                    <span style="color:rgb(189, 166, 36)"
                                        class="badge bg-primary rounded-pill">Active</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-light text-end border-top-0 rounded-bottom">
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-success">Edit</a>
            </div>
        </div>
    </div>
</div>
