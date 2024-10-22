@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <x-client-information-card :client="$client" :clientServices="$clientServices" />
    </div>
    <div class="mb-2"></div>
    <div class="container">
        <x-income-expense-chart-custom :id="$client->id" />
    </div>
@endsection
