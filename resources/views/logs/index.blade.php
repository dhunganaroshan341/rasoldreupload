@extends('layouts.main')

@section('content')
    <h1 class="mb-4">Activity Logs</h1>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Model</th>
                    <th>Model ID</th>
                    <th>Changes</th>
                    <th>URL</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->user->name }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->model }}</td>
                        <td>{{ $log->model_id }}</td>
                        <td>{{ $log->changes }}</td>
                        <td>{{ $log->url }}</td>
                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $logs->links() }}
@endsection
