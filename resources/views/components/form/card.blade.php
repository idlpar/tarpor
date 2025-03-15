@props([
    'label' => '', // Default value for `label` is an empty string
    'required' => false, // Default value for `required` is `false`
])

<!-- Card Component -->
<div class="mb-6 bg-white p-6 rounded-lg shadow-lg">
    <!-- Label with Bottom Border -->
    <div class="border-b border-gray-200 pb-2 mb-4">
        <label class="block font-semibold text-gray-700">
            {{ $label }} <!-- Display the label -->
            @if ($required) <!-- Check if `required` is true -->
            <span class="text-red-500">*</span> <!-- Red asterisk for required fields -->
            @endif
        </label>
    </div>

    <!-- Content -->
    <div class="card-content">
        {{ $slot }} <!-- Slot for dynamic content -->
    </div>
</div>
