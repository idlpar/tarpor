<option value="{{ $category->id }}" {{ $oldValue == $category->id ? 'selected' : '' }}>
    @for($i = 0; $i < $depth; $i++) &nbsp;&nbsp; @endfor
    {{ $category->name }}
</option>

@if($category->children->isNotEmpty())
    @foreach($category->children as $child)
        @include('dashboard.admin.categories.partials.category-option', [
            'category' => $child,
            'depth' => $depth + 1,
            'oldValue' => $oldValue
        ])
    @endforeach
@endif
