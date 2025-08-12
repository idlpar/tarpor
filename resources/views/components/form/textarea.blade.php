@props(['name', 'label' => null, 'required' => false, 'rows' => 3])

<div class="col-span-1 lg:col-span-2">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500']) }}
    >{{ $slot ?? old($name) }}</textarea>
    @error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
