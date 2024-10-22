<!-- Invoice Form Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Generate Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Invoice Details Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Client Service Name</th>
                            <th>Income Amount</th>
                            {{-- <th>Total Amount</th>
                            <th>Remaining Amount</th> --}}
                        </tr>
                    </thead>
                    <tbody id="invoiceDetailsList">
                        <!-- Populated by AJAX -->
                    </tbody>
                </table>

                <!-- Summary Section -->
                <div class="d-flex justify-content-end mt-3">
                    <div>
                        <p><strong>Paid Amount: </strong><span id="totalPaidAmount">$0.00</span></p>
                        <p><strong>Total Amount: </strong><span id = "totalClientServiceAmount">$0.00</span></p>
                        <p><strong>Remaining Amount: </strong><span id="totalRemainingAmount">$0.00</span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="generateInvoiceButton">Generate Invoice</button>
            </div>
        </div>
    </div>
</div>
<script></script>
