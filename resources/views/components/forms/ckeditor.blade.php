@props(['id', 'name', 'value' => ''])

<div class="mb-6">
    <label for="{{ $id }}" class="block font-semibold text-gray-700 mb-2">{{ $slot }}</label>
    <textarea id="{{ $id }}" name="{{ $name }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error($name) border-red-500 @enderror">{{ $value }}</textarea>
    @error($name)
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.querySelector('#{{ $id }}');
        if (textarea) {
            window.ClassicEditor.create(textarea, {
                placeholder: 'Type or paste your content here...',
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn', 'tableRow', 'mergeTableCells'
                    ]
                },
                language: 'en',
            })
            .then(editor => {
                window.editors['{{ $id }}'] = editor;
                editor.model.document.on('change:data', () => {
                    textarea.value = editor.getData();
                });
            })
            .catch(error => {
                console.error(`CKEditor initialization failed for #${'{{ $id }}'}:`, error);
            });
        }
    });
</script>
@endpush
