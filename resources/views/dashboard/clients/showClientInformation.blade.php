@extends('layouts.main')
@section('header-left-title', 'Client Info')

@section('header-right')
    <a href="{{ route('ClientServices.index', ['client_id' => $client->id]) }}" class="badge bg-sidebar mt-4 mr-3">
        list-view
    </a>

@endsection
@section('content')
    <div class="container mt-5">
        <h3>Client Information</h3>

        <div class="mt-3">
            <button class="btn btn-light badge" type="button" data-bs-toggle="collapse" data-bs-target="#clientInfoCollapse"
                aria-expanded="false" aria-controls="clientInfoCollapse">
                View Details
            </button>
            <a href="{{ route('clients.edit', ['client' => $client->id]) }}"><i class="fas fa-pencil"></i></a>
            @if (!$client->hasIncome())
                <form action="{{ route('clients.destroy', ['client' => $client->id]) }}" method="POST"
                    id="confirmDelete{{ $client->id }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger" onclick="confirmDeleteThis({{ $client->id }})">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </form>
            @endif

        </div>
        <div class="collapse show mt-3" id="clientInfoCollapse">
            @include('components.client-information-table')
        </div>
    </div>
    {{-- endof client info --}}

    <div class="container mt-5">
        <h3>Services Used</h3>
        <div class="mt-3">
            <button class="btn btn-light badge" type="button" data-bs-toggle="collapse"
                data-bs-target="#servicesUsedCollapse" aria-expanded="false" aria-controls="servicesUsedCollapse">
                View Details
            </button>

        </div>
        <div class="collapse mt-3" id="servicesUsedCollapse">
            <x-client-information-card :client="$client" :clientServices="$clientServices" />
        </div>
    </div>

    <div class="container mt-5">
        <h3>Graph</h3>
        <div class="mt-3">
            <button class="btn btn-light badge" type="button" data-bs-toggle="collapse"
                data-bs-target="#transactionsCollapse" aria-expanded="false" aria-controls="transactionsCollapse">
                View Details
            </button>
        </div>
        <div class="collapse mt-3" id="transactionsCollapse">
            <x-income-expense-chart-custom :id="$client->id" />
        </div>
    </div>
@endsection
