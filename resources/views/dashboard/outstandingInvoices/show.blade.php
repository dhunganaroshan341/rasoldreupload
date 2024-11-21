@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <x-card-view title="OurService Information" :data="[
                'name' => $ourservice->name,
                'price' => $ourservice->price,
                'duration' => $ourservice->duration,
                'description' => $ourservice->description,
                'category' => $ourservice->category,
                'status' => $ourservice->status,
                'parent' => $ourservice->parent,
                'id' => $ourservice->id,
            ]" :services="$ourserviceServices" editRoute="ourservices.edit"
                serviceLabel="Associated Services" />
        </div>
    </div>
@endsection
