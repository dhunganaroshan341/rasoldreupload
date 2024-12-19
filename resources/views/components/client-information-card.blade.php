<!-- Client Services Grid Section -->
<div class="row mt-5">
    @if ($clientServices->isEmpty())
        <p class="col-12 text-center text-muted">No services associated with this client.</p>
    @else
        @foreach ($clientServices as $service)
            <!-- Individual Service Card -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card shadow-sm h-100 border-light rounded">
                    <!-- Card Header -->
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">{{ $service->name }}</h5>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <p>
                            <strong>Duration:</strong> {{ $service->duration . ' ' . $service->duration_type }}<br>
                            <strong>Billing Start-End:</strong> {{ $service->billing_start_date }} to
                            {{ $service->billing_end_date }}<br>
                            <strong>Billing Cycle:</strong> {{ $service->billing_period_frequency }}<br>
                        </p>

                        @foreach (['hosting_service' => 'Hosting Service', 'email_service' => 'Email Service'] as $key => $label)
                            @if (!empty($service->$key))
                                <p><strong>{{ $label }}:</strong> {{ $service->$key }}</p>
                            @endif
                        @endforeach

                        <p>
                            <strong>Amount:</strong>Rs-{{ $service->amount }}/-<br>
                            <strong>Remaining:</strong> Rs-{{ $service->remaining_amount }}/-
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <!-- Income Status -->
                            <div class="d-flex align-items-center">
                                @if ($service->has_income)
                                    @if ($service->fully_paid)
                                        <span class="badge bg-success">Fully Paid</span>
                                    @else
                                        <span class="badge bg-info">{{ number_format($service->income_percentage, 2) }}%
                                            - paid</span>
                                    @endif
                                @else
                                    <span class="badge bg-warning text-dark">No Income</span>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="btn-group">
                                <a href="{{ route('ledger-client-service.show', ['ledger_client_service' => $service->id]) }}"
                                    class="btn  btn-sm">
                                    <i class="fas fa-book"></i> Summary
                                </a>
                                <a href="{{ route('ClientServices.edit', ['client_service_id' => $service->id]) }}"
                                    class="btn  btn-sm">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>

                                @if (!$service->getHasIncomeAttribute())
                                    <form
                                        action="{{ route('ClientServices.destroy', ['client_service_id' => $service->id]) }}"
                                        method="POST" id="confirmDelete{{ $service->id }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn  btn-sm"
                                            onclick="confirmDeleteThis({{ $service->id }})">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer bg-light text-end">
                        <span class="badge bg-success rounded-pill">Active</span>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
