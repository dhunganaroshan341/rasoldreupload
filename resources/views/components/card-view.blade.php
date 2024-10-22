<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-light rounded-lg">
                <div class="card-header bg-primary text-white border-bottom-0 rounded-top">
                    <h3 class="mb-0">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    <!-- Data Information -->
                    <div class="row mb-4">
                        @foreach ($data as $key => $value)
                            <div class="col-md-6">
                                <p class="mb-2"><strong class="text-primary">{{ ucfirst($key) }}:</strong>
                                    {{ $value }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Related Data -->
                    @if ($relatedData && $relatedData->count())
                        <div class="mt-4">
                            <h5 class="mb-3 text-secondary">{{ $relatedLabel }}:</h5>
                            <ul class="list-group list-group-flush">
                                @foreach ($relatedData as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ route('related.show', $item->id) }}">{{ $item->name }}</a>
                                        <span class="badge bg-primary rounded-pill">Active</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                @if ($editRoute)
                    <div class="card-footer bg-light text-end border-top-0 rounded-bottom">
                        <a href="{{ route($editRoute, $data['id']) }}" class="btn btn-success">Edit</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
