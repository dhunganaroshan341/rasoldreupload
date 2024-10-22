<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title> Rass </title>
    <!-- Add Livewire Styles -->

    <!-- Site favicon -->
    {{-- <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}"> --}}
    {{-- <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}"> --}}
    {{-- <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}"> --}}

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @livewireStyles
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('src/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('src/fonts/foundation-icons/foundation-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/vendors/styles/core.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/vendors/styles/icon-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('src/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/src/plugins/jvectormap/jquery-jvectormap-2.0.3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}"> --}}
    <script src="//cdn.datatables.net/2.1.2/js/dataTables.min.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js')}}"></script>

    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}"
        rel="stylesheet" />
    @yield('header_file')
    @yield('script')
    @yield('styles')
    <style>
        /* Tooltip container */
        .tooltip-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        /* Tooltip text */
        .tooltip-text {
            visibility: hidden;
            width: 160px;
            background-color: #333;
            color: #834a4a;
            text-align: center;
            border-radius: 5px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 100%;
            /* Position above the container */
            left: 50%;
            margin-left: -80px;
            /* Center the tooltip */
            opacity: 0;
            transition: opacity 0.3s;
            white-space: nowrap;
        }

        /* Show tooltip on hover */
        .tooltip-container:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>

</head>

<body>

    <div class="header">
        <div class="header-left">
            {{-- <div class="form-group col-md-2 mt-2 align-self-end" onclick="toggleFullScreen()">
                <button type="button" class="btn btn-outline-dark">
                    <i class="dw dw-expand"></i> Full Screen
                </button>
            </div> --}}

            {{-- back button left --}}
            @include('components.back-button')

            <div class="menu-icon dw dw-menu"></div>
            <div class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
            <div class="header-search">
            </div>
        </div>
        <div class="header-right">



            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <span class="user-icon">
                            <img src="vendors/images/photo1.jpg" alt="">
                        </span>
                        {{-- <span class="user-name">{{ Auth::user()->name }}</span> --}}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="{{ route('profile') }}"><i class="dw dw-user1"></i> Profile</a>
                        <a class="dropdown-item" href="{{ route('profile') }}"><i class="dw dw-settings2"></i>
                            Setting</a>
                        <a class="dropdown-item" href="faq.html"><i class="dw dw-help"></i> Help</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"><i class="dw dw-logout"></i> Log
                            Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right-sidebar">
        <div class="sidebar-title">
            <h3 class="weight-600 font-16 text-blue">
                Layout Settings
                <span class="btn-block font-weight-400 font-12">User Interface Settings</span>
            </h3>
            <div class="close-sidebar" data-toggle="right-sidebar-close">
                <i class="icon-copy ion-close-round"></i>
            </div>
        </div>
        <div class="right-sidebar-body customscroll">
            <div class="right-sidebar-body-content">
                <h4 class="weight-600 font-18 pb-10">Header Background</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
                    <a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light ">White</a>
                    <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Dark</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
                <div class="sidebar-radio-group pb-10 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-1" name="menu-dropdown-icon" class="custom-control-input"
                            value="icon-style-1" checked="">
                        <label class="custom-control-label" for="sidebaricon-1"><i class="fa fa-angle-down"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-2" name="menu-dropdown-icon"
                            class="custom-control-input" value="icon-style-2">
                        <label class="custom-control-label" for="sidebaricon-2"><i
                                class="ion-plus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-3" name="menu-dropdown-icon"
                            class="custom-control-input" value="icon-style-3">
                        <label class="custom-control-label" for="sidebaricon-3"><i
                                class="fa fa-angle-double-right"></i></label>
                    </div>
                </div>

                <h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
                <div class="sidebar-radio-group pb-30 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-1" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-1" checked="">
                        <label class="custom-control-label" for="sidebariconlist-1"><i
                                class="ion-minus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-2" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-2">
                        <label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o"
                                aria-hidden="true"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-3" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-3">
                        <label class="custom-control-label" for="sidebariconlist-3"><i
                                class="dw dw-check"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-4" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-4" checked="">
                        <label class="custom-control-label" for="sidebariconlist-4"><i
                                class="icon-copy dw dw-next-2"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-5" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-5">
                        <label class="custom-control-label" for="sidebariconlist-5"><i
                                class="dw dw-fast-forward-1"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-6" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-6">
                        <label class="custom-control-label" for="sidebariconlist-6"><i
                                class="dw dw-next"></i></label>
                    </div>
                </div>

                <div class="reset-options pt-30 text-center">
                    <button class="btn btn-danger" id="reset-settings">Reset Settings</button>
                </div>
            </div>
        </div>
    </div>

    @php
        $menuItems = [
            [
                'name' => 'Our Services',
                'icon' => 'dw dw-settings',

                'route' => 'OurServices.index', // Main menu item route
                'subItems' => [
                    ['name' => 'View All Services', 'route' => 'OurServices.index'],
                    [
                        'name' => 'Categories',
                        'route' => 'ServiceCategory.index',
                        'subItems' => [
                            ['name' => 'All Categories', 'route' => 'ServiceCategory.index'],
                            ['name' => ' New Categories ', 'route' => 'ServiceCategory.create'],
                        ],
                    ],
                    ['name' => 'New Service', 'route' => 'OurServices.create'],
                ],
            ],
            [
                'name' => 'Clients',
                'icon' => 'dw dw-user',
                'route' => 'clients.index', // Main menu item route
                'subItems' => [
                    ['name' => 'All Clients', 'route' => 'clients.index'],
                    ['name' => 'Add New Client', 'route' => 'clients.create'],
                ],
            ],
            // [
            //     'name' => 'Client Projects  ',
            //     'icon' => 'dw dw-file',
            //     'route' => 'contracts.index', // Main menu item route
            //     'subItems' => [
            //         ['name' => 'All Contracts', 'route' => 'contracts.index'],
            //         ['name' => 'New Contract', 'route' => 'contracts.create'],
            //         ['name' => 'Custom Create', 'route' => 'contracts.create.custom'],
            //     ],
            // ],
            [
                'name' => 'Transactions',
                'icon' => 'dw dw-exchange',
                'route' => '',
                'subItems' => [
                    ['name' => 'View Transactions', 'route' => 'transactions.index'],
                    [
                        'name' => 'Incomes',
                        'subItems' => [['name' => 'Create New', 'route' => 'incomes.create']],
                    ],
                    [
                        'name' => 'Expenses',
                        'subItems' => [['name' => 'Create New', 'route' => 'expenses.create']],
                    ],
                ],
            ],
            [
                'name' => 'Invoices',
                'icon' => 'dw dw-invoice',
                'route' => '',
                'subItems' => [
                    ['name' => 'View Invoices', 'route' => 'invoices.index'],
                    [
                        'name' => 'create',
                        'subItems' => [['name' => 'Create New', 'route' => 'incomes.create']],
                    ],
                ],
            ],
            [
                'name' => 'Ledger',
                'icon' => 'dw dw-book',
                'route' => 'ledger.index',
                'subItems' => [
                    ['name' => 'View ledger', 'route' => 'ledger.index'],
                    [
                        'name' => 'create',
                        'subItems' => [['name' => 'Create New', 'route' => 'ledger.create']],
                    ],
                ],
            ],
        ];
    @endphp



    @push('css')
        {{-- <style>
            small {
                text-decoration: none !important;
                /* Reset the text decoration */
            }
        </style> --}}
    @endpush

    <div class="left-side-bar containter-fluid">
        <div class="brand-logo">
            <a href="{{ url('home') }}">
                Rass
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu" class="list-unstyled">
                    @foreach ($menuItems as $menuItem)
                        <li class="dropdown {{ request()->is($menuItem['route'] . '*') ? 'active' : '' }}">
                            <a href="javascript:;" class="dropdown-toggle">
                                <span class="micon dw {{ $menuItem['icon'] }}"></span>
                                <span class="mtext">{{ $menuItem['name'] }}</span>
                            </a>
                            <ul class="submenu list-unstyled">
                                @foreach ($menuItem['subItems'] as $subItem)
                                    @if (isset($subItem['subItems']))
                                        <li class="dropdown">
                                            <a href="javascript:;"
                                                class="dropdown-toggle ">{{ $subItem['name'] }}</a>
                                            <ul class="submenu list-unstyled">
                                                @foreach ($subItem['subItems'] as $subSubItem)
                                                    <li><a href="{{ route($subSubItem['route']) }}"
                                                            class="">{{ $subSubItem['name'] }}</a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        <li><a href="{{ route($subItem['route']) }}"
                                                class="">{{ $subItem['name'] }}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>





    <div class="mobile-menu-overlay"></div>

    <main class="main-container">
        @yield('content')
    </main>
    <div class="footer">
        <div class="footer-wrap pd-20 card-box">
            Billing Management System | All right reserved by <a href="" target="_blank">Roshan
                Dhungana</a>
        </div>

    </div>
    @livewireScripts

    <!-- js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
    @yield('footer_file')
    <script>
        function toggleFullScreen() {
            let elem = document.documentElement;
            if (!document.fullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.webkitRequestFullscreen) {
                    /* Safari */
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    /* IE11 */
                    elem.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    /* Safari */
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    /* IE11 */
                    document.msExitFullscreen();
                }
            }
        }
    </script>
    <!-- Livewire Scripts (best placed just before closing body tag) -->

</body>

</html>
