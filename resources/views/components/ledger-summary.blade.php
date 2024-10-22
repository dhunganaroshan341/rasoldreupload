<div>
    <span class="text-info">
        {{ $ledger->remaining_amount != null ? $client_service_name : '' }}
    </span>
    <span class="text-muted"> - remaining up to: {{ $ledger->transaction_date }}</span>
    <span
        class="text-warning">{{ $ledger->remaining_amount != null ? number_format($ledger->remaining_amount) : '' }}</span>
    <br>
    <span class="text-success">
        {{ $remainingAmount !== 'N/A' ? $client_service_name : 'N/A' }}
    </span>
    <span class="text-muted"> - total Remaining = </span>
    <span class="text-warning">{{ $remainingAmount !== 'N/A' ? '$' . number_format($remainingAmount, 2) : 'N/A' }}</span>
    <br>
    <span class="text-muted">
        @if ($ledger->transaction_type == 'income')
            @if ($ledger->remaining_amount > 0)
                This entry is categorized as income, with a remaining balance of
                ${{ number_format($ledger->remaining_amount, 2) }} as of {{ $ledger->transaction_date }}.
            @elseif ($ledger->remaining_amount == 0)
                This entry is categorized as income, but there is no remaining balance as of
                {{ $ledger->transaction_date }}.
            @else
                This entry is categorized as income, but it appears you have a deficit of
                ${{ number_format(abs($ledger->remaining_amount), 2) }} as of {{ $ledger->transaction_date }}.
            @endif
        @else
            @if ($ledger->remaining_amount > 0)
                This entry is categorized as an expense, with a remaining balance of
                ${{ number_format($ledger->remaining_amount, 2) }} as of {{ $ledger->transaction_date }}.
            @elseif ($ledger->remaining_amount == 0)
                This entry is categorized as an expense, but there is no remaining balance as of
                {{ $ledger->transaction_date }}.
            @else
                This entry is categorized as an expense, but it appears you have a deficit of
                ${{ number_format(abs($ledger->remaining_amount), 2) }} as of {{ $ledger->transaction_date }}.
            @endif
        @endif
    </span>care of the present moment. - Thich Nhat Hanh -->
</div>
