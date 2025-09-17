{{--<ul class="category-tree">--}}
{{--    @foreach ($categories as $category)--}}
{{--        <li>--}}
{{--            <label>--}}
{{--                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"--}}
{{--                    {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>--}}
{{--                {{ $category->name }}--}}
{{--            </label>--}}

{{--            @if ($category->children->count())--}}
{{--                <ul>--}}
{{--                    @include('components.category-tree', ['categories' => $category->children])--}}
{{--                </ul>--}}
{{--            @endif--}}
{{--        </li>--}}
{{--    @endforeach--}}
{{--</ul>--}}
