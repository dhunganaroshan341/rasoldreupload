@extends('layouts.main')
@section('content')
    <div class="container mt-4">
        <div class="justify-content-between mb-4">
            <h2>Create Custom Contracts</h2>
            <p>
                <i>You'll create:</i><br>
                <i>Client, Our Services, Contract</i>
            </p>
            <a href="{{ route('contracts.index') }}" class="btn btn-secondary">Back</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contracts.custom.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Contract Name:</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="e.g., Web Development"
                    required>
            </div>

            <div class="form-group">
                <label for="client_name">Client Name:</label>
                <input type="text" class="form-control" name="client_name" id="client_name" placeholder="Client Name"
                    required>
            </div>

            <div class="form-group">
                <label for="service_type">Service Type:</label>
                <input type="text" class="form-control" name="service_type" id="service_type" placeholder="Service Type"
                    required>
            </div>

            <div class="form-group">
                <label for="duration">Duration:</label>
                <input type="number" class="form-control" name="duration" id="duration" required>
                <select name="duration_type" id="duration_type" class="form-control mt-2">
                    <option value="hours">Hours</option>
                    <option value="days">Days</option>
                    <option value="weeks">Weeks</option>
                    <option value="months">Months</option>
                </select>
            </div>

            <div class="form-group">
                <label for="start_date">Starting Date:</label>
                <input type="date" class="form-control" name="start_date" id="start_date">
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>




            <div class="form-group">
                <label for="price">Total Price:</label>
                <input type="number" class="form-control" name="price" id="price" min="0" required>
                <select name="currency" id="currency" class="form-control mt-2">
                    <option value="npr">NPR</option>
                    <option value="inr">INR</option>
                    <option value="dollar">$</option>
                    <option value="euro">Euro</option>
                    <option value="dhiram">DHIRAM</option>
                </select>
            </div>

            <div class="form-group">
                <label for="advance_amount">Advance Amount (if paid):</label>
                <input type="number" class="form-control" name="advance_amount" id="advance_amount">
            </div>

            <div class="form-group">
                <label for="remarks">Remarks:</label>
                <input type="text" class="form-control" name="remarks" id="remarks">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Save</button>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $('#start_date').change(function() {
                var startDate = $(this).val();
                var today = new Date();
                var selectedDate = new Date(startDate);
                var difference = Math.ceil((selectedDate - today) / (1000 * 60 * 60 * 24));

                // Set status based on the selected date
                if (difference >= 2) {
                    $('#status').val('pending');
                } else {
                    $('#status').val('in_progress');
                }
            });
        });
    </script>
@endsection
