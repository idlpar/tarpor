@push('styles')
    <style>
        .tree-node-line::before {
            content: '';
            @apply absolute left-0 top-0 w-px h-full bg-gray-200;
        }
    </style>
@endpush

<div class="tree-node" style="padding-left: {{ $level * 24 }}px" x-data="{ open: false }">
    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg hover:bg-gray-100 transition cursor-pointer border border-gray-200" @click="open = !open">
        <div class="flex items-center space-x-2">
            @if($category->children->isNotEmpty())
                <button @click.stop="open = !open" class="w-5 h-5 flex items-center justify-center rounded-full bg-white shadow-sm hover:bg-gray-100 transition border border-gray-300">
                    <svg class="w-3 h-3 text-gray-600 transition-transform" :class="{'transform rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            @else
                <div class="w-5 h-5 flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                </div>
            @endif

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
        <div x-show="open" x-collapse class="tree-children ml-6 mt-2 relative space-y-2 tree-node-line">
            @foreach($category->children as $child)
                @include('dashboard.admin.categories.partials.tree-node', [
                    'category' => $child,
                    'level' => $level + 1
                ])
            @endforeach
        </div>
    @endif
</div>
