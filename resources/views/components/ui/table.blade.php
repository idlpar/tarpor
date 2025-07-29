<div class="overflow-x-auto">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider @if($header !== 'Actions') cursor-pointer @endif" @if($header !== 'Actions') data-sortable @endif>
    <div class="flex items-center">
        <span>{{ $header }}</span>
        @if ($header !== 'Actions')
            <button class="ml-2 focus:outline-none sort-button">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
            </button>
        @endif
    </div>
</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
