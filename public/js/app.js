document.addEventListener('DOMContentLoaded', () => {
    // Enhanced Cookie Consent
    const cookieConsent = document.getElementById('cookie-consent');
    const acceptCookies = document.getElementById('accept-cookies');
    const rejectCookies = document.getElementById('reject-cookies');
    const stickyCta = document.getElementById('sticky-cta'); // Moved up for use in updateOffCanvasHeight
    const offCanvasMenu = document.getElementById('off-canvas-menu'); // Moved up for use in updateOffCanvasHeight

    const updateBodyMargin = (element) => {
        // Set marginBottom to the height of the given element (cookie consent or sticky CTA)
        document.body.style.marginBottom = `${element.offsetHeight}px`;
    };

    // Function to update off-canvas menu height based on cookie consent or sticky CTA visibility
    const updateOffCanvasHeight = () => {
        if (window.innerWidth <= 768) {
            const isCookieConsentVisible = cookieConsent && !cookieConsent.classList.contains('translate-y-full');
            const isStickyCtaVisible = stickyCta && !stickyCta.classList.contains('translate-y-full');
            offCanvasMenu.style.setProperty(
                '--off-canvas-height',
                isCookieConsentVisible || isStickyCtaVisible ? '100vh' : 'calc(100vh - 48px)'
            );
        } else {
            offCanvasMenu.style.setProperty('--off-canvas-height', 'calc(100vh - 60px)');
        }
    };

    // Initial height update
    updateOffCanvasHeight();
    window.addEventListener('resize', updateOffCanvasHeight);

    // Show cookie consent if it hasn't been accepted before
    if (!localStorage.getItem('cookieConsent')) {
        setTimeout(() => {
            cookieConsent.classList.remove('translate-y-full');
            cookieConsent.classList.add('translate-y-0');
            // Set margin for cookie consent popup
            updateBodyMargin(cookieConsent);
            localStorage.setItem('cookieConsentShown', 'true');
            localStorage.setItem('cookieConsentTimestamp', new Date().getTime());
            updateOffCanvasHeight(); // Update menu height when cookie consent is shown
        }, 1000);
    }

    const handleCookieAcceptance = (acceptAll) => {
        localStorage.setItem('cookieConsent', acceptAll ? 'all' : 'essential');
        cookieConsent.classList.add('translate-y-full');
        // Reset margin when cookie consent is dismissed
        document.body.style.marginBottom = '0';

        // Implement cookie logic
        if (acceptAll) {
            console.log('All cookies accepted');
        } else {
            console.log('Only essential cookies accepted');
        }

        // Now show the sticky CTA and set margin if not already closed
        if (stickyCta && !localStorage.getItem('ctaClosed')) {
            setTimeout(() => {
                stickyCta.classList.remove('translate-y-full');
                stickyCta.classList.add('translate-y-0');
                // Set margin for sticky CTA popup
                updateBodyMargin(stickyCta);
                updateOffCanvasHeight(); // Update menu height when sticky CTA is shown
            }, 1000); // Show after a slight delay
        } else {
            updateOffCanvasHeight(); // Update menu height if no sticky CTA
        }
    };

    acceptCookies.addEventListener('click', () => handleCookieAcceptance(true));
    rejectCookies.addEventListener('click', () => handleCookieAcceptance(false));

    // Enhanced Sticky CTA
    const closeCta = document.getElementById('close-cta');

    closeCta?.addEventListener('click', () => {
        stickyCta.classList.add('translate-y-full');
        localStorage.setItem('ctaClosed', 'true');
        // Reset margin after CTA is dismissed
        document.body.style.marginBottom = '0';
        updateOffCanvasHeight(); // Update menu height when sticky CTA is dismissed
    });

    // Navigation Active State
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            navLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        });
    });

    // Search Modal
    const searchBtn = document.getElementById('search-btn');
    const mobileSearchBtn = document.getElementById('mobile-search-btn');
    const searchModal = document.getElementById('search-modal');
    const closeSearch = document.getElementById('close-search');
    const mobileSearchInput = document.getElementById('mobile-search-input');
    [searchBtn, mobileSearchBtn].forEach(btn => {
        btn.addEventListener('click', () => {
            if (window.innerWidth < 768) {
                mobileSearchInput.classList.remove('hidden');
                setTimeout(() => {
                    mobileSearchInput.classList.remove('translate-y-full', 'opacity-0');
                }, 10);
            } else {
                searchModal.classList.remove('hidden');
                setTimeout(() => {
                    searchModal.querySelector('.search-modal').classList.remove('scale-95', 'opacity-0');
                }, 10);
            }
        });
    });
    closeSearch.addEventListener('click', () => {
        searchModal.querySelector('.search-modal').classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            searchModal.classList.add('hidden');
        }, 300);
    });

    // Off-Canvas Menu
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const offCanvasClose = document.getElementById('off-canvas-close');
    mobileMenuBtn.addEventListener('click', () => {
        offCanvasMenu.classList.add('open');
        offCanvasMenu.setAttribute('aria-hidden', 'false');
        updateOffCanvasHeight(); // Update height when menu is opened
    });
    offCanvasClose.addEventListener('click', () => {
        offCanvasMenu.classList.remove('open');
        offCanvasMenu.setAttribute('aria-hidden', 'true');
    });

    // Submenu Toggle
    const submenuButtons = document.querySelectorAll('.menu-item[data-submenu]');
    submenuButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const submenuId = btn.dataset.submenu;
            const submenu = document.getElementById(`${submenuId}-submenu`);
            const chevron = btn.querySelector('.chevron');
            const isExpanded = btn.getAttribute('aria-expanded') === 'true';

            btn.setAttribute('aria-expanded', !isExpanded);
            submenu.classList.toggle('open');
            chevron.classList.toggle('open');
        });
    });

    // Quick View Modal
    const quickViewModal = document.getElementById('quick-view-modal');
    const closeModal = document.getElementById('close-modal');
    const modalImage = document.getElementById('modal-image');
    const modalTitle = document.getElementById('modal-title');
    const modalPrice = document.getElementById('modal-price');
    const quickViewBtns = document.querySelectorAll('.quick-view-btn');
    quickViewBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const product = btn.dataset.product;
            let imageSrc, title, price;
            switch (product) {
                case 'kids-festive-kurta':
                    imageSrc = 'https://plus.unsplash.com/premium_photo-1661540638251-a8e663bf45f8?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTMyfHxmcmVlJTIwaW1hZ2VzfGVufDB8fDB8fHww';
                    title = 'Kids Festive Kurta';
                    price = 'BDT 1,500';
                    break;
                case 'mens-panjabi':
                    imageSrc = 'https://images.unsplash.com/photo-1729861229315-4c9672f15a53?q=80&w=1946&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
                    title = 'Men\'s Premium Panjabi';
                    price = 'BDT 2,800';
                    break;
                case 'kids-embroidered-kurta':
                    imageSrc = 'https://images.unsplash.com/photo-1729861229315-4c9672f15a53?q=80&w=1946&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
                    title = 'Kids Embroidered Kurta';
                    price = 'BDT 1,800';
                    break;
                case 'mens-silk-panjabi':
                    imageSrc = 'https://images.unsplash.com/photo-1729861229315-4c9672f15a53?q=80&w=1946&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
                    title = 'Men\'s Silk Panjabi';
                    price = 'BDT 3,200';
                    break;
                case 'kids-kurta':
                    imageSrc = 'https://images.unsplash.com/photo-1729861229315-4c9672f15a53?q=80&w=1946&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
                    title = 'Kids Cotton Kurta';
                    price = 'BDT 1,200';
                    break;
                default:
                    return;
            }
            modalImage.src = imageSrc;
            modalImage.alt = title;
            modalTitle.textContent = title;
            modalPrice.textContent = price;
            quickViewModal.classList.remove('hidden');
            setTimeout(() => {
                quickViewModal.querySelector('.modal').classList.remove('scale-95', 'opacity-0');
            }, 10);
        });
    });
    closeModal.addEventListener('click', () => {
        quickViewModal.querySelector('.modal').classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            quickViewModal.classList.add('hidden');
        }, 300);
    });

    // Filter Products
    const filterBtns = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.dataset.filter;
            filterBtns.forEach(b => b.classList.remove('bg-[var(--primary)]', 'text-white'));
            btn.classList.add('bg-[var(--primary)]', 'text-white');
            productCards.forEach(card => {
                if (filter === 'all' || card.dataset.category === filter) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Back-to-Top Button
    const backToTop = document.getElementById('back-to-top');
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        if (scrollY > 300) {
            backToTop.classList.add('scale-100', 'visible');
            const maxScroll = document.body.scrollHeight - window.innerHeight;
            const opacity = Math.min(scrollY / maxScroll * 2, 1);
            backToTop.style.opacity = opacity;
        } else {
            backToTop.classList.remove('scale-100', 'visible');
            backToTop.style.opacity = '0';
        }
    });
    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Lazy Load Images
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src || img.src;
                observer.unobserve(img);
            }
        });
    });
    lazyImages.forEach(img => observer.observe(img));

    // Smooth Scroll for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});
