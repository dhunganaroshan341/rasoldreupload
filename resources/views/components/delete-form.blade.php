<form id="{{ $formId }}" action="{{ $actionUrl }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ $formId }}')">
        {{ $buttonText }}
    </button>
</form>

<script>
    function confirmDelete(formId) {
        if (confirm('Are you sure you want to delete this item?')) {
            document.getElementById(formId).submit();
        }
    }
</script>
