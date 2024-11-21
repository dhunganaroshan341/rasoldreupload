<div class="dropdown ">
    <button type="button" class="btn btn-secondary dropdown-toggle" id="dropdownMenuOffset" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false" data-offset="10,20">
        {{ $name }}
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
        @foreach ($dropdownItem as $item)
            <a class="dropdown-item" href="{{ $item->link }}">
                <i class="fas fa-{{ $item->faClass ?? '' }}"></i> {{ $item->linkName }}
            </a>
        @endforeach
    </div>
</div>
