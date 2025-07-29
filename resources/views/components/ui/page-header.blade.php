<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $description }}</p>
    </div>
    <div class="flex items-center">
        {{ $slot }}
    </div>
</div>
