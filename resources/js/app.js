import './bootstrap';
import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';
import '@fortawesome/fontawesome-free/js/all.js';
import Splide from '@splidejs/splide';
import $ from 'jquery';
window.$ = window.jQuery = $;
import Swal from 'sweetalert2';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import Sortable from 'sortablejs';
window.Sortable = Sortable;
import mediumZoom from 'medium-zoom';
window.mediumZoom = mediumZoom;

// TinyMCE
import tinymce from 'tinymce/tinymce';
import 'tinymce/themes/silver/theme.js';
import 'tinymce/models/dom/model.js';

// Import plugins
import 'tinymce/plugins/code';
import 'tinymce/plugins/table';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/image';
import 'tinymce/plugins/media';

window.tinymce = tinymce;



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

    // TinyMCE Initialization
    if (document.querySelector('textarea.tinymce-editor')) {
        tinymce.init({
            selector: 'textarea.tinymce-editor',
            base_url: '/build/tinymce',
            plugins: 'code table lists image media',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image media',
            height: 500,
            image_caption: true,
            image_advtab: true,
            image_class_list: [
                { title: 'None', value: '' },
                { title: 'Responsive', value: 'img-fluid' },
            ],
            extended_valid_elements: 'figure[class|style],figcaption[class|style]',
            license_key: 'gpl',
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
                editor.on('init', function () {
                    editor.setContent(editor.getElement().value); // Load initial content
                });
            },
            images_upload_handler: function (blobInfo, success, failure) {
                let formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/api/images/upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error ' + response.status);
                    }
                    return response.json();
                })
                .then(result => {
                    if (result.location) {
                        success(result.location);
                    } else {
                        failure('Upload failed: ' + (result.message || 'No location returned'));
                    }
                })
                .catch(error => {
                    failure('Upload failed: ' + error.message);
                });
            }
        });
    }


});

window.Alpine = Alpine;
window.Swal = Swal;
window.tippy = tippy;
Alpine.plugin(Collapse);

window.Alpine.start();

// Re-initialize Alpine after DOM changes if needed, but usually DOMContentLoaded is enough.
// If you have dynamic components, you might need a more specific approach.
document.addEventListener('alpine:init', () => {
    // You can register Alpine stores or components here if needed.
});

