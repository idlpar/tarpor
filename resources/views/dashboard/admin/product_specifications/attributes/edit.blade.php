@extends('layouts.admin')

@section('title', 'Edit Product Specification Attribute')

@section('admin_content')
    <div class="container mx-auto px-4 py-4">
        @include('components.breadcrumbs', [
            'links' => [
                'Product Specification Attributes' => route('admin.product_specifications.attributes.index'),
                'Edit' => null
            ]
        ])

        <x-ui.page-header title="Edit Product Specification Attribute" description="Update the details of the specification attribute.">
            <a href="{{ route('admin.product_specifications.attributes.index') }}" class="ml-4 flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                View All Attributes
            </a>
        </x-ui.page-header>

        <x-ui.session-messages />

        <form action="{{ route('admin.product_specifications.attributes.update', $attribute) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left Column -->
                <div class="w-full lg:w-9/12">
                    <x-ui.content-card class="p-6">
                        <div class="mb-4">
                            <label for="product_specification_group_id" class="block text-gray-700 text-sm font-bold mb-2">Group:</label>
                            <select name="product_specification_group_id" id="product_specification_group_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Select a Group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ old('product_specification_group_id', $attribute->product_specification_group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('product_specification_group_id')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Attribute Name:</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $attribute->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            @error('name')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="unit" class="block text-gray-700 text-sm font-bold mb-2">Unit (Optional):</label>
                            <input type="text" name="unit" id="unit" value="{{ old('unit', $attribute->unit) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('unit')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </x-ui.content-card>
                </div>

                <!-- Right Column -->
                <div class="w-full lg:w-3/12 sticky top-6">
                    <x-ui.content-card class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Publish</h3>
                        <div class="pt-4 border-t border-gray-200 flex gap-4">
                            <button type="submit" id="saveButton" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors text-sm">Update</button>
                            <button type="submit" name="save_exit" value="1" id="saveExitButton" class="flex-1 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors text-sm">Update & Exit</button>
                        </div>
                    </x-ui.content-card>
                </div>
            </div>
        </form>
    </div>
@endsection