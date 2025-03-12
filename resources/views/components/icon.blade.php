@props(['name', 'class' => ''])

<svg class="inline-block {{ $class }}" aria-hidden="true">
    <use xlink:href="{{ asset('svg/sprite.svg#' . $name) }}" width="100%" height="100%"></use>
</svg>
