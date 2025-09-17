@props(['category', 'selected' => [], 'level' => 0])
{{--{{ $category->id }} - Selected: {{ json_encode($selected) }}--}}
<li class="relative pl-2 category-tree">
    <div class="flex items-center space-x-2">
        <!-- Tree Connector -->
        @if ($level > 0)
            <div class="absolute left-[-12px] top-1 w-4 border-l border-gray-400"></div>
            <div class="absolute left-[-8px] h-0.5 w-4 bg-gray-400"></div>
        @endif

        <!-- Checkbox -->
        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" data-name="{{ $category->name }}"
               class="rounded text-blue-500 border-gray-300 focus:ring focus:ring-blue-300 hover:border-blue-500 transition-all duration-200"
            {{ in_array($category->id, $selected, true) ? 'checked' : '' }}>

        <!-- Category Name -->
        <span class="text-gray-800 font-medium hover:text-blue-600 transition duration-200 cursor-pointer">
            {{ $category->name }}
        </span>
    </div>

    <!-- Child Categories -->
    @if ($category->children->count())
        <ul class="ml-2 mt-1 border-l border-gray-300 pl-4 space-y-1">
            @foreach ($category->children as $child)
                @include('components.form.category-tree-item', ['category' => $child, 'selected' => $selected, 'level' => $level + 1])
            @endforeach
        </ul>
    @endif
</li>
