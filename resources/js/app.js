import './bootstrap';
import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';
import '@fortawesome/fontawesome-free/js/all.js'; // Optional: Consider specific icon imports for performance
import Splide from '@splidejs/splide';
import Swal from 'sweetalert2';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

window.ClassicEditor = ClassicEditor;
const editors = {}; // Store CKEditor instances
window.editors = editors; // Expose editors globally for form submission

document.addEventListener('DOMContentLoaded', () => {
    // Splide Slider Initialization
    document.querySelectorAll('.splide').forEach(element => {
        new Splide(element, {
            type: 'loop',
            perPage: 3,
            perMove: 1,
            gap: '1rem',
            breakpoints: {
                1024: { perPage: 2 },
                768: { perPage: 1 },
            },
        }).mount();
    });
});

window.Alpine = Alpine;
window.Swal = Swal;
Alpine.plugin(Collapse);
Alpine.start();
