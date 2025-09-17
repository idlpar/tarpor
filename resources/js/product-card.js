function flyToCartAnimation(buttonElement) {
    let flyingImage = null;
    const cartIcon = document.querySelector('.cart-icon');
    let productImage = null;

    const productCard = buttonElement.closest('.group');
    const quickViewModal = buttonElement.closest('#quick-view-modal');

    if (productCard) {
        productImage = productCard.querySelector('img');
    } else if (quickViewModal) {
        productImage = document.getElementById('qv-main-image');
    }

    if (productImage && cartIcon) {
        flyingImage = productImage.cloneNode();
        flyingImage.style.position = 'fixed';
        flyingImage.style.left = `${productImage.getBoundingClientRect().left}px`;
        flyingImage.style.top = `${productImage.getBoundingClientRect().top}px`;
        flyingImage.style.width = `${productImage.width}px`;
        flyingImage.style.height = `${productImage.height}px`;
        flyingImage.style.transition = 'all 1s ease-in-out';
        flyingImage.style.zIndex = '9999';
        document.body.appendChild(flyingImage);

        setTimeout(() => {
            flyingImage.style.left = `${cartIcon.getBoundingClientRect().left}px`;
            flyingImage.style.top = `${cartIcon.getBoundingClientRect().top}px`;
            flyingImage.style.width = '0px';
            flyingImage.style.height = '0px';
            flyingImage.style.opacity = '0';
        }, 100);
    }
    return flyingImage;
}

function addToCart(productId, quantity, buttonElement, variantId = null) {
    const originalText = buttonElement.innerHTML;
    buttonElement.innerHTML = `
        <span class="flex items-center justify-center truncate">
            <svg class="w-4 h-4 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L4.707 15.293a1 1 0 00-.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="truncate">Adding...</span>
        </span>
    `;
    buttonElement.disabled = true;

    const flyingImage = flyToCartAnimation(buttonElement);

    return fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ product_id: productId, quantity: quantity, variant_id: variantId, action: 'add_to_cart' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            setTimeout(() => {
                if (flyingImage) flyingImage.remove();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    html: `
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-semibold">Product Added!</div>
                                <div class="text-sm text-gray-500">Your item is in the cart.</div>
                            </div>
                        </div>
                    `,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                updateCartCount(data.cart_count);
            }, 1000);
            return true;
        } else {
            if (flyingImage) flyingImage.remove();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message || 'Could not add to cart.',
            });
            buttonElement.innerHTML = originalText;
            buttonElement.disabled = false;
            return false;
        }
    })
    .catch(error => {
        if (flyingImage) flyingImage.remove();
        console.error('Error adding to cart:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while adding to cart.',
        });
        buttonElement.innerHTML = originalText;
        buttonElement.disabled = false;
        return false;
    });
}

function buyNow(productId, quantity, buttonElement, variantId = null) {
    const originalText = buttonElement.innerHTML;
    buttonElement.innerHTML = `
        <span class="flex items-center justify-center truncate">
            <svg class="w-4 h-4 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L4.707 15.293a1 1 0 00-.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="truncate">Buying...</span>
        </span>
    `;
    buttonElement.disabled = true;

    const flyingImage = flyToCartAnimation(buttonElement);

    return fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ product_id: productId, quantity: quantity, variant_id: variantId, action: 'buy_now' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.cart_count);
            setTimeout(() => {
                if (flyingImage) flyingImage.remove();
                window.location.href = '/checkout';
            }, 1000);
            return true;
        } else {
            if (flyingImage) flyingImage.remove();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message || 'Could not add to cart.',
            });
            buttonElement.innerHTML = originalText;
            buttonElement.disabled = false;
            return false;
        }
    })
    .catch(error => {
        if (flyingImage) flyingImage.remove();
        console.error('Error adding to cart for buy now:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while processing your request.',
        });
        buttonElement.innerHTML = originalText;
        buttonElement.disabled = false;
        return false;
    });
}

function openQuickViewModal(productId, isBuyNow = false) {
    const quickViewModal = document.getElementById('quick-view-modal');
    const quickViewContent = document.getElementById('quick-view-content');

    // Show loading state
    quickViewContent.innerHTML = `
        <div class="flex justify-center items-center h-64">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        </div>
    `;

    quickViewModal.classList.remove('hidden');
    quickViewModal.classList.add('flex');
    document.body.style.overflow = 'hidden';

    // Fetch product details
    fetch(`/api/products/${productId}/quick-view`)
        .then(response => response.json())
        .then(product => {
            const template = document.getElementById('quick-view-product-template').content.cloneNode(true);

            const qvProductName = template.querySelector('#qv-product-name');
            const qvProductBrand = template.querySelector('#qv-product-brand');
            const qvProductPrice = template.querySelector('#qv-product-price');
            const qvProductSalePrice = template.querySelector('#qv-product-sale-price');
            const qvProductShortDescription = template.querySelector('#qv-product-short-description');
            const qvProductId = template.querySelector('#qv-product-id');
            const qvMainImage = template.querySelector('#qv-main-image');
            const qvGalleryThumbnails = template.querySelector('#qv-gallery-thumbnails');
            const qvVariantSelection = template.querySelector('#qv-variant-selection');
            const qvVariantOptions = template.querySelector('#qv-variant-options');
            const qvSelectedVariantId = template.querySelector('#qv-selected-variant-id');
            const qvQuantityInput = template.querySelector('#qv-quantity-input');
            const qvStockStatusDisplay = template.querySelector('#qv-stock-status-display');
            const qvDecrementQuantity = template.querySelector('#qv-decrement-quantity');
            const qvIncrementQuantity = template.querySelector('#qv-increment-quantity');
            const qvAddToCartForm = template.querySelector('#qv-add-to-cart-form');
            const qvAddToCartBtn = template.querySelector('#qv-add-to-cart-btn');
            const qvBuyNowBtn = template.querySelector('#qv-buy-now-btn');

            qvProductName.textContent = product.name;
            qvProductBrand.textContent = product.brand ? product.brand.name : 'N/A';
            qvProductPrice.textContent = product.formatted_price;
            if (product.formatted_sale_price) {
                qvProductSalePrice.textContent = product.formatted_sale_price;
                qvProductSalePrice.classList.remove('hidden');
            } else {
                qvProductSalePrice.classList.add('hidden');
            }
            qvProductShortDescription.innerHTML = product.short_description;
            qvProductId.value = product.id;

            const defaultImage = '/images/placeholder-product.png';
            qvMainImage.src = product.thumbnail_url || defaultImage;

            if (product.media && product.media.length > 0) {
                qvGalleryThumbnails.innerHTML = '';
                product.media.forEach(mediaItem => {
                    const img = document.createElement('img');
                    img.src = mediaItem.thumb_url;
                    img.alt = product.name + ' thumbnail';
                    img.classList.add('w-full', 'h-16', 'object-cover', 'rounded-md', 'cursor-pointer', 'border-2', 'border-transparent', 'hover:border-blue-500', 'transition-colors', 'duration-200', 'qv-thumbnail-image');
                    img.dataset.src = mediaItem.url;
                    qvGalleryThumbnails.appendChild(img);
                });

                template.querySelectorAll('.qv-thumbnail-image').forEach(thumb => {
                    thumb.addEventListener('click', function() {
                        qvMainImage.src = this.dataset.src;
                    });
                });
            }

            function updateQuickViewDisplay(selectedRadio) {
                const formattedPrice = selectedRadio.dataset.formattedPrice;
                const formattedSalePrice = selectedRadio.dataset.formattedSalePrice;
                const stock = parseInt(selectedRadio.dataset.stock);
                const stockStatus = selectedRadio.dataset.stockStatus;
                const variantId = selectedRadio.value;

                qvProductPrice.textContent = formattedSalePrice || formattedPrice;
                if (formattedSalePrice) {
                    qvProductSalePrice.textContent = formattedPrice;
                    qvProductSalePrice.classList.remove('hidden');
                } else {
                    qvProductSalePrice.classList.add('hidden');
                }

                if (stockStatus === 'in_stock') {
                    qvStockStatusDisplay.innerHTML = `<span class="text-green-600">In Stock (${stock} items)</span>`;
                } else if (stockStatus === 'out_of_stock') {
                    qvStockStatusDisplay.innerHTML = `<span class="text-red-600">Out of Stock</span>`;
                } else {
                    qvStockStatusDisplay.innerHTML = `<span class="text-yellow-600">Backorder</span>`;
                }

                qvQuantityInput.max = stock;
                if (parseInt(qvQuantityInput.value) > stock) {
                    qvQuantityInput.value = 1;
                }

                const isOutOfStock = (stockStatus === 'out_of_stock');
                qvAddToCartBtn.disabled = isOutOfStock;
                qvBuyNowBtn.disabled = isOutOfStock;
                qvQuantityInput.disabled = isOutOfStock;
                qvDecrementQuantity.disabled = isOutOfStock;
                qvIncrementQuantity.disabled = isOutOfStock;

                qvSelectedVariantId.value = variantId;
            }

            if (product.type === 'variable' && product.variants.length > 0) {
                qvVariantSelection.classList.remove('hidden');
                qvVariantOptions.innerHTML = '';

                product.variants.forEach(variant => {
                    const variantDiv = document.createElement('div');
                    variantDiv.classList.add('variant-option-wrapper');
                    variantDiv.innerHTML = `
                        <input type="radio" name="qv_variant_id" id="qv-variant-${variant.id}" value="${variant.id}" class="sr-only qv-variant-radio"
                            data-stock="${variant.stock_quantity}"
                            data-stock-status="${variant.stock_status}"
                            data-formatted-price="${variant.formatted_price}"
                            data-formatted-sale-price="${variant.formatted_sale_price || ''}"
                            ${variant.stock_status === 'out_of_stock' ? 'disabled' : ''}>
                        <label for="qv-variant-${variant.id}" class="variant-label cursor-pointer block border border-gray-300 rounded-md p-3 text-center transition-all duration-200">
                            <span class="variant-name text-sm font-medium text-gray-800">
                                ${variant.attributes_list}
                            </span>
                            <span class="variant-price text-xs text-gray-500 block mt-1">
                                ${variant.formatted_sale_price || variant.formatted_price}
                            </span>
                        </label>
                    `;
                    qvVariantOptions.appendChild(variantDiv);
                });

                template.querySelectorAll('.qv-variant-radio').forEach(radio => {
                    radio.addEventListener('change', function() {
                        updateQuickViewDisplay(this);
                    });
                });

                const firstAvailableVariant = product.variants.find(v => v.stock_status !== 'out_of_stock') || product.variants[0];
                if (firstAvailableVariant) {
                    const firstAvailableRadio = template.querySelector(`#qv-variant-${firstAvailableVariant.id}`);
                    firstAvailableRadio.checked = true;
                    updateQuickViewDisplay(firstAvailableRadio);
                }

            } else {
                qvVariantSelection.classList.add('hidden');
                qvSelectedVariantId.value = '';
                updateQuickViewDisplay({
                    dataset: {
                        stock: product.stock_quantity,
                        stock_status: product.stock_status,
                        formatted_price: product.formatted_price,
                        formatted_sale_price: product.formatted_sale_price
                    },
                    value: ''
                });
            }

            qvDecrementQuantity.addEventListener('click', () => {
                if (parseInt(qvQuantityInput.value) > 1) {
                    qvQuantityInput.value = parseInt(qvQuantityInput.value) - 1;
                }
            });
            qvIncrementQuantity.addEventListener('click', () => {
                const max = parseInt(qvQuantityInput.max) || 999;
                if(parseInt(qvQuantityInput.value) < max) {
                    qvQuantityInput.value = parseInt(qvQuantityInput.value) + 1;
                }
            });

            qvAddToCartForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const selectedProductId = product.id;
                const selectedVariantId = qvSelectedVariantId.value || null;
                const selectedQuantity = qvQuantityInput.value;
                const action = e.submitter.value;
                const buttonElement = e.submitter;

                let promise;
                if (action === 'add_to_cart') {
                    promise = addToCart(selectedProductId, selectedQuantity, buttonElement, selectedVariantId);
                } else if (action === 'buy_now') {
                    promise = buyNow(selectedProductId, selectedQuantity, buttonElement, selectedVariantId);
                }

                if (promise && action === 'add_to_cart') {
                    promise.then(success => {
                        if (success) {
                            setTimeout(() => {
                                quickViewModal.classList.add('hidden');
                                document.body.style.overflow = '';
                            }, 1000); // Wait for notification to be seen
                        }
                    });
                }
            });

            if (isBuyNow) {
                qvBuyNowBtn.click();
            }

            quickViewContent.innerHTML = '';
            quickViewContent.appendChild(template);
        })
        .catch(error => {
            quickViewContent.innerHTML = '<p class="text-center text-red-500">Failed to load product details.</p>';
        });
}

document.addEventListener('click', function(e) {
    if (e.target.matches('.quick-view-btn')) {
        const productId = e.target.dataset.productId;
        openQuickViewModal(productId);
    }

    if (e.target.matches('.add-to-cart-btn')) {
        const productId = e.target.dataset.productId;
        const productType = e.target.dataset.productType;

        if (productType === 'variable') {
            openQuickViewModal(productId);
        } else {
            addToCart(productId, 1, e.target);
        }
    }

    if (e.target.matches('.buy-now-btn')) {
        const productId = e.target.dataset.productId;
        const productType = e.target.dataset.productType;

        if (productType === 'variable') {
            openQuickViewModal(productId, true);
        } else {
            buyNow(productId, 1, e.target);
        }
    }
});
