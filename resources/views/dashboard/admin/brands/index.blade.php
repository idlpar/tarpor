@extends('layouts.admin')

@section('title', 'Brands | ' . strtoupper(config('app.name')))

@section('admin_content')
    <div class="container mx-auto px-4 py-4">
        @include('components.ui.breadcrumbs', [
            'links' => [
                'Brands' => null
            ]
        ])

        <x-ui.page-header title="Brands" description="Manage product brands.">
            <x-ui.search-box />
            <a href="{{ route('brands.create') }}" class="ml-4 flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Add New Brand</span>
            </a>
        </x-ui.page-header>

        <x-ui.session-messages />

        @include('components.ui.table-filter')

        <x-ui.content-card>
            @php(
                $headers = ['ID', 'Logo', 'Name', 'Slug', 'Description', 'Status', 'Actions']
            )
            <x-ui.table :headers="$headers">
                @forelse ($brands as $brand)
                    <tr class="border-b border-gray-200 text-sm hover:bg-amber-50">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $brand->id }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            @if($brand->logo)
                                <img src="{{ $brand->logo->thumb_url }}" alt="{{ $brand->name }} Logo" class="h-10 w-10 object-contain">
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $brand->name }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $brand->slug }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ Str::limit($brand->description, 50) }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $brand->status == 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                {{ ucfirst($brand->status) }}
                            </span>
                            @if($brand->trashed())
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800 ml-2">Trashed</span>
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm text-right">
                            <x-ui.table-actions :item="$brand" baseRoute="brands" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                            No brands found.
                        </td>
                    </tr>
                @endforelse
            </x-ui.table>
            <div class="mt-4 flex justify-end">
                {{ $brands->links('components.ui.custom-pagination') }}
            </div>
        </x-ui.content-card>
    </div>
@endsection

@push('scripts')
<script>
    // SweetAlert functions are now handled within table-actions.blade.php
</script>
@endpush