@push('styles')
    <style>
        .breadcrumb {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #6b7280;
        }
        .breadcrumb a {
            color: #3b82f6;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .breadcrumb a:hover {
            color: #2563eb;
        }
        .breadcrumb .separator {
            margin: 0 8px;
            color: #9ca3af;
        }
    </style>

@endpush
@props(['links', 'title' => null])

<div class="max-w-7xl mx-auto mb-6">
    <div class="breadcrumb">
        @foreach ($links as $name => $url)
            @if ($url)
                <a href="{{ $url }}" class="text-blue-500 hover:text-blue-700 transition-colors">
                    {{ $name }}
                </a>
                <span class="separator">/</span>
            @else
                <span class="text-gray-700">{{ $name }}</span>
            @endif
        @endforeach
    </div>
</div>
