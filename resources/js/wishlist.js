
document.addEventListener('DOMContentLoaded', function () {
    const wishlists = document.querySelectorAll('.add-to-wishlist');

    wishlists.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            addToWishlist(productId);
        });
    });

    function addToWishlist(productId) {
        fetch('/wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => {
            console.log('Wishlist add response status:', response.status);
            if (response.status === 401) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Required',
                    text: 'You need to log in or register to add products to your wishlist.',
                    showConfirmButton: true,
                    confirmButtonText: 'Go to Login',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/login';
                    }
                });
                // Throw an error to prevent further processing of the response as JSON
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            if (data.message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                updateWishlistCount();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updateWishlistCount() {
        fetch('/wishlist/count')
            .then(response => response.json())
            .then(data => {
                console.log('Wishlist count data:', data);
                const wishlistCount = document.getElementById('wishlist-count');
                if (wishlistCount) {
                    wishlistCount.textContent = data.count;
                }
            });
    }
});
