@props([
    'name',
    'label',
    'multiple' => false,
    'existingImages' => [],
    'uploadLabel' => 'Click to upload',
    'ManyImagesInput' => false,
    'isSingleImage' => false,
    'description' => null,
])

<div class="col-span-1 lg:col-span-2" x-data="{
    images: {{ json_encode(collect($existingImages)->map(fn($url) => ['url' => $url, 'file' => null])->toArray(), JSON_THROW_ON_ERROR) }},
    inputId: 'file-input-' + Math.random().toString(36).substring(7),
    isSingleImage: {{ $isSingleImage ? 'true' : 'false' }},
    fileInput: null
}">
    <!-- Larger and Bold Label -->
    <label for="{{ $name }}" class="block text-base font-bold text-gray-700 mt-4 mb-2">{{ $label }}</label>

    <!-- Description (if provided) -->
    @if($description)
        <p class="text-sm text-orange-500 mb-2">{{ $description }}</p>
    @endif

    <!-- Hidden File Input -->
    <input
        type="file"
        name="{{ $name }}"
        :id="inputId"
        :multiple="!isSingleImage"
        class="hidden"
        x-ref="fileInput"
        x-on:change="
            const files = Array.from($event.target.files);
            if (files.length > 0) {
                const dataTransfer = new DataTransfer();
                if (isSingleImage) {
                    // For single image upload, only keep the first file
                    const file = files[0];
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        images = [{ url: e.target.result, file }]; // Replace the array with a single image
                        dataTransfer.items.add(file); // Add the file to DataTransfer
                        $refs.fileInput.files = dataTransfer.files; // Update the file input
                    };
                    reader.readAsDataURL(file);
                } else {
                    // For multiple image upload, process all files
                    files.forEach(file => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            images.push({ url: e.target.result, file }); // Add to the array for multiple images
                            dataTransfer.items.add(file); // Add the file to DataTransfer
                            $refs.fileInput.files = dataTransfer.files; // Update the file input
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }
            $event.target.value = ''; // Reset the file input
        "
    >

    <!-- Image Preview Section -->
    <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <!-- Existing Images -->
        <template x-for="(image, index) in images" :key="index">
            <div class="relative group w-48 h-48 border border-gray-300 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                <!-- Image Preview -->
                <img
                    :src="image.url"
                    class="w-full h-full object-cover"
                    alt="Preview"
                >
                <!-- Hover Overlay with Click-to-Replace Functionality -->
                <div
                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition duration-300 flex items-center justify-center cursor-pointer"
                    x-on:click="
                        const input = document.createElement('input');
                        input.type = 'file';
                        input.accept = 'image/*';
                        input.style.display = 'none';
                        document.body.appendChild(input);

                        input.onchange = (e) => {
                            const file = e.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    images[index] = { url: e.target.result, file }; // Update the specific image
                                    document.body.removeChild(input);
                                    // Update the file input with the new files
                                    const dataTransfer = new DataTransfer();
                                    images.forEach(image => dataTransfer.items.add(image.file));
                                    $refs.fileInput.files = dataTransfer.files;
                                };
                                reader.readAsDataURL(file);
                            }
                        };

                        input.click();
                    "
                >
                    <span class="text-white text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                        Click to Replace
                    </span>
                </div>

                <!-- Remove Button (Only for multiple images) -->
                @if($ManyImagesInput)
                    <button
                        type="button"
                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-opacity opacity-0 group-hover:opacity-100"
                        x-on:click="
                            images.splice(index, 1);
                            // Update the file input after removing an image
                            const dataTransfer = new DataTransfer();
                            images.forEach(image => dataTransfer.items.add(image.file));
                            $refs.fileInput.files = dataTransfer.files;
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                @endif
            </div>
        </template>

        <!-- Add More Images Button (Visible only if ManyImagesInput is true) -->
        @if($ManyImagesInput)
            <div
                class="w-48 h-48 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-gray-400 hover:bg-gray-50 p-3 space-y-2 transition-all"
                x-on:click="document.getElementById(inputId).click()"
            >
                <!-- Upload Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"></path>
                    <polyline points="16 10 12 6 8 10"></polyline>
                    <line x1="12" y1="6" x2="12" y2="16"></line>
                </svg>

                <!-- Dynamic Upload Label -->
                <span class="text-gray-500 text-sm font-medium">{{ $uploadLabel }}</span>
            </div>
        @endif
    </div>

    @error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
