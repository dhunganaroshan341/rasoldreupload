<x-head-upto-body />
<x-header />
<x-right-side-bar />
{{-- getting menuItems from ViewService provider --}}
<x-left-side-bar :menu-items="$menuItems" />
<x-content-component />
<x-footercomponent />
