<!-- resources/views/components/recent-changes.blade.php -->
<div class="recent-changes">
    <ul>
        @foreach ($changes as $change)
            <li class="d-flex flex-wrap align-items-center">
                <div class="change-detail">{{ $change->description }}</div>
                <div class="changed-by">Changed by: <strong>{{ $change->user->name }}</strong></div>
                <div class="change-time"><span
                        class="badge badge-pill badge-info">{{ $change->created_at->diffForHumans() }}</span></div>
            </li>
        @endforeach
    </ul>
</div>
