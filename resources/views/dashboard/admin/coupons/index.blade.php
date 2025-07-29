@extends('layouts.admin')

@section('title', 'Coupons')

@section('admin_content')
    <div class="container mx-auto px-4 py-4">
        @include('components.ui.breadcrumbs', [
            'links' => [
                'Coupons' => null
            ]
        ])

        <x-ui.page-header title="Coupons" description="Manage discount coupons.">
            <x-ui.search-box />
            <a href="{{ route('coupons.create') }}" class="ml-4 flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Add New Coupon</span>
            </a>
        </x-ui.page-header>

        <x-ui.session-messages />

        @include('components.ui.table-filter')

        <x-ui.content-card>
            @php(
                $headers = ['Code', 'Type', 'Value', 'Usage Limit', 'Used', 'Max Discount', 'Expires At', 'Actions']
            )
            <x-ui.table :headers="$headers">
                @forelse($coupons as $coupon)
                    <tr class="border-b border-gray-200 text-sm hover:bg-amber-50">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $coupon->code }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ ucfirst($coupon->type) }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $coupon->type === 'percentage' ? $coupon->value . '%' : format_taka($coupon->value, '৳') }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $coupon->usage_limit ?? 'N/A' }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $coupon->times_used }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $coupon->max_discount_amount ?? 'N/A' }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : 'Never' }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm text-right">
                            <x-ui.table-actions :item="$coupon" baseRoute="coupons" :showRestore="false" :showForceDelete="false" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                            No coupons found.
                        </td>
                    </tr>
                @endforelse
            </x-ui.table>
            <div class="mt-4 flex justify-end">
                {{ $coupons->links('components.ui.custom-pagination') }}
            </div>
        </x-ui.content-card>
    </div>
@endsection

@push('scripts')
<script>
    // SweetAlert functions are now handled within table-actions.blade.php
</script>
@endpush
