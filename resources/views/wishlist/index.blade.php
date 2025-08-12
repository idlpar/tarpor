@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <nav class="text-sm text-gray-500 mb-6">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Home</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 67.254c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                </li>
                <li>
                    <span>My Wishlist</span>
                </li>
            </ol>
        </nav>

        <h1 class="text-3xl font-bold text-gray-900 mb-8">My Wishlist</h1>

        @if ($wishlist->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($wishlist as $item)
                    <div id="wishlist-item-{{ $item->product->id }}" class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-200 hover:scale-105">
                        <a href="{{ route('products.show.frontend', $item->product->slug) }}" class="block">
                            <img src="{{ $item->product->thumbnail_url ?? asset('images/default-product.jpg') }}" alt="{{ $item->product->name }}" class="w-full h-48 object-cover">
                        </a>
                        <div class="p-4">
                            <h2 class="text-xl font-semibold text-gray-800 mb-2 truncate">
                                <a href="{{ route('products.show.frontend', $item->product->slug) }}" class="hover:text-blue-600">{{ $item->product->name }}</a>
                            </h2>
                            <p class="text-gray-700 text-lg font-medium mb-4">BDT {{ number_format($item->product->price, 2) }}</p>
                            <button type="button" class="remove-from-wishlist bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition-colors duration-200 w-full"
                                    data-product-id="{{ $item->product->id }}">
                                Remove from Wishlist
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <h2 class="mt-4 text-2xl font-semibold text-gray-900">Your Wishlist is Empty</h2>
                <p class="mt-2 text-gray-600">Looks like you haven't added anything to your wishlist yet. Start browsing and add your favorite items!</p>
                <a href="{{ route('shop.index') }}" class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const removeButtons = document.querySelectorAll('.remove-from-wishlist');

        removeButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const productId = this.dataset.productId;
                removeProductFromWishlist(productId, this.closest('[id^="wishlist-item-"]'));
            });
        });

        function removeProductFromWishlist(productId, elementToRemove) {
            fetch('/wishlist/remove', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Removed!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    if (elementToRemove) {
                        elementToRemove.remove();
                    }
                    updateWishlistCount();
                }
            })
            .catch(error => {
                console.error('Error removing from wishlist:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                });
            });
        }

        function updateWishlistCount() {
            fetch('/wishlist/count')
                .then(response => response.json())
                .then(data => {
                    const wishlistCount = document.getElementById('wishlist-count');
                    if (wishlistCount) {
                        wishlistCount.textContent = data.count;
                    }
                })
                .catch(error => console.error('Error updating wishlist count:', error));
        }
    });
</script>
@endpush