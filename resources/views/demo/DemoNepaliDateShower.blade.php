@extends('layouts.main')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">You're logged in as a user!</div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p>Name: {{ auth()->user()->name }} </p>
                    <p>Email: {{ auth()->user()->email }} </p>
                    <p>CreatedAt :@php$year= auth()->user()->created_at->format('Y');$month= auth()->user()->created_at->format('m');
                                            $day= auth()->user()->created_at->format('d');$date=Bsdate::eng_to_nep($year,$month,$day);
                                        echo $date['date'].' '.$date['nmonth'].' '.$date['year'].','.$date['day']@endphp ?></p>
                    {{-- OR --}}{{-- @php$year= auth()->user()->created_at->format('Y');$month= auth()->user()->created_at->format('m');$day= auth()->user()->created_at->format('d');$date=Bsdate::eng_to_nep($year,$month,$day);@endphp<p>CreatedAt :{{$date['date'].' '.$date['nmonth'].' '.$date['year'].','.$date['day']}} </p> --}}
                @endSection
