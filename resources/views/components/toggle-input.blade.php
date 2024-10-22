<div class="form-group mt-3" id="toggleInputForm" style="display: none;">
    <label for="toggleInput">Add New</label>
    <input type="text" class="form-control" name="toggleInput" id="toggleInput" placeholder="Enter new">
</div>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#service_type').on('change', function() {
                if (this.value === 'new') {
                    $('#toggleInputForm').show();
                } else {
                    $('#toggleInputForm').hide();
                }
            });
        });
    </script>
@endpush
