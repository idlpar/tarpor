@props(['seo' => null])

<div x-data="{ seo: @json($seo !== null) }" class="col-span-1 lg:col-span-2">
    <h3 class="text-xl font-semibold text-gray-800 mt-4 mb-4">SEO Metadata</h3>

    <button type="button" @click="seo = !seo" class="mb-4 inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg">
        <span x-text="seo ? 'Hide SEO Form' : 'Add SEO Metadata'"></span>
    </button>

    <div x-show="seo" x-transition class="space-y-4">
        <!-- Google Meta -->
        <div class="mb-6">
            <h4 class="text-lg font-semibold text-gray-800 text-center bg-amber-200 px-2 py-2">Google Meta</h4>
            <x-form.input name="meta_title" label="Meta Title" value="{{ $seo->meta_title ?? '' }}" />
            <x-form.textarea name="meta_description" label="Meta Description">{{ $seo->meta_description ?? '' }}</x-form.textarea>
            <x-form.input name="meta_keywords" label="Meta Keywords" value="{{ $seo->meta_keywords ?? '' }}" />
            <x-form.input name="canonical_url" label="Canonical URL" value="{{ $seo->canonical_url ?? '' }}" />
        </div>

        <!-- Facebook Meta -->
        <div class="mb-6">
            <h4 class="text-lg font-semibold text-gray-800 text-center bg-amber-200 px-2 py-2">Facebook Meta</h4>
            <x-form.input name="og_title" label="Open Graph Title" value="{{ $seo->og_title ?? '' }}" />
            <x-form.textarea name="og_description" label="Open Graph Description">{{ $seo->og_description ?? '' }}</x-form.textarea>
            <x-form.file-upload name="og_image" label="Open Graph Image" isSingleImage="true" :existingImages="isset($seo->og_image) && $seo->og_image ? [asset($seo->og_image)] : []" />
        </div>

        <!-- Twitter Meta -->
        <div class="mb-6">
            <h4 class="text-lg font-semibold text-gray-800 text-center bg-amber-200 px-2 py-2">Twitter Meta</h4>
            <x-form.input name="twitter_title" label="Twitter Title" value="{{ $seo->twitter_title ?? '' }}" />
            <x-form.textarea name="twitter_description" label="Twitter Description">{{ $seo->twitter_description ?? '' }}</x-form.textarea>
            <x-form.file-upload name="twitter_image" label="Twitter Image" isSingleImage="true" :existingImages="isset($seo->twitter_image) && $seo->twitter_image ? [asset($seo->twitter_image)] : []" />
        </div>

        <!-- Schema & Robots -->
        <div class="mb-6">
            <x-form.textarea name="schema_markup" label="Schema Markup (JSON-LD)" rows="6">{{ $seo->schema_markup ?? '' }}</x-form.textarea>
            <x-form.input name="robots" label="Robots Meta Tag" value="{{ $seo->robots ?? 'index, follow' }}" />
        </div>
    </div>
</div>
