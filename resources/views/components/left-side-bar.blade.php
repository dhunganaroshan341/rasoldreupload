<div class="left-side-bar">
    <div class="brand-logo">
        <a href="{{ url('home') }}">Rass</a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu" class="list-unstyled">
                @foreach ($menuItems as $menuItem)
                    @php
                        // Initialize active state as false
                        $isMenuActive = false;

                        // Check if the main menu URL is active, accounting for dynamic URLs with wildcards
                        if (request()->is(trim(parse_url($menuItem['url'] ?? '', PHP_URL_PATH), '/') . '*')) {
                            $isMenuActive = true;
                        }

                        // Check if any of the submenu URLs are active, with wildcard matching
                        foreach ($menuItem['subItems'] ?? [] as $subItem) {
                            if (request()->is(trim(parse_url($subItem['url'] ?? '', PHP_URL_PATH), '/') . '*')) {
                                $isMenuActive = true;
                            }

                            // Check for sub-submenu URLs with wildcard matching
                            foreach ($subItem['subItems'] ?? [] as $subSubItem) {
                                if (request()->is(trim(parse_url($subSubItem['url'] ?? '', PHP_URL_PATH), '/') . '*')) {
                                    $isMenuActive = true;
                                }
                            }
                        }
                    @endphp
                    <li class="dropdown {{ $isMenuActive ? 'active' : '' }}">
                        <a href="javascript:;" class="dropdown-toggle {{ $isMenuActive ? 'active' : '' }}">
                            <span class="micon dw {{ $menuItem['icon'] }}"></span>
                            <span class="mtext">{{ $menuItem['name'] }}</span>
                        </a>
                        <ul class="submenu list-unstyled">
                            @foreach ($menuItem['subItems'] ?? [] as $subItem)
                                @php
                                    // Check if the submenu item is active with wildcard matching
                                    $isSubItemActive = request()->is(
                                        trim(parse_url($subItem['url'] ?? '', PHP_URL_PATH), '/') . '*',
                                    );
                                @endphp
                                @if (isset($subItem['subItems']))
                                    <li class="dropdown {{ $isSubItemActive ? 'active' : '' }}">
                                        <a href="javascript:;" class="dropdown-toggle">{{ $subItem['name'] }}</a>
                                        <ul class="submenu list-unstyled">
                                            @foreach ($subItem['subItems'] ?? [] as $subSubItem)
                                                @php
                                                    // Check if the sub-submenu item URL is active with wildcard matching
                                                    $isSubSubItemActive = request()->is(
                                                        trim(parse_url($subSubItem['url'] ?? '', PHP_URL_PATH), '/') .
                                                            '*',
                                                    );
                                                @endphp
                                                <li class="{{ $isSubSubItemActive ? 'active' : '' }}">
                                                    <a href="{{ $subSubItem['url'] }}">{{ $subSubItem['name'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li class="{{ $isSubItemActive ? 'active' : '' }}">
                                        <a class="{{ $isSubItemActive ? 'active' : '' }}"
                                            href="{{ $subItem['url'] }}">{{ $subItem['name'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
