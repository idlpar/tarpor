@extends('layouts.admin')

@section('admin_content')
    <div class="container mx-auto p-4">
        @include('components.admin.breadcrumb')
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Add New Product Specification Table</h1>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.product_specifications.tables.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Table Name:</label>
                    <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Table Type:</label>
                    <select name="type" id="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Select Type</option>
                        <option value="technical" {{ old('type') == 'technical' ? 'selected' : '' }}>Technical Specification</option>
                        <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General Specification</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Select Groups:</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($groups as $group)
                            <div class="flex items-center">
                                <input type="checkbox" name="selected_groups[]" id="group_{{ $group->id }}" value="{{ $group->id }}" class="form-checkbox h-5 w-5 text-blue-600" data-group-name="{{ $group->name }}">
                                <label for="group_{{ $group->id }}" class="ml-2 text-gray-700">{{ $group->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Selected Groups Order:</label>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <ul id="sortable-groups" class="space-y-2">
                            <!-- Selected groups will be added here dynamically -->
                        </ul>
                        @error('groups')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Add Table
                    </button>
                    <a href="{{ route('admin.product_specifications.tables.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sortableList = document.getElementById('sortable-groups');
            const checkboxes = document.querySelectorAll('input[name="selected_groups[]"]');

            new Sortable(sortableList, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function (evt) {
                    updateHiddenInputs();
                },
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const groupId = this.value;
                    const groupName = this.dataset.groupName;
                    if (this.checked) {
                        const listItem = document.createElement('li');
                        listItem.id = `group-item-${groupId}`;
                        listItem.classList.add('bg-white', 'p-2', 'rounded', 'shadow-sm', 'flex', 'justify-between', 'items-center');
                        listItem.innerHTML = `
                            <span>${groupName}</span>
                            <input type="hidden" name="groups[]" value="${groupId}">
                        `;
                        sortableList.appendChild(listItem);
                    } else {
                        const listItem = document.getElementById(`group-item-${groupId}`);
                        if (listItem) {
                            listItem.remove();
                        }
                    }
                    updateHiddenInputs();
                });
            });

            function updateHiddenInputs() {
                // Ensure the order of hidden inputs matches the sorted list
                const orderedGroupIds = Array.from(sortableList.children).map(item => item.querySelector('input[type="hidden"]').value);
                // Clear existing hidden inputs and re-add them in the correct order
                sortableList.querySelectorAll('input[name="groups[]"]').forEach(input => input.remove());
                orderedGroupIds.forEach(groupId => {
                    const listItem = document.getElementById(`group-item-${groupId}`);
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'groups[]';
                    hiddenInput.value = groupId;
                    listItem.appendChild(hiddenInput);
                });
            }

            // Initial population of sortable list based on old input if validation fails
            @if(old('groups'))
                const oldGroups = {!! json_encode(old('groups')) !!};
                const allGroups = {!! json_encode($groups->pluck('name', 'id')) !!};

                oldGroups.forEach(groupId => {
                    const groupName = allGroups[groupId];
                    if (groupName) {
                        const listItem = document.createElement('li');
                        listItem.id = `group-item-${groupId}`;
                        listItem.classList.add('bg-white', 'p-2', 'rounded', 'shadow-sm', 'flex', 'justify-between', 'items-center');
                        listItem.innerHTML = `
                            <span>${groupName}</span>
                            <input type="hidden" name="groups[]" value="${groupId}">
                        `;
                        sortableList.appendChild(listItem);
                        // Check the corresponding checkbox
                        const checkbox = document.getElementById(`group_${groupId}`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    }
                });
                updateHiddenInputs();
            @endif
        });
    </script>
@endsection
