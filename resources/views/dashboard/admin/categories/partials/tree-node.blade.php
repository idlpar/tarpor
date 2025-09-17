<div class="tree-node" data-id="{{ $category->id }}" style="padding-left: {{ $level * 24 }}px">
    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg hover:bg-gray-100 transition border border-gray-200">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-gray-400 cursor-move handle" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            <span class="font-medium text-gray-800 text-sm">{{ $category->name }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ $category->children->count() }} sub
            </span>
            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                {{ $category->products_count ?? 0 }} items
            </span>
        </div>
    </div>

    @if($category->children->isNotEmpty())
        <div class="tree-children ml-6 mt-2 space-y-2 sortable-group">
            @foreach($category->children as $child)
                @include('dashboard.admin.categories.partials.tree-node', [
                    'category' => $child,
                    'level' => $level + 1
                ])
            @endforeach
        </div>
    @endif
</div>