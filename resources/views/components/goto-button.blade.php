<a name="{{ $name }}" id="{{ $name }}" class="btn buttonInnerBoxShadow   text-grayish mt-3 mr-4"
    href="{{ $route }}" role="button">{{ $name }}</a>
@push('styles')
    <style>
        .buttonInnerBoxShadow {
            box-shadow: inset 2px 2px 2px 2px lightgray;
        }

        .text-grayish {
            color: #767259e3
        }
    </style>
@endpush
