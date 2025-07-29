@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortableButtons = document.querySelectorAll('th .sort-button');

        sortableButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent the click from bubbling up to the th
                const th = this.closest('th');
                const table = th.closest('table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                const columnIndex = th.cellIndex;

                // Read sortDirection from the th element
                let currentSortDirection = th.dataset.sortDirection;
                let newSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';

                // Reset sort indicators on all headers
                document.querySelectorAll('th[data-sortable]').forEach(header => {
                    header.dataset.sortDirection = ''; // Clear sort direction on th
                    const btn = header.querySelector('.sort-button');
                    if (btn) {
                        btn.innerHTML = '<svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>';
                    }
                });

                // Set new sort direction on the clicked th and update its button's indicator
                th.dataset.sortDirection = newSortDirection;
                this.innerHTML = newSortDirection === 'asc' ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4 4 4m0 10l-4 4-4-4"></path></svg>' : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m0-10l-4-4-4 4"></path></svg>';

                rows.sort((a, b) => {
                    const aValue = a.cells[columnIndex].textContent.trim();
                    const bValue = b.cells[columnIndex].textContent.trim();

                    if (newSortDirection === 'asc') {
                        return aValue.localeCompare(bValue);
                    } else {
                        return bValue.localeCompare(aValue);
                    }
                });

                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    });
</script>
@endpush