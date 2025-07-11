@extends('layouts.admin')
@push('styles')
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -20px);
            }
            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translate(-50%, 0);
            }
            to {
                opacity: 0;
                transform: translate(-50%, -20px);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        .animate-fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }
    </style>
@endpush
@section('admin_content')
    <div class="max-w-full mx-auto bg-white px-6 rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-6 sticky top-10 bg-blue-300 z-10 shadow-md p-4">
            <!-- Title on the left -->
            <h1 class="text-3xl font-semibold text-gray-800">SVG Icon List</h1>

            <!-- Search Bar in the center -->
            <div class="flex-1 flex justify-center">
                <input type="text" id="search" placeholder="Search icons..." class="w-1/2 p-3 mx-auto border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Button to trigger sorting -->
            <form action="{{ route('icons.sort') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-emerald-500 text-white rounded-md shadow-sm hover:bg-red-700 transition-colors mr-4 flex items-center">
                    <x-icon name="arrow-up-arrow-down" class="h-6 w-6 mr-1 text-white fill-current"/>Sort SVG
                </button>
            </form>

            <!-- Delete Button on the right -->
            <form action="{{ route('icons.cleanup') }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-md shadow-sm hover:bg-red-700 transition-colors flex items-center">
                    <x-icon name="icon-trash-alt" class="h-4 w-4 mr-2 text-white fill-current"/> Duplicates
                </button>
            </form>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">
                {{ session('error') }}
            </div>
        @endif
        <div style="display: none;">
            {!! file_get_contents(public_path('svg/sprite.svg')) !!}
        </div>
        <!-- Icon List -->
        <div id="icon-list" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-6">
            @foreach($icons as $index => $icon)
                <div class="relative flex flex-col items-center p-6 border rounded-lg bg-teal-50 hover:bg-orange-100 shadow-md hover:scale-105 hover:shadow-xl transition-all duration-300 ease-in-out cursor-pointer" onclick="copyIconId('{{ $icon }}')">
                    <!-- Number in round span -->
                    <span class="absolute top-0 right-0 transform translate-x-1 translate-y-1 bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">
                        {{ $index + 1 }}
                    </span>
                    <!-- SVG Icon -->
                    <svg class="w-16 h-16 text-gray-700 mb-3 transition-all duration-300 ease-in-out hover:text-blue-500">
                        <use xlink:href="{{ asset('svg/sprite.svg') }}#{{ $icon }}"></use>
                    </svg>

                    <p class="text-sm mt-2 text-gray-600 font-medium">{{ $icon }}</p>
                </div>
            @endforeach
        </div>

        <!-- Toast Notification -->
        <div id="toast" class="fixed top-32 left-1/2 transform -translate-x-1/2 px-6 py-3 bg-green-600 text-white text-sm rounded-md shadow-lg hidden flex items-center space-x-2 animate-fade-in z-40">
            <x-icon name="icon-check-circle" class="h-5 w-5 text-white"></x-icon>
            <span id="copied-id-message">Icon ID copied to clipboard!</span>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        // Function to copy the icon ID to clipboard
        function copyIconId(iconId) {
            // Create a temporary input element to copy the icon ID
            const input = document.createElement('input');
            input.value = iconId;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);

            // Show toast notification with the copied ID
            showToast(iconId);
        }

        // Function to show toast notification with copied ID
        function showToast(iconId) {
            const toast = document.getElementById('toast');
            const copiedMessage = document.getElementById('copied-id-message');
            copiedMessage.textContent = `${iconId} copied to clipboard!`;

            toast.classList.remove('hidden');

            // Show toast for 3 seconds before hiding
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        // Deleting Duplicate SVG
        document.getElementById('search').addEventListener('input', function () {
            let searchQuery = this.value.toLowerCase();
            let icons = document.querySelectorAll("#icon-list div");

            icons.forEach(icon => {
                let name = icon.querySelector("p").textContent.toLowerCase();
                icon.style.display = name.includes(searchQuery) ? "block" : "none";
            });
        });
    </script>
@endpush
