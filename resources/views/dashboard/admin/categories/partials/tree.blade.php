@foreach($tree as $category)
    @include('dashboard.admin.categories.partials.tree-node', ['category' => $category, 'level' => 0])
@endforeach