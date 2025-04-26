import './bootstrap';
import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';
import '@fortawesome/fontawesome-free/js/all.js';
import Splide from '@splidejs/splide';
import LazyLoad from 'vanilla-lazyload'; // Import vanilla-lazyload

// Initialize Splide slider and LazyLoad
document.addEventListener('DOMContentLoaded', () => {
    // Splide Slider Initialization
    const splideElements = document.querySelectorAll('.splide'); // Ensure these elements exist

    if (splideElements.length) {
        splideElements.forEach(element => {
            new Splide(element, {
                type: 'loop',
                perPage: 3,
                perMove: 1,
                gap: '1rem',
                breakpoints: {
                    1024: {
                        perPage: 2,
                    },
                    768: {
                        perPage: 1,
                    },
                },
            }).mount();
        });
    }

});

window.Alpine = Alpine;
Alpine.plugin(Collapse);
Alpine.start();
