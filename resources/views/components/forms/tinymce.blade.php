@props(['id', 'name', 'value' => ''])

<div class="mb-6">
    <label for="{{ $id }}" class="block font-semibold text-gray-700 mb-2">{{ $slot }}</label>
    <textarea id="{{ $id }}" name="{{ $name }}" class="tinymce-editor w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error($name) border-red-500 @enderror">{!! $value !!}</textarea>
    @error($name)
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
