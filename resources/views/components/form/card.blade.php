@props([
    'label' => '',
    'required' => false,
    'class' => '', // Add class prop for additional styling
    'bodyClass' => '', // Add body class prop
])

<div class="mb-6 bg-white p-6 rounded-lg shadow-lg {{ $class }}">
    <div class="border-b border-gray-200 pb-2 mb-4">
        <label class="block font-semibold text-gray-700">
            {{ $label }}
            @if ($required)<span class="text-red-500">*</span>@endif
        </label>
    </div>
    <div class="card-content {{ $bodyClass }}">
        {{ $slot }}
    </div>
</div>
