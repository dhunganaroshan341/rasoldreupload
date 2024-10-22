<!-- resources/views/components/client-card.blade.php -->
<style>
    .card-custom {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        padding: 20px;
        margin: 20px;
        border: 1px solid #ddd;
    }

    .card-header-custom {
        background-color: #007bff;
        color: #fff;
        font-size: 1.5rem;
        padding: 10px;
        border-radius: 8px 8px 0 0;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-title {
        font-weight: bold;
        color: #333;
    }

    .card-text {
        font-size: 1rem;
        line-height: 1.6;
    }

    ul.list-unstyled li {
        padding-left: 10px;
        margin-bottom: 5px;
    }

    /* Toggle Button */
    .btn-toggle {
        margin-bottom: 15px;
        cursor: pointer;
        color: #007bff;
        font-weight: bold;
    }

    .btn-toggle:hover {
        text-decoration: underline;
    }

    /* Custom Button Style */
    .btn-outline-primary {
        background-color: #ccd1d7;
        border: none;
        border-radius: 5px;
    }

    .btn-outline-primary:hover {
        background-color: #8e959d;
    }
</style>

<div class="container my-4">

    <!-- Toggle Client Information Button -->
    <div class="btn-toggle" onclick="toggleClientInfo()">
        Show Client Information
    </div>

    <!-- Client Details Card (Initially Hidden) -->
    <div class="card card-custom mb-4 shadow" id="client-info-card" style="display: none;">
        <div class="card-header card-header-custom">
            <h4 class="card-title">{{ $client->name }}</h4>
        </div>
        <div class="card-body">
            <p class="card-text">
                <strong>Address:</strong> {{ $client->address }}<br>
                <strong>Pan No:</strong> {{ $client->pan_no }}<br>
                <strong>Phone:</strong> {{ $client->phone }}<br>
                <strong>Email:</strong> {{ $client->email }}
            </p>

            <div class="mb-3">
                <strong>Services Used:</strong>
                <ul class="list-unstyled">
                    @foreach ($client->services as $service)
                        <li><i class="fas fa-caret-right"></i> {{ $service->name }}</li>
                    @endforeach
                </ul>
            </div>

            <a href="{{ route('clients.show', $client->id) }}" class="btn btn-outline-primary">View Full Client
                Profile</a>
        </div>
    </div>

    <!-- Single Ledger Transaction for the Client -->
    <h4>Transaction Details</h4>
    <div class="card card-custom mb-3 shadow-sm">
        <div class="card-header card-header-custom">
            <h4 class="card-title">{{ $ledger->transaction_type }}</h4>
        </div>
        <div class="card-body">
            <p class="card-text">
                <strong>Client:</strong> {{ $client ? $client->name : 'Client not found' }}<br>
                <strong>Service:</strong> {{ $service ? $service->name : 'Service not found' }}<br>
                <strong>Transaction Date:</strong>
                {{ \Carbon\Carbon::parse($ledger->transaction_date)->format('d-m-Y') }}<br>
                <strong>Amount:</strong> NPR {{ number_format($ledger->amount, 2) }}<br>
                <strong>Payment Medium:</strong> {{ $ledger->medium }}
            </p>
        </div>
    </div>
</div>

<script>
    function toggleClientInfo() {
        var clientCard = document.getElementById('client-info-card');
        if (clientCard.style.display === 'none') {
            clientCard.style.display = 'block';
        } else {
            clientCard.style.display = 'none';
        }
    }
</script>

<script>
    // JavaScript function to toggle client info
    function toggleClientInfo() {
        var clientInfoCard = document.getElementById("client-info-card");
        var toggleButton = document.querySelector(".btn-toggle");

        if (clientInfoCard.style.display === "none") {
            clientInfoCard.style.display = "block";
            toggleButton.innerText = "Hide Client Information";
        } else {
            clientInfoCard.style.display = "none";
            toggleButton.innerText = "Show Client Information";
        }
    }
</script>
