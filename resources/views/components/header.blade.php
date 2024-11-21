<div class="header">
    <div class="header-left">
        {{-- back button left --}}
        @include('components.back-button')
        <div class="menu-icon dw dw-menu"></div>
        <div class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
        <!-- Here we're including the dropdown inside header-left section -->
        <div class="header-search">
            <div class="d-flex ml-2">
                {{-- yielding header left --}}
                @yield('header-left')
            </div>
        </div>
    </div>
    <div class="header-right">
        <div class="header-search">
            <div class="d-flex ml-1">
                {{-- yielding header right --}}
                @yield('header-right')
            </div>
        </div>
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <i class="fa fa-user"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="{{ route('profile') }}"><i class="fa fa-user"></i> Profile</a>
                    <a class="dropdown-item" href="{{ route('profile') }}"><i class="dw dw-settings2"></i> Setting</a>
                    <a class="dropdown-item" href="faq.html"><i class="dw dw-help"></i> Help</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"><i class="dw dw-logout"></i> Log Out</a>
                </div>
            </div>
        </div>
    </div>
</div>
