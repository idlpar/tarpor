@props(['title' => null, 'maxWidth' => '6xl'])

<div class="max-w-{{ $maxWidth }} mx-auto bg-white shadow-lg rounded-lg p-6 md:p-8">
    @if($title)
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">{{ $title }}</h2>
    @endif
    <div class="space-y-6">
        {{ $slot }}
    </div>
</div>
