document.addEventListener("DOMContentLoaded", function () {
    // Sample Product Data (Can be replaced with API response)
    const sampleData = [
        {
            title: "Lenovo IdeaPad D330",
            link: "product-1",
            imageUrl: "{{ asset('uploads/products/thumbnail/product-1.webp') }}",
            description: "10.1 Inch HD IPS Touch Display Mineral Grey Laptop",
            price: "BDT 27,500",
            discount: "Save Tk 1,200"
        },
        {
            title: "HP Pavilion 14",
            link: "product-2",
            imageUrl: "{{ asset('uploads/products/thumbnail/product-2.webp') }}",
            description: "Intel Core i5, 8GB RAM, 512GB SSD",
            price: "BDT 48,000",
            discount: "Save Tk 2,000"
        },
        {
            title: "Samsung Galaxy S21",
            link: "product-3",
            imageUrl: "{{ asset('uploads/products/thumbnail/product-3.webp') }}",
            description: "6.2 Inch Dynamic AMOLED, 128GB Storage",
            price: "BDT 85,000",
            discount: "Save Tk 5,000"
        },
        {
            title: "Logitech Wireless Mouse",
            link: "product-4",
            imageUrl: "{{ asset('uploads/products/thumbnail/product-4.webp') }}",
            description: "Wireless Optical Mouse, Ergonomic Design",
            price: "BDT 1,500",
            discount: "Save Tk 200"
        }
    ];

    // Function to handle search for both desktop and mobile
    function handleSearch(searchBox, searchDropdown, searchResults) {
        const query = searchBox.value.toLowerCase();
        searchResults.innerHTML = "";

        if (query.length > 1) {
            const filteredResults = sampleData.filter(item =>
                item.title.toLowerCase().includes(query) || item.description.toLowerCase().includes(query)
            );

            if (filteredResults.length > 0) {
                searchDropdown.classList.remove("hidden");
                filteredResults.forEach(item => {
                    const li = document.createElement("li");
                    li.classList.add("px-2", "py-2", "border-b", "border-gray-300", "hover:bg-gray-100", "cursor-pointer");

                    li.innerHTML = `
                        <a href="${item.link}" class="flex items-center space-x-3 p-1 hover:bg-gray-50 transition-colors duration-200 rounded-lg">
                            <span class="size-20 shrink-0 border border-lime-500 rounded-lg">
                                <img src="${item.imageUrl}" alt="${item.title}" class="w-full h-full object-cover rounded-md shadow-sm">
                            </span>
                            <span class="flex-1 text-sm">
                                <div class="text-left">
                                    <span class="font-semibold text-gray-800 truncate">${item.title}</span>
                                    <span class="block text-xs text-gray-500 mt-1 truncate">${item.description}</span>
                                </div>
                                <div class="text-left mt-1">
                                    <span class="text-sm font-medium text-gray-900">${item.price}</span>
                                    <span class="text-xs text-green-500 block mt-0.5">${item.discount}</span>
                                </div>
                            </span>
                        </a>
                    `;

                    li.addEventListener("click", function () {
                        searchBox.value = item.title;
                        searchDropdown.classList.add("hidden");
                    });

                    searchResults.appendChild(li);
                });
            } else {
                searchDropdown.classList.remove("hidden");
                const li = document.createElement("li");
                li.classList.add("px-4", "py-2", "text-center", "text-gray-900", "bg-teal-50", "rounded-lg");
                li.textContent = "Nothing found";
                searchResults.appendChild(li);
            }
        } else {
            searchDropdown.classList.add("hidden");
        }
    }

    // Desktop Search Elements
    const searchBox = document.getElementById("user-search-box");
    const searchDropdown = document.getElementById("search-dropdown");
    const searchResults = document.getElementById("search-results");

    if (searchBox && searchDropdown && searchResults) {
        searchBox.addEventListener("input", function () {
            handleSearch(searchBox, searchDropdown, searchResults);
        });
    }

    // Mobile Search Elements
    const mobileSearchBox = document.querySelector("#mobile-search-box input[type='search']");
    const mobileSearchDropdown = document.getElementById("mobile-search-dropdown");
    const mobileSearchResults = document.getElementById("mobile-search-results");

    if (mobileSearchBox && mobileSearchDropdown && mobileSearchResults) {
        mobileSearchBox.addEventListener("input", function () {
            handleSearch(mobileSearchBox, mobileSearchDropdown, mobileSearchResults);
        });
    }

    // Hide dropdowns when clicking outside
    document.addEventListener("click", function (e) {
        if (searchDropdown && !searchBox.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.classList.add("hidden");
        }

        if (mobileSearchDropdown && !mobileSearchBox.contains(e.target) && !mobileSearchDropdown.contains(e.target)) {
            mobileSearchDropdown.classList.add("hidden");
        }
    });

    // Toggle Mobile Search Visibility
    const toggleSearchButton = document.getElementById('toggle-search');
    const mobileSearchBoxContainer = document.getElementById('mobile-search-box');

    if (toggleSearchButton && mobileSearchBoxContainer) {
        toggleSearchButton.addEventListener('click', function () {
            mobileSearchBoxContainer.classList.toggle('hidden');
        });
    }

    // Cart Sidebar
    const cartSidebar = document.getElementById('cart-sidebar');
    const openCartButton = document.querySelector('[aria-label="Cart"]');
    const closeCartButton = document.getElementById('close-cart-sidebar');

    if (openCartButton && cartSidebar && closeCartButton) {
        openCartButton.addEventListener('click', () => {
            cartSidebar.classList.remove('translate-x-full');
        });

        closeCartButton.addEventListener('click', () => {
            cartSidebar.classList.add('translate-x-full');
        });
    }

    // Mobile Menu Functionality
    const mobileMenu = document.getElementById("mobile-menu");
    const openMenuBtn = document.getElementById("open-mobile-menu");
    const closeMenuBtn = document.getElementById("close-menu");
    const mobileMenuList = document.getElementById("mobile-menu-list");
    const desktopMenu = document.getElementById("desktop-menu");

    if (desktopMenu && mobileMenuList) {
        mobileMenuList.innerHTML = desktopMenu.innerHTML;

        mobileMenuList.querySelectorAll("li").forEach((menuItem) => {
            menuItem.classList.add(
                "border-b",
                "border-gray-700",
                "pb-2",
                "flex",
                "justify-between",
                "items-center",
                "cursor-pointer",
                "relative"
            );

            const childLis = menuItem.querySelectorAll(".dropdown li");
            childLis.forEach((childLi) => {
                childLi.classList.add(
                    "py-1",
                    "px-4",
                    "border-b-[1px]",
                    "border-lime-500",
                    "bg-lime-200"
                );
            });

            let dropdown = menuItem.querySelector(".dropdown");
            if (dropdown) {
                dropdown.className = "";
                dropdown.classList.add("hidden", "w-full");

                let toggleBtn = document.createElement("span");
                toggleBtn.textContent = "+";
                toggleBtn.classList.add("text-white", "text-lg", "font-bold", "ml-auto");

                let dropdownWrapper = document.createElement("div");
                dropdownWrapper.classList.add("w-full");
                dropdownWrapper.appendChild(dropdown);
                menuItem.after(dropdownWrapper);

                menuItem.appendChild(toggleBtn);

                menuItem.addEventListener("click", function (e) {
                    if (e.target.closest(".dropdown")) return;

                    dropdown.classList.toggle("hidden");
                    toggleBtn.textContent = dropdown.classList.contains("hidden") ? "+" : "−";

                    if (!dropdown.classList.contains("hidden")) {
                        dropdown.className = "";
                        dropdown.classList.add("transition-all", "duration-300", "ease-in-out");
                        dropdown.classList.remove("flex", "justify-between", "bg-gray-50", "item-center", "relative");
                    }
                });
            }
        });
    }

    if (openMenuBtn && mobileMenu && closeMenuBtn) {
        openMenuBtn.addEventListener("click", function () {
            mobileMenu.classList.remove("-translate-x-full");
        });

        closeMenuBtn.addEventListener("click", function () {
            mobileMenu.classList.add("-translate-x-full");
        });
    }

    // Slider Functionality
    const slideContainer = document.getElementById("slide-container");
    const slides = document.querySelectorAll(".slide");
    const indicators = document.querySelectorAll(".slide-indicator");
    const prevButton = document.getElementById("prev-button");
    const nextButton = document.getElementById("next-button");
    let currentIndex = 0;

    function updateSlide() {
        if (slideContainer) {
            slideContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
        }
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle("opacity-100", index === currentIndex);
            indicator.classList.toggle("opacity-50", index !== currentIndex);
        });
    }

    if (prevButton && nextButton) {
        prevButton.addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            updateSlide();
        });

        nextButton.addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % slides.length;
            updateSlide();
        });
    }

    // Tooltips
    document.querySelectorAll('.btn').forEach(button => {
        const tooltip = button.querySelector('.tooltip-text');
        if (tooltip) {
            button.addEventListener('mouseenter', () => {
                const buttonRect = button.getBoundingClientRect();
                const tooltipRect = tooltip.getBoundingClientRect();

                if (buttonRect.right + tooltipRect.width > window.innerWidth) {
                    tooltip.classList.remove('left-full', 'ml-2');
                    tooltip.classList.add('right-full', 'mr-2');
                    tooltip.querySelector('span').classList.remove('-left-2', 'border-r-4');
                    tooltip.querySelector('span').classList.add('-right-2', 'border-l-4');
                } else {
                    tooltip.classList.remove('right-full', 'mr-2');
                    tooltip.classList.add('left-full', 'ml-2');
                    tooltip.querySelector('span').classList.remove('-right-2', 'border-l-4');
                    tooltip.querySelector('span').classList.add('-left-2', 'border-r-4');
                }
            });
        }
    });

    // Scroll to Top
    const scrollToTopButton = document.getElementById("scrollToTop");

    if (scrollToTopButton) {
        window.addEventListener("scroll", () => {
            const scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;
            const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
            const opacity = Math.min(scrollPosition / maxScroll, 1);
            scrollToTopButton.style.opacity = opacity;

            if (scrollPosition > 100) {
                scrollToTopButton.classList.remove("hidden");
            } else {
                scrollToTopButton.classList.add("hidden");
            }
        });

        scrollToTopButton.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }

    // Read More/Less Functionality
    const readMoreLink = document.getElementById("readMoreLink");

    if (readMoreLink) {
        readMoreLink.addEventListener("click", function () {
            const dots = document.getElementById("dots");
            const moreText = document.getElementById("more");
            const extraContent = document.getElementById("extraContent");

            if (moreText.style.display === "none") {
                moreText.style.display = "inline";
                dots.style.display = "none";
                this.textContent = "Read Less";
                extraContent.style.display = "block";
            } else {
                moreText.style.display = "none";
                dots.style.display = "inline";
                this.textContent = "Read More";
                extraContent.style.display = "none";
            }
        });
    }
});
