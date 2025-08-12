@extends('layouts.admin')

@section('title', 'Edit Product Specification Table')

@section('admin_content')
    <div class="container mx-auto px-4 py-4">
        @include('components.breadcrumbs', [
            'links' => [
                'Product Specification Tables' => route('admin.product_specifications.tables.index'),
                'Edit' => null
            ]
        ])

        <x-ui.page-header title="Edit Product Specification Table" description="Update the details of the specification table.">
            <a href="{{ route('admin.product_specifications.tables.index') }}" class="ml-4 flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                View All Tables
            </a>
        </x-ui.page-header>

        <x-ui.session-messages />

        <form action="{{ route('admin.product_specifications.tables.update', $table) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left Column -->
                <div class="w-full lg:w-9/12">
                    <x-ui.content-card class="p-6">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Table Name:</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $table->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            @error('name')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Table Type:</label>
                            <select name="type" id="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Select Type</option>
                                <option value="technical" {{ old('type', $table->type) == 'technical' ? 'selected' : '' }}>Technical Specification</option>
                                <option value="general" {{ old('type', $table->type) == 'general' ? 'selected' : '' }}>General Specification</option>
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
                                        <input type="checkbox" name="selected_groups[]" id="group_{{ $group->id }}" value="{{ $group->id }}" class="form-checkbox h-5 w-5 text-blue-600" data-group-name="{{ $group->name }}" {{ in_array($group->id, $selectedGroups) ? 'checked' : '' }}>
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

                // Initial population for checked checkboxes
                if (checkbox.checked) {
                    const groupId = checkbox.value;
                    const groupName = checkbox.dataset.groupName;
                    const listItem = document.createElement('li');
                    listItem.id = `group-item-${groupId}`;
                    listItem.classList.add('bg-white', 'p-2', 'rounded', 'shadow-sm', 'flex', 'justify-between', 'items-center');
                    listItem.innerHTML = `
                        <span>${groupName}</span>
                        <input type="hidden" name="groups[]" value="${groupId}">
                    `;
                    sortableList.appendChild(listItem);
                }
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
        });
    </script>
@endsection