<div class="action-buttons">
    @if ($indexRoute)
        <a href="{{ route($indexRoute, [$indexRouteIdVariable => $indexRouteId]) }}" class="btn btn-primary btn-sm">
            <i class="fa fa-list"></i>
        </a>
    @endif

    @if ($showRoute)
        <a href="{{ route($showRoute, [$showRouteIdVariable => $showRouteId]) }}" class="btn btn-info btn-sm">
            <i class="fa fa-eye"></i>
        </a>
    @else
        <a href="{{ route($route . '.show', [$routeIdVariable => $routeId]) }}" class="btn btn-info btn-sm">
            <i class="fa fa-eye"></i>
        </a>
    @endif

    @if ($editRoute)
        <a href="{{ route($editRoute, [$editRouteIdVariable => $editRouteId]) }}" class="btn btn-warning btn-sm">
            <i class="fa fa-edit"></i>
        </a>
    @else
        <a href="{{ route($route . '.edit', [$routeIdVariable => $routeId]) }}" class="btn btn-warning btn-sm">
            <i class="fa fa-edit"></i>
        </a>
    @endif

    <form
        action="{{ $destroyRoute ? route($destroyRoute, [$destroyRouteIdVariable => $destroyRouteId]) : route($route . '.destroy', [$routeIdVariable => $routeId]) }}"
        method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm delete-btn">
            <i class="fa fa-trash text-dark"></i>
        </button>
    </form>
</div>
<style>
    .action-row {
        position: relative;
    }

    .action-buttons {
        display: none;
        position: absolute;
        right: 0;
        top: 0;
        background: rgba(41, 38, 38, 0.3);
        padding: 1px;
        box-shadow: 0 2px 5px rgba(211, 204, 204, 0.2);
        transition: opacity 0.5s ease, transform 0.5s ease;
        white-space: nowrap;
        transform: translateY(-100%);
        opacity: 0;
        z-index: 1000;
    }

    .action-row:hover .action-buttons {
        display: block;
        transform: translateY(0);
        opacity: 1;
    }

    .action-buttons a,
    .action-buttons button {
        display: inline-block;
        margin-right: 10px;
    }

    .action-buttons a {
        text-decoration: none;
        color: inherit;
    }

    .action-buttons button {
        border: none;
        background: none;
        cursor: pointer;
    }

    .delete-form {
        display: inline-block;
    }
</style>
