@extends('layouts.admin')

@section('admin_content')
    <div class="container mx-auto p-4">
        @include('components.admin.breadcrumb')
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Add New Product Specification Attribute</h1>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.product_specifications.attributes.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="product_specification_group_id" class="block text-gray-700 text-sm font-bold mb-2">Group:</label>
                    <select name="product_specification_group_id" id="product_specification_group_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Select a Group</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('product_specification_group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                    @error('product_specification_group_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Attribute Name:</label>
                    <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="unit" class="block text-gray-700 text-sm font-bold mb-2">Unit (Optional):</label>
                    <input type="text" name="unit" id="unit" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('unit') }}">
                    @error('unit')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Add Attribute
                    </button>
                    <a href="{{ route('admin.product_specifications.attributes.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
