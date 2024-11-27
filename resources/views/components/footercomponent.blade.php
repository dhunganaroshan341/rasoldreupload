<div style="margin:3%;padding-top:2%;box-sizing:border-box">
    {{-- @livewireScripts --}}
    <!-- js -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- toaster messge --}}
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- jQuery -->
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js')}}"></script> --}}
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- DataTables JS (if needed) -->
    {{-- <script src="https://cdn.datatables.net/2.1.2/js/jquery.dataTables.min.js"></script> --}}


    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>
    <script src="{{ asset('assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/dist/jszip.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
    <script src="{{ asset('src/plugins/jQuery-Knob-master/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('src/plugins/highcharts-6.0.7/code/highcharts.js') }}"></script>
    <script src="{{ asset('src/plugins/highcharts-6.0.7/code/highcharts-more.js') }}"></script>
    {{-- <script src="{{ asset('src/plugins/jvectormap/jquery-jvectormap-2.0.3.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('src/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script> --}}
    <script src="{{ asset('vendors/scripts/dashboard2.js') }}"></script>
    <script src="{{ asset('/assets/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
    {{-- toaster success error --}}
    <script>
        $(document).ready(function() {
            // Check for success message from session
            @if (session('success'))
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "3000", // How long the toast will be visible (in milliseconds)
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn", // Animation for showing
                    "hideMethod": "fadeOut" // Animation for hiding
                };
                toastr.success("{{ session('success') }}", "Success");
            @endif

            // Check for success message passed directly
            @if (isset($successMessage))
                toastr.success("{{ $successMessage }}", "Success");
            @endif

            // Check for error message
            @if (session('error'))
                toastr.error("{{ session('error') }}", "Error");
            @endif
        });
    </script>


    @yield('footer_file')


    @stack('script-items') <!-- This will include your custom scripts -->
    <!-- Livewire Scripts (best placed just before closing body tag) -->


    <div class="footer">
        <div class="footer-wrap pd-20 card-box">
            Real Accounting System | All right reserved by <a href="" target="_blank">(R-A-S)</a>
        </div>

    </div>
</div>

@stack('scripts')
</body>

</html>
