<div class="row">
    <div class="col-lg-7 col-md-12 col-sm-12 mb-4">
        <div class="card-box pd-30 height-100-p">
            <h4 class="mb-30 h4">Recent Incomes</h4>
            @foreach ($recentIncomesExpenses['incomes'] as $income)
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div class="flex-grow-1">
                        {{-- <strong>{{ $income->amount }}</strong> - by <span --}}
                        class="text-muted">{{ $income->clientService->name ?? $income->clientService->service->name }}
                        - {{ $income->clientService->client->name }}</span>
                    </div>
                    <div>
                        <span class="text-secondary">{{ $income->transaction_date }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-lg-5 col-md-12 col-sm-12 mb-4">
        <div class="card-box pd-30 height-100-p">
            <h4 class="mb-30 h4">Recent Expenses</h4>
            @foreach ($recentIncomesExpenses['expenses'] as $expense)
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div class="flex-grow-1">
                        <strong>{{ $expense->amount }}</strong> - by <span
                            class="text-muted">{{ $expense->clientService->name ?? $expense->clientService->service->name }}
                            - {{ $expense->clientService->client->name }}</span>
                    </div>
                    <div>
                        <span class="text-secondary">{{ $expense->transaction_date }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 col-md-12 col-sm-12 mb-4">
        <div class="card-box pd-30 height-100-p">
            <h4 class="mb-30 h4">Client Services with no payments</h4>
            @foreach ($clientServicesWithZeroPayments as $zeroPayingClient)
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div class="flex-grow-1">
                        <strong>{{ $zeroPayingClient->name ?? $zeroPayingClient->service->name . '-' . $zeroPayingClient->client->name }}</strong>

                    </div>
                    <div>
                        <span class="text-secondary">Total:
                            ${{ $zeroPayingClient->sum('amount') }}
                        </span>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    {{-- <div class="col-lg-5 col-md-12 col-sm-12 mb-4">
        <div class="card-box pd-30 height-100-p">
            <h4 class="mb-30 h4">Recent Expenses</h4>
            @foreach ($recentIncomesExpenses['expenses'] as $expense)
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div class="flex-grow-1">
                        <strong>{{ $expense->amount }}</strong> - by <span
                            class="text-muted">{{ $expense->clientService->name ?? $expense->clientService->service->name }}
                            - {{ $expense->clientService->client->name }}</span>
                    </div>
                    <div>
                        <span class="text-secondary">{{ $expense->transaction_date }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div> --}}
