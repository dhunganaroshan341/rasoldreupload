@extends('layouts.main')
@section('content')
    <div class="container mt-5 d-flex d-col">
        <!-- Include the client card component and pass the necessary data -->
        @foreach ($client as $client){
            @include('components.client-card')
            }
    </div>
@endSection
