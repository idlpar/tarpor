@extends('layouts.admin')

@section('title', 'Shipping Methods | ' . strtoupper(config('app.name')))

@section('admin_content')
    <div class="container mx-auto px-4 py-4">
        @include('components.ui.breadcrumbs', [
            'links' => [
                'Shipping Methods' => null
            ]
        ])

        <x-ui.page-header title="Shipping Methods" description="Manage available shipping methods.">
            <x-ui.search-box />
            <a href="{{ route('shipping_methods.create') }}" class="ml-4 flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Add New Shipping Method</span>
            </a>
        </x-ui.page-header>

        <x-ui.session-messages />

        @include('components.ui.table-filter')

        <x-ui.content-card>
            @php(
                $headers = ['ID', 'Name', 'Cost', 'Description', 'Status', 'Actions']
            )
            <x-ui.table :headers="$headers">
                @forelse ($shippingMethods as $shippingMethod)
                    <tr class="border-b border-gray-200 text-sm hover:bg-amber-50">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $shippingMethod->id }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $shippingMethod->name }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ format_taka($shippingMethod->cost) }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ Str::limit($shippingMethod->description, 50) }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $shippingMethod->is_active ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                {{ $shippingMethod->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm text-right">
                            <x-ui.table-actions :item="$shippingMethod" baseRoute="shipping_methods" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                            No shipping methods found.
                        </td>
                    </tr>
                @endforelse
            </x-ui.table>
            <div class="mt-4 flex justify-end">
                {{ $shippingMethods->links('components.ui.custom-pagination') }}
            </div>
        </x-ui.content-card>
    </div>
@endsection

@push('scripts')
<script>
    // SweetAlert functions are now handled within table-actions.blade.php
</script>
@endpush