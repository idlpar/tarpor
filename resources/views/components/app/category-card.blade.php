<a href="{{ $href }}" class="category-card group relative rounded-xl overflow-hidden shadow-lg transition-transform duration-300">
    <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-64 sm:h-80 object-cover group-hover:scale-105 transition duration-500" loading="lazy" data-src="{{ $image }}">
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4 sm:p-6">
        <h3 class="text-xl sm:text-2xl font-bold text-white">{{ $title }}</h3>
    </div>
</a>
