<div style="margin:3%;padding-top:2%;box-sizing:border-box">
    <div class="footer">
        <div class="footer-wrap pd-20 card-box">
            Real Accounting System | All rights reserved by <a href="" target="_blank">(R-A-S)</a>
        </div>
    </div>
</div>



<!-- Core JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- ================== BEGIN core-js ================== -->
<script src="{{ asset('/assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('/assets/js/app.min.js') }}"></script>
<!-- ================== END core-js ================== -->
<!-- DataTables JS -->
<script src="{{ asset('assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/dist/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/build/vfs_fonts.js') }}"></script>

<!-- Custom Scripts -->
<script src="{{ asset('vendors/scripts/core.js') }}"></script>
<script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
<script src="{{ asset('vendors/scripts/process.js') }}"></script>
<script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>

<!-- Toastr Notification -->
<script>
    @if (session('success'))
        toastr.success("{{ session('success') }}", "Success");
    @endif
    @if (session('error'))
        toastr.error("{{ session('error') }}", "Error");
    @endif
</script>

<script>
    function confirmDeleteThis(clientId) {
        if (confirm('Are you sure you want to delete this client?')) {
            document.getElementById('confirmDelete' + clientId).submit();
        }
    }
</script>

@yield('footer_file')
@stack('script-items')
@stack('scripts')
