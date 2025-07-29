<div class="flex items-center justify-end space-x-2">
    <a href="{{ route($baseRoute . '.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 custom-tooltip-trigger" data-tooltip="Edit">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
    </a>
    <span class="text-gray-300">|</span>
    @if ($item->trashed())
        <form action="{{ route($baseRoute . '.restore', $item->id) }}" method="POST" class="inline-block restore-form">
            @csrf
            @method('PATCH')
            <button type="button" class="text-green-600 hover:text-green-900 custom-tooltip-trigger restore-button" data-tooltip="Restore" data-restore-title="{{ $restoreTitle ?? 'Are you sure?' }}" data-restore-text="{{ $restoreText ?? 'This will restore the item!' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
            </button>
        </form>
    @else
        <form action="{{ route($baseRoute . '.destroy', $item->id) }}" method="POST" class="inline-block delete-form">
            @csrf
            @method('DELETE')
            <button type="button" class="text-red-600 hover:text-red-900 custom-tooltip-trigger delete-button" data-tooltip="Delete" data-delete-title="{{ $deleteTitle ?? 'Are you sure?' }}" data-delete-text="{{ $deleteText ?? 'You won\'t be able to revert this!' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </form>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const title = this.dataset.deleteTitle || 'Are you sure?';
                const text = this.dataset.deleteText || 'You won\'t be able to revert this!';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.restore-button').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const title = this.dataset.restoreTitle || 'Are you sure?';
                const text = this.dataset.restoreText || 'This will restore the item!';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore it!',
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
