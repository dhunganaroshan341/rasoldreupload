<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <!-- Navigation bar or other header content -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @yield('content')
</div>
