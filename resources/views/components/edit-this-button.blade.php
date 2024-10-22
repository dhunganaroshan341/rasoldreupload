<a href="{{ $routeIdVariable && $routeId ? route($route, [$routeIdVariable => $routeId]) : route($route) }}"
    class="btn btn-primary">
    <i class="fa fa-edit"></i> {{ $label ? $label : 'Edit this' }}
</a>
