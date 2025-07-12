@foreach($categories as $category)
    <li>
        <label class="form-check flex items-center py-1">
            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                   class="form-check-input h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                {{ (isset($selected) && in_array($category->id, $selected)) ? 'checked' : '' }}>
            <span class="category-label ml-2 text-gray-700">
                {{ $category->name }}
            </span>
        </label>

        @if($category->children->isNotEmpty())
            <ul class="list-unstyled ms-4 mt-1">
                @include('partials.category-checkboxes', ['categories' => $category->children, 'selected' => $selected])
            </ul>
        @endif
    </li>
@endforeach
