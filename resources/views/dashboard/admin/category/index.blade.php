{{--@extends('layouts.admin')--}}
{{--@push('styles')--}}
{{--    <style>--}}
{{--        .tree-children {--}}
{{--            transition: all 0.3s ease;--}}
{{--            overflow: hidden;--}}
{{--        }--}}
{{--    </style>--}}
{{--@endpush--}}

{{--@section('page-content')--}}
{{--    <div class="w-full h-full bg-sky-100 p-4 md:p-8 transition-all duration-300">--}}
{{--        <!-- Breadcrumbs -->--}}
{{--        <div class="flex justify-between items-center mb-6">--}}
{{--            <div class="flex justify-between items-center">--}}
{{--                <button @click="isSidebarCollapsed = !isSidebarCollapsed"--}}
{{--                        class="p-2 rounded-lg hover:bg-teal-100 mr-1 md:mr-4">--}}
{{--                    <x-sidebar.sidebar-toogle-right-icon />--}}
{{--                </button>--}}
{{--                <h2 class="text-2xl font-semibold text-gray-800">Category Management</h2>--}}
{{--            </div>--}}
{{--            <nav class="flex" aria-label="Breadcrumb">--}}
{{--                <ol class="inline-flex items-center space-x-1 md:space-x-3">--}}
{{--                    <li class="inline-flex items-center">--}}
{{--                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-700 hover:text-blue-600">Dashboard</a>--}}
{{--                    </li>--}}
{{--                    <li aria-current="page">--}}
{{--                        <div class="flex items-center">--}}
{{--                            <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">--}}
{{--                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>--}}
{{--                            </svg>--}}
{{--                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Categories</span>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                </ol>--}}
{{--            </nav>--}}
{{--        </div>--}}

{{--        <!-- Content Container -->--}}
{{--        <div class="flex gap-6">--}}
{{--            <!-- Category Table -->--}}
{{--            <div class="w-1/2 bg-white shadow-lg rounded-lg overflow-hidden">--}}
{{--                <div class="p-4">--}}
{{--                    <div class="flex justify-between items-center mb-4">--}}
{{--                        <a href="{{ route('category.create') }}"--}}
{{--                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">--}}
{{--                            Create New Category--}}
{{--                        </a>--}}
{{--                    </div>--}}

{{--                    <div class="overflow-auto max-h-screen">--}}
{{--                        <table class="min-w-full border-collapse border border-gray-300 divide-y divide-gray-200">--}}
{{--                            <thead class="bg-lime-500 sticky top-0">--}}
{{--                            <tr>--}}
{{--                                <th class="px-6 py-3 text-left text-sm font-medium text-white">Name</th>--}}
{{--                                <th class="px-6 py-3 text-left text-sm font-medium text-white">Slug</th>--}}
{{--                                <th class="px-6 py-3 text-left text-sm font-medium text-white">Parent</th>--}}
{{--                                <th class="px-6 py-3 text-left text-sm font-medium text-white">Status</th>--}}
{{--                                <th class="px-6 py-3 text-left text-sm font-medium text-white">Actions</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody class="bg-white divide-y divide-gray-200">--}}
{{--                            @foreach($categories as $category)--}}
{{--                                <tr class="hover:bg-gray-50">--}}
{{--                                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>--}}
{{--                                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->slug }}</td>--}}
{{--                                    <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                                        {{ $category->parent->name ?? '—' }}--}}
{{--                                    </td>--}}
{{--                                    <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                                    <span class="px-2 py-1 text-sm font-semibold rounded-full--}}
{{--                                        {{ $category->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">--}}
{{--                                        {{ ucfirst($category->status) }}--}}
{{--                                    </span>--}}
{{--                                    </td>--}}
{{--                                    <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                                        <div class="flex space-x-2">--}}
{{--                                            <a href="{{ route('category.edit', $category) }}"--}}
{{--                                               class="text-blue-500 hover:text-blue-700">--}}
{{--                                                <i class="fas fa-edit"></i>--}}
{{--                                            </a>--}}
{{--                                            <form action="{{ route('category.destroy', $category) }}"--}}
{{--                                                  method="POST"--}}
{{--                                                  onsubmit="return confirm('Are you sure?')">--}}
{{--                                                @csrf--}}
{{--                                                @method('DELETE')--}}
{{--                                                <button type="submit" class="text-red-500 hover:text-red-700">--}}
{{--                                                    <i class="fas fa-trash"></i>--}}
{{--                                                </button>--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <!-- Category Tree Visualization -->--}}
{{--            <div class="w-1/2 bg-lime-100 shadow-lg rounded-lg overflow-hidden p-6">--}}
{{--                <h3 class="text-lg font-semibold mb-4 text-gray-800">Category Hierarchy</h3>--}}
{{--                <div class="tree-container space-y-2">--}}
{{--                    @foreach($tree as $category)--}}
{{--                        @include('dashboard.admin.category.partials.tree-node', [--}}
{{--                            'category' => $category,--}}
{{--                            'level' => 0--}}
{{--                        ])--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}
{{--@push('scripts')--}}
{{--    <script>--}}
{{--        function toggleChildren(element) {--}}
{{--            const children = element.nextElementSibling;--}}
{{--            const icon = element.querySelector('i');--}}
{{--            if (children.style.maxHeight) {--}}
{{--                children.style.maxHeight = null;--}}
{{--                icon.classList.remove('fa-minus');--}}
{{--                icon.classList.add('fa-plus');--}}
{{--            } else {--}}
{{--                children.style.maxHeight = children.scrollHeight + "px";--}}
{{--                icon.classList.remove('fa-plus');--}}
{{--                icon.classList.add('fa-minus');--}}
{{--            }--}}
{{--        }--}}
{{--    </script>--}}
{{--@endpush--}}
