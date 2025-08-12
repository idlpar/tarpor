@props(['name', 'label' => null, 'type' => 'text', 'required' => false, 'value' => null, 'urlPrefix' => null, 'hasDropdown' => false])

<div class="col-span-1">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    <div class="relative mt-1">
        <div class="flex">
            @if($urlPrefix)
                <span class="min-w-fit flex items-center px-4 py-2 text-sm text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">
                    {{ $urlPrefix }}
                </span>
            @endif
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $name }}"
                value="{{ old($name, $value) }}"
                {{ $required ? 'required' : '' }}
                @class([
                    'block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500',
                    'rounded-l-none' => $urlPrefix,
                    'pl-2' => !$urlPrefix
                ])
                {{ $attributes->except('class') }}
            >
        </div>

        <!-- Dropdown for suggestions -->
        @if($hasDropdown)
            <div id="{{ $name }}-suggestions" class="hidden absolute mt-1 w-full bg-gray-100 border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto z-10"></div>
        @endif
    </div>

    @error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
