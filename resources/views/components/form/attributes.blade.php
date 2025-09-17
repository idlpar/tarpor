@props(['name' => 'attributes', 'label' => 'Product Attributes'])

<div class="col-span-1 lg:col-span-2">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <div x-data="{ attributes: {{ json_encode(old($name, [['key' => '', 'value' => '']])) }} }">
        <template x-for="(attribute, index) in attributes" :key="index">
            <div class="flex items-center space-x-4 mb-4">
                <input
                    type="text"
                    x-model="attribute.key"
                    :name="`{{ $name }}[${index}][key]`"
                    placeholder="Attribute Name"
                    class="block w-1/2 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <input
                    type="text"
                    x-model="attribute.value"
                    :name="`{{ $name }}[${index}][value]`"
                    placeholder="Attribute Value"
                    class="block w-1/2 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <button
                    type="button"
                    @click="attributes.splice(index, 1)"
                    class="text-red-500 hover:text-red-700 focus:outline-none"
                >
                    Remove
                </button>
            </div>
        </template>
        <button
            type="button"
            @click="attributes.push({ key: '', value: '' })"
            class="text-blue-500 hover:text-blue-700 focus:outline-none"
        >
            Add Attribute
        </button>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
