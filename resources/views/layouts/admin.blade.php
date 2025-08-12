@extends('layouts.app')

@section('content')
<div class="flex bg-bg-light">
    <!-- Sidebar -->
    @include('components.admin.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- You might want a separate admin navbar here if needed --}}

        <main class="flex-1 overflow-x-hidden bg-bg-light p-4">
            @include('components.app.toast')
            <div class="container mx-auto px-4">
                @includeIf('components.admin.breadcrumb')
                @yield('admin_content')
            </div>
        </main>
    </div>
</div>
@endsection

@push('styles')
<style>
    .custom-tooltip {
        position: absolute;
        background-color: #333;
        color: #fff;
        padding: 5px 8px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s, visibility 0.2s;
        pointer-events: none; /* Allows clicks to pass through */
    }

    .custom-tooltip.show {
        opacity: 1;
        visibility: visible;
    }

    .custom-tooltip::before {
        content: '';
        position: absolute;
        border-width: 5px;
        border-style: solid;
    }

    /* Tooltip on top */
    .custom-tooltip.top::before {
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-color: #333 transparent transparent transparent;
    }

    /* Tooltip on bottom */
    .custom-tooltip.bottom::before {
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-color: transparent transparent #333 transparent;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Custom Tooltip Logic
        const tooltipTriggers = document.querySelectorAll('.custom-tooltip-trigger');
        let currentTooltip = null;

        tooltipTriggers.forEach(trigger => {
            trigger.addEventListener('mouseenter', function () {
                const tooltipText = this.dataset.tooltip;
                if (!tooltipText) return;

                // Create tooltip element
                currentTooltip = document.createElement('div');
                currentTooltip.className = 'custom-tooltip';
                currentTooltip.textContent = tooltipText;
                document.body.appendChild(currentTooltip);

                // Position tooltip
                const triggerRect = this.getBoundingClientRect();
                const tooltipHeight = currentTooltip.offsetHeight;
                const tooltipWidth = currentTooltip.offsetWidth;

                let top = triggerRect.top - tooltipHeight - 10; // 10px buffer
                let left = triggerRect.left + (triggerRect.width / 2) - (tooltipWidth / 2);

                // Check if there's enough space on top
                if (top < 0) {
                    // Not enough space on top, position on bottom
                    top = triggerRect.bottom + 10; // 10px buffer
                    currentTooltip.classList.add('bottom');
                } else {
                    currentTooltip.classList.add('top');
                }

                // Adjust for left/right screen edges
                if (left < 0) {
                    left = 0;
                } else if (left + tooltipWidth > window.innerWidth) {
                    left = window.innerWidth - tooltipWidth;
                }

                currentTooltip.style.top = `${top + window.scrollY}px`;
                currentTooltip.style.left = `${left}px`;
                currentTooltip.classList.add('show');
            });

            trigger.addEventListener('mouseleave', function () {
                if (currentTooltip) {
                    currentTooltip.classList.remove('show');
                    currentTooltip.remove();
                    currentTooltip = null;
                }
            });

            // Clean up tooltip if mouse leaves window or element is removed
            trigger.addEventListener('blur', function() {
                if (currentTooltip) {
                    currentTooltip.classList.remove('show');
                    currentTooltip.remove();
                    currentTooltip = null;
                }
            });
        });
    });
</script>
@endpush
