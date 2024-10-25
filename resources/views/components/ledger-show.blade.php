<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Ledger Entries for <span class="text-light bg-danger   ">{{ $client->name }}</span>
                <br>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table-default" class="table table-striped table-bordered align-middle">
                        <thead>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll"> Select</th>
                                    <th>Date</th>
                                    <th>Source</th>
                                    <th>Medium</th>
                                    <th>Debit (Expense)</th>
                                    <th>Credit (Income)</th>
                                    <th>Balance</th>
                                    <th>Summary</th>
                                </tr>
                            </thead>
                        <tbody>
                            @php $balance = 0; @endphp
                            @foreach ($ledgers as $ledger)
                                {{-- Ledger Calculations --}}
                                @php
                                    $balance +=
                                        $ledger->transaction_type == 'income' ? $ledger->amount : -$ledger->amount;
                                    $client_service_name =
                                        $ledger->clientService->name ??
                                        $ledger->clientService->service->name .
                                            '-' .
                                            $ledger->clientService->client->name;
                                @endphp
                                <tr>
                                    <td>
                                        @if ($ledger->transaction_type === 'income')
                                            <input type="checkbox" class="ledger-checkbox" value="{{ $ledger->id }}">
                                        @endif
                                    </td>
                                    </td>
                                    <td>{{ $ledger->transaction_date }}</td>
                                    <td><a
                                            href="{{ route('ClientServices.edit', ['client_service_id' => $ledger->clientService->id]) }}">{{ $ledger->clientService->name ?? $ledger->clientService->client->name . '-' . $ledger->clientService->service->name }}</a>
                                    </td>
                                    <td>{{ $ledger->medium }}</td>
                                    <td>{{ $ledger->transaction_type == 'expense' ? '$' . number_format($ledger->amount, 2) : '--' }}
                                        @if ($ledger->transaction_type == 'expense')
                                            {{ number_format($ledger->amount, 1) }}
                                            {{-- <x-edit-income-expense-button :expenseId="$ledger->clientService->client->id" /> --}}
                                        @endif
                                    </td>
                                    <td>{{ $ledger->transaction_type == 'income' ? '$' . number_format($ledger->amount, 2) : '--' }}
                                    </td>
                                    <td>${{ number_format($balance, 2) }}</td>
                                    <td>{{ $ledger->clientService->remaining_amount > 0 ? "$client_service_name - Remaining: $" . number_format($ledger->clientService->remaining_amount, 2) : 'cleared' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Display Ledger Calculation Totals --}}
                <div class="mt-4">
                    <h4>Client Ledger Summary</h4>
                    <p><strong>Total Client Service Amount:</strong>
                        ${{ number_format($totalClientServiceAmount, 2) }}
                    </p>
                    <p><strong>Total Income:</strong>
                        ${{ number_format($ledgerCalculationForClient['clientTotalIncome'], 2) }}</p>
                    <p><strong>Total Expenses:</strong>
                        ${{ number_format($ledgerCalculationForClient['clientTotalExpense'], 2) }}</p>
                    <p><strong>Balance:</strong>
                        ${{ number_format($ledgerCalculationForClient['clientBalance'], 2) }}</p>
                    <p><strong>Total Remaining:</strong>
                        $${{ number_format($ledgerCalculationForClient['clientTotalRemaining'], 2) }}
                    </p>
                </div>

                {{-- <button id="process-selected" class="btn btn-success mt-3">Process Selected Ledgers</button> --}}
            </div>
        </div>
    </div>
</div>
