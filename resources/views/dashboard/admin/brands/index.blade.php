@extends('layouts.admin')

@section('title', 'Brands | ' . strtoupper(config('app.name')))

@section('admin_content')
    <div class="min-h-screen bg-gray-100 p-6 md:p-8">
        

        @include('components.breadcrumbs', [
            'links' => [
                'Brands' => null
            ]
        ])

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Brands</h2>
                <a href="{{ route('brands.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">Add New Brand</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">Logo</th>
                            <th class="py-3 px-6 text-left">Name</th>
                            <th class="py-3 px-6 text-left">Slug</th>
                            <th class="py-3 px-6 text-left">Description</th>
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @forelse ($brands as $brand)
                            <tr class="border-b border-gray-200 hover:bg-gray-100 {{ $brand->trashed() ? 'bg-red-50 opacity-75' : '' }}">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $brand->id }}</td>
                                <td class="py-3 px-6 text-left">
                                    @if($brand->logo)
                                        <img src="{{ $brand->logo->thumb_url }}" alt="{{ $brand->name }} Logo" class="h-10 w-10 object-contain">
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-left">{{ $brand->name }}</td>
                                <td class="py-3 px-6 text-left">{{ $brand->slug }}</td>
                                <td class="py-3 px-6 text-left">{{ Str::limit($brand->description, 50) }}</td>
                                <td class="py-3 px-6 text-left">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $brand->status == 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ ucfirst($brand->status) }}
                                    </span>
                                    @if($brand->trashed())
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800 ml-2">Trashed</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        @if($brand->trashed())
                                            <form action="{{ route('brands.restore', $brand->id) }}" method="POST" class="restore-form inline-block mr-2">
                                                @csrf
                                                <button type="submit" class="w-4 transform hover:text-green-500 hover:scale-110" title="Restore">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004 12m7-7v5h.582m-15.356 2A8.001 8.001 0 0020 12V8l-2.745 3.908C17.47 15.09 19.93 16 22 16c1.01 0 1.97-.11 2.9-.31M12 18v-6m0 0V6m0 6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('brands.force-delete', $brand->id) }}" method="POST" class="force-delete-form inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-4 transform hover:text-red-500 hover:scale-110" title="Force Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('brands.edit', $brand->id) }}" class="w-4 mr-2 transform text-green-500 hover:text-green-700 hover:scale-110" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="delete-form inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-4 transform text-red-500 hover:text-red-700 hover:scale-110" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-3 px-6 text-center">No brands found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $brands->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        focusConfirm: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            const restoreForms = document.querySelectorAll('.restore-form');
            restoreForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This will restore the brand.",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, restore it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        focusConfirm: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            const forceDeleteForms = document.querySelectorAll('.force-delete-form');
            forceDeleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you absolutely sure?',
                        text: "This action cannot be undone. The brand will be permanently deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it permanently!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        focusConfirm: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
