<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Ledger Entries for <span class="text-light bg-danger">{{ $client->name }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table-default" class="table table-striped table-bordered align-middle"
                        style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <th class="no-order exclude-column" style="width: 5%;"> <input type="checkbox"
                                        id="checkAll"> Select </th>
                                <th style="width: 5%;">id</th>
                                <th style="width: 10%;">Date</th>
                                <th style="width: 20%;">Source</th>
                                <th style="width: 10%;">Medium</th>
                                <th style="width: 15%;">Debit (Expense)</th>
                                <th style="width: 15%;">Credit (Income)</th>
                                <th style="width: 10%;">Balance</th>
                                <th style="width: 15%;">Summary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $balance = 0;
                                $id = 1;
                            @endphp
                            @foreach ($ledgers as $ledger)
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
                                    <td>{{ $id++ }}</td>
                                    <td>{{ $ledger->transaction_date }}</td>
                                    <td class="exclude-column no-export">
                                        @include('components.client-ledger-td-dropdown')
                                    </td>
                                    <td>{{ $ledger->medium }}</td>
                                    <td>{{ $ledger->transaction_type == 'expense' ? 'Rs.' . number_format($ledger->amount, 2) : '--' }}
                                    </td>
                                    <td>{{ $ledger->transaction_type == 'income' ? 'Rs.' . number_format($ledger->amount, 2) : '--' }}
                                    </td>
                                    <td>Rs.{{ number_format($balance, 2) }}</td>
                                    <td>
                                        {{ $ledger->transaction_type == 'expense' ? 'Expense of ' . number_format($ledger->amount, 2) . ' was outsourced' : 'Income of ' . number_format($ledger->amount, 2) . ' was made' }}
                                        <br>
                                        {{ $ledger->clientService->remaining_amount > 0
                                            ? "$client_service_name - Remaining: Rs." . number_format($ledger->clientService->remaining_amount, 2)
                                            : 'Cleared' }}
                                        <br>
                                        --remarks::{{ $ledger->income->remarks ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Display Ledger Calculation Totals --}}
            {{-- <div class="mt-4">
                <h4>Client Ledger Summary</h4>
                <p><strong>Total Client Service Amount:</strong> Rs.{{ number_format($totalClientServiceAmount, 2) }}</p>
                <p><strong>Total Income:</strong>
                    Rs.{{ number_format($ledgerCalculationForClient['clientTotalIncome'], 2) }}</p>
                <p><strong>Total Expenses:</strong>
                    Rs.{{ number_format($ledgerCalculationForClient['clientTotalExpense'], 2) }}</p>
                <p><strong>Balance:</strong> Rs.{{ number_format($ledgerCalculationForClient['clientBalance'], 2) }}</p>
                <p><strong>Total Remaining:</strong>
                    Rs.{{ $totalClientServiceAmount - $ledgerCalculationForClient['clientTotalIncome'] }}</p>
            </div> --}}
        </div>
    </div>
</div>
