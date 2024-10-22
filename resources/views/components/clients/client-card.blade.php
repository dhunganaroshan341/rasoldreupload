<!-- resources/views/components/client-card.blade.php -->
<style>
    .card-custom {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        padding: 20px;
        margin: 20px;
    }

    .card-custom .card-title {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .card-custom .card-text {
        font-size: 1rem;
        color: #555;
    }

    .card-custom .list-unstyled {
        margin: 0;
        padding: 0;
    }

    .card-custom .list-unstyled li {
        padding: 5px 0;
    }

    .card-custom .btn-primary {
        background-color: #ccd1d7;
        border: none;
        border-radius: 5px;
    }

    .card-custom .btn-primary:hover {
        background-color: #8e959d;
    }
</style>

<div class="card card-custom mb-4">
    <div class="card-body">
        <h5 class="card-title">{{ $client->name }}</h5>
        <p class="card-text">
            <strong>Address:</strong> {{ $client->address }}<br>
            {{-- <strong>Client Type:</strong> {{ $client->type }}<br> --}}
            <strong>Pan No:</strong> {{ $client->pan_no }}
            <strong>Phone:</strong> {{ $client->phone }}<br>
            <strong>Email:</strong> {{ $client->email }}
        </p>

        <div class="mb-3">
            <strong>Services Used:</strong>
            <ul class="list-unstyled">
                @foreach ($client->services as $service)
                    <li>{{ $service->name }}</li>
                @endforeach
            </ul>
        </div>

        <a href="{{ route('clients.show', $client->id) }}" class="btn btn-primary">View More</a>
    </div>
</div>
