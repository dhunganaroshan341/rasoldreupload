@extends('layouts.main')
@section('header-right')
    <a href="{{ route('employees.index') }}" class="mt-4 mr-4 badge badge-dark">Employee</a>
@endsection
@section('header-left-title', 'New Employee')

@section('content')
    @include('dashboard.employees.form')
@endsection
