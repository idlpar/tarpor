@props(['name', 'label', 'categories' => [], 'required' => false, 'selected' => []])

<div class="relative w-full">
    <!-- Label -->
    <label class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>

    <!-- Dropdown Toggle Button -->
    <button type="button"
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-left bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center hover:border-gray-400 transition-all duration-200"
            onclick="toggleDropdown()">
        <span id="selected-text" class="text-gray-700">Select Categories</span>
        <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" id="dropdown-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    <!-- Dropdown Panel -->
    <div id="dropdown-menu" class="absolute left-0 mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden z-10">
        <div class="max-h-96 overflow-y-auto p-3 bg-gray-50 rounded-md">
            <ul class="space-y-1">
                @foreach ($categories as $category)
                    @include('components.form.category-tree-item', ['category' => $category, 'selected' => $selected, 'level' => 0])
                @endforeach
            </ul>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function toggleDropdown() {
            let dropdown = document.getElementById('dropdown-menu');
            let icon = document.getElementById('dropdown-icon');

            dropdown.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');

            if (!dropdown.classList.contains('hidden')) {
                document.addEventListener('click', closeDropdownOnClickOutside);
            }
        }

        function closeDropdownOnClickOutside(event) {
            let dropdown = document.getElementById('dropdown-menu');
            let button = event.target.closest('button');

            if (!dropdown.contains(event.target) && !button) {
                dropdown.classList.add('hidden');
                document.getElementById('dropdown-icon').classList.remove('rotate-180');
                document.removeEventListener('click', closeDropdownOnClickOutside);
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            let checkboxes = document.querySelectorAll('.category-tree input[type="checkbox"]');
            let selectedText = document.getElementById('selected-text');

            // ✅ Toggle the clicked item's checkbox
            function toggleCategoryCheckbox(event, listItem) {
                if (event.target.tagName === 'INPUT') return;
                event.stopPropagation();

                let checkbox = listItem.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    updateSelectedText();
                }
            }

            // ✅ Update the selected categories text
            function updateSelectedText() {
                let selectedCategories = [];
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        selectedCategories.push(checkbox.dataset.name);
                    }
                });
                selectedText.textContent = selectedCategories.length > 0 ? selectedCategories.join(', ') : "Select Categories";
            }

            // ✅ Attach click event to each list item
            document.querySelectorAll('.category-tree li').forEach(li => {
                li.addEventListener('click', function (e) {
                    toggleCategoryCheckbox(e, li);
                });
            });

            // ✅ Update text when checkboxes change
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener("change", updateSelectedText);
            });

            // ✅ Initialize selected text
            updateSelectedText();
        });
    </script>
@endpush


{{--<script>--}}
{{--    function toggleDropdown() {--}}
{{--        document.getElementById('dropdown-menu').classList.toggle('hidden');--}}
{{--        document.getElementById('dropdown-icon').classList.toggle('rotate-180');--}}
{{--    }--}}

{{--    document.addEventListener("DOMContentLoaded", function () {--}}
{{--        let checkboxes = document.querySelectorAll('.category-tree input[type="checkbox"]');--}}
{{--        let selectedText = document.getElementById('selected-text');--}}

{{--        // ✅ Toggle the clicked item's checkbox--}}
{{--        function toggleCategoryCheckbox(event, listItem) {--}}
{{--            // If the clicked element is the checkbox itself, do nothing (let the checkbox handle its own click)--}}
{{--            if (event.target.tagName === 'INPUT') return;--}}

{{--            // Stop event propagation to prevent parent checkboxes from being toggled--}}
{{--            event.stopPropagation();--}}

{{--            // Find the checkbox within the clicked list item--}}
{{--            let checkbox = listItem.querySelector('input[type="checkbox"]');--}}
{{--            if (checkbox) {--}}
{{--                checkbox.checked = !checkbox.checked;--}}
{{--                updateSelectedText();--}}
{{--            }--}}
{{--        }--}}

{{--        // ✅ Update the selected categories text--}}
{{--        function updateSelectedText() {--}}
{{--            let selectedCategories = [];--}}
{{--            checkboxes.forEach(checkbox => {--}}
{{--                if (checkbox.checked) {--}}
{{--                    selectedCategories.push(checkbox.dataset.name);--}}
{{--                }--}}
{{--            });--}}
{{--            selectedText.textContent = selectedCategories.length > 0 ? selectedCategories.join(', ') : "Select Categories";--}}
{{--        }--}}

{{--        // ✅ Attach click event to each list item--}}
{{--        document.querySelectorAll('.category-tree li').forEach(li => {--}}
{{--            li.addEventListener('click', function (e) {--}}
{{--                toggleCategoryCheckbox(e, li);--}}
{{--            });--}}
{{--        });--}}

{{--        // ✅ Update text when checkboxes change--}}
{{--        checkboxes.forEach(checkbox => {--}}
{{--            checkbox.addEventListener("change", updateSelectedText);--}}
{{--        });--}}

{{--        // ✅ Initialize selected text--}}
{{--        updateSelectedText();--}}
{{--    });--}}
{{--</script>--}}

