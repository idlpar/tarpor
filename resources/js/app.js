import './bootstrap';
import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';
import '@fortawesome/fontawesome-free/js/all.js'; // Optional: Consider specific icon imports for performance
import Splide from '@splidejs/splide';
import Swal from 'sweetalert2';
import FullEditor from '@blowstack/ckeditor5-full-free-build';

window.ClassicEditor = FullEditor;

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

    // CKEditor Initialization
    ['description', 'short_description'].forEach(id => {
        const textarea = document.querySelector(`#${id}`);
        if (textarea) {
            ClassicEditor.create(textarea, {
                toolbar: {
                    items: [
                        'heading', '|',
                        'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                        'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript', 'highlight', '|',
                        'alignment', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'link', 'blockQuote', 'insertTable', 'imageUpload', 'mediaEmbed', 'horizontalLine', '|',
                        'code', 'codeBlock', '|',
                        'undo', 'redo', '|',
                        'removeFormat', 'specialCharacters', 'pageBreak'
                    ],
                    shouldNotGroupWhenFull: true
                },
                image: {
                    toolbar: [
                        'imageTextAlternative', 'linkImage'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn', 'tableRow', 'mergeTableCells', 'tableCellProperties', 'tableProperties'
                    ]
                },
                mediaEmbed: {
                    toolbar: ['mediaEmbed']
                },
                language: 'en',
                simpleUpload: {
                    uploadUrl: '/upload-image',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }
            })
                .then(editor => {
                    console.log(`CKEditor initialized for #${id}`);
                    editor.ui.view.editable.element.style.minHeight = '300px';
                    editors[id] = editor; // Store editor instance
                    // Sync data on form submission
                    textarea.form.addEventListener('submit', () => {
                        const data = editor.getData();
                        console.log(`Syncing CKEditor data for #${id}:`, data);
                        textarea.value = data;
                    });
                })
                .catch(error => {
                    console.error(`CKEditor initialization failed for #${id}:`, error);
                });
        }
    });
});

window.Alpine = Alpine;
window.Swal = Swal;
Alpine.plugin(Collapse);
Alpine.start();
