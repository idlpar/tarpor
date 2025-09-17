@props(['name', 'class' => ''])

<svg class="inline-block {{ $class }}" aria-hidden="true" loading="lazy">
    <use href="{{ asset('svg/sprite.svg#icon-' . $name) }}" width="100%" height="100%"></use>
</svg>
