<div class="col-lg-4 col-md-6 col-sm-12 mb-4">
    <div class="card-box pd-30 pt-10 height-100-p">
        <h2 class="mb-30 h4">Client Services</h2>
        <div class="client-services">
            <ul>
                @foreach ($recentincomeExpense['incomes'] as $incomes)
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <!-- Client name as clickable link -->
                        <a href="{{ route('ClientServices.index', ['client_id' => $client->id]) }}"
                            class="client-name h6 mb-0">
                            {{ $client->name }}
                        </a>

                        <!-- Services count badge -->
                        <span class="badge badge-pill badge-primary">
                            {{ $client->clientServices->count() }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-6 col-sm-12 mb-4">
    <div class="card-box pd-30 pt-10 height-100-p">
        <h2 class="mb-30 h4">Client Services</h2>
        <div class="client-services">
            <ul>
                @foreach ($recentClients as $client)
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <!-- Client name as clickable link -->
                        <a href="{{ route('ClientServices.index', ['client_id' => $client->id]) }}"
                            class="client-name h6 mb-0">
                            {{ $client->name }}
                        </a>

                        <!-- Services count badge -->
                        <span class="badge badge-pill badge-primary">
                            {{ $client->clientServices->count() }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
